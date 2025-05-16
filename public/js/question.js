document.querySelectorAll('.faq-item').forEach(item => {
    item.addEventListener('click', () => {
        // Закрываем все другие открытые вопросы
        document.querySelectorAll('.faq-item').forEach(otherItem => {
            if (otherItem !== item) {
                otherItem.classList.remove('active');
                otherItem.querySelector('.faq-toggle').textContent = '+';
                otherItem.querySelector('.faq-answer').style.display = 'none';
            }
        });

        // Переключаем текущий вопрос
        const toggle = item.querySelector('.faq-toggle');
        const answer = item.querySelector('.faq-answer');
        
        item.classList.toggle('active');
        
        if (item.classList.contains('active')) {
            toggle.textContent = '−';
            answer.style.display = 'block';
        } else {
            toggle.textContent = '+';
            answer.style.display = 'none';
        }
    });
});