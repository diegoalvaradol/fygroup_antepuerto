<?php
class UIComponents
{

    public static function scrollToTopButton()
    {
        ob_start();
        ?>

        <style>
            /* =====================================================
            FYGROUP — SCROLL TO TOP
            Compatible con Bootstrap 4
            ===================================================== */

            #scrollTopBtn {
                position: fixed !important;
                right: 25px !important;
                bottom: 60px !important;
                z-index: 999999 !important;

                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;

                padding: 12px 18px;
                border: 1px solid rgba(255, 255, 255, 0.15);
                border-radius: 30px;

                background: #003152;
                color: #ffffff;

                font-family: inherit;
                font-size: 12px;
                font-weight: 600;

                box-shadow: 0 4px 12px rgba(0, 31, 53, 0.25);

                cursor: pointer;
                outline: none;

                opacity: 0;
                visibility: hidden;
                pointer-events: none;

                transform: translateY(15px);

                transition:
                    opacity 0.35s ease,
                    transform 0.35s ease,
                    visibility 0.35s ease,
                    background-color 0.25s ease,
                    box-shadow 0.25s ease;
            }

            /* Botón visible */
            #scrollTopBtn.show {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
                transform: translateY(0);
            }

            /* Hover */
            #scrollTopBtn:hover {
                background: #001f35;
                box-shadow: 0 6px 16px rgba(0, 31, 53, 0.35);
                transform: translateY(-3px);
            }

            #scrollTopBtn:active {
                transform: translateY(0);
            }

            #scrollTopBtn:focus-visible {
                outline: 3px solid #addff1;
                outline-offset: 3px;
            }

            #scrollTopBtn i {
                font-size: 15px;
            }

            /* Móviles */
            @media (max-width: 768px) {
                #scrollTopBtn {
                    right: 15px !important;
                    bottom: 60px !important;
                    padding: 10px 14px;
                    font-size: 12px;
                }

                #scrollTopBtn i {
                    font-size: 13px;
                }
            }

            /* Accesibilidad: reducir animaciones */
            @media (prefers-reduced-motion: reduce) {
                #scrollTopBtn {
                    transition: none;
                }
            }
        </style>

        <button
            type="button"
            id="scrollTopBtn"
            aria-label="Volver al inicio"
            title="Volver al inicio"
        >
            <i class="fas fa-arrow-up" aria-hidden="true"></i>
            <span>Ir al inicio</span>
        </button>

        <script>
            (function () {
                'use strict';

                function initScrollTop() {
                    var btn = document.getElementById('scrollTopBtn');

                    if (!btn || btn.dataset.scrollReady === '1') {
                        return;
                    }

                    btn.dataset.scrollReady = '1';

                    var animationFrame = null;

                    /*
                    * Detectar el desplazamiento de la página.
                    */
                    function getScrollPosition() {
                        return Math.max(
                            document.body.scrollTop || 0,
                            document.documentElement.scrollTop || 0,
                            window.pageYOffset || 0
                        );
                    }

                    /*
                    * Mostrar u ocultar el botón.
                    */
                    function updateButton() {
                        btn.classList.toggle(
                            'show',
                            getScrollPosition() > 200
                        );
                    }

                    /*
                    * Desplazamiento suave hacia el inicio.
                    * Duración: 900 milisegundos.
                    */
                    function scrollToTop() {
                        if (animationFrame !== null) {
                            cancelAnimationFrame(animationFrame);
                            animationFrame = null;
                        }

                        var startPosition = getScrollPosition();

                        if (startPosition <= 0) {
                            updateButton();
                            return;
                        }

                        var startTime = null;
                        var duration = 900;

                        function animateScroll(currentTime) {
                            if (startTime === null) {
                                startTime = currentTime;
                            }

                            var elapsed = currentTime - startTime;
                            var progress = Math.min(
                                elapsed / duration,
                                1
                            );

                            /*
                            * Curva ease-out:
                            * movimiento rápido al principio
                            * y desaceleración suave al final.
                            */
                            var easing = 1 - Math.pow(1 - progress, 4);

                            var position = Math.round(
                                startPosition * (1 - easing)
                            );

                            document.body.scrollTop = position;
                            document.documentElement.scrollTop = position;

                            if (progress < 1) {
                                animationFrame =
                                    requestAnimationFrame(animateScroll);
                            } else {
                                document.body.scrollTop = 0;
                                document.documentElement.scrollTop = 0;

                                animationFrame = null;
                                updateButton();
                            }
                        }

                        animationFrame = requestAnimationFrame(animateScroll);
                    }

                    /*
                    * Eventos.
                    */
                    btn.addEventListener('click', function (event) {
                        event.preventDefault();
                        scrollToTop();
                    });

                    window.addEventListener(
                        'scroll',
                        updateButton,
                        { passive: true }
                    );

                    document.addEventListener(
                        'scroll',
                        updateButton,
                        { passive: true }
                    );

                    document.body.addEventListener(
                        'scroll',
                        updateButton,
                        { passive: true }
                    );

                    /*
                    * Estado inicial.
                    */
                    updateButton();
                }

                if (document.readyState === 'loading') {
                    document.addEventListener(
                        'DOMContentLoaded',
                        initScrollTop
                    );
                } else {
                    initScrollTop();
                }
            })();
        </script>

        <?php

        return ob_get_clean();
    }

    public static function whatsappChatBox()
    {
        ob_start();
        ?>
            <style>
                #whatsapp-chat-box {
                    position: fixed;
                    right: 25px;
                    bottom: 175px; /* 90px del botón + 55px de alto + 10px de separación */
                    left: auto;

                    width: 320px;
                    max-width: calc(100vw - 30px);

                    background: #fff;
                    border-radius: 16px;
                    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
                    font-family: "Segoe UI", sans-serif;

                    z-index: 999999;
                    overflow: hidden;

                    transform: translateY(15px);
                    opacity: 0;
                    visibility: hidden;
                    pointer-events: none;

                    transition:
                        opacity 0.25s ease,
                        transform 0.25s ease,
                        visibility 0.25s ease;
                }

                #whatsapp-chat-box.active {
                    transform: translateY(0);
                    opacity: 1;
                    visibility: visible;
                    pointer-events: auto;
                }

                .chat-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 14px;
                    background: linear-gradient(135deg, #25d366, #1ebe5d);
                    color: white;
                }

                .chat-title {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                .chat-title img {
                    width: 28px;
                }

                .chat-close {
                    cursor: pointer;
                    font-size: 18px;
                }

                .chat-body {
                    padding: 16px;
                    display: none;
                    background: #ece5dd;
                }

                .msg {
                    background: #fff;
                    padding: 10px 14px;
                    border-radius: 12px;
                    font-size: 14px;
                    max-width: 85%;
                    margin-bottom: 10px;
                }

                .btn-chat {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    margin-top: 12px;
                    padding: 12px;
                    background: #25d366;
                    color: white;
                    text-decoration: none;
                    font-weight: 600;
                    border-radius: 10px;
                }

                .btn-chat img {
                    width: 18px;
                }

                #whatsapp-float-btn {
                    position: fixed;
                    right: 25px;
                    bottom: 110px; /* Encima del botón Ir al inicio */

                    width: 55px;
                    height: 55px;

                    background: linear-gradient(135deg, #25d366, #1ebe5d);
                    border-radius: 50%;

                    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    cursor: pointer;
                    z-index: 999998;

                    transition: transform 0.25s ease, box-shadow 0.25s ease;
                }

                #whatsapp-float-btn:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 9px 20px rgba(0, 0, 0, 0.25);
                }

                #whatsapp-float-btn img {
                    width: 30px;
                    height: 30px;
                }

                #whatsapp-chat-box a {
                    pointer-events: none;
                }

                #whatsapp-chat-box.active a.btn-chat {
                    pointer-events: auto;
                    color: white;
                }

                /* Ajustes para móviles */
                @media (max-width: 768px) {
                    #whatsapp-float-btn {
                        right: 15px;
                        bottom: 110px;
                        width: 50px;
                        height: 50px;
                    }

                    #whatsapp-float-btn img {
                        width: 27px;
                        height: 27px;
                    }

                    #whatsapp-chat-box {
                        right: 15px;
                        bottom: 175px;
                        left: auto;
                        width: 320px;
                        max-width: calc(100vw - 30px);
                    }
                }
            </style>

            <!-- botón flotante -->
            <div id="whatsapp-float-btn" onclick="toggleChatBox(event)">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg">
            </div>

            <!-- chat -->
            <div id="whatsapp-chat-box">
                <div class="chat-header">
                    <div class="chat-title">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg">
                        <span>Soporte FYGroup</span>
                    </div>

                    <span class="chat-close" onclick="closeChatBox()">✕</span>
                </div>

                <div class="chat-body" id="chatBody">
                    <div class="msg">
                        Hola <?= $_SESSION['user']['name'] ?> 👋<br>
                        ¿En qué podemos ayudarte?
                    </div>

                    <a href="https://wa.me/56923816700?text=Hola%20necesito%20ayuda" target="_blank" rel="noopener noreferrer" class="btn-chat">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg">
                        Escribir por WhatsApp
                    </a>
                </div>
            </div>

            <script>
                function toggleChatBox(e) {
                    e.stopPropagation();

                    const box = document.getElementById("whatsapp-chat-box");
                    const body = document.getElementById("chatBody");

                    if (box.classList.contains("active")) {
                        box.classList.remove("active");
                        body.style.display = "none";
                    } else {
                        box.classList.add("active");
                        body.style.display = "block";
                    }
                }

                function closeChatBox() {
                    const box = document.getElementById("whatsapp-chat-box");
                    const body = document.getElementById("chatBody");

                    box.classList.remove("active");
                    body.style.display = "none";
                }
            </script>
        <?php

        return ob_get_clean();
    }

    public static function customAlert($type, $title, $message)
    {
        $alerts = [

            'danger' => [
                'icon' => 'fa-circle-xmark',
                'color' => 'var(--color-danger)',
            ],

            'success' => [
                'icon' => 'fa-circle-check',
                'color' => 'var(--color-success)',
            ],

            'warning' => [
                'icon' => 'fa-triangle-exclamation',
                'color' => 'var(--color-warning)',
            ],

            'info' => [
                'icon' => 'fa-circle-info',
                'color' => 'var(--color-info)',
            ],
        ];

        $alert = $alerts[$type] ?? $alerts['info'];

        ob_start();
        ?>
        <div class="custom-alert-<?= htmlspecialchars($type) ?> d-flex align-items-start" role="alert">
            <div class="custom-alert-icon" style="color: <?= $alert['color'] ?>;">
                <i class="fa-solid <?= $alert['icon'] ?>"></i>
            </div>

            <div class="custom-alert-content">
                <div class="custom-alert-title">
                    <?= htmlspecialchars($title) ?>
                </div>

                <div class="custom-alert-message">
                    <?= strip_tags($message,'<b><strong><i><em><s><strike><mark><br><ul><ol><li>')?>
                </div>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }

}
