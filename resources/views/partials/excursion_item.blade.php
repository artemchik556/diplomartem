<a href="{{ route('excursion.detail', $excursion->id) }}" class="cards-link">
    <div class="cards">
        <div class="excursion-card">
            <img class="size" src="{{ asset('storage/' . $excursion->image) }}" alt="{{ $excursion->title }}">
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
