<div class="rating-form">
    <h3>Оставить отзыв</h3>
    <form id="ratingForm" class="rating-form-content">
        @csrf
        <div class="stars-input">
            @for($i = 5; $i >= 1; $i--)
                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" />
                <label for="star{{ $i }}"><i class="fas fa-star"></i></label>
            @endfor
        </div>
        <textarea name="review" placeholder="Ваш отзыв (необязательно)"></textarea>
        <button type="submit">Отправить отзыв</button>
    </form>
</div>

<style>
.rating-form {
    margin-top: 30px;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
}

.stars-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}

.stars-input input {
    display: none;
}

.stars-input label {
    cursor: pointer;
    font-size: 24px;
    color: #ddd;
}

.stars-input label:hover,
.stars-input label:hover ~ label,
.stars-input input:checked ~ label {
    color: #ffd700;
}

.rating-form textarea {
    width: 100%;
    margin: 15px 0;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    min-height: 100px;
}

.rating-form button {
    background: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
}

.rating-form button:hover {
    background: #45a049;
}
</style>

<script>
document.getElementById('ratingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("excursion.rate", $excursion->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при отправке отзыва');
    });
});
</script> 