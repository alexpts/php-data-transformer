<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use Exception;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser as YamlParser;

class MapsManager
{
    /** @var YamlParser */
    protected $yamlParser;
    /** @var array */
    protected $cache = [];
    /** @var array */
    protected $mapsDirs = [];

    public function __construct(YamlParser $parser)
    {
        $this->yamlParser = $parser;
    }

    public function setMapDir(string $name, string $dir)
    {
        $this->mapsDirs[$name] = $dir;
    }

    public function getMap(string $name, string $type = 'dto'): array
    {
        $map = $this->tryCache($name, $type);
        if (is_array($map)) {
            return $map;
        }

        $dir = $this->mapsDirs[$name];
        $map = $this->getByPath($dir . '/' . $type . '.yml');

        $this->setCache($name, $type, $map);
        return $map;
    }

    protected function setCache(string $name, string $type, array $map)
    {
        $this->cache[$name][$type] = $map;
    }

    protected function tryCache(string $name, string $type): ?array
    {
        if (isset($this->cache[$name], $this->cache[$name][$type])) {
            return $this->cache[$name][$type];
        }

        return null;
    }

    protected function getByPath(string $path): array
    {
        return (array)$this->yamlParser->parse(file_get_contents($path));
    }
}
