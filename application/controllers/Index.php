<?php
/**
 * @name IndexController
 * @author Mike
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */

use library\func\IdVerify;

class IndexController extends Yaf_Controller_Abstract
{

	public function indexAction()
	{
        $msg = '';

        $postArr = $this->getRequest()->getPost();
        if($postArr['cardId'])
        {
            $str = IdVerify::getInstance()->validationCardId($postArr['cardId']);
            if($str === true)
            {
                $msg = '这是有效身份证';
            }
            else
            {
                $msg = '这是无效身份证';
            }
        }

        $this->getView()->assign('message', $msg);
        return true;
	}
}
