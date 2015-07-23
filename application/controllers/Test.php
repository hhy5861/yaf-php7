<?php

/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 7/23/15
 * Time: 5:53 PM
 */
class Test extends Yaf_Controller_Abstract
{
    public function IndexAction(int $name)
    {
        $this->getView()->assign("name", $name);

        return true;
    }

}