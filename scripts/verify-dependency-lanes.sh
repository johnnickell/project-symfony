#!/usr/bin/env sh
set -eu

project_root=$(CDPATH= cd -- "$(dirname "$0")/.." && pwd)
mode=${1:-verify}

copy_lane() {
    target=$1
    for path in composer.json config public scripts src templates tests phpunit.xml.dist; do
        cp -R "$project_root/$path" "$target/"
    done
}

if [ "$mode" = "refresh-lowest" ]; then
    lane_root=$(mktemp -d)
    trap 'rm -rf "$lane_root"' EXIT
    copy_lane "$lane_root"
    composer update --working-dir="$lane_root" --prefer-lowest --prefer-stable --no-interaction --no-progress
    cp "$lane_root/composer.lock" "$project_root/composer-lowest.lock"
    php -r 'echo hash_file("sha256", $argv[1]), PHP_EOL;' "$project_root/composer-lowest.lock" > "$project_root/composer-lowest.lock.sha256"
    exit 0
fi

if [ "$mode" != "verify" ]; then
    echo "Usage: scripts/verify-dependency-lanes.sh [verify|refresh-lowest]" >&2
    exit 2
fi

expected_lowest=$(tr -d '\r\n' < "$project_root/composer-lowest.lock.sha256")
actual_lowest=$(php -r 'echo hash_file("sha256", $argv[1]);' "$project_root/composer-lowest.lock")
if [ "$expected_lowest" != "$actual_lowest" ]; then
    echo "composer-lowest.lock digest has drifted." >&2
    exit 1
fi

for lane in latest lowest; do
    lane_root=$(mktemp -d)
    copy_lane "$lane_root"
    if [ "$lane" = latest ]; then
        cp "$project_root/composer.lock" "$lane_root/composer.lock"
    else
        cp "$project_root/composer-lowest.lock" "$lane_root/composer.lock"
    fi
    before=$(php -r 'echo hash_file("sha256", $argv[1]);' "$lane_root/composer.lock")
    composer install --working-dir="$lane_root" --no-interaction --prefer-dist --no-progress
    after=$(php -r 'echo hash_file("sha256", $argv[1]);' "$lane_root/composer.lock")
    if [ "$before" != "$after" ]; then
        echo "$lane dependency lane changed its lock during install." >&2
        exit 1
    fi
    (
        cd "$lane_root"
        APP_ENV=test APP_SECRET=lane-only-secret vendor/bin/phpunit tests/Composition tests/Integration tests/Functional
    )
    rm -rf "$lane_root"
done
