<?php

namespace Graviton\MongoDB\Fixtures;

use Doctrine\ODM\MongoDB\DocumentManager;

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
     * @param DocumentManager $manager
     */
    public function load(DocumentManager $manager);
}
