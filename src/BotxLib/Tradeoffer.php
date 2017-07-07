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
    $this->offer_id    = $tradeoffer->offer_id;
    $this->is_our_offer = $tradeoffer->is_our_offer;
    $this->partner    = $tradeoffer->partner;
    $this->message    = $tradeoffer->message;
    $this->state      = $tradeoffer->state;
    $this->state_name  = $tradeoffer->state_name;
    $this->expires    = $tradeoffer->expires;
    $this->bot_id      = $tradeoffer->bot_id;
    $this->created_at  = $tradeoffer->created_at;
    $this->updated_at   = $tradeoffer->updated_at;
  }
}