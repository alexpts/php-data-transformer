<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\DataTransformer\Types\ArrayType;
use PTS\DataTransformer\Types\BaseType;
use PTS\DataTransformer\Types\BoolType;
use PTS\DataTransformer\Types\DateType;
use PTS\DataTransformer\Types\FloatType;
use PTS\DataTransformer\Types\IntType;
use PTS\DataTransformer\Types\RefModelsType;
use PTS\DataTransformer\Types\RefModelType;
use PTS\DataTransformer\Types\StringType;
use PTS\DataTransformer\Types\RefModelsToArrayStringIdType;
use Symfony\Component\Yaml\Parser;

require_once __DIR__ . '/data/ContentModel.php';
require_once __DIR__ . '/data/UserModel.php';
require_once __DIR__ . '/data/BadType.php';
require_once __DIR__ . '/data/BadType2.php';

class ArrayTypeTest extends TestCase
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

        $this->addTypesToConverter();
    }

    protected function addTypesToConverter()
    {
        $converter = $this->transformer->getConverter();
        $converter->addType('proxy', new BaseType);
        $converter->addType('int', new IntType);
        $converter->addType('string', new StringType);
        $converter->addType('array', new ArrayType);
        $converter->addType('date', new DateType);
        $converter->addType('float', new FloatType);
        $converter->addType('bool', new BoolType);
        $converter->addType('refModelsToArrayStringId', new RefModelsToArrayStringIdType);
        $converter->addType('refModels', new RefModelsType);
        $converter->addType('refModel', new RefModelType);
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
            'similarContent' => [1, 2, 4],
            'someRelIds' => [48, 21, 4],
            'prevUsers' => [
                [
                    'id' => 1,
                    'name' => 'alex',
                    'login' => 'alex',
                    'creAt' => new \DateTime,
                    'active' => false,
                    'email' => 'alex@gmail.com'
                ]
            ]
        ];
        $this->transformer->getMapsManager()->setMapDir(ContentModel::class, __DIR__ . '/data');
        $this->transformer->getMapsManager()->setMapDir(UserModel::class, __DIR__ . '/data');
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
        self::assertEquals($data['someRelIds'], $model->getSomeRelIds());

        self::assertCount(1, $model->getPrevUsers());
        self::assertEquals($data['prevUsers'][0]['email'], $model->getPrevUsers()[0]->getEmail());
        self::assertEquals($data['prevUsers'][0]['id'], $model->getPrevUsers()[0]->getId());
        self::assertEquals($data['prevUsers'][0]['login'], $model->getPrevUsers()[0]->getLogin());
        self::assertEquals($data['prevUsers'][0]['name'], $model->getPrevUsers()[0]->getName());
        self::assertEquals($data['prevUsers'][0]['creAt'], $model->getPrevUsers()[0]->getCreAt());
    }

    public function testToData()
    {
        $user = new UserModel();
        $user->setId(99);
        $user->setName('user');
        $user->setLogin('user');
        $user->setEmail('user@gmial.com');
        $user->setActive(true);
        $user->setCreAt(new \DateTime);

        $content = new ContentModel;
        $content->setActive(true);
        $content->setTitle('title');
        $content->setCreAt(new \DateTime);
        $content->setCats([1, 2, 4]);
        $content->setAny('any');
        $content->setFloat(12.345);
        $content->setCount(23);
        $content->setUser($user);
        $content->setPrevUsers([$user]);
        $content->setSomeRelIds([12, 31]);


        $similarContent = clone $content;
        $similarContent->setId('content1');
        $similarContent2 = clone $content;
        $similarContent2->setId('content2');

        $content->setSimilarContent([$similarContent, $similarContent2]);

        $this->transformer->getMapsManager()->setMapDir(ContentModel::class, __DIR__ . '/data');
        $this->transformer->getMapsManager()->setMapDir(UserModel::class, __DIR__ . '/data');
        $dto = $this->transformer->getData($content, 'content');

        self::assertInstanceOf('DateTime', $dto['creAt']);
        self::assertEquals('title', $dto['title']);
        self::assertEquals([1, 2, 4], $dto['cats']);
        self::assertEquals('any', $dto['any']);
        self::assertEquals(12.345, $dto['float']);
        self::assertEquals(23, $dto['count']);
        self::assertEquals(99, $dto['user']);
        self::assertEquals([12, 31], $dto['someRelIds']);
        self::assertEquals(['content1', 'content2'], $dto['similarContent']);
        self::assertTrue($dto['active']);

        self::assertCount(1, $dto['prevUsers']);
        self::assertEquals($dto['prevUsers'][0]['id'], $user->getId());
        self::assertEquals($dto['prevUsers'][0]['email'], $user->getEmail());
        self::assertEquals($dto['prevUsers'][0]['name'], $user->getName());
        self::assertEquals($dto['prevUsers'][0]['login'], $user->getLogin());
        self::assertInstanceOf('DateTime', $dto['prevUsers'][0]['creAt']);
    }

    /**
     * @expectedException \PTS\DataTransformer\TypeException
     */
    public function testBadType2()
    {
        $converter = $this->transformer->getConverter();
        $converter->addType('badType2', new BadType2);
    }

    /**
     * @expectedException \PTS\DataTransformer\TypeException
     */
    public function testBadType()
    {
        $converter = $this->transformer->getConverter();
        $converter->addType('badType', new BadType);
    }

}