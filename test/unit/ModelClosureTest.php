<?php
namespace PTS\DataTransformer;

use PHPUnit_Framework_TestCase;

class ModelClosureTest extends PHPUnit_Framework_TestCase
{
    public function testGettersCache()
    {
        $modelClosures = new ModelClosure;
        $modelClosures->getFromModel();
        $modelClosures->setToModel();

        self::assertInstanceOf('\Closure', $modelClosures->getFromModel());
        self::assertInstanceOf('\Closure', $modelClosures->setToModel());
    }
}