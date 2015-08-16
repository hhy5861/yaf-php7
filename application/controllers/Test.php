<?php
/**
 * @name IndexController
 * @author Mike
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */

use App\business\func\Test;

class TestController extends Yaf_Controller_Abstract
{
	public function listAction(int $id = 1): bool
    {
        $result = (new Test())->data($id);
        var_dump($result);
        return false;
	}

    public function phpInfoAction(): bool
    {
        $obj = App::createObject(['class' => 'App\business\func\Func',
                                  'type'  => 'Jeson',
                                 ]);

        $result = $obj->baseList([1 => 'Mike']);
        var_dump($result);
        return false;
    }
}
