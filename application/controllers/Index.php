<?php
namespace controllers;
/**
 * @name IndexController
 * @author Mike
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
use library\func\IdVerify;

class IndexController extends \Yaf_Controller_Abstract
{

	public function indexAction($cardId = '')
	{
        $cardId = $this->getRequest()->getPost()['cardId'];

        $str    = false;
        $cardId && $str = IdVerify::getInstance()->validationCardId($cardId);
        if($str === true)
        {
            $msg = '这是有效身份证：【'.$cardId.'】';
        }
        else
        {
            $msg = '这是无效身份证：【'.$cardId.'】';
        }

        $this->getView()->assign('message', $msg);
        return true;
	}
}
