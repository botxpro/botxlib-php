<?php
/**
 * @author    Gleb Vishnevsky (nfteam.ru/gleb.vishnevsky)
 * @copyright Copyright (c) 2017 Gleb Vishnevsky
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace BotxLib;
use BotxLib\Botx;
use BotxLib\Exception;
use Botxlib\Transaction;

class IpnHandler {
  public $botx;

  /** @var string notification signature */
  private $signature;

  /** @var Array transaction */
  public $transaction;

  public function __construct($botx, $notification) {
    $this->botx = $botx;
    $this->signature = $notification['sign'];
    $this->transaction = new Transaction($notification['transaction']);

    $this->checkSign();
  }

  private function checkSign() {
    if($this->signature != $this->calculateSign())
      throw new Exception\WrongSignatureException;
  }

  private function calculateSign() {
    return hash('sha256', '{'.join('}{', [$this->transaction->id, $this->botx->projectId, $this->transaction->type, (int)($this->transaction->amount*100), (int)($this->transaction->steamAmount*100), $this->transaction->state, $this->botx->apiKey]).'}');
  }
}