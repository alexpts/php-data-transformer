<?php
namespace PTS\DataTransformer;

use PHPUnit_Framework_TestCase;

require_once __DIR__ .'/data/CustomType.php';

class TypeConverterTest extends PHPUnit_Framework_TestCase
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