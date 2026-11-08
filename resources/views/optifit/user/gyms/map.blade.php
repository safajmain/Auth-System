@extends('layouts.optifit-user')
@section('title', 'خريطة النوادي | OptiFit APEX')
@section('eyebrow', 'USER / MAP')
@section('page-heading', 'خريطة النوادي')
@push('head')<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous">@endpush
@section('content')
<section class="apx-page-hero"><div><span class="apx-eyebrow">OPENSTREETMAP / NO PAID KEY</span><h1>الأندية على <em>الخريطة.</em></h1><p>تعرض الخريطة النوادي المعتمدة التي أضاف أصحابها إحداثيات حقيقية. لا تستخدم خدمة مدفوعة.</p></div></section>
@if($gyms->isEmpty())
    <section class="apx-panel apx-empty"><svg aria-hidden="true"><use href="#apx-map"/></svg><h2>لا توجد إحداثيات متاحة</h2><p>يمكن متابعة البحث النصي حتى يضيف أصحاب النوادي مواقعهم.</p><a class="apx-primary" href="{{ route('gyms.index') }}">البحث عن النوادي</a></section>
@else
    <div id="gym-map" class="apx-map-canvas" role="region" aria-label="خريطة النوادي الحقيقية"></div>
    <section id="map-fallback" class="apx-panel apx-map-fallback" role="alert">
        <div class="apx-panel-body"><h2>تعذر تحميل طبقة الخريطة</h2><p>بيانات النوادي متاحة، لكن مكتبة Leaflet أو مربعات OpenStreetMap لم تصل من الشبكة. يمكنك فتح النادي مباشرة:</p><div class="apx-chips">@foreach($gyms as $gym)<a class="apx-chip" href="{{ route('gyms.show', $gym) }}">{{ $gym->gym_name }} — {{ collect([$gym->city, $gym->area])->filter()->join('، ') }}</a>@endforeach</div></div>
    </section>
@endif
@endsection
@if($gyms->isNotEmpty())
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const gyms = {{ Illuminate\Support\Js::from($mapGyms) }};
    const mapElement = document.getElementById('gym-map');
    const fallback = document.getElementById('map-fallback');
    const showFallback = () => fallback?.classList.add('is-visible');

    if (!mapElement || !window.L || !Array.isArray(gyms) || gyms.length === 0) {
        showFallback();
        return;
    }

    try {
        const map = L.map(mapElement, { scrollWheelZoom: false });
        const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
        });
        tiles.on('tileerror', showFallback);
        tiles.addTo(map);
        const points = [];

        gyms.forEach((gym) => {
            if (!Number.isFinite(gym.lat) || !Number.isFinite(gym.lng)) return;
            points.push([gym.lat, gym.lng]);
            const popup = document.createElement('div');
            const name = document.createElement('strong');
            const location = document.createElement('p');
            const link = document.createElement('a');
            name.textContent = gym.name;
            location.textContent = [gym.city, gym.area].filter(Boolean).join('، ');
            link.textContent = 'عرض التفاصيل';
            link.href = gym.url;
            popup.append(name, location, link);
            L.marker([gym.lat, gym.lng]).addTo(map).bindPopup(popup);
        });

        if (points.length === 1) map.setView(points[0], 14);
        else if (points.length > 1) map.fitBounds(points, { padding: [35, 35], maxZoom: 14 });
        else showFallback();
    } catch (error) {
        console.error('OptiFit map initialization failed', error);
        showFallback();
    }
});
</script>
@endpush
@endif
