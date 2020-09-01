<?php

namespace Graviton\MongoDB\Fixtures;

use Doctrine\Persistence\ObjectManager;

/**
 * Interface contract for fixture classes to implement.
 *
 * @author Jonathan H. Wage <jonwage@gmail.com>
 */
interface FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager);
}
