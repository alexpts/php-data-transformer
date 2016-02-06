<?php

use PTS\DataTransformer\DataTransformer;
use PTS\DataTransformer\MapsManager;
use PTS\DataTransformer\ModelClosure;
use PTS\DataTransformer\TypeConverter;
use Symfony\Component\Yaml\Parser;

include_once __DIR__ . '/../../vendor/autoload.php';
include_once __DIR__ . '/../models/Post/Post.php';
include_once __DIR__ . '/../models/Tag/Tag.php';

$transformer = new DataTransformer(
    new TypeConverter,
    new MapsManager(new Parser),
    new ModelClosure
);

$transformer->getMapsManager()->setMapDir(Tag::class, __DIR__ . '/../models/Tag/');
$transformer->getMapsManager()->setMapDir(Post::class, __DIR__ . '/../models/Post/');

$tag1 = (new Tag)->setId(1)->setTitle('music');
$tag2 = (new Tag)->setId(2)->setTitle('js');

$post = (new Post)->setId(1)->setTitle('Title')->setTags([$tag1, $tag2]);
$post2 = new Post;

$postDto = $transformer->getData($post, 'dto'); // model to data
$transformer->fillModel($postDto, $post2, 'dto'); // data to model

print_r($postDto);
print_r($post2);