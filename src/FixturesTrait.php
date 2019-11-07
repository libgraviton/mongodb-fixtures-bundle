<?php

namespace Graviton\MongoDB\Fixtures;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ResettableContainerInterface;

trait FixturesTrait
{
    protected $environment = 'test';

    protected function loadFixtures(array $classNames = [], bool $append = false, ?string $omName = null, string $registryName = 'doctrine', ?int $purgeMode = null): ?AbstractExecutor
    {
        $container = $this->getContainer();


    }
}
