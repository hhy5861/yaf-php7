<?php
/**
 * @name IndexController
 * @author Mike
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */

class MikeController extends \Yaf_Controller_Abstract {

	public function indexAction() {
		$arr = $this->getRequest()->controller;
		echo '<pre>';
		print_r($arr);

		return false;
	}
}
