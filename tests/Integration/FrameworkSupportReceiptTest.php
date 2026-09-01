<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Composition\FrameworkSupport\ReceiptCanonicalizer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class FrameworkSupportReceiptTest extends TestCase
{
    public function testTheCommittedReceiptIsCanonicalAndMatchesTheSelectedCandidate(): void
    {
        $path = dirname(__DIR__, 2).'/evidence/framework-support/receipt-v1.json';
        $contents = (string) file_get_contents($path);
        $receipt = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);

        ReceiptCanonicalizer::assertValid($receipt);

        self::assertSame(ReceiptCanonicalizer::canonicalize($receipt), $contents);
        self::assertSame(ReceiptCanonicalizer::CANDIDATE_REFERENCE, $receipt['candidate']['reference']);
        self::assertSame(hash_file('sha256', dirname(__DIR__, 2).'/composer.lock'), $receipt['lock_sha256']);
    }

    public function testTheValidatorRejectsCandidateDigestAndPassingJourneyDrift(): void
    {
        $receipt = $this->receipt();
        $candidateDrift = $receipt;
        $candidateDrift['candidate']['reference'] = str_repeat('a', 40);
        $digestDrift = $receipt;
        $digestDrift['lock_sha256'] = str_repeat('a', 64);
        $journeyDrift = $receipt;
        $journeyDrift['journeys'][0]['status'] = 'failed';

        foreach ([$candidateDrift, $digestDrift, $journeyDrift] as $invalidReceipt) {
            try {
                ReceiptCanonicalizer::assertValid($invalidReceipt);
                self::fail('Malformed receipt must fail closed.');
            } catch (InvalidArgumentException) {
                self::addToAssertionCount(1);
            }
        }
    }

    /** @return array<string, mixed> */
    private function receipt(): array
    {
        $path = dirname(__DIR__, 2).'/evidence/framework-support/receipt-v1.json';

        return json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }
}
