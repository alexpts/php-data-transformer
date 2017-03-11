<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;

class ModelClosureTest extends TestCase
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