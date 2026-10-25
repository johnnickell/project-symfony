#!/usr/bin/env python3
"""Run the selected checkout's Compose commands, refusing foreign resources."""

import json
import os
from pathlib import Path
import socket
import subprocess
import sys

ROOT = Path(__file__).resolve().parent.parent
ENV = dict(os.environ, PROJECT_SYMFONY_CHECKOUT=str(ROOT))
COMPOSE = ["docker", "compose", "--project-directory", str(ROOT), "-f", str(ROOT / "compose.yaml")]


def fail(message):
    raise RuntimeError(message)


def output(command):
    return subprocess.check_output(command, cwd=ROOT, env=ENV, text=True).strip()


def run(arguments):
    subprocess.run(COMPOSE + arguments, cwd=ROOT, env=ENV, check=True)


def inspect(ids):
    return json.loads(output(["docker", "inspect", *ids])) if ids else []


def guard(starting=False):
    if (ROOT / ".git").is_file() and not ENV.get("COMPOSE_PROJECT_NAME"):
        fail("Linked worktrees require an explicit unique COMPOSE_PROJECT_NAME and free HTTP_PORT; see docs/engineering/BUILD.md.")
    # Do not let an override attach another checkout or reuse its mutable trees.
    for name in ("vendor", "var"):
        path = ROOT / name
        if path.is_symlink() or (path.exists() and not path.is_dir()):
            fail(f"{path} must be a checkout-owned directory, not a symlink or file.")
    for base in (ROOT / "vendor", ROOT / "var"):
        if base.exists():
            for directory, directories, files in os.walk(base, followlinks=False):
                for name in directories + files:
                    path = Path(directory) / name
                    if path.is_symlink() and not path.resolve().is_relative_to(ROOT):
                        fail(f"Mutable-tree symlink escapes this checkout: {path}")
    config = json.loads(output(COMPOSE + ["config", "--format", "json"]))
    project = config["name"]
    for service in ("api", "server"):
        mounts = config["services"][service]["volumes"]
        if not any(m.get("type") == "bind" and m.get("target") == "/app"
                   and Path(m["source"]).resolve() == ROOT for m in mounts):
            fail(f"{service} must bind this checkout at /app.")
    ids = output(["docker", "ps", "-aq", "--filter", f"label=com.docker.compose.project={project}"]).split()
    containers = inspect(ids)
    for container in containers:
        labels = container["Config"]["Labels"]
        owner = labels.get("com.docker.compose.project.working_dir", "")
        if not owner or Path(owner).resolve() != ROOT:
            fail(f"Compose project {project} belongs to another checkout ({container['Name']}); choose a unique COMPOSE_PROJECT_NAME.")
        if not any(m["Type"] == "bind" and m["Destination"] == "/app"
                   and Path(m["Source"]).resolve() == ROOT for m in container["Mounts"]):
            fail(f"{container['Name']} does not mount this checkout at /app; inspect it before proceeding.")
    networks = output(["docker", "network", "ls", "-q", "--filter", f"label=com.docker.compose.project={project}"]).split()
    if networks:
        for network in json.loads(output(["docker", "network", "inspect", *networks])):
            owner = (network.get("Labels") or {}).get("project-symfony.checkout")
            # Existing pre-guard networks can be adopted only with matching containers.
            if owner != str(ROOT) and not (owner is None and containers):
                fail(f"Network {network['Name']} has unknown/foreign checkout ownership; inspect it, do not remove it blindly.")
            if set(network.get("Containers", {})) - {c["Id"] for c in containers}:
                fail(f"Network {network['Name']} has foreign attached containers; stop and inspect ownership.")
    if starting:
        port = int(config["services"]["server"]["ports"][0]["published"])
        if not 1 <= port <= 65535:
            fail("HTTP_PORT must be between 1 and 65535.")
        allowed = {c["Id"] for c in containers if c["Config"]["Labels"].get("com.docker.compose.service") == "server"}
        publishers = output(["docker", "ps", "-q", "--no-trunc", "--filter", f"publish={port}"]).split()
        if set(publishers) - allowed:
            fail(f"HTTP port {port} belongs to another container; choose a free HTTP_PORT. No services were changed.")
        if not publishers:
            with socket.socket() as probe:
                try:
                    probe.bind(("0.0.0.0", port))
                except OSError:
                    fail(f"HTTP port {port} is unavailable; choose a free HTTP_PORT. No services were changed.")
    print(f"Compose checkout={ROOT} project={project}", flush=True)


def main():
    action, *arguments = sys.argv[1:]
    if action not in ("up", "down", "exec"):
        fail("Expected up, down or exec.")
    if action == "up" and arguments not in ([], ["--logs"]):
        fail("Usage: ./bin/up [--logs]")
    if action == "down" and arguments:
        fail("Usage: ./bin/down (preserves volumes)")
    guard(starting=action == "up")
    if action == "up":
        run(["build", "--pull", "api"])
        run(["build", "server"])
        run(["up", "-d", "--force-recreate", "--wait", "--wait-timeout", "60"])
        if arguments:
            run(["logs", "-f"])
    elif action == "down":
        run(["down"])
    else:
        if not output(COMPOSE + ["ps", "--status", "running", "-q", "api"]):
            fail("Selected api service is not running; run ./bin/prepare first.")
        run(["exec", "-T", "api", *arguments])


if __name__ == "__main__":
    try:
        main()
    except (RuntimeError, ValueError, KeyError, subprocess.CalledProcessError, OSError) as error:
        print(f"Compose preparation: {error}", file=sys.stderr)
        sys.exit(error.returncode if isinstance(error, subprocess.CalledProcessError) else 1)
