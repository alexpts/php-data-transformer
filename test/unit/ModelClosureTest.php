<?php
namespace PTS\DataTransformer;

use PHPUnit_Framework_TestCase;

class ModelClosureTest extends PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $modelClosures = new ModelClosure;
        self::assertInstanceOf('\Closure', $modelClosures->getFillFn());
        self::assertInstanceOf('\Closure', $modelClosures->getToDataFn());
    }

    public function testGettersCache()
    {
        $modelClosures = new ModelClosure;
        $modelClosures->getFillFn();
        $modelClosures->getToDataFn();

        self::assertInstanceOf('\Closure', $modelClosures->getFillFn());
        self::assertInstanceOf('\Closure', $modelClosures->getToDataFn());
    }
}