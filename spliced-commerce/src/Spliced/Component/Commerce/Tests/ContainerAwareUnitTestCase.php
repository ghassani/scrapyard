<?php
/**
 * http://stackoverflow.com/questions/7004601/symfony2-testing-entity-validation-constraints/7016258#7016258
 */
namespace Spliced\Component\Commerce\Tests;

// This assumes that this class file is located at:
// src/Application/AcmeBundle/Tests/ContainerAwareUnitTestCase.php
// with Symfony 2.0 Standard Edition layout. You may need to change it
// to fit your own file system mapping.
require_once '/var/www/project/Symfony/frontend/AppKernel.php';

class ContainerAwareUnitTestCase extends \PHPUnit_Framework_TestCase
{
    protected static $kernel;
    protected static $container;

    public static function setUpBeforeClass()
    {
        self::$kernel = new \AppKernel('dev', true);
        self::$kernel->boot();

        self::$container = self::$kernel->getContainer();
    }

    public function get($serviceId)
    {
        return self::$kernel->getContainer()->get($serviceId);
    }
}
