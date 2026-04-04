<?php
class UIComponents
{
  public static function scrollToTopButton()
  {
    $top = '
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
					z-index: 1000;
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

			<button id="scrollTopBtn" onclick="window.scrollTo({ top: 0, behavior: \'smooth\' });">
				<i class="fas fa-circle-arrow-up"></i> Ir al inicio
			</button>

			<script>
				const scrollBtn = document.getElementById("scrollTopBtn");
				window.addEventListener("scroll", () => {
					if (window.scrollY > 200) {
						scrollBtn.classList.add("show");
					} else {
						scrollBtn.classList.remove("show");
					}
				});
			</script>
		';

    return $top;
  }

  public static function whatsappChatBox()
  {
    $whatsappBtn = '
			<style>
				#whatsapp-chat-box {
					position: fixed;
					bottom: 20px;
					left: 20px;
					width: 280px;
					max-width: 90%;
					background-color: #fff;
					border-radius: 12px;
					box-shadow: 0 4px 8px rgba(0,0,0,0.2);
					font-family: Arial, sans-serif;
					z-index: 9999;
				}

				.chat-header {
					display: flex;
					align-items: center;
					justify-content: space-between;
					padding: 12px;
					background-color: #25d366;
					color: white;
					border-radius: 12px 12px 0 0;
				}

				.chat-title {
					display: flex;
					align-items: center;
					gap: 10px;
					cursor: pointer;
				}

				.chat-close {
					cursor: pointer;
					font-weight: bold;
					font-size: 16px;
				}

				.chat-body {
					padding: 15px;
					display: none;
					font-size: 14px;
				}

				.btn-chat {
					display: inline-block;
					margin-top: 10px;
					padding: 10px 15px;
					background-color: #25d366;
					color: white;
					text-decoration: none;
					border-radius: 8px;
					font-weight: bold;
				}

				/* Botón flotante */
				#whatsapp-float-btn {
					display: none;
					position: fixed;
					bottom: 20px;
					left: 20px;
					width: 60px;
					height: 60px;
					background-color: #25d366;
					border-radius: 50%;
					box-shadow: 0 4px 8px rgba(0,0,0,0.3);
					align-items: center;
					justify-content: center;
					z-index: 9999;
					cursor: pointer;
				}

				#whatsapp-float-btn img {
					width: 30px;
				}

				/* Móvil */
				@media (max-width: 480px) {
					#whatsapp-chat-box {
						display: none;
					}

					#whatsapp-chat-box.active {
						display: block;
					}

					#whatsapp-float-btn {
						display: flex;
					}

					#whatsapp-float-btn.hidden {
						display: none;
					}
				}
			</style>

			<!-- Botón flotante -->
			<div id="whatsapp-float-btn" onclick="openChatBox()">
				<img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg">
			</div>

			<!-- Chat -->
			<div id="whatsapp-chat-box">
				<div class="chat-header">
					<div class="chat-title" onclick="toggleChatBox()">
						<img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" width="30" />
						<span>¿Necesitas ayuda?</span>
					</div>
					<span class="chat-close" onclick="closeChatBox()">✕</span>
				</div>

				<div class="chat-body" id="chatBody">
					<p>Hola ' . $_SESSION["user"]["name"] . ' 👋<br>¿En qué podemos ayudarte?</p>
					<a href="https://wa.me/56923816700?text=Hola%2C%20necesito%20ayuda" target="_blank" class="btn-chat">Iniciar chat</a>
				</div>
			</div>

			<script>
				function toggleChatBox() {
					const chatBody = document.getElementById("chatBody");
					chatBody.style.display = chatBody.style.display === "block" ? "none" : "block";
				}

				function openChatBox() {
					const box = document.getElementById("whatsapp-chat-box");
					const btn = document.getElementById("whatsapp-float-btn");
					const body = document.getElementById("chatBody");

					box.classList.add("active");
					body.style.display = "block";
					btn.classList.add("hidden");
				}

				function closeChatBox() {
					const box = document.getElementById("whatsapp-chat-box");
					const btn = document.getElementById("whatsapp-float-btn");
					const body = document.getElementById("chatBody");

					box.classList.remove("active");
					body.style.display = "none";
					btn.classList.remove("hidden");
				}
			</script>
  	';

    return $whatsappBtn;
  }

}
