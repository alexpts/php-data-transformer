<?php
namespace PTS\DataTransformer;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Yaml\Parser;

class MapsManagerTest extends PHPUnit_Framework_TestCase
{
    /** @var MapsManager */
    protected $manager;

    public function setUp()
    {
        $this->manager = new MapsManager(new Parser);
    }

    public function testSetMapDir()
    {
        $this->manager->setMapDir('model.user', __DIR__ . '/data');
    }

    public function testGetMap()
    {
        $this->manager->setMapDir('model.user', __DIR__ . '/data');
        $map = $this->manager->getMap('model.user', 'dto');
        self::assertCount(6, $map);
    }

    public function testGetMapWithCache()
    {
        $this->manager->setMapDir('model.user', __DIR__ . '/data');
        $map = $this->manager->getMap('model.user', 'dto');
        $map2 = $this->manager->getMap('model.user', 'dto');
        self::assertCount(6, $map2);
        self::assertEquals($map, $map2);
    }
}