<?php
/**
 * @author    Gleb Vishnevsky (nfteam.ru/gleb.vishnevsky)
 * @copyright Copyright (c) 2017 Gleb Vishnevsky
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace BotxLib;
use BotxLib\Botx;

class IpnHandler {
  public $botx;

  /** @var string notification signature */
  private $signature;

  /** @var Array transaction */
  private $transaction;

  public function __construct($botx, $notification) {
    $this->botx = $botx;
    $this->signature = (object)$notification['signature'];
    $this->transaction = (object)$notification['transaction'];
  }

  private function checkSign() {

  }

  private function calculateSign() {
    // Digest::SHA256.hexdigest "{#{id}}{#{project.id}}{#{transaction_type}}{#{amount}}{#{state}}{#{project.api_key}}"
    return hash('sha256', '{'.join('}{', [$transaction->id, $botx->projectId, $transaction->type, $transaction->amount, $transaction->steam_amount, $transaction->state, $botx->apiKey]).'}');
  }
}