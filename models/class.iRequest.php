<?php

declare(strict_types=1);
class iRequest
{
    public function __get($key)
    {
        return $this->input($key);
    }

    public function input($key, $default = null)
    {
        $value = $_POST[$key] ?? $_GET[$key] ?? $default;

        return is_string($value) ? trim($value) : $value;
    }

    public function all()
    {
        return array_merge($_GET, $_POST);
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    // --- NUEVO: manejar filtros ---
    public function handlePostFiltros()
    {
        if ($this->isPost()) {
            $_SESSION['filtros'] = $_POST;

            // mantener misma ruta SIN parámetros
            $url = strtok($_SERVER['REQUEST_URI'], '?');

            header("Location: $url");
            exit;
        }
    }

    // --- NUEVO: render JS ---
    public function renderFiltrosScript($clear = true)
    {
        $filtros = $_SESSION['filtros'] ?? [];

        if ($clear) {
            unset($_SESSION['filtros']);
        }

        echo '
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const filtros = ' . json_encode($filtros) . ';

                    Object.entries(filtros).forEach(([key, value]) => {
                        const el = document.querySelector(`[name="${key}"]`);
                        if (!el) return;

                        el.value = value;

                        // soporte select2
                        if (el.classList.contains("select2")) {
                            $(el).trigger("change");
                        }
                    });
                });
            </script>
        ';
    }
}
