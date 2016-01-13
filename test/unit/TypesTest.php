<?php
namespace PTS\DataTransformer;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Yaml\Parser;

require_once __DIR__ . '/data/ContentModel.php';
require_once __DIR__ . '/data/UserModel.php';

class ArrayTypeTest extends PHPUnit_Framework_TestCase
{
    /** @var DataTransformer */
    protected $transformer;

    public function setUp()
    {
        $this->transformer = new DataTransformer(
            new TypeConverter,
            new MapsManager(new Parser),
            new ModelClosure
        );
    }

    public function testToModel()
    {
        $data = [
            'title' => 'name',
            'creAt' => new \DateTime,
            'active' => false,
            'cats' => [2, 5, 1],
            'any' => 'any',
            'float' => '12.345',
            'count' => '23',
            'user' => 'someId',
            'similarContent' => [1, 2, 4]
        ];
        $this->transformer->getMapsManager()->setMapDir(ContentModel::class, __DIR__ . '/data');
        /** @var ContentModel $model */
        $model = $this->transformer->createModel(ContentModel::class);
        $this->transformer->fillModel($data, $model, 'content');

        self::assertInstanceOf('DateTime', $model->getCreAt());
        self::assertEquals($data['creAt']->getTimestamp(), $model->getCreAt()->getTimestamp());
        self::assertEquals($data['title'], $model->getTitle());
        self::assertEquals($data['active'], $model->isActive());
        self::assertEquals($data['cats'], $model->getCats());
        self::assertEquals($data['any'], $model->getAny());
        self::assertEquals($data['float'], $model->getFloat());
        self::assertEquals($data['count'], $model->getCount());
        self::assertEquals($data['user'], $model->getUser());
        self::assertEquals($data['similarContent'], $model->getSimilarContent());
    }

    public function testToStorage()
    {
        $user = new UserModel();
        $user->setId(99);
        $user->setName('user');

        $content = new ContentModel;
        $content->setActive(true);
        $content->setTitle('title');
        $content->setCreAt(new \DateTime);
        $content->setCats([1, 2, 4]);
        $content->setAny('any');
        $content->setFloat(12.345);
        $content->setCount(23);
        $content->setUser($user);

        $similarContent = clone $content;
        $similarContent->setId('content1');
        $similarContent2 = clone $content;
        $similarContent2->setId('content2');

        $content->setSimilarContent([$similarContent, $similarContent2]);


        $this->transformer->getMapsManager()->setMapDir(ContentModel::class, __DIR__ . '/data');
        $dto = $this->transformer->getData($content, 'content');

        self::assertInstanceOf('DateTime', $dto['creAt']);
        self::assertEquals('title', $dto['title']);
        self::assertEquals([1, 2, 4], $dto['cats']);
        self::assertEquals('any', $dto['any']);
        self::assertEquals(12.345, $dto['float']);
        self::assertEquals(23, $dto['count']);
        self::assertEquals(99, $dto['user']);
        self::assertEquals(['content1', 'content2'], $dto['similarContent']);
        self::assertTrue($dto['active']);
    }

}