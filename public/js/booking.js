document.addEventListener("DOMContentLoaded", function() {
    const bookingForm = document.querySelector('.booking-form form');
    const dateInput = document.getElementById('excursion_date');
    const groupInputs = document.querySelectorAll('input[name="group_type"]');
    const overlay = document.querySelector('.discount-popup-overlay');
    const closeButton = document.querySelector('.btn-close-popup');
    
    // Функция для обновления количества доступных мест
    function updateAvailableSeats() {
        const selectedDate = dateInput.value;
        if (!selectedDate) {
            console.log('Дата не выбрана');
            return;
        }

        console.log('Отправляем запрос на получение мест для даты:', selectedDate);
        console.log('ID экскурсии:', excursionId);

        const url = `/api/excursion/${excursionId}/available-seats?date=${selectedDate}`;
        console.log('URL запроса:', url);

        // Отправляем AJAX запрос для получения доступных мест
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin'
        })
        .then(response => {
            console.log('Получен ответ от сервера:', response.status);
            console.log('Заголовки ответа:', Object.fromEntries(response.headers.entries()));
            
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Ошибка ответа:', text);
                    throw new Error(text || 'Ошибка сервера');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Получены данные о местах:', data);
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Обновляем количество мест для каждой группы
            groupInputs.forEach(input => {
                const groupType = input.value;
                const seatsCount = data[groupType] || 0;
                const label = input.nextElementSibling;
                
                console.log(`Обновляем места для группы ${groupType}:`, seatsCount);
                
                // Обновляем текст с количеством мест
                label.textContent = `Группа ${groupType.toUpperCase()} (Осталось мест: ${seatsCount})`;
                
                // Блокируем/разблокируем радио-кнопку
                input.disabled = seatsCount <= 0;
                
                // Если радио-кнопка была выбрана и теперь заблокирована, снимаем выбор
                if (input.checked && seatsCount <= 0) {
                    input.checked = false;
                }
            });
        })
        .catch(error => {
            console.error('Ошибка при получении данных:', error);
            alert(`Ошибка при обновлении информации о доступных местах: ${error.message}`);
        });
    }

    // Обработчик изменения даты
    if (dateInput) {
        dateInput.addEventListener('change', updateAvailableSeats);
        // Вызываем функцию при загрузке страницы для установки начальных значений
        updateAvailableSeats();
    }

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

    // Обработка всплывающего окна скидки
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