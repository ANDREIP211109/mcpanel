<?php

/* Various HTTP utils */
class Http {
  const HTTP_OK = 200;
  const HTTP_MOVED = 301;
  const HTTP_FORBIDDEN = 403;
  const HTTP_NOT_FOUND = 404;

  const STATUS_NAMES = [
    self::HTTP_OK => 'OK',
    self::HTTP_MOVED => 'Moved Permanently',
    self::HTTP_FORBIDDEN => 'Forbidden',
    self::HTTP_NOT_FOUND => 'Not Found',
  ];

  static function statusCode($s) {
    header(sprintf("HTTP/1.1 %d %s", $s, self::STATUS_NAMES[$s]));
  }

  static function redirect($location) {
    FlashMessage::saveToSession();
    self::statusCode(self::HTTP_MOVED);
    header("Location: $location");
    exit;
  }

  // TODO: fetch to file, not in memory
  /* Returns a pair of ($contents, $httpCode) */
  static function fetchUrl($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects
    $contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return array($contents, $httpCode);
  }

}

?>