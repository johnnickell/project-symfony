<?php

declare(strict_types=1);

$projectRoot = dirname(__DIR__);
$candidateRoot = $argv[1] ?? $projectRoot.'/vendor/johnnickell/fight-common';
$authorityFile = $candidateRoot.'/release/src/Application/StarterSupportReceiptAuthority.php';
if (!is_file($authorityFile)) {
    fwrite(STDERR, "Fight Common receipt authority is missing from the exact candidate checkout.\n");
    exit(1);
}

require $projectRoot.'/scripts/framework-support-receipt.php';
require $authorityFile;

$receiptPath = $projectRoot.'/evidence/framework-support/receipt-v1.json';
$receipt = json_decode((string) file_get_contents($receiptPath), true, flags: JSON_THROW_ON_ERROR);
$authority = new Fight\Release\Application\StarterSupportReceiptAuthority();

if (!$authority->isValid($receipt)) {
    fwrite(STDERR, "Fight Common StarterSupportReceiptAuthority rejected the committed receipt.\n");
    exit(1);
}

if (!hash_equals(frameworkSupportCanonicalJson(frameworkSupportReceipt($projectRoot)), (string) file_get_contents($receiptPath))) {
    fwrite(STDERR, "The committed receipt bytes do not match the current latest lock evidence.\n");
    exit(1);
}
