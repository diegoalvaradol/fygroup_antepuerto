(function ($) {
  'use strict'; // Start of use strict

  // Toggle the side navigation
  $('#sidebarToggle, #sidebarToggleTop').on('click', function (e) {
    $('body').toggleClass('sidebar-toggled');
    $('.sidebar').toggleClass('toggled');
    if ($('.sidebar').hasClass('toggled')) {
      $('.sidebar .collapse').collapse('hide');
    }
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function () {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    }

    // Toggle the side navigation when window is resized below 480px
    if ($(window).width() < 480 && !$('.sidebar').hasClass('toggled')) {
      $('body').addClass('sidebar-toggled');
      $('.sidebar').addClass('toggled');
      $('.sidebar .collapse').collapse('hide');
    }
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function (e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button appear
  $(document).on('scroll', function () {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function (e) {
    var $anchor = $(this);
    $('html, body')
      .stop()
      .animate(
        {
          scrollTop: $($anchor.attr('href')).offset().top,
        },
        1000,
        'easeInOutExpo',
      );
    e.preventDefault();
  });
})(jQuery); // End of use strict

/* Guarda información del usuario */
var saveInfoUser = function () {
  const password = $('#password').val();
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
  let hasError = false;

  /* Revisa que la contraseña tenga los caracteres obligatorios */
  if (!regex.test(password)) {
    Swal.fire({
      title: 'Oops...',
      text: 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.',
      icon: 'error',
      cancelButtonColor: '#d33',
    });

    hasError = true;
  }

  if (!hasError) {
    $.ajax({
      url: '../controllers/userSaveController.php',
      data: $('#editUserInfoForm').serialize(),
      type: 'POST',
    }).done(function (x) {
      if (x == 'OK') {
        Swal.fire({
          title: '¡Éxito!',
          html: '¡Información actualizada con éxito! </br> Por motivos de seguridad deberás iniciar sesión nuevamente.',
          icon: 'success',
          confirmButtonColor: '#4CAF50',
        }).then((result) => {
          window.location = 'logout.php';
        });
      } else {
        Swal.fire({
          title: 'Oops...',
          text: 'Error al actualizar la información.',
          icon: 'error',
          cancelButtonColor: '#d33',
        });
      }
    });
  }
};

/* Actualiza reloj */
function actualizarReloj() {
  const ahora = new Date();

  const hora = ahora.toLocaleTimeString('es-CL', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false,
  });

  const diaSemana = ahora.toLocaleDateString('es-CL', { weekday: 'long' });
  const fecha = ahora.toLocaleDateString('es-CL', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  });

  document.getElementById('relojFecha').innerHTML = `
    <div style="display:flex; align-items:center; gap:10px;">
      <div style="font-size:20px; font-weight:bold;">
        ${hora}
      </div>
      |
      <div style="line-height:1.2;">
        <div>${diaSemana}</div>
        <div style="font-size:12px;">${fecha}</div>
      </div>
    </div>
  `;
}

/* Conteo regresivo para cierre de sesion */
let inactivityTime = function () {
  let time;
  let warningTimeout = 30 * 60 * 1000; /* Minutos a convenir */
  let countdownTime = 30; /* 30 segundos para responder */

  function startTimer() {
    window.addEventListener('mousemove', resetTimer, false);
    window.addEventListener('keypress', resetTimer, false);
    window.addEventListener('click', resetTimer, false);
    window.addEventListener('scroll', resetTimer, false);
    resetTimer();
  }

  function logoutCountdown() {
    let timerInterval;
    Swal.fire({
      title: '¿Sigues ahí?',
      html: `Serás desconectado en <b></b> segundos por inactividad.`,
      icon: 'warning',
      timer: countdownTime * 1000,
      timerProgressBar: true,
      showCancelButton: true,
      allowOutsideClick: false,
      allowEscapeKey: false,
      confirmButtonColor: '#4e73df',
      cancelButtonColor: '#d33',
      confirmButtonText: '¡Sigo aquí!',
      cancelButtonText: 'Cerrar sesión',
      didOpen: () => {
        const b = Swal.getHtmlContainer().querySelector('b');
        timerInterval = setInterval(() => {
          b.textContent = Math.ceil(Swal.getTimerLeft() / 1000);
        }, 1000);
      },
      willClose: () => {
        clearInterval(timerInterval);
      },
    }).then((result) => {
      if (result.isConfirmed) {
        resetTimer(); /* Usuario activo, reiniciar contador */
      } else {
        window.location = 'login.php?msg=sesion_expirada';
      }
    });
  }

  function resetTimer() {
    clearTimeout(time);
    time = setTimeout(logoutCountdown, warningTimeout);
  }

  startTimer();
};

window.onload = function () {
  inactivityTime();
};

/* Inicializa el popover */
document.addEventListener('DOMContentLoaded', function () {
  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="popover"]'));
  popoverTriggerList.forEach(function (el) {
    new bootstrap.Popover(el);
  });
});

/* Select2 */
$(document).on('select2:open', function () {
  let searchField = document.querySelector('.select2-container--open .select2-search__field');
  if (searchField) {
    searchField.focus();
  }
});

/* PORTAL CLIENTE */
/* Si ya hay un tiempo guardado en sessionStorage, úsalo. Si no, restablece a 30 segundos */
let tiempoLimite = sessionStorage.getItem('tiempoLimite')
  ? parseInt(sessionStorage.getItem('tiempoLimite'))
  : 1800; /* 30 segundos por defecto si no está en sessionStorage */

/* Función que actualiza el contador */
function actualizarConteo() {
  let minutos = Math.floor(tiempoLimite / 60);
  let segundos = tiempoLimite % 60;

  /* Muestra el tiempo en formato MM:SS */
  $('#countDownSession').html(
    `Tiempo restante: ${minutos}:${segundos < 10 ? '0' + segundos : segundos}`,
  );

  if (tiempoLimite <= 0) {
    clearInterval(contador); /* Detiene el contador cuando llega a 0 */
  } else {
    tiempoLimite--;
    sessionStorage.setItem(
      'tiempoLimite',
      tiempoLimite,
    ); /* Guarda el tiempo restante en sessionStorage */
  }
}

/* Puedes simular el inicio y cierre de sesión con botones: */
// startCountDown(); // Llama esta función cuando el usuario inicie sesión
// finishCountDown(); // Llama esta función cuando el usuario cierre sesión

/* Simulación de inicio de sesión */
function startCountDown() {
  sessionStorage.setItem(
    'tiempoLimite',
    1800,
  ); /* Restablece el temporizador a 30 segundos al iniciar sesión */
  localStorage.setItem(
    'tiempoLimite',
    1800,
  ); /* Si es el primer inicio, establece el tiempo por defecto en localStorage */
}

/* Simulación de cierre de sesión */
function finishCountDown() {
  sessionStorage.removeItem('tiempoLimite'); /* Elimina el tiempo de la sesión actual */
}

$(document).ready(function () {
  inactivityTime();
  setInterval(actualizarReloj, 1000);
  actualizarReloj();
});
