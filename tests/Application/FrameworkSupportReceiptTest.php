<?php

declare(strict_types=1);

namespace App\Tests\Application;

use Fight\Release\Application\StarterSupportReceiptAuthority;
use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 2).'/vendor/johnnickell/fight-common/release/src/Application/StarterSupportReceiptAuthority.php';

final class FrameworkSupportReceiptTest extends TestCase
{
    public function testTheCommittedDataOnlyReceiptMatchesTheLatestLockAndRealAuthority(): void
    {
        $root = dirname(__DIR__, 2);
        require_once $root.'/scripts/framework-support-receipt.php';
        $contents = (string) file_get_contents($root.'/evidence/framework-support/receipt-v1.json');
        $receipt = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);

        self::assertTrue((new StarterSupportReceiptAuthority())->isValid($receipt));
        self::assertSame(frameworkSupportCanonicalJson(frameworkSupportReceipt($root)), $contents);
        self::assertSame('1.2.0-dev', $receipt['candidate']['version']);
        self::assertSame('4a798b1db8fdb5e4af7d0ba8c98a88ac53c50c16', $receipt['candidate']['reference']);
        self::assertSame(hash_file('sha256', $root.'/composer.lock'), $receipt['lock_sha256']);
    }

    public function testAuthorityAcceptsResumableNonPassingEvidenceAndRejectsMissingNextAction(): void
    {
        $receipt = json_decode(
            (string) file_get_contents(dirname(__DIR__, 2).'/evidence/framework-support/receipt-v1.json'),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
        $receipt['result'] = 'failed';
        $receipt['journeys'][0]['status'] = 'failed';
        $receipt['next_action'] = ['action' => 'refresh_lowest_lock_and_retry'];
        $authority = new StarterSupportReceiptAuthority();

        self::assertTrue($authority->isValid($receipt));

        $receipt['next_action'] = null;
        self::assertFalse($authority->isValid($receipt));
    }
}
