#!/usr/bin/env python3
"""Validate planning records and refresh only explicitly marked Markdown views."""

from __future__ import annotations

import argparse
from dataclasses import dataclass
import os
from pathlib import Path
import re
import subprocess
import sys

ROOT = Path(__file__).resolve().parents[1]
PLANNING = ROOT / "planning"
VALID_STATUSES = {
    "needs-triage", "needs-info", "ready-for-agent", "ready-for-human",
    "in-progress", "done", "wontfix",
}
TERMINAL = {"done", "wontfix"}
LAYOUT = {
    "epics": ("EPIC", r"EPIC-\d{5}"),
    "specs": ("PRD", r"PRD-\d{5}"),
    "tickets": ("TICKET", r"(?:T|TICKET)-\d{5}"),
    "tasks": ("TASK", r"TASK-\d{5}"),
}
# Preserve the existing identities, not an open-ended second naming scheme.
LEGACY_TICKETS = {f"T-{number:05d}" for number in range(1, 8)}
TRANSITIONAL_REQUIREMENTS = {f"T-{number:05d}" for number in range(8, 13)}
LINK = re.compile(r"\!?\[[^\]]*\]\(([^\s)]+)")


def frontmatter(path: Path) -> dict[str, str]:
    text = path.read_text(encoding="utf-8")
    if not text.startswith("---\n") or "\n---\n" not in text[4:]:
        raise ValueError(f"{path.relative_to(ROOT)}: missing frontmatter")
    block = text[4:].split("\n---\n", 1)[0]
    values: dict[str, str] = {}
    for line in block.splitlines():
        key, separator, value = line.partition(":")
        if not separator or not re.fullmatch(r"[a-z_]+", key) or key in values:
            raise ValueError(f"{path.relative_to(ROOT)}: invalid or duplicate field: {line}")
        values[key] = value.strip()
    return values


@dataclass(frozen=True)
class Record:
    path: Path
    data: dict[str, str]
    kind: str

    @property
    def id(self) -> str:
        return self.data["id"]

    @property
    def status(self) -> str:
        return self.data["status"]

    @property
    def archived(self) -> bool:
        return "archive" in self.path.relative_to(PLANNING).parts

    @property
    def parent(self) -> str:
        return self.data.get("ticket") or self.data.get("prd") or self.data.get("epic", "")

    @property
    def blockers(self) -> list[str]:
        return [value.strip() for value in self.data.get("blocked_by", "").split(",") if value.strip()]


def load_records() -> dict[str, Record]:
    records: dict[str, Record] = {}
    numbers: set[tuple[str, str]] = set()
    for directory, (suffix, pattern) in LAYOUT.items():
        for path in sorted((PLANNING / directory).rglob("*.md")):
            if path.name.startswith("_") or path.name == "README.md" or path == PLANNING / "tickets/BOARD.md":
                continue
            if path.is_symlink() or not path.resolve().is_relative_to(PLANNING.resolve()):
                raise ValueError(f"planning record must remain inside owned planning directories: {path}")
            if path.parent not in {PLANNING / directory, PLANNING / directory / "archive"}:
                raise ValueError(f"unsupported record directory: {path.relative_to(ROOT)}")
            data = frontmatter(path)
            identifier = data.get("id", "")
            if not re.fullmatch(pattern, identifier):
                raise ValueError(f"{path.relative_to(ROOT)}: invalid id {identifier!r}")
            number = identifier.rsplit("-", 1)[1]
            if path.name != f"{number}-{suffix}.md":
                raise ValueError(f"{identifier}: filename does not match identity")
            if identifier in records or (directory, number) in numbers:
                raise ValueError(f"duplicate identity or reused number: {identifier}")
            numbers.add((directory, number))
            if not data.get("title") or data.get("status") not in VALID_STATUSES:
                raise ValueError(f"{identifier}: missing title or invalid status")
            kind = {"epics": "epic", "specs": "prd", "tasks": "task"}.get(directory, "requirement")
            if directory == "tickets" and identifier.startswith("T-"):
                if identifier in LEGACY_TICKETS:
                    kind = "legacy"
                elif identifier not in TRANSITIONAL_REQUIREMENTS:
                    raise ValueError(f"{identifier}: new requirements must use TICKET-NNNNN")
            records[identifier] = Record(path, data, kind)
    return records


def children(record: Record, records: dict[str, Record]) -> list[Record]:
    return [child for child in records.values() if child.parent == record.id]


def validate_evidence(source: Path, reference: str) -> None:
    if not reference:
        return
    if re.fullmatch(r"https://[^\s]+", reference):
        return  # Remote evidence identity is reviewed by a human, not fetched by this tool.
    destination, _, anchor = reference.partition("#")
    path = source.parent / destination
    if not destination.endswith(".md") or not path.is_file() or not path.resolve().is_relative_to(ROOT):
        raise ValueError(f"{source.relative_to(ROOT)}: evidence must reference an existing Markdown file or HTTPS URL")
    if anchor:
        headings = re.findall(r"^#{1,6}\s+(.+)$", path.read_text(encoding="utf-8"), re.MULTILINE)
        anchors = {re.sub(r"[^\w -]", "", heading.lower()).replace(" ", "-") for heading in headings}
        if anchor not in anchors:
            raise ValueError(f"{source.relative_to(ROOT)}: unknown evidence anchor {reference}")


def validate_records(records: dict[str, Record]) -> None:
    parent_contract = {
        "epic": None, "prd": ("epic", "epic"), "legacy": ("prd", "prd"),
        "requirement": ("epic", "epic"), "task": ("ticket", "requirement"),
    }
    for record in records.values():
        data = record.data
        contract = parent_contract[record.kind]
        parent_keys = [key for key in ("epic", "prd", "ticket") if data.get(key)]
        optional = record.kind == "prd" or (record.kind == "task" and data.get("kind") in {"bug", "chore"})
        if contract is None:
            if parent_keys:
                raise ValueError(f"{record.id}: epic cannot have a parent")
        elif parent_keys or not optional:
            field, expected_kind = contract
            parent = records.get(data.get(field, ""))
            if parent_keys != [field] or parent is None or parent.kind != expected_kind:
                raise ValueError(f"{record.id}: requires a {expected_kind} parent in {field}")
        if record.kind == "epic" and not data.get("target"):
            raise ValueError(f"{record.id}: missing target")
        if record.kind in {"epic", "requirement"} and data.get("approved", "no") not in {"yes", "no"}:
            raise ValueError(f"{record.id}: approved must be yes or no")
        if record.archived and record.status not in TERMINAL:
            raise ValueError(f"{record.id}: archived record is not terminal")
        if record.status in TERMINAL and any(child.status not in TERMINAL for child in children(record, records)):
            raise ValueError(f"{record.id}: terminal parent still has unfinished children")
        allowed_blocker_kind = record.kind if record.kind in {"task", "requirement", "legacy"} else None
        if len(record.blockers) != len(set(record.blockers)):
            raise ValueError(f"{record.id}: duplicate blockers")
        for identifier in record.blockers:
            blocker = records.get(identifier)
            if blocker is None or allowed_blocker_kind is None or blocker.kind != allowed_blocker_kind:
                raise ValueError(f"{record.id}: invalid {record.kind} blocker {identifier}")
        if record.kind != "task":
            continue
        for field in ("ticket", "kind", "order", "blocked_by", "pr", "authorized", "review", "review_evidence", "verification"):
            if field not in data:
                raise ValueError(f"{record.id}: missing {field}")
        if data["kind"] not in {"feature", "bug", "chore"}:
            raise ValueError(f"{record.id}: invalid TASK kind")
        if data["order"] and not re.fullmatch(r"[1-9]\d*", data["order"]):
            raise ValueError(f"{record.id}: order must be blank or a positive integer")
        if data["pr"] and not re.fullmatch(r"https://[^\s]+/pull/[1-9]\d*", data["pr"]):
            raise ValueError(f"{record.id}: pr must be blank or a full pull-request URL")
        if data["authorized"] not in {"yes", "no"} or data["review"] not in {"pending", "revise", "accepted"}:
            raise ValueError(f"{record.id}: invalid authorization or review state")
        validate_evidence(record.path, data["review_evidence"])
        validate_evidence(record.path, data["verification"])
        if data["review"] == "accepted" and not data["review_evidence"]:
            raise ValueError(f"{record.id}: accepted review requires evidence")
        if record.status == "done" and (data["review"] != "accepted" or not data["verification"]):
            raise ValueError(f"{record.id}: done requires independent acceptance and verification evidence")
        if record.status in {"ready-for-agent", "in-progress"} and data["authorized"] != "yes":
            raise ValueError(f"{record.id}: execution requires recorded authorization")

    visiting: set[str] = set()
    visited: set[str] = set()

    def visit(identifier: str) -> None:
        if identifier in visiting:
            raise ValueError(f"dependency cycle at {identifier}")
        if identifier in visited:
            return
        visiting.add(identifier)
        for blocker in records[identifier].blockers:
            visit(blocker)
        visiting.remove(identifier)
        visited.add(identifier)

    for identifier in records:
        visit(identifier)


def lifecycle(records: dict[str, Record]) -> str:
    data = frontmatter(PLANNING / "README.md")
    state = data.get("lifecycle")
    if state not in {"bootstrap", "active"}:
        raise ValueError("planning/README.md: lifecycle must be bootstrap or active")
    validate_evidence(PLANNING / "README.md", data.get("cutover_review", ""))
    bootstrap = records.get("TASK-00001")
    if bootstrap is None or bootstrap.parent != "T-00008":
        raise ValueError("lifecycle requires the preserved TASK-00001 bootstrap under T-00008")
    if state == "active" and (
        bootstrap.status != "done" or bootstrap.data.get("review") != "accepted" or not data.get("cutover_review")
    ):
        raise ValueError("cutover requires TASK-00001 done, independent acceptance, and cutover_review evidence")
    return state


def unavailable(record: Record, records: dict[str, Record], state: str) -> list[str]:
    reasons: list[str] = []
    if record.kind == "task":
        if state != "active" and record.id != "TASK-00001":
            reasons.append("verified lifecycle cutover pending")
        if record.data.get("authorized") != "yes":
            reasons.append("execution authorization missing")
    cursor = record
    while True:
        for identifier in cursor.blockers:
            if records[identifier].status not in TERMINAL:
                reasons.append(f"unfinished {identifier}")
        if not cursor.parent:
            break
        cursor = records[cursor.parent]
        if cursor.kind in {"epic", "requirement"} and cursor.data.get("approved") != "yes":
            reasons.append(f"unapproved {cursor.id}")
        if cursor.status in TERMINAL:
            reasons.append(f"closed {cursor.id}")
    return list(dict.fromkeys(reasons))


def validate_links(documents: dict[Path, str]) -> None:
    for path, text in documents.items():
        if path.name.startswith("_"):
            continue
        for target in LINK.findall(text):
            destination = target.partition("#")[0]
            if not destination.endswith(".md") or destination.startswith(("/", "http:", "https:", "mailto:")):
                continue
            if not (path.parent / destination).resolve().is_file():
                raise ValueError(f"{path.relative_to(ROOT)}: broken local Markdown link {target}")


def cell(value: str) -> str:
    return value.replace("|", "\\|").replace("\n", " ")


def link(record: Record, source: Path) -> str:
    relative = os.path.relpath(record.path, source.parent).replace(os.sep, "/")
    return f"[{record.id} — {cell(record.data['title'])}]({relative})"


def priority(record: Record) -> tuple[bool, int, str]:
    order = record.data.get("order", "")
    return (not bool(order), int(order or 0), record.id)


def table(rows: list[Record], source: Path, records: dict[str, Record], state: str) -> str:
    lines = ["| Order | Record | Parent | Status | Blockers / gates | PR |", "| --- | --- | --- | --- | --- | --- |"]
    for record in sorted(rows, key=priority):
        parent = link(records[record.parent], source) if record.parent else "—"
        blockers = ", ".join(link(records[identifier], source) for identifier in record.blockers) or "—"
        gates = "; ".join(unavailable(record, records, state)) if record.status not in TERMINAL else ""
        if gates:
            blockers += f"; {cell(gates)}"
        lines.append(f"| {record.data.get('order') or '—'} | {link(record, source)} | {parent} | {record.status} | {blockers} | {record.data.get('pr') or '—'} |")
    if not rows:
        lines.append("| — | None | — | — | — | — |")
    return "\n".join(lines)


def views(records: dict[str, Record], state: str) -> dict[tuple[Path, str], str]:
    result: dict[tuple[Path, str], str] = {}
    live = [record for record in records.values() if not record.archived]
    board = PLANNING / "tickets/BOARD.md"
    tasks = [record for record in live if record.kind == "task"]
    sections = {
        "active": [r for r in tasks if r.status == "in-progress"],
        "ready": [r for r in tasks if r.status == "ready-for-agent" and not unavailable(r, records, state)],
        "waiting": [r for r in tasks if r.status == "ready-for-agent" and unavailable(r, records, state)],
        "human": [r for r in tasks if r.status == "ready-for-human"],
        "info": [r for r in tasks if r.status == "needs-info"],
        "triage": [r for r in tasks if r.status == "needs-triage"],
        "closed": [r for r in tasks if r.status in TERMINAL],
        "legacy": [r for r in live if r.kind == "legacy" and r.status not in TERMINAL],
    }
    for name, rows in sections.items():
        result[(board, name)] = table(rows, board, records, state)
    result[(board, "mode")] = (
        "Bootstrap mode: only explicitly authorized TASK-00001 may execute under T-00008's exception. General cutover awaits independent acceptance."
        if state == "bootstrap" else "Active lifecycle: approved, authorized TASKs form the execution frontier."
    )
    for directory in LAYOUT:
        index = PLANNING / directory / "README.md"
        selected = [record for record in live if record.path.parent == PLANNING / directory]
        result[(index, "records")] = table(selected, index, records, state)
        archived = [record for record in records.values() if record.archived and record.path.is_relative_to(PLANNING / directory)]
        archive_index = PLANNING / directory / "archive/README.md"
        if archived or archive_index.exists():
            result[(archive_index, "records")] = table(archived, archive_index, records, state)
    for record in live:
        # Legacy documents remain byte-preserved history, not generated status stores.
        if record.kind in {"epic", "requirement"}:
            result[(record.path, "children")] = table(children(record, records), record.path, records, state)
    roadmap = PLANNING / "ROADMAP.md"
    result[(roadmap, "epics")] = table([r for r in live if r.kind == "epic"], roadmap, records, state)
    frontier = []
    for record in sorted(live, key=lambda r: r.id):
        if record.kind not in {"epic", "requirement"} or record.status in TERMINAL:
            continue
        owned = children(record, records)
        if not owned:
            action = "Accept requirements before decomposition" if record.data.get("approved") != "yes" else "Decompose into TICKETs" if record.kind == "epic" else "Decompose into TASKs after prerequisites"
        elif all(child.status in TERMINAL for child in owned):
            action = "Explicit closeout review; terminal children do not imply satisfied requirements"
        else:
            continue
        reasons = unavailable(record, records, state)
        suffix = f"; {'; '.join(reasons)}" if reasons else ""
        frontier.append(f"- {link(record, roadmap)}: {action}{suffix}.")
    result[(roadmap, "frontier")] = "\n".join(frontier) or "None."
    return result


def render_views(documents: dict[Path, str], generated: dict[tuple[Path, str], str]) -> dict[Path, str]:
    rendered = dict(documents)
    for (path, name), content in generated.items():
        if path not in rendered:
            if path.name != "README.md" or path.parent.name != "archive":
                raise ValueError(f"missing view document: {path.relative_to(ROOT)}")
            rendered[path] = f"# Archived {path.parent.parent.name}\n\n<!-- planning:{name} -->\n<!-- /planning:{name} -->\n"
        text = rendered[path]
        start, end = f"<!-- planning:{name} -->", f"<!-- /planning:{name} -->"
        if text.count(start) != 1 or text.count(end) != 1 or text.index(start) > text.index(end):
            raise ValueError(f"{path.relative_to(ROOT)}: missing, duplicate, or reversed {name} markers")
        before, rest = text.split(start, 1)
        _, after = rest.split(end, 1)
        rendered[path] = before + start + "\n" + content + "\n" + end + after
    # Reject unknown/nested marker pairs rather than leaving a stale unowned view behind.
    for path, text in rendered.items():
        if path.name.startswith("_") or ("archive" in path.relative_to(PLANNING).parts and path.name != "README.md"):
            continue
        names = re.findall(r"<!-- planning:([a-z]+) -->", text)
        endings = re.findall(r"<!-- /planning:([a-z]+) -->", text)
        if sorted(names) != sorted(endings):
            raise ValueError(f"{path.relative_to(ROOT)}: unmatched planning markers")
        for name in names:
            if (path, name) not in generated:
                raise ValueError(f"{path.relative_to(ROOT)}: unknown generated view {name}")
    return rendered


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--write", action="store_true", help="refresh marked views after validation")
    args = parser.parse_args()
    try:
        records = load_records()
        validate_records(records)
        state = lifecycle(records)
        for record in records.values():
            if record.kind == "task" and record.status == "in-progress" and unavailable(record, records, state):
                raise ValueError(f"{record.id}: active work has unresolved gates: {unavailable(record, records, state)}")
        documents = {path: path.read_text(encoding="utf-8") for path in PLANNING.rglob("*.md")}
        validate_links(documents)
        rendered = render_views(documents, views(records, state))
        validate_links(rendered)
        ignored = subprocess.run(
            ["git", "-c", f"safe.directory={ROOT.resolve()}", "check-ignore", "-q", ".runs/planning-check"],
            cwd=ROOT, check=False,
        )
        if ignored.returncode != 0:
            raise ValueError(".runs/ must be gitignored")
        stale = [path for path, text in rendered.items() if documents.get(path) != text]
        if stale and not args.write:
            raise ValueError("stale generated views (run ./bin/planning-check --write): " + ", ".join(str(p.relative_to(ROOT)) for p in stale))
        for path in stale:
            if path.is_symlink():
                raise ValueError(f"refusing to write a symlink: {path}")
        for path in stale:
            path.parent.mkdir(parents=True, exist_ok=True)
            path.write_text(rendered[path], encoding="utf-8")
        active = sum(record.status not in TERMINAL for record in records.values())
        tasks = sum(record.kind == "task" for record in records.values())
        print(f"Planning validation passed: {len(records)} records, {active} active, {tasks} TASKs; lifecycle={state}; refreshed={len(stale)}")
        return 0
    except (ValueError, OSError) as exception:
        print(f"Planning validation failed: {exception}", file=sys.stderr)
        return 1


if __name__ == "__main__":
    sys.exit(main())
