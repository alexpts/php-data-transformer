<?php
namespace PTS\DataTransformer;

use PHPUnit_Framework_TestCase;
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
    }

    public function testGetMapsManager()
    {
        self::assertInstanceOf(__NAMESPACE__ . '\MapsManager', $this->transformer->getMapsManager());
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

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Bad mapping config
     */
    public function testGetDataFromBadConfig()
    {
        $user = $this->createUser();
        $this->transformer->getMapsManager()->setMapDir(UserModel::class, __DIR__ . '/data');
        $this->transformer->getData($user, 'bad-dto');
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