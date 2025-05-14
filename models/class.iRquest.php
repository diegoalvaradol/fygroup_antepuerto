<?php

class iRequest
{
  /* Obtener un valor desde $_GET o $_POST (prioriza POST) */
  public static function input(string $key, $default = null)
  {
    if (isset($_POST[$key])) {
      return self::sanitize($_POST[$key]);
    }

    if (isset($_GET[$key])) {
      return self::sanitize($_GET[$key]);
    }

    return $default;
  }

  /* Obtener un valor exclusivamente de $_POST */
  public static function post(string $key, $default = null)
  {
    return isset($_POST[$key]) ? self::sanitize($_POST[$key]) : $default;
  }

  /* Obtener un valor exclusivamente de $_GET */
  public static function get(string $key, $default = null)
  {
    return isset($_GET[$key]) ? self::sanitize($_GET[$key]) : $default;
  }

  /* Verificar si una clave existe en POST o GET */
  public static function has(string $key)
  {
    return isset($_POST[$key]) || isset($_GET[$key]);
  }

  /* Devuelve todos los datos combinados (POST sobreescribe GET) */
  public static function all()
  {
    return array_map([self::class, 'sanitize'], array_merge($_GET, $_POST));
  }

  /* Sanitizador básico (puedes personalizarlo más si deseas) */
  protected static function sanitize($value)
  {
    if (is_array($value)) {
      return array_map([self::class, 'sanitize'], $value);
    }

    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
  }
}
