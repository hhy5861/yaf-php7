<?php
//网站目录

$wwwPath = '/usr/local/serv';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if($data)
{
    switch($data['repository']['name'])
    {
        case 'yiban-base':
            $shell  = 'cd /usr/local/serv';
            break;
        case 'public-terrace':
            $shell  = 'cd /usr/local/serv';
            break;
    }

    switch($data['ref'])
    {
        case 'refs/heads/develop':
            $shell .= 'git pull origin develop';
            break;
        case 'refs/heads/master':
            break;
    }

    $shell && $output = shell_exec("$shell 2>&1");
}
