<?php

declare(strict_types=1);

use Deptrac\Deptrac\Contract\Config\Collector\ClassLikeConfig;
use Deptrac\Deptrac\Contract\Config\Collector\PhpInteralConfig;
use Deptrac\Deptrac\Contract\Config\DeptracConfig;
use Deptrac\Deptrac\Contract\Config\Layer;
use Deptrac\Deptrac\Contract\Config\Ruleset;

return static function (DeptracConfig $config): void {
    $config
        ->paths(__DIR__.'/src')
        ->layers(
            $domain = Layer::withName('Domain')->collectors(
                ClassLikeConfig::create('^App\\Domain\\'),
            ),
            $application = Layer::withName('Application')->collectors(
                ClassLikeConfig::create('^App\\Application\\'),
            ),
            $adapter = Layer::withName('Adapter')->collectors(
                ClassLikeConfig::create('^App\\Adapter\\'),
            ),
            $fightDomain = Layer::withName('Fight Domain')->collectors(
                ClassLikeConfig::create('^Fight\\(?:AccessControl|Common)\\Domain\\'),
            ),
            $fightApplication = Layer::withName('Fight Application')->collectors(
                ClassLikeConfig::create('^Fight\\(?:AccessControl|Common)\\Application\\'),
            ),
            $fightAdapter = Layer::withName('Fight Adapter')->collectors(
                ClassLikeConfig::create('^Fight\\Common\\Adapter\\'),
            ),
            $framework = Layer::withName('Framework infrastructure')->collectors(
                ClassLikeConfig::create('^(?:Doctrine|Symfony|Twig)\\'),
            ),
            $psr = Layer::withName('PSR contracts')->collectors(
                ClassLikeConfig::create('^Psr\\'),
            ),
            $phpInternals = Layer::withName('PHP internals')->collectors(
                PhpInteralConfig::create('.*'),
            ),
        )
        ->rulesets(
            Ruleset::forLayer($domain)->accesses($fightDomain, $phpInternals),
            Ruleset::forLayer($application)->accesses(
                $domain,
                $fightDomain,
                $fightApplication,
                $psr,
                $phpInternals,
            ),
            Ruleset::forLayer($adapter)->accesses(
                $application,
                $domain,
                $fightApplication,
                $fightDomain,
                $fightAdapter,
                $framework,
                $psr,
                $phpInternals,
            ),
            Ruleset::forLayer($fightDomain),
            Ruleset::forLayer($fightApplication)->accesses($fightDomain, $psr, $phpInternals),
            Ruleset::forLayer($fightAdapter)->accesses(
                $fightApplication,
                $fightDomain,
                $framework,
                $psr,
                $phpInternals,
            ),
            Ruleset::forLayer($framework),
            Ruleset::forLayer($psr),
            Ruleset::forLayer($phpInternals),
        )
        ->cacheFile(__DIR__.'/var/cache/deptrac.cache');
};
