<?php
/**
 * @name IndexController
 * @author Mike
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */

class TestController extends Yaf_Controller_Abstract
{
	public function listAction(int $id = 1): bool
    {
        echo 'ths is test' . $id;
        return false;
	}
}
