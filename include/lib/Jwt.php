<?php
class JWT {
  private static $secret = 'DaVchezt';

  private static $header;
  private static $payload;
  private static $signature;

  public static $jwt;

  public static function getSignature($payload = ['user_id' => '1'], $useBase64EncodeSecret = false)
  {
    $headers = ['alg' => 'HS256', 'typ' => 'JWT'];
    self::$header = self::base64url_encode(json_encode($headers));

    self::$payload = self::base64url_encode(json_encode($payload));

    if ($useBase64EncodeSecret) {
      self::$secret = self::base64url_encode(json_encode(self::$secret));
    }

    $signature = hash_hmac('SHA256', self::$header . '.' . self::$payload, self::$secret, true);
    self::$signature = self::base64url_encode($signature);

    $jwt = self::$header . '.' . self::$payload . '.' . self::$signature;
    self::$jwt = $jwt;

    return $jwt;
  }

  public static function getMatch()
  {
    $token = self::getBearerToken();

    if (self::$jwt == $token) return true;

    return false;
  }

  private static function base64url_encode()
  {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
  }

  private static function getAuthorizationHeader()
  {
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
      $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
      $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
      $requestHeaders = apache_request_headers();
      $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
      if (isset($requestHeaders['Authorization'])) {
        $headers = trim($requestHeaders['Authorization']);
      }
    }
    return $headers;
 }

 public static function getBearerToken()
 {
    $headers = self::getAuthorizationHeader();
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
  }

}