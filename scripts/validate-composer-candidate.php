<?php

declare(strict_types=1);

/**
 * Validates the Composer manifest while Fight Common is an immutable pre-tag
 * candidate. Composer has no commit-reference-specific suppression flag, so
 * this deliberately permits one and only one warning. Remove this allowlist
 * when Fight Common 1.2 has a release tag.
 */

const EXPECTED_WARNING = '- The package "johnnickell/fight-common" is pointing to a commit-ref, this is bad practice and can cause unforeseen issues.';

$process = proc_open(
    ['composer', 'validate'],
    [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
    $pipes,
    dirname(__DIR__),
);

if (!is_resource($process)) {
    fwrite(STDERR, "Unable to start composer validate.\n");
    exit(1);
}

$output = stream_get_contents($pipes[1]).stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);
$exitCode = proc_close($process);

fwrite(STDOUT, $output);

if ($exitCode !== 0) {
    fwrite(STDERR, "composer validate reported an error.\n");
    exit($exitCode);
}

$sections = preg_split('/^# General warnings\R/m', $output, 2);
$warnings = [];

if (count($sections) === 2) {
    foreach (preg_split('/\R/', $sections[1]) as $line) {
        if (str_starts_with($line, '- ')) {
            $warnings[] = $line;
        }
    }
}

if ($warnings !== [EXPECTED_WARNING]) {
    fwrite(STDERR, "Unexpected Composer warnings; only the pinned Fight Common commit-reference warning is allowed.\n");
    exit(1);
}
