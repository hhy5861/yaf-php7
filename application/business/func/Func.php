<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 8/15/15
 * Time: 4:00 PM
 */

namespace app\business\func;

use App\library\base\Object;

class Func extends Object
{
    public $type;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @param array $param
     * @return array
     */
    public function baseList(array $param): array
    {
        return [$this->type,$param];
    }

}