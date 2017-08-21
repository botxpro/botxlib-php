<?php
/**
 * @author    Gleb Vishnevsky (nfteam.ru/gleb.vishnevsky)
 * @copyright Copyright (c) 2017 Gleb Vishnevsky
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Kaikash\BotxLib;

use Kaikash\BotxLib\Transaction;
use Kaikash\BotxLib\IpnHandler;
use Kaikash\BotxLib\Exception\BotxException;
use Kaikash\BotxLib\Exception\BadRequestException;
use Kaikash\BotxLib\Exception\InternalException;
use Kaikash\BotxLib\Exception\WrongProjectTypeException;
use Kaikash\BotxLib\Exception\WrongSignatureException;
use Exception;
use Requests;

class Botx {
  /** @var string Botx api url */
  public $api_url = 'https://api.botx.pro';

  /** @var integer Project id */
  public $project_id;

  /** @var string Project api key */
  public $api_key;

  /** @var string Project type */
  public $project_type;

  /** const string Project types*/
  const MARKET_TYPE     = 'market';
  const INDIVIDUAL_TYPE = 'individual';
  const ENDPOINTS       = [
    'market_items'              => 'v1/remote/market/items',
    'market_user_inventory'     => 'v1/remote/market/inventories',
    'individual_user_inventory' => 'v1/remote/individual/inventories',
    'individual_items'          => 'v1/remote/individual/items',
    'market_deposit'            => 'v1/remote/market/deposit',
    'market_withdraw'           => 'v1/remote/market/withdraw'
  ];

  public function __construct($project_id, $api_key, $project_type) {
    $this->project_id    = $project_id;
    $this->api_key       = $api_key;
    $this->project_type  = $project_type;
    if(!in_array($this->project_type, [self::MARKET_TYPE, self::INDIVIDUAL_TYPE]))
      throw new WrongProjectTypeException;
  }

  /** 
   * Loads market items
   *
   * @param array $filters Search filters
   *
   * @return array market items
   */ 
  public function load_market_items($filters = []) {
    $this->market_only();
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
  public function load_user_inventory($filters = []) {
    if($this->project_type == self::MARKET_TYPE) {
      $response = $this->send('get', self::ENDPOINTS['market_user_inventory'], $filters);
    } else {
      $response = $this->send('get', self::ENDPOINTS['individual_user_inventory'], $filters);
    }
    return $response;
  }

  public function deposit($items) {
    $this->market_only();
    if(!$items) throw new BadRequestException('no items present', 422);
    $response = $this->send('post', self::ENDPOINTS['market_deposit'], ['deposit' => $items]);
    return new Transaction($response->transaction);
  }

  public function withdraw($items) {
    $this->market_only();
    if(!$items) throw new BadRequestException('no items present', 422);
    $response = $this->send('post', self::ENDPOINTS['market_withdraw'], ['withdraw' => $items]);
    return new Transaction($response->transaction);
  }

  public function check_withdraw_items($items) {
    if(!$items) throw new BadRequestException('no items present', 422);
    foreach($items as $item) {
      $this->check_item_hash($item);
    }
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
        $response = Requests::request($this->build_url($endpoint), [], $this->build_options($options));
      } else if($method == 'post') {
        $response = Requests::post($this->build_url($endpoint), [], $this->build_options($options));
      }

      /* decode json */
      $body = (object)json_decode($response->body);

      /* pasring error msg */
      if(!$response->success)
        if(isset($body->errors->full_messages[0])) $error_msg = $body->errors->full_messages[0];
        else if(isset($body->errors[0])) $error_msg = $body->errors[0];
        else $error_msg = $body->error;

      /* throw excaption if status not 200 */
      if($response->status_code >= 500)
        throw new InternalException($error_msg, $response->status_code);
      else if($response->status_code >= 400)
        throw new BadRequestException($error_msg, $response->status_code);

      return $body;
    } catch(\Requests_Exception $e) {
      throw new InternalException($e->getMessage());
    }
  }

  private function build_url($endpoint) {
    return $this->api_url . "/" . $endpoint;
  }

  private function build_options($options = []) {
    return array_merge($options, ['api_key' => $this->api_key, 'project_id' => $this->project_id]);
  }

  private function market_only() {
    if($this->project_type != self::MARKET_TYPE) 
      throw new WrongProjectTypeException;
  }

  private function individual_only() {
    if($this->project_type != self::INDIVIDUAL_TYPE) 
      throw new WrongProjectTypeException;
  }

  private function check_item_hash($item) {
    if(!isset($item['hash']) || !isset($item['appid']) || !isset($item['contextid']) || !isset($item['assetid']) || !isset($item['steam_price']) || !isset($item['steam_price']))
      throw new WrongSignatureException;
    if($item['hash'] != $this->calculate_item_hash($item))
      throw new WrongSignatureException;
  }

  private function calculate_item_hash($item) {
    return hash('sha256', '{'.join('}{', [$item['appid'], $item['contextid'], $item['assetid'], $item['our_price'], $item['steam_price'], $this->api_key]).'}');
  }
}













