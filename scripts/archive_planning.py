#!/usr/bin/env python3
"""Archive explicitly selected terminal planning records and repair local Markdown links."""

from __future__ import annotations

import argparse
import os
import re
import shutil
import subprocess
import sys
from pathlib import Path

from planning_portfolio import children, frontmatter, load_records, validate_records

ROOT = Path(__file__).resolve().parents[1]
PLANNING = ROOT / "planning"
TERMINAL = {"done", "wontfix"}
LINK = re.compile(r"(\!?\[[^\]]*\]\()([^\s)]+)(\))")


def wayfinder_status(path: Path) -> str:
    match = re.search(r"^\*\*Status:\*\*\s*(.+?)\s*$", path.read_text(encoding="utf-8"), re.MULTILINE)
    return match.group(1).strip().casefold() if match else ""


def local_target(source: Path, destination: str) -> Path | None:
    target, _, _anchor = destination.partition("#")
    if not target or target.startswith(("/", "http:", "https:", "mailto:")):
        return None
    return (source.parent / target).resolve()


def require_terminal(selected: list[Path]) -> None:
    for path in selected:
        if frontmatter(path).get("status") not in TERMINAL:
            raise ValueError(f"{path.relative_to(ROOT)} is not terminal")


def archive_records(directory: str, identifiers: list[str]) -> dict[Path, Path]:
    portfolio = load_records()
    validate_records(portfolio)
    moves: dict[Path, Path] = {}
    for identifier in identifiers:
        record = portfolio[identifier]
        if record.archived or record.path.parent != PLANNING / directory:
            raise ValueError(f"{identifier} is not a live {directory} record")
        require_terminal([record.path])
        pending = children(record, portfolio)
        while pending:
            child = pending.pop()
            require_terminal([child.path])
            pending.extend(children(child, portfolio))
        moves[record.path] = PLANNING / directory / "archive" / record.path.name
    return moves


def archive_wayfinder(name: str) -> dict[Path, Path]:
    filename = name if name.endswith(".md") else f"{name}.md"
    path = PLANNING / "wayfinder" / filename
    if not path.is_file():
        raise ValueError(f"unknown Wayfinder map: {name}")
    text = path.read_text(encoding="utf-8")
    if wayfinder_status(path) != "closed":
        raise ValueError(f"{path.relative_to(ROOT)} is not Closed")
    if not re.search(r"^## Frontier\s*\n+None\.", text, re.MULTILINE):
        raise ValueError(f"{path.relative_to(ROOT)} still has a Wayfinder frontier")
    ticket_paths = [
        PLANNING / "wayfinder/tickets" / match
        for match in re.findall(r"\]\(tickets/(WF-\d{3}[^)#]+\.md)\)", text)
    ]
    if not ticket_paths:
        raise ValueError(f"{path.relative_to(ROOT)} has no linked decision tickets")
    if any(not ticket.is_file() or wayfinder_status(ticket) != "closed" for ticket in ticket_paths):
        raise ValueError(f"{path.relative_to(ROOT)} has unresolved decision tickets")
    if not re.search(r"\]\(\.\./(?:epics|specs|tickets|tasks)/", text):
        raise ValueError(f"{path.relative_to(ROOT)} lacks a linked implementation handoff")

    moves = {path: PLANNING / "wayfinder/archive/maps" / path.name}
    for ticket in ticket_paths:
        moves[ticket] = PLANNING / "wayfinder/tickets/archive" / ticket.name
        research = PLANNING / "wayfinder/research" / f"{ticket.stem}-research.md"
        if research.is_file():
            moves[research] = PLANNING / "wayfinder/research/archive" / research.name
    return moves


def rewrite_links(moves: dict[Path, Path]) -> None:
    # Only repository-owned documents: never traverse vendor, ignored evidence, or other worktrees.
    owned = subprocess.check_output(
        ["git", "ls-files", "-z", "--cached", "--others", "--exclude-standard"], cwd=ROOT,
    ).decode().split("\0")
    documents = [
        ROOT / name for name in set(owned)
        if name.endswith(".md") and (ROOT / name).is_file()
        and not (ROOT / name).is_symlink() and (ROOT / name).resolve().is_relative_to(ROOT)
    ]
    documents.extend(path for path in moves.values() if path not in documents)
    originals = {path.resolve(): path.read_text(encoding="utf-8") for path in documents}
    normalized = {source.resolve(): destination.resolve() for source, destination in moves.items()}
    original_sources = {destination: source for source, destination in normalized.items()}

    for new_source, text in originals.items():
        old_source = original_sources.get(new_source, new_source)

        def rewritten_target(target: str) -> str:
            old_target = local_target(old_source, target)
            if old_target is None:
                return target
            new_target = normalized.get(old_target, old_target)
            if new_source == old_source and new_target == old_target:
                return target
            _path, marker, anchor = target.partition("#")
            relative = os.path.relpath(new_target, new_source.parent).replace(os.sep, "/")
            return f"{relative}{marker}{anchor}"

        rewritten = LINK.sub(
            lambda match: match.group(1) + rewritten_target(match.group(2)) + match.group(3), text,
        )
        # Evidence references are scalar metadata, not Markdown links; repair them too.
        rewritten = re.sub(
            r"^((?:review_evidence|verification|cutover_review):[ \t]*)(\S+)[ \t]*$",
            lambda match: match.group(1) + rewritten_target(match.group(2)), rewritten, flags=re.MULTILINE,
        )
        if rewritten != text:
            new_source.parent.mkdir(parents=True, exist_ok=True)
            new_source.write_text(rewritten, encoding="utf-8")


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("kind", choices=("tasks", "tickets", "specs", "epics", "wayfinder"))
    parser.add_argument("identifiers", nargs="+")
    parser.add_argument("--apply", action="store_true", help="perform the validated archive move")
    args = parser.parse_args()

    try:
        if args.kind != "wayfinder":
            moves = archive_records(args.kind, args.identifiers)
        else:
            if len(args.identifiers) != 1:
                raise ValueError("archive wayfinder accepts exactly one map name")
            moves = archive_wayfinder(args.identifiers[0])
        if any(destination.exists() for destination in moves.values()):
            raise ValueError("an archive destination already exists")
        for path in [*moves, *moves.values()]:
            if not path.resolve().is_relative_to(PLANNING.resolve()) or any(
                parent.is_symlink() for parent in [path, *path.parents] if parent != ROOT
            ):
                raise ValueError("archive paths must remain inside owned planning directories")
        if subprocess.run([str(ROOT / "bin/planning-check")], cwd=ROOT, check=False).returncode:
            raise ValueError("portfolio validation must pass before archiving")
    except (KeyError, ValueError) as exception:
        print(f"Archive refused: {exception}", file=sys.stderr)
        return 2

    action = "Archive" if args.apply else "Would archive"
    for source, destination in moves.items():
        print(f"{action}: {source.relative_to(ROOT)} -> {destination.relative_to(ROOT)}")
    if not args.apply:
        print("Dry run only. Re-run with --apply after reviewing these moves.")
        return 0

    for source, destination in moves.items():
        destination.parent.mkdir(parents=True, exist_ok=True)
        shutil.move(source, destination)
    rewrite_links(moves)
    refreshed = subprocess.run([str(ROOT / "bin/planning-check"), "--write"], cwd=ROOT, check=False)
    if refreshed.returncode:
        return refreshed.returncode
    return subprocess.run([str(ROOT / "bin/planning-check")], cwd=ROOT, check=False).returncode


if __name__ == "__main__":
    sys.exit(main())