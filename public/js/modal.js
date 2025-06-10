document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("consultationModal");
    const openModalHeaderBtn = document.getElementById("openModalHeader");
    const openModalMainBtn = document.getElementById("openModalMain");
    const closeModalBtn = document.querySelector(".close");
    const form = document.getElementById("consultationForm");

    // Открытие модального окна при клике на любую из кнопок
    openModalHeaderBtn.addEventListener("click", () => modal.style.display = "block");
    openModalMainBtn.addEventListener("click", () => modal.style.display = "block");

    // Закрытие модального окна
    closeModalBtn.addEventListener("click", () => modal.style.display = "none");
    window.addEventListener("click", (e) => {
        if (e.target === modal) modal.style.display = "none";
    });

    // Обработка отправки формы
    form.addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch(form.action, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || "Спасибо! Мы свяжемся с вами в ближайшее время.");
            modal.style.display = "none";
            form.reset();
        })
        .catch(error => {
            console.error("Ошибка:", error);
            alert("Произошла ошибка при отправке формы. Пожалуйста, попробуйте позже.");
        });
    });
});
