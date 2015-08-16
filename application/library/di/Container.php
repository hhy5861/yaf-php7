<?php
namespace App\library\di;


use Exception;
use ReflectionClass;
use App\library\base\Object;

class Container extends Object
{
    private $_singletons   = [];

    private $_definitions  = [];

    private $_params       = [];

    private $_reflections  = [];

    private $_dependencies = [];

    /**
     * @param $class
     * @param array $params
     * @param array $config
     * @return mixed
     * @throws Exception
     */
    public function get($class, $params = [], $config = [])
    {
        if (isset($this->_singletons[$class]))
        {
            return $this->_singletons[$class];
        }
        elseif (!isset($this->_definitions[$class]))
        {
            return $this->build($class, $params, $config);
        }

        $definition = $this->_definitions[$class];

        if (is_callable($definition, true))
        {
            $params = $this->resolveDependencies($this->mergeParams($class, $params));
            $object = call_user_func($definition, $this, $params, $config);
        }
        elseif (is_array($definition))
        {
            $concrete = $definition['class'];
            unset($definition['class']);

            $config = array_merge($definition, $config);
            $params = $this->mergeParams($class, $params);

            if ($concrete === $class)
            {
                $object = $this->build($class, $params, $config);
            }
            else
            {
                $object = $this->get($concrete, $params, $config);
            }
        }
        elseif (is_object($definition))
        {
            return $this->_singletons[$class] = $definition;
        }
        else
        {
            throw new Exception("Unexpected object definition type: " . gettype($definition));
        }

        if (array_key_exists($class, $this->_singletons))
        {
            $this->_singletons[$class] = $object;
        }

        return $object;
    }

    /**
     *
     * @param $class
     * @param $params
     * @param $config
     * @return mixed
     * @throws Exception
     */
    protected function build($class, $params, $config)
    {
        list ($reflection, $dependencies) = $this->getDependencies($class);

        foreach ($params as $index => $param)
        {
            $dependencies[$index] = $param;
        }

        $dependencies = $this->resolveDependencies($dependencies, $reflection);
        if (empty($config))
        {
            return $reflection->newInstanceArgs($dependencies);
        }

        if (!empty($dependencies) && $reflection->implementsInterface('App\library\base\Configurable'))
        {
            $dependencies[count($dependencies) - 1] = $config;
            return $reflection->newInstanceArgs($dependencies);
        }
        else
        {
            $object = $reflection->newInstanceArgs($dependencies);
            foreach ($config as $name => $value)
            {
                $object->$name = $value;
            }

            return $object;
        }
    }

    /**
     *
     * @param $class
     * @param $params
     * @return mixed
     */
    protected function mergeParams($class, $params)
    {
        if (empty($this->_params[$class]))
        {
            return $params;
        }
        elseif (empty($params))
        {
            return $this->_params[$class];
        }
        else
        {
            $ps = $this->_params[$class];
            foreach ($params as $index => $value)
            {
                $ps[$index] = $value;
            }

            return $ps;
        }
    }

    /**
     * @param $class
     * @return array
     */
    protected function getDependencies($class)
    {
        if (isset($this->_reflections[$class]))
        {
            return [$this->_reflections[$class], $this->_dependencies[$class]];
        }

        $dependencies = [];
        $reflection = new ReflectionClass($class);

        $constructor = $reflection->getConstructor();
        if ($constructor !== null)
        {
            foreach ($constructor->getParameters() as $param)
            {
                if ($param->isDefaultValueAvailable())
                {
                    $dependencies[] = $param->getDefaultValue();
                }
                else
                {
                    $c              = $param->getClass();
                    $dependencies[] = Instance::of($c === null ? null : $c->getName());
                }
            }
        }

        $this->_reflections[$class]  = $reflection;
        $this->_dependencies[$class] = $dependencies;

        return [$reflection, $dependencies];
    }

    /**
     *
     * @param $dependencies
     * @param null $reflection
     * @return mixed
     * @throws Exception
     */
    protected function resolveDependencies($dependencies, $reflection = null)
    {
        foreach ($dependencies as $index => $dependency)
        {
            if ($dependency instanceof Instance)
            {
                if ($dependency->id !== null)
                {
                    $dependencies[$index] = $this->get($dependency->id);
                }
                elseif ($reflection !== null)
                {
                    $name = $reflection->getConstructor()->getParameters()[$index]->getName();
                    $class = $reflection->getName();
                    throw new Exception("Missing required parameter \"$name\" when instantiating \"$class\".");
                }
            }
        }

        return $dependencies;
    }
}
