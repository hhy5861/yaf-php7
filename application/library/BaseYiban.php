<?php
namespace App\library;
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 8/14/15
 * Time: 11:36 PM
 */
use Exception;

class BaseYiban
{
    public static $container;

    public static function createObject($type, array $params = [])
    {
        if (is_string($type))
        {
            return static::$container->get($type, $params);
        }
        elseif(is_array($type) && isset($type['class']))
        {
            $class = $type['class'];
            unset($type['class']);
            return static::$container->get($class, $params, $type);
        }
        elseif (is_callable($type, true))
        {
            return call_user_func($type, $params);
        }
        elseif (is_array($type))
        {
            throw new Exception('Object configuration must be an array containing a "class" element.');
        }
        else
        {
            throw new Exception("Unsupported configuration type: " . gettype($type));
        }
    }

    /**
     * @param $object
     * @param $properties
     * @return mixed
     */
    public static function configure($object, $properties)
    {
        foreach ($properties as $name => $value)
        {
            $object->$name = $value;
        }

        return $object;
    }
}