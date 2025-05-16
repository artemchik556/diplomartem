document.addEventListener("DOMContentLoaded", function () {
    const popup = document.getElementById("auth-popup");
    const closeBtn = document.querySelector(".close-popup");
    const registerTab = document.getElementById("register-tab");
    const loginTab = document.getElementById("login-tab");
    const registerForm = document.getElementById("register-form");
    const loginForm = document.getElementById("login-form");
    const loginButton = document.querySelector(".login");

    // Открытие поп-ап окна только для кнопки входа
    if (loginButton) {
        loginButton.addEventListener("click", function (event) {
            const target = event.target;
            if (target.hasAttribute("onclick") && target.getAttribute("onclick").includes("openPopup('login-popup')")) {
                event.preventDefault();
                popup.classList.add("active");
            }
        });
    }

    // Закрытие поп-ап окна при клике на крестик
    if (closeBtn) {
        closeBtn.addEventListener("click", function () {
            popup.classList.remove("active");
        });
    }

    // Закрытие поп-апа при клике вне формы
    if (popup) {
        window.addEventListener("click", function (event) {
            if (event.target === popup) {
                popup.classList.remove("active");
            }
        });
    }

    // Переключение на вкладку "Регистрация"
    if (registerTab && registerForm && loginForm && loginTab) {
        registerTab.addEventListener("click", function () {
            registerForm.style.display = "block";
            loginForm.style.display = "none";
            registerTab.classList.add("active");
            loginTab.classList.remove("active");
        });

        // Переключение на вкладку "Вход"
        loginTab.addEventListener("click", function () {
            loginForm.style.display = "block";
            registerForm.style.display = "none";
            loginTab.classList.add("active");
            registerTab.classList.remove("active");
        });
    }

    // Закрытие по фону для .popup, исключая .discount-popup-overlay
    document.querySelectorAll('.popup:not(.discount-popup-overlay)').forEach(popup => {
        popup.addEventListener("click", function(e) {
            if (e.target === this) {
                closePopup(this.id);
            }
        });
    });
});

function openPopup(id) {
    const popup = document.getElementById(id);
    if (popup) {
        popup.style.display = "flex";
    }
}

function closePopup(id) {
    const popup = document.getElementById(id);
    if (popup) {
        popup.style.display = "none";
    }
}

function switchPopup(closeId, openId) {
    closePopup(closeId);
    openPopup(openId);
}