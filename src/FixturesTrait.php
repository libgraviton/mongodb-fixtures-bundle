<?php

namespace Graviton\MongoDB\Fixtures;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ResettableContainerInterface;

trait FixturesTrait
{
    static protected $environment = 'test';
    static protected $containers = [];

    protected static function getContainer(): ContainerInterface
    {
        $cacheKey = self::$environment;
        if (empty(self::$containers[$cacheKey])) {
            $options = [
                'environment' => self::$environment,
            ];
            $kernel = self::createKernel($options);
            $kernel->boot();

            $container = $kernel->getContainer();
            if ($container->has('test.service_container')) {
                self::$containers[$cacheKey] = $container->get('test.service_container');
            } else {
                self::$containers[$cacheKey] = $container;
            }
        }

        return self::$containers[$cacheKey];
    }

    protected function loadFixtures(array $classNames = [], bool $append = false, ?string $omName = null, string $registryName = 'doctrine', ?int $purgeMode = null)
    {
        if (is_numeric($omName)) {
            throw new \LogicException("You must provide an 'omName' parameter to specify which DocumentManager to use!");
        }

        /**
         * @var $dm DocumentManager document manager
         */
        $dm = $this->getContainer()->get($omName);

        if (!$append) {
            $this->mongoDbPurge($dm);
        }

        // load classes
        foreach ($classNames as $className) {
            if (!class_exists($className)) {
                throw new \LogicException('Fixtures class "'.$className.'" could not be found.');
            }

            $inst = $this->getContainer()->get($className);

            if (!$inst instanceof FixtureInterface) {
                throw new \LogicException('Fixtures class "'.$className.'" is not instance of FixtureInterface.');
            }

            $inst->load($dm);
        }
    }

    protected function mongoDbPurge(DocumentManager $dm)
    {
        $metadatas = $dm->getMetadataFactory()->getAllMetadata();
        foreach ($metadatas as $metadata) {
            if (!$metadata->isMappedSuperclass) {
                $dm->getDocumentCollection($metadata->name)->drop();
            }
        }
        $dm->getSchemaManager()->ensureIndexes();
    }

    protected function tearDown(): void
    {
        if (is_array(self::$containers)) {
            foreach (self::$containers as $idx => $container) {
                if ($container instanceof ResettableContainerInterface) {
                    self::$containers[$idx]->reset();
                }
            }
        }

        self::$containers = null;

        parent::tearDown();
    }
}
