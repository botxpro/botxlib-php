<?php
class Helper {
  public static function convertKeysToCamelCase($apiResponseArray) {
      $keys = array_map(function ($i) {
          $parts = explode('_', $i);
          return array_shift($parts). implode('', array_map('ucfirst', $parts));
      }, array_keys($apiResponseArray));

      return array_combine($keys, $apiResponseArray);
  }
}