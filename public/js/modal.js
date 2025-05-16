document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("consultationModal");
    const openModalBtn = document.getElementById("openModal");
    const closeModalBtn = document.querySelector(".close");
    const submitBtn = document.getElementById("submitForm");

    openModalBtn.addEventListener("click", () => modal.style.display = "block");
    closeModalBtn.addEventListener("click", () => modal.style.display = "none");
    window.addEventListener("click", (e) => { if (e.target === modal) modal.style.display = "none"; });

    submitBtn.addEventListener("click", () => {
        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const phone = document.getElementById("phone").value;

        if (name && email && phone) {
            fetch("/submit-consultation", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ name, email, phone })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                modal.style.display = "none";
            })
            .catch(error => console.error("Ошибка:", error));
        } else {
            alert("Заполните все поля!");
        }
    });
});
