<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Parser;

class MapsManagerTest extends TestCase
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
        self::assertNotNull(1, $this->manager->getMap('model.user'));
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