<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 8/14/15
 * Time: 12:35 AM
 */

namespace app\business\func;

class Test
{
    public function data(string $string): array
    {
        return (new \SampleModel())->insertSample($string);
    }
}