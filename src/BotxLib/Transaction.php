<?php
/**
 * @author    Gleb Vishnevsky (nfteam.ru/gleb.vishnevsky)
 * @copyright Copyright (c) 2017 Gleb Vishnevsky
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Kaikash\BotxLib;

use BotxLib\Botx;
use BotxLib\Exception;

class Transaction {
  public $id;
  public $amount;
  public $steamAmount;
  public $type;
  public $state;
  public $description;
  public $cancelDescription;
  public $attachmentable;

  const TYPES = ['balance_charge', 'cash_out', 'sent_offer', 'respond_to_offer', 'sell_to_market', 'buy_from_market', 'deposit', 'withdraw'];
  const OFFER_TYPES = ["deposit", "withdraw", "send_offer", "respond_to_offer"];

  public function __construct($transaction) {
    $transaction = (object)$transaction;

    $this->id                 = $transaction->id;
    $this->amount             = $transaction->amount;
    $this->steamAmount        = $transaction->steam_amount;
    $this->type               = $transaction->type;
    $this->state              = $transaction->state;
    $this->description        = $transaction->description;
    $this->cancelDescription  = $transaction->cancelDescription;

    if(in_array($this->type, self::OFFER_TYPES)) {
      $this->tradeoffer = new Tradeoffer($transaction->tradeoffer);
    }
  }
}