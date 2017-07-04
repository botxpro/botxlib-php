<?php
/**
 * @author    Gleb Vishnevsky (nfteam.ru/gleb.vishnevsky)
 * @copyright Copyright (c) 2017 Gleb Vishnevsky
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Kaikash\BotxLib;

use BotxLib\Botx;
use BotxLib\Exception;

class Tradeoffer {
  public function __construct($tradeoffer) {
    $tradeoffer = (object)$tradeoffer;
    $this->offerId    = $tradeoffer->offer_id;
    $this->isOurOffer = $tradeoffer->is_our_offer;
    $this->partner    = $tradeoffer->partner;
    $this->message    = $tradeoffer->message;
    $this->state      = $tradeoffer->state;
    $this->stateName  = $tradeoffer->state_name;
    $this->expires    = $tradeoffer->expires;
    $this->botId      = $tradeoffer->botId;
    $this->createdAt  = $tradeoffer->created_at;
    $this->updateAt   = $tradeoffer->updated_at;
  }
}