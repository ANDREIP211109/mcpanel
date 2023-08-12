<?php

Util::init();

/**
 * This class is the only one that needs to be called explicitly from every web page or script.
 * It loads everything else on demand.
 **/
class Util {
  static $rootPath;
  static $wwwRoot;
  static $maintenance;
  static $db;
  static $url;
  static $flashMsg;

  static function init() {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    self::definePaths();
    spl_autoload_register('self::autoloadClasses');
    extension_loaded('pcntl');
    Session::init();
    FlashMessage::restoreFromSession();

    self::$db = new Db();
    self::$url = explode('/',$_GET['url'] ?? 'home');
  }

  private static function definePaths() {
    self::$rootPath = realpath(__DIR__ . '/..');
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $pos = strrpos($scriptName, '/www/');
    self::$wwwRoot = ($pos === false) ? '/mcpanel/' : substr($scriptName, 0, $pos + 5);
  }

  static function autoloadClasses($className) {
    $paths = ['/lib', '/lib/model', '/lib/third-party'];
    foreach ($paths as $p) {
      $fileName = self::$rootPath . "{$p}/{$className}.php";
      if (file_exists($fileName)) {
        require_once($fileName);
        return;
      }
    }
  }
}

?>