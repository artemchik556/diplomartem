@foreach ($excursions as $excursion)
<a href="{{ route('excursion.detail', $excursion->id) }}" class="cards-link">
    <div class="cards">
        <div class="excursion-card">
            <div class="rating-badge">
                <span class="rating-number">{{ number_format($excursion->average_rating, 1) }}</span>
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= round($excursion->average_rating) ? 'active' : '' }}"></i>
                    @endfor
                </div>
            </div>
            <img class="size" src="{{ $excursion->previewPhoto() ? asset('storage/' . $excursion->previewPhoto()->photo_path) : asset('img/placeholder.jpg') }}" alt="{{ $excursion->title }}">
            <div class="card-texts">
                <div class="card-text">
                    <h3>{{ $excursion->title }}</h3>
                    <p class="price">{{ number_format($excursion->price, 0, ',', ' ') }} ₽</p>
                </div>
                <div class="card-text texts">
                    <p><i class="far fa-calendar-alt"></i> {{ $excursion->start_date->format('d.m.Y') }}</p>
                    <p><i class="fas fa-map-marker-alt"></i> {{ $excursion->location }}</p>
                </div>
            </div>
        </div>
    </div>
</a>
@endforeach 