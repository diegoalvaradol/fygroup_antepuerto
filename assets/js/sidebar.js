/* JS Principal */
document.addEventListener('DOMContentLoaded', function () {
  const sidebar = document.getElementById('accordionSidebar');
  const toggle = document.getElementById('mobileSidebarToggle');
  const overlay = document.getElementById('sidebarOverlay');

  function close() {
    sidebar.classList.remove('show');
    overlay.classList.remove('active');
    toggle.classList.remove('active');
  }

  function open() {
    sidebar.classList.add('show');
    overlay.classList.add('active');
    toggle.classList.add('active');
  }

  toggle.addEventListener('click', function () {
    const isOpen = sidebar.classList.contains('show');

    if (isOpen) {
      close();
    } else {
      open();
    }
  });

  overlay.addEventListener('click', close);

  window.addEventListener('resize', function () {
    if (window.innerWidth >= 769) close();
  });

  close();
});
