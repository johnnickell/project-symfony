<?php

declare(strict_types=1);

namespace App\Composition\FrameworkSupport;

use InvalidArgumentException;

final class ReceiptCanonicalizer
{
    public const string SCHEMA_VERSION = 'fight-common.framework-support-receipt/v1';
    public const string CANDIDATE_REFERENCE = '4a798b1db8fdb5e4af7d0ba8c98a88ac53c50c16';
    public const string CANDIDATE_VERSION = 'dev-develop';

    /** @var list<string> */
    private const array ROOT_KEYS = [
        'schema_version', 'content_id', 'candidate', 'framework', 'lock_sha256', 'capabilities', 'journeys', 'result',
        'evidence', 'next_action',
    ];

    /** @var list<string> */
    private const array RESULTS = ['passed', 'failed', 'unavailable', 'skipped', 'indeterminate'];

    /** @param array<string, mixed> $receipt */
    public static function canonicalize(array $receipt): string
    {
        self::assertValid($receipt);

        return json_encode($receipt, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n";
    }

    /** @param array<string, mixed> $receipt */
    public static function withDigests(array $receipt): array
    {
        $receipt['content_id'] = self::digest($receipt, true, true);
        $receipt['evidence']['receipt_sha256'] = self::digest($receipt, false, true);

        return $receipt;
    }

    /** @param array<string, mixed> $receipt */
    public static function assertValid(array $receipt): void
    {
        if (array_keys($receipt) !== self::ROOT_KEYS || ($receipt['schema_version'] ?? null) !== self::SCHEMA_VERSION) {
            throw new InvalidArgumentException('Receipt root shape is not canonical.');
        }

        if (
            !is_array($receipt['candidate'] ?? null)
            || $receipt['candidate'] !== [
                'package' => 'johnnickell/fight-common',
                'version' => self::CANDIDATE_VERSION,
                'reference' => self::CANDIDATE_REFERENCE,
            ]
        ) {
            throw new InvalidArgumentException('Receipt candidate does not match the selected Fight Common candidate.');
        }

        foreach (['content_id', 'lock_sha256'] as $field) {
            self::assertDigest($receipt[$field] ?? null, $field);
        }

        if (
            !is_array($receipt['framework'] ?? null)
            || array_keys($receipt['framework']) !== ['name', 'version', 'providers']
            || $receipt['framework']['name'] !== 'symfony'
            || !is_string($receipt['framework']['version'])
            || $receipt['framework']['version'] === ''
            || !is_array($receipt['framework']['providers'])
            || $receipt['framework']['providers'] === []
        ) {
            throw new InvalidArgumentException('Receipt framework declaration is not canonical.');
        }

        if (!is_array($receipt['capabilities'] ?? null) || $receipt['capabilities'] === []) {
            throw new InvalidArgumentException('Receipt capabilities are required.');
        }

        foreach ($receipt['capabilities'] as $name => $state) {
            if (!is_string($name) || $name === '' || !in_array($state, ['ship', 'wire', 'unavailable'], true)) {
                throw new InvalidArgumentException('Receipt capability state is unsupported.');
            }
        }

        if (!is_array($receipt['journeys'] ?? null) || $receipt['journeys'] === []) {
            throw new InvalidArgumentException('Receipt journeys are required.');
        }

        foreach ($receipt['journeys'] as $journey) {
            if (
                !is_array($journey)
                || array_keys($journey) !== ['name', 'status', 'evidence']
                || !is_string($journey['name']) || $journey['name'] === ''
                || !in_array($journey['status'], self::RESULTS, true)
                || !is_string($journey['evidence']) || $journey['evidence'] === ''
            ) {
                throw new InvalidArgumentException('Receipt journey is not canonical.');
            }
        }

        if (
            $receipt['result'] !== 'passed'
            || $receipt['next_action'] !== null
            || array_any($receipt['journeys'], static fn(array $journey): bool => $journey['status'] !== 'passed')
        ) {
            throw new InvalidArgumentException('A passing receipt requires only passing journeys and no next action.');
        }

        if (
            !is_array($receipt['evidence'] ?? null)
            || array_keys($receipt['evidence']) !== ['build', 'planning_check', 'receipt_sha256']
            || !is_string($receipt['evidence']['build']) || $receipt['evidence']['build'] === ''
            || !is_string($receipt['evidence']['planning_check']) || $receipt['evidence']['planning_check'] === ''
        ) {
            throw new InvalidArgumentException('Receipt evidence declaration is not canonical.');
        }

        self::assertDigest($receipt['evidence']['receipt_sha256'], 'evidence.receipt_sha256');

        if (!hash_equals($receipt['content_id'], self::digest($receipt, true, true))) {
            throw new InvalidArgumentException('Receipt content ID has drifted.');
        }

        if (!hash_equals($receipt['evidence']['receipt_sha256'], self::digest($receipt, false, true))) {
            throw new InvalidArgumentException('Receipt evidence digest has drifted.');
        }
    }

    /** @param array<string, mixed> $receipt */
    private static function digest(array $receipt, bool $withoutContentId, bool $withoutReceiptDigest): string
    {
        if ($withoutContentId) {
            $receipt['content_id'] = str_repeat('0', 64);
        }

        if ($withoutReceiptDigest) {
            $receipt['evidence']['receipt_sha256'] = str_repeat('0', 64);
        }

        return hash('sha256', json_encode($receipt, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
    }

    private static function assertDigest(mixed $value, string $field): void
    {
        if (!is_string($value) || preg_match('/^[a-f0-9]{64}$/', $value) !== 1) {
            throw new InvalidArgumentException(sprintf('Receipt %s must be a lowercase SHA-256 digest.', $field));
        }
    }
}
