<?php
namespace App\library\di;

use App;
use Exception;

class Instance
{
    /**
     * @var string the component ID, class name, interface name or alias name
     */
    public $id;


    /**
     * Constructor.
     * @param string $id the component ID
     */
    protected function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Creates a new Instance object.
     * @param string $id the component ID
     * @return Instance the new Instance object.
     */
    public static function of($id)
    {
        return new static($id);
    }

    public static function ensure($reference, $type = null, $container = null)
    {
        if ($reference instanceof $type) {
            return $reference;
        } elseif (is_array($reference)) {
            $class = isset($reference['class']) ? $reference['class'] : $type;
            if (!$container instanceof Container) {
                $container = \App::$container;
            }
            unset($reference['class']);
            return $container->get($class, [], $reference);
        } elseif (empty($reference)) {
            throw new Exception('The required component is not specified.');
        }

        if (is_string($reference)) {
            $reference = new static($reference);
        }

        if ($reference instanceof self) {
            $component = $reference->get($container);
            if ($component instanceof $type || $type === null) {
                return $component;
            } else {
                throw new Exception('"' . $reference->id . '" refers to a ' . get_class($component) . " component. $type is expected.");
            }
        }

        $valueType = is_object($reference) ? get_class($reference) : gettype($reference);
        throw new Exception("Invalid data type: $valueType. $type is expected.");
    }
}
