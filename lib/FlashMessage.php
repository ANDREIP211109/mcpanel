<?php

class FlashMessage {
  public static $messages = []; // an array of [$text, $type] pairs

  static function add($message, $type = 'danger') {
    self::$messages[] = [
      'text' => $message,
      'type' => $type
    ];
  }

  static function getMessages() {
    return self::$messages;
  }

  static function saveToSession() {
    if (count(self::$messages)) {
      Session::set('flashMessages', self::$messages);
    }
  }

  static function restoreFromSession() {
    if ($messages = Session::get('flashMessages')) {
      self::$messages = $messages;
      Session::unsetVariable('flashMessages');
    }
  }
}

?>