document.addEventListener('DOMContentLoaded', function() {
    const commentForms = document.querySelectorAll('.comment-form');
    
    commentForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const questionId = form.dataset.questionId;
            const submitButton = form.querySelector('.submit-buttons');
            const textarea = form.querySelector('textarea');
            
            // Отключаем кнопку и поле ввода на время отправки
            submitButton.disabled = true;
            textarea.disabled = true;
            
            fetch(`/questions/${questionId}/comments`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Создаем новый элемент комментария
                    const commentsList = form.closest('.question-block').querySelector('.comments-list');
                    const newComment = document.createElement('div');
                    newComment.className = 'comment';
                    newComment.innerHTML = `
                        <div class="comment-header">
                            <span class="comment-author">${data.comment.user_name}</span>
                            <span class="comment-date">${data.comment.created_at}</span>
                        </div>
                        <div class="comment-content">${data.comment.content}</div>
                    `;
                    
                    // Добавляем комментарий в начало списка
                    if (commentsList.firstChild) {
                        commentsList.insertBefore(newComment, commentsList.firstChild);
                    } else {
                        commentsList.appendChild(newComment);
                    }
                    
                    // Очищаем форму
                    form.reset();
                    
                    // Показываем сообщение об успехе
                    const successMessage = document.createElement('div');
                    successMessage.className = 'alert alert-success';
                    successMessage.textContent = data.message;
                    form.parentNode.insertBefore(successMessage, form);
                    
                    // Удаляем сообщение через 3 секунды
                    setTimeout(() => {
                        successMessage.remove();
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorMessage = document.createElement('div');
                errorMessage.className = 'alert alert-danger';
                errorMessage.textContent = 'Произошла ошибка при отправке комментария. Пожалуйста, попробуйте позже.';
                form.parentNode.insertBefore(errorMessage, form);
                
                setTimeout(() => {
                    errorMessage.remove();
                }, 3000);
            })
            .finally(() => {
                // Включаем кнопку и поле ввода обратно
                submitButton.disabled = false;
                textarea.disabled = false;
            });
        });
    });
}); 