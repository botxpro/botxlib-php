<?php
/**
 * @author    Gleb Vishnevsky (nfteam.ru/gleb.vishnevsky)
 * @copyright Copyright (c) 2017 Gleb Vishnevsky
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Kaikash\BotxLib;

use Kaikash\BotxLib\Botx;
use Kaikash\BotxLib\Exception;
use Kaikash\BotxLib\Transaction;

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

    $this->check_sign();
  }

  private function check_sign() {
    if($this->signature != $this->calculate_sign())
      throw new Exception\WrongSignatureException;
  }

  private function calculate_sign() {
    return hash('sha256', '{'.join('}{', [$this->transaction->id, $this->botx->project_id, $this->transaction->type, (int)($this->transaction->amount*100), (int)($this->transaction->steam_amount*100), $this->transaction->state, $this->botx->api_key]).'}');
  }
}