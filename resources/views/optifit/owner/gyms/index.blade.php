@extends('layouts.optifit-apex')
@section('title', 'إدارة النوادي | OptiFit APEX')
@section('eyebrow', 'OWNER / GYMS')
@section('page-heading', 'إدارة النوادي')
@section('actions')<a class="apx-primary" href="{{ route('owner.gyms.create') }}"><svg><use href="#apx-plus"/></svg>إضافة نادٍ</a>@endsection
@section('content')
<section class="apx-page-hero"><div><span class="apx-eyebrow">YOUR GYM PORTFOLIO</span><h1>كل نواديك، <em>تحت سيطرتك.</em></h1><p>أضف بيانات النادي وعدّل مرافقه وأنشطته واشتراكاته وساعات عمله وصوره. جميع العمليات محمية بالملكية على الخادم.</p></div></section>
<div class="apx-stat-strip">
    <article class="apx-mini-stat"><strong>{{ $gyms->count() }}</strong><span>إجمالي النوادي</span></article>
    <article class="apx-mini-stat"><strong>{{ $gyms->where('verification_status','pending')->count() }}</strong><span>قيد المراجعة</span></article>
    <article class="apx-mini-stat"><strong>{{ $gyms->sum('reviews_count') }}</strong><span>إجمالي التقييمات</span></article>
    <article class="apx-mini-stat"><strong>{{ $gyms->sum('favorited_by_users_count') }}</strong><span>مرات الحفظ</span></article>
</div>
@if($gyms->isEmpty())
    <section class="apx-panel apx-empty"><svg><use href="#apx-building"/></svg><h2>لا يوجد نادٍ بعد</h2><p>أنشئ أول نادٍ لتبدأ عملية المراجعة.</p><a class="apx-primary" href="{{ route('owner.gyms.create') }}">إضافة نادٍ</a></section>
@else
    <div class="apx-cards">
        @foreach($gyms as $gym)
            <article class="apx-gym-tile">
                <div class="apx-tile-media">
                    @if($gym->images->first())<img src="{{ asset('storage/'.$gym->images->first()->image_path) }}" alt="صورة {{ $gym->gym_name }}" width="640" height="360" loading="lazy">
                    @else<div class="apx-tile-placeholder"><svg><use href="#apx-dumbbell"/></svg></div>@endif
                </div>
                <div class="apx-tile-body">
                    <div class="apx-tile-meta"><span>{{ $gym->city }}{{ $gym->area ? ' · '.$gym->area : '' }}</span><span class="apx-status-chip {{ $gym->verification_status }}">{{ ['pending'=>'قيد المراجعة','approved'=>'معتمد','rejected'=>'مرفوض'][$gym->verification_status] ?? $gym->verification_status }}</span></div>
                    <h2>{{ $gym->gym_name }}</h2><p>{{ \Illuminate\Support\Str::limit($gym->description ?: 'لم تتم إضافة وصف للنادي.', 120) }}</p>
                    <div class="apx-tile-footer"><span>{{ $gym->reviews_count }} تقييم · {{ $gym->favorited_by_users_count }} حفظ</span><a class="apx-primary" href="{{ route('owner.gyms.edit', $gym) }}"><svg><use href="#apx-edit"/></svg>إدارة</a></div>
                </div>
            </article>
        @endforeach
    </div>
@endif
@endsection
