<?php
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

    /**
     * @param YamlParser $parser
     */
    public function __construct(YamlParser $parser)
    {
        $this->yamlParser = $parser;
    }

    /**
     * @param string $name
     * @param string $dir
     * @throws Exception
     */
    public function setMapDir($name, $dir)
    {
        $this->mapsDirs[$name] = $dir;
    }

    /**
     * @param string $name
     * @param string $type
     * @return array
     *
     * @throws ParseException
     */
    public function getMap($name, $type = 'dto')
    {
        $map = $this->tryCache($name, $type);
        if ($map) {
            return $map;
        }

        $dir = $this->mapsDirs[$name];
        $map = $this->getByPath($dir . '/' . $type . '.yml');

        $this->setCache($name, $type, $map);
        return $map;
    }

    /**
     * @param string $name
     * @param string $type
     * @param array $map
     */
    protected function setCache($name, $type, array $map)
    {
        $this->cache[$name][$type] = $map;
    }

    /**
     * @param string $name
     * @param string $type
     * @return array|null
     */
    protected function tryCache($name, $type)
    {
        if (isset($this->cache[$name], $this->cache[$name][$type])) {
            return $this->cache[$name][$type];
        }

        return null;
    }

    /**
     * @param string $path
     * @return array
     * @throws ParseException
     */
    protected function getByPath($path)
    {
        return $this->yamlParser->parse(file_get_contents($path));
    }
}