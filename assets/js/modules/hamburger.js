const $hamburgerIcon = document.getElementById('hamburger-icon');
const $hamburgerMenu = document.getElementById('hamburger-menu');


$hamburgerIcon.addEventListener('click', () => {
  $hamburgerIcon.classList.toggle('is-active');
  $hamburgerMenu.classList.toggle('is-active');
});

// メニュー内のリンクがクリックされたときにメニューを閉じる
const menuLinks = $hamburgerMenu.querySelectorAll('a');
menuLinks.forEach(link => {
  link.addEventListener('click', () => {
    $hamburgerIcon.classList.remove('is-active');
    $hamburgerMenu.classList.remove('is-active');
  });
});
