/**
 * Phần login_register
 */

// hiệu ứng đăng nhập và đăng ký
const registerButton = document.getElementById('register')
const loginButton = document.getElementById('login')
const container = document.getElementById('container')

registerButton.addEventListener('click', () => {
    console.log(1)
    container.classList.add("right-panel-active");
});

loginButton.addEventListener("click", () => {
    container.classList.remove("right-panel-active");
});

// Xử lý phần nội dung

