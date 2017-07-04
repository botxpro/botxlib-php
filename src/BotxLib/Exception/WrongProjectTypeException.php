<?php
/**
 * @author    Gleb Vishnevsky (nfteam.ru/gleb.vishnevsky)
 * @copyright Copyright (c) 2017 Gleb Vishnevsky
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Kaikash\BotxLib\Exception;

class WrongProjectTypeException extends BotxException {
  public function __construct() {
      parent::__construct('Wrong project type. Project type can be `market` or `individual`');
  }
}