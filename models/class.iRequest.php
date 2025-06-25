<?php
class iRquest
{
  public function __get($key)
  {
    $value = $_POST[$key] ?? $_GET[$key] ?? null;

    return is_string($value) ? trim($value) : $value;
  }
}
