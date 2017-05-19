<?php
/**
 * @author    Gleb Vishnevsky (nfteam.ru/gleb.vishnevsky)
 * @copyright Copyright (c) 2017 Gleb Vishnevsky
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace BotxLib\Exception;

class InternalException extends BotxException {
  protected $status;
  public function __construct($message, $status = 500) {
    $this->status = $status;
    parent::__construct($message);
  }

  public function getStatus() {
    return $this->status;
  }
}