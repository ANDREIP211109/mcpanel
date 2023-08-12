<?php

/**
 * This class handles session-specific variables.
 **/
class Session {
  const ONE_MONTH_IN_SECONDS = 30 * 86400;

  static $user = null;

  static function init() {
    if (isset($_COOKIE[session_name()])) {
      session_start();
    }
    if (self::getUser() == null) {
      self::loadUserFromCookie();
    }
  }

  static function get($name, $default = null) {
    return (isset($_SESSION[$name]))
      ? $_SESSION[$name]
      : $default;
  }

  static function set($var, $value) {
    // Lazy start of the session so we don't send a PHPSESSID cookie unless we have to
    if (!isset($_SESSION)) {
      session_start();
    }
    $_SESSION[$var] = $value;
  }

  static function unsetVariable($var) {
    if (isset($_SESSION)) {
      unset($_SESSION[$var]);
    }
  }

  static function getUser() {
    if (!self::$user) {
      $userId = self::get('userId');
      self::$user = $userId ?? null;
    }
    return self::$user;
  }

  private static function kill() {
    if (!isset($_SESSION)) {
      session_start(); // It has to have been started in order to be destroyed.
    }
    session_unset();
    session_destroy();
    if (ini_get("session.use_cookies")) {
      setcookie(session_name(), '', time() - 3600, '/'); // expire it
    }
  }

  static function login($user) {
    self::set('userId', $user);

    Http::redirect(Util::$wwwRoot);
  }

  static function logout() {
    $cookieName = "loginCookie";
    if (isset($_COOKIE[$cookieName])) {
      setcookie($cookieName, NULL, time() - 3600, '/');
      unset($_COOKIE[$cookieName]);
    }
    self::kill();
    Http::redirect(Util::$wwwRoot);
  }

  static function loadUserFromCookie() {
    $cookieName = "loginCookie";
    if (!isset($_COOKIE[$cookieName])) {
      return;
    }

    // invalid cookie
    setcookie($cookieName, NULL, time() - 3600, '/');
  }
}

?>