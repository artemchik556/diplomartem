document.addEventListener("DOMContentLoaded", function() {
    const bookingForm = document.querySelector('.booking-form form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Проверка выбранной группы
            const selectedGroup = document.querySelector('input[name="group_type"]:checked');
            if (!selectedGroup) {
                alert('Пожалуйста, выберите группу');
                return;
            }

            // Проверка даты
            const dateInput = document.getElementById('excursion_date');
            if (!dateInput.value) {
                alert('Пожалуйста, выберите дату экскурсии');
                return;
            }

            // Проверка количества человек
            const peopleInput = document.getElementById('number_of_people');
            if (!peopleInput.value || peopleInput.value < 1 || peopleInput.value > 10) {
                alert('Пожалуйста, укажите корректное количество человек (от 1 до 10)');
                return;
            }

            // Если все проверки пройдены, отправляем форму
            this.submit();
        });
    }
}); 

document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.querySelector('.discount-popup-overlay');
    const closeButton = document.querySelector('.btn-close-popup');

    if (overlay && closeButton) {
        closeButton.addEventListener('click', closePopup);
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                closePopup();
            }
        });

        function closePopup() {
            overlay.classList.add('hidden');

            fetch('{{ route("clear.discount.session") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                overlay.style.display = 'none';
            })
            .catch(error => {
                console.error('Error clearing session:', error);
            });
        }
    }
});