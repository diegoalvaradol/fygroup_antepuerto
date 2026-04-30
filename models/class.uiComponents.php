<?php
class UIComponents
{
    public static function scrollToTopButton()
    {
        ob_start();
        ?>
            <style>
                #scrollTopBtn {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;

                    background-color: #1e293b;
                    color: white;
                    border: none;

                    padding: 10px 15px;
                    border-radius: 50px;
                    font-size: 14px;

                    box-shadow: 0 4px 6px rgba(0,0,0,0.3);
                    cursor: pointer;

                    z-index: 1100;

                    opacity: 0;
                    transform: translateY(40px);
                    transition: transform 0.4s ease, opacity 0.4s ease;

                    pointer-events: none;
                }

                #scrollTopBtn.show {
                    opacity: 1;
                    transform: translateY(0);
                    pointer-events: auto;
                }

                #scrollTopBtn i {
                    margin-right: 5px;
                }

                @media (max-width: 768px) {
                    #scrollTopBtn {
                        font-size: 12px;
                        padding: 8px 12px;
                    }
                }
            </style>

            <button id="scrollTopBtn"
                    onclick="window.scrollTo({ top: 0, behavior: 'smooth' });">
                <i class="fas fa-circle-arrow-up"></i> Ir al inicio
            </button>

            <script>
                let scrollBtn = document.getElementById("scrollTopBtn");

                window.addEventListener("scroll", () => {
                    if (window.scrollY > 200) {
                        scrollBtn.classList.add("show");
                    } else {
                        scrollBtn.classList.remove("show");
                    }
                });
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
                    bottom: 20px;
                    left: 20px;

                    width: 320px;
                    max-width: 92%;

                    background: #fff;
                    border-radius: 16px;

                    box-shadow: 0 12px 30px rgba(0,0,0,0.25);
                    font-family: "Segoe UI", sans-serif;

                    z-index: 1100;

                    overflow: hidden;
                    transform: translateY(20px);
                    opacity: 0;
                    pointer-events: none;

                    transition: all 0.25s ease;
                }

                #whatsapp-chat-box.active {
                    transform: translateY(0);
                    opacity: 1;
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
                    bottom: 20px;
                    left: 20px;

                    width: 65px;
                    height: 65px;

                    background: linear-gradient(135deg, #25d366, #1ebe5d);
                    border-radius: 50%;

                    box-shadow: 0 6px 15px rgba(0,0,0,0.3);

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    cursor: pointer;

                    z-index: 1100;
                }

                #whatsapp-float-btn img {
                    width: 32px;
                }

                #whatsapp-chat-box a {
                    pointer-events: none;
                }

                #whatsapp-chat-box.active a.btn-chat {
                    pointer-events: auto;
                    color: white;
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

                    <a href="https://wa.me/56923816700?text=Hola%20necesito%20ayuda"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn-chat">

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
}
