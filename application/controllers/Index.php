<?php

use application\library\IdVerify;
/**
 * @name IndexController
 * @author Mike
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends Yaf_Controller_Abstract
{

	public function indexAction(string $cardId)
	{
		exit;

		$id = $this->getRequest()->getQuery('id');

		$str = IdVerify::getInstance()->validationCardId($cardId);
		if($str === true)
		{
			echo '这是有效身份证';
		}
		else
		{
			echo '这是无效身份证';
		}
	}
}
