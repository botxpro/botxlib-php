<?php
/**
 * @author    Gleb Vishnevsky (nfteam.ru/gleb.vishnevsky)
 * @copyright Copyright (c) 2017 Gleb Vishnevsky
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Kaikash\BotxLib\Exception;

class WrongSignatureException extends BotxException {
  public function __construct() {
    parent::__construct('Wrong signature');
  }
}