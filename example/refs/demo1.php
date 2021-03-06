<?php

use PTS\DataTransformer\DataTransformer;
use PTS\DataTransformer\MapsManager;
use PTS\DataTransformer\ModelClosure;
use PTS\DataTransformer\TypeConverter;
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

include_once __DIR__ . '/../../vendor/autoload.php';
include_once __DIR__ . '/../models/Post/Post.php';
include_once __DIR__ . '/../models/Tag/Tag.php';

$transformer = new DataTransformer(
    new TypeConverter,
    new MapsManager(new Parser),
    new ModelClosure
);

$converter = $transformer->getConverter();
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


$transformer->getMapsManager()->setMapDir(Tag::class, __DIR__ . '/../models/Tag/');
$transformer->getMapsManager()->setMapDir(Post::class, __DIR__ . '/../models/Post/');

$tag1 = (new Tag)->setId(1)->setTitle('music');
$tag2 = (new Tag)->setId(2)->setTitle('js');

$post = (new Post)->setId(1)->setTitle('Title')->setTags([$tag1, $tag2]);
$post2 = new Post;

$postDto = $transformer->getData($post, 'dto'); // model to data
$transformer->fillModel($postDto, $post2, 'dto'); // data to model
