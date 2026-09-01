<?php

declare(strict_types=1);

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;

return [
    DoctrineBundle::class => ['all' => true],
    FrameworkBundle::class => ['all' => true],
    TwigBundle::class => ['all' => true],
];
