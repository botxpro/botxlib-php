<?php
/**
 * @author    Gleb Vishnevsky (nfteam.ru/gleb.vishnevsky)
 * @copyright Copyright (c) 2017 Gleb Vishnevsky
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Kaikash\BotxLib;

use BotxLib\IpnHandler;
use BotxLib\Exception;
use Requests;

class Botx {
  /** @var string Botx api url */
  public $apiUrl = 'https://api.botx.pro';

  /** @var integer Project id */
  public $projectId;

  /** @var string Project api key */
  public $apiKey;

  /** @var string Project type */
  public $projectType;

  /** const string Project types*/
  const MARKET_TYPE     = 'market';
  const INDIVIDUAL_TYPE = 'individual';
  const ENDPOINTS       = [
    'market_items'              => 'v1/remote/market/items',
    'market_user_inventory'     => 'v1/remote/market/inventories',
    'individual_user_inventory' => 'v1/remote/individual/inventories',
    'individual_items'          => 'v1/remote/individual/items',
    'market_deposit'            => 'v1/remote/market/despoit',
    'market_withdraw'           => 'v1/remote/market/withdraw'
  ];

  public function __construct($projectId, $apiKey, $projectType) {
    $this->projectId    = $projectId;
    $this->apiKey       = $apiKey;
    $this->projectType  = $projectType;
    if(!in_array($this->projectType, [self::MARKET_TYPE, self::INDIVIDUAL_TYPE]))
      throw new Exception\WrongProjectTypeException;
  }

  /** 
   * Loads market items
   *
   * @param array $filters Search filters
   *
   * @return array market items
   */ 
  public function loadMarketItems($filters = []) {
    $this->marketOnly();
    $response = $this->send('get', self::ENDPOINTS['market_items'], $filters);
    return $response;
  }

  /** 
   * Loads market items
   *
   * @param array $filters Search filters
   *
   * @return array market items
   */ 
  public function loadUserInventory($filters = []) {
    if($this->projectType == self::MARKET_TYPE) {
      $response = $this->send('get', self::ENDPOINTS['market_user_inventory'], $filters);
    } else {
      $response = $this->send('get', self::ENDPOINTS['individual_user_inventory'], $filters);
    }
    return $response;
  }

  public function deposit($items) {
    $this->marketOnly();
    $response = $this->send('post', self::ENDPOINTS['market_deposit'], ['deposit' => $items]);
    return $response;
  }

  public function withdraw($items) {
    $this->marketOnly();
    $response = $this->send('post', self::ENDPOINTS['market_withdraw'], ['withdraw' => $items]);
    return $response;
  }

  /** 
   * Sends http requst to botx api
   *
   * @param string  @method
   * @param array   @options
   *
   * @return array response
   */ 
  private function send($method, $endpoint, $options = []) {
    try {
      if($method == 'get') {
        $response = Requests::request($this->buildUrl($endpoint), [], $this->buildOptions($options));
      } else if($method == 'post') {
        $response = Requests::post($this->buildUrl($endpoint), [], $this->buildOptions($options));
      }

      /* decode json */
      $body = (object)json_decode($response->body);

      /* pasring error msg */
      if(!$response->success)
        $error_msg = $body->errors->full_messages[0] ? $body->errors->full_messages[0] : $body->errors[0];

      /* throw excaption if status not 200 */
      if($response->status_code >= 500)
        throw new Exception\InternalException($error_msg, $response->status_cde);
      else if($response->status_code >= 400)
        throw new Exception\BadRequestException($error_msg, $response->status_code);

      return $body;
    } catch(\Requests_Exception $e) {
      throw new Exception\InternalException($e->getMessage());
    }
  }

  private function buildUrl($endpoint) {
    return $this->apiUrl . "/" . $endpoint;
  }

  private function buildOptions($options = []) {
    return array_merge($options, ['api_key' => $this->apiKey, 'project_id' => $this->projectId]);
  }

  private function marketOnly() {
    if($this->projectType != self::MARKET_TYPE) 
      throw new Exception\WrongProjectTypeException;
  }

  private function individualOnly() {
    if($this->projectType != self::INDIVIDUAL_TYPE) 
      throw new Exception\WrongProjectTypeException;
  }
}















