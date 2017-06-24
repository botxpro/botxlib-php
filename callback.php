<?php
require_once 'Requests/library/Requests.php';
Requests::register_autoloader();
spl_autoload_register('AutoLoader');

function AutoLoader($className)
{
    $file = str_replace('\\',DIRECTORY_SEPARATOR,$className);

    require_once __DIR__.DIRECTORY_SEPARATOR . $file . '.php'; 
}
use BotxLib\Botx;
use Botxlib\IpnHandler;


$botx = new Botx(4, 'VxSJLaAB0ZtpsLTV9ZeFQw', 'market');

$data = json_decode(file_get_contents('php://input'), true);
// print_r($data);
try {
  $handler = new IpnHandler($botx, $data);
} catch(Exception\WrongSignatureException $e) {
  die($e->getMessage());
}