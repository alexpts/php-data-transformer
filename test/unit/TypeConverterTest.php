<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/data/CustomType.php';

class TypeConverterTest extends TestCase
{
    /** @var TypeConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new TypeConverter;
    }

    public function testAddType()
    {
        $beforeCount = count($this->converter->getTypes());
        $this->converter->addType('custom', new CustomType);
        $afterCount = count($this->converter->getTypes());

        self::assertEquals(1, $afterCount - $beforeCount);
        self::assertInstanceOf(CustomType::class, $this->converter->getTypes()['custom']);
    }
}