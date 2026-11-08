@extends('layouts.optifit-user')
@section('title', 'المفضلة | OptiFit APEX')
@section('eyebrow', 'USER / FAVORITES')
@section('page-heading', 'النوادي المفضلة')
@section('content')
<section class="apx-page-hero"><div><span class="apx-eyebrow">SAVED FOR LATER</span><h1>نواديك <em>المحفوظة.</em></h1><p>قائمة حقيقية من جدول المفضلة في MySQL.</p></div></section>
@if($gyms->isEmpty())<section class="apx-panel apx-empty"><svg><use href="#apx-heart"/></svg><h2>المفضلة فارغة</h2><a class="apx-primary" href="{{ route('gyms.index') }}">استكشف النوادي</a></section>@else<div class="apx-cards">@foreach($gyms as $gym)<article class="apx-gym-tile"><div class="apx-tile-media">@if($gym->images->first())<img src="{{ asset('storage/'.$gym->images->first()->image_path) }}" alt="صورة {{ $gym->gym_name }}">@else<div class="apx-tile-placeholder"><svg><use href="#apx-heart"/></svg></div>@endif</div><div class="apx-tile-body"><span class="apx-eyebrow">{{ $gym->city }}</span><h2>{{ $gym->gym_name }}</h2><p>{{ \Illuminate\Support\Str::limit($gym->description ?: 'لا يوجد وصف.',100) }}</p><div class="apx-tile-footer"><a class="apx-primary" href="{{ route('gyms.show',$gym) }}">التفاصيل</a><form method="POST" action="{{ route('favorites.destroy',$gym) }}">@csrf @method('DELETE')<button class="apx-danger" type="submit">إزالة</button></form></div></div></article>@endforeach</div>@endif
@endsection
