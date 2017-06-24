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

// $botx = new Botx(1, '7gnGkaQGkb83UgQhqpJBFA', 'market');
// try {
//   // $items = $botx->loadMarketItems();
// // } catch (BotxLib\Exception\BotxException $e) {
//   // echo $e->getMessage().' '.$e->getStatus();
// // }
// // $items = $botx->loadMarketItems()->items;
// // foreach($items as $item) {
// //   echo $item->market_hash_name.'<br>';
// // }

//   $deposit = $botx->withdraw([
//     "uid" => "76561198078016715",
//     "token" => "bT7eJeTM",
//     "message" => "privet",
//     "my_items" => [
//       [
//         "assetid" => 10174480792,
//         "contextid" => 2,
//         "appid" => 730
//       ]
//     ]
//   ]);
//   var_dump($deposit);
// } catch(BotxLib\Exception\BadRequestException $e) {
//   echo $e->getMessage().' '. $e->getStatus();
// }

$botx = new Botx(1, '7gnGkaQGkb83UgQhqpJBFA', 'market');

// $notification = sadfadfad
try {
  $handler = new IpnHandler($botx, $notification);
  $handler->transaction->id;
  $handler->transaction->amount;
  $handler->transaction->steam_amount;
} catch(Exception\WrongSignatureException $e) {
  die($e->getMessage());
}

// if(!$handler->check())
  // die('error wring signature');








