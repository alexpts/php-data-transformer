<?php
namespace PTS\DataTransformer;

use PHPUnit_Framework_TestCase;
use PTS\DataTransformer\Types\ArrayType;
use PTS\DataTransformer\Types\BaseType;
use PTS\DataTransformer\Types\BoolType;
use PTS\DataTransformer\Types\DateType;
use PTS\DataTransformer\Types\FloatType;
use PTS\DataTransformer\Types\IntType;
use PTS\DataTransformer\Types\RefModelsToArrayStringIdType;
use PTS\DataTransformer\Types\RefModelsType;
use PTS\DataTransformer\Types\RefModelType;
use PTS\DataTransformer\Types\StringType;
use Symfony\Component\Yaml\Parser;

require_once __DIR__ .'/data/UserModel.php';

class DataTransformerTest extends PHPUnit_Framework_TestCase
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

    public function testGetMapsManager()
    {
        self::assertInstanceOf(__NAMESPACE__ . '\MapsManager', $this->transformer->getMapsManager());
    }

    public function testGetConverter()
    {
        self::assertInstanceOf(__NAMESPACE__ . '\TypeConverter', $this->transformer->getConverter());
    }

    /**
     * @return UserModel
     */
    protected function createUser()
    {
        $user = new UserModel();
        $user->setActive(true);
        $user->setEmail('some@some.com');
        $user->setLogin('login');
        $user->setName('name');

        return $user;
    }

    public function testGetData()
    {
        $user = $this->createUser();
        $this->transformer->getMapsManager()->setMapDir(UserModel::class, __DIR__ . '/data');
        $dto = $this->transformer->getData($user, 'dto');

        self::assertCount(5, $dto);
        self::assertInstanceOf('DateTime', $dto['creAt']);
        self::assertEquals('some@some.com', $dto['email']);
        self::assertEquals('name', $dto['name']);
        self::assertEquals('login', $dto['login']);
        self::assertTrue($dto['active']);
    }

    public function testFillModel()
    {
        $data = [
            'name' => 'name',
            'login' => 'login',
            'creAt' => new \DateTime,
            'active' => false,
            'email' => '',
            'someField' => 'someValue'
        ];
        $this->transformer->getMapsManager()->setMapDir(UserModel::class, __DIR__ . '/data');
        /** @var UserModel $model */
        $model = $this->transformer->createModel(UserModel::class);
        $this->transformer->fillModel($data, $model, 'dto');

        self::assertInstanceOf('DateTime', $model->getCreAt());
        self::assertEquals($data['creAt']->getTimestamp(), $model->getCreAt()->getTimestamp());
        self::assertEquals($data['email'], $model->getEmail());
        self::assertEquals($data['name'], $model->getName());
        self::assertEquals($data['login'], $model->getLogin());
        self::assertEquals($data['active'], $model->isActive());
    }
}