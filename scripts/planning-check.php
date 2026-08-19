<?php

declare(strict_types=1);

$projectRoot = dirname(__DIR__);
$required = [
    'planning/README.md',
    'planning/ROADMAP.md',
    'planning/specs/README.md',
    'planning/specs/00001-PRD.md',
    'planning/tickets/README.md',
    'planning/tickets/00001-TICKET.md',
    'planning/tickets/00002-TICKET.md',
    'planning/tickets/BOARD.md',
];

foreach ($required as $path) {
    if (!is_file($projectRoot.'/'.$path)) {
        throw new RuntimeException(sprintf('Missing canonical planning artifact: %s', $path));
    }
}

$prd = (string) file_get_contents($projectRoot.'/planning/specs/00001-PRD.md');
$ticket = (string) file_get_contents($projectRoot.'/planning/tickets/00001-TICKET.md');
$webFoundationTicket = (string) file_get_contents($projectRoot.'/planning/tickets/00002-TICKET.md');
$board = (string) file_get_contents($projectRoot.'/planning/tickets/BOARD.md');

foreach ([
    'id: PRD-00001' => $prd,
    'status: adopted-local-authority' => $prd,
    'id: T-00001' => $ticket,
    'prd: PRD-00001' => $ticket,
    'id: T-00002' => $webFoundationTicket,
    'prd: PRD-00001' => $webFoundationTicket,
    '## Ready Frontier' => $board,
    '00001-TICKET.md' => $board,
] as $requiredText => $document) {
    if (!str_contains($document, $requiredText)) {
        throw new RuntimeException(sprintf('Planning authority is incomplete: %s', $requiredText));
    }
}

fwrite(STDOUT, "Planning integrity: clean\n");
