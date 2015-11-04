<?php
/**
 * @name IndexController
 * @author Mike
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */

class IndexController extends \Yaf_Controller_Abstract
{

    /**
     * @param int $id
     * @return bool
     */
    public function indexAction(int $id): bool
    {
        echo '<pre>';

        if(1 <=> 2 === -1)
        {
            echo 'Yes';
        }
        else
        {
            echo 'No';
        }

        return true;
	}

    public function listAction(int $rid): array
    {
        $arr = [1,2,3,4,5,6];

        return $arr;
    }

    public function getDbAction()
    {
        return false;
    }
}