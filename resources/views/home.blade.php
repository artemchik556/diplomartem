@php
    $excursions = App\Models\Excursion::latest()->take(4)->get();
@endphp

<section>
    <h2>Куда идти сейчас</h2>
    <div class="excursions">
        @foreach ($excursions as $excursion)
            <div class="excursion-card">
                <img src="{{ asset('storage/' . $excursion->image) }}" alt="{{ $excursion->title }}">
                <h3>{{ $excursion->title }}</h3>
                <p>{{ $excursion->location }}</p>
                <p>{{ $excursion->date }}</p>
                <p>{{ $excursion->price }} р.</p>
            </div>
        @endforeach
    </div>
</section>
