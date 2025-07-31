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
				<i class="fas fa-angle-up"></i> Ir al inicio
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
				left: 20px; /* alineado a la izquierda */
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
				gap: 10px;
				padding: 12px;
				background-color: #25d366;
				color: white;
				border-top-left-radius: 12px;
				border-top-right-radius: 12px;
				cursor: pointer;
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

			@media (max-width: 480px) {
				#whatsapp-chat-box {
					bottom: 10px;
					left: 10px;
					width: 90%;
				}

				.chat-body {
					font-size: 13px;
				}
			}
			</style>

      <!-- Chat flotante expandible estilo WhatsApp - Responsive -->
			<div id="whatsapp-chat-box" class="chat-box">
				<div class="chat-header" onclick="toggleChatBox()">
					<img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" width="30" />
					<span>¿Necesitas ayuda?</span>
				</div>
				<div class="chat-body" id="chatBody">
					<p>Hola <?php echo $_SESSION["user"]["name"] . ' . '; ?> 👋<br>¿En qué podemos ayudarte?</p>
					<a href="https://wa.me/56923816700?text=Hola%2C%20necesito%20ayuda" target="_blank" class="btn-chat">Iniciar chat</a>
				</div>
			</div>

			<script>
				function toggleChatBox() {
					const chatBody = document.getElementById("chatBody");
					chatBody.style.display = chatBody.style.display === "block" ? "none" : "block";
				}
			</script>
		';

    return $whatsappBtn;
  }
}
