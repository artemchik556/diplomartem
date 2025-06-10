document.addEventListener('DOMContentLoaded', function() {
    // Функция для валидации имени (только буквы и пробелы)
    function validateName(input) {
        const nameRegex = /^[а-яА-ЯёЁa-zA-Z\s-]+$/;
        if (!nameRegex.test(input.value)) {
            input.value = input.value.replace(/[^а-яА-ЯёЁa-zA-Z\s-]/g, '');
        }
    }

    // Находим все поля ввода имени на странице
    const nameInputs = document.querySelectorAll('input[name="name"]');
    
    // Добавляем обработчики событий для каждого поля
    nameInputs.forEach(input => {
        // Добавляем атрибут pattern для HTML5 валидации
        input.setAttribute('pattern', '[а-яА-ЯёЁa-zA-Z\\s-]+');
        input.setAttribute('title', 'Имя может содержать только буквы, пробелы и дефис');
        
        // Добавляем обработчики событий
        input.addEventListener('input', function() {
            validateName(this);
        });
        
        input.addEventListener('keypress', function(e) {
            const char = String.fromCharCode(e.which);
            if (!/[а-яА-ЯёЁa-zA-Z\s-]/.test(char)) {
                e.preventDefault();
            }
        });
    });
}); 