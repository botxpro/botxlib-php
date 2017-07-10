<?php
/**
 * @author    Gleb Vishnevsky (nfteam.ru/gleb.vishnevsky)
 * @copyright Copyright (c) 2017 Gleb Vishnevsky
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Kaikash\BotxLib;

use Kaikash\BotxLib\Botx;
use Kaikash\BotxLib\Exception;

class Transaction {
  const TYPES = ['balance_charge', 'cash_out', 'sent_offer', 'respond_to_offer', 'sell_to_market', 'buy_from_market', 'deposit', 'withdraw'];
  const OFFER_TYPES = ["deposit", "withdraw", "send_offer", "respond_to_offer"];

  public function __construct($transaction) {
    $transaction = (object)$transaction;

    $this->id                 = $transaction->id;
    $this->amount             = $transaction->amount;
    $this->steam_amount       = $transaction->steam_amount;
    $this->fee_percent        = $transaction->fee_percent;
    $this->fee_amount         = $transaction->fee_amount;
    $this->type               = $transaction->type;
    $this->state              = $transaction->state;
    $this->description        = $transaction->description;
    $this->cancel_description = $transaction->cancel_description;

    if(in_array($this->type, self::OFFER_TYPES)) {
      $this->tradeoffer = new Tradeoffer($transaction->tradeoffer);
    }
  }
}