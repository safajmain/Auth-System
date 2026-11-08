<!DOCTYPE html>
<html lang="ar" dir="rtl" data-apx-theme="volt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0d120f">
    <script src="{{ asset('optifit-apex-assets/js/apex-theme.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OptiFit APEX')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('optifit-apex-assets/css/apex-shared.css') }}" rel="stylesheet">
    <link href="{{ asset('optifit-apex-assets/css/apex-features.css') }}" rel="stylesheet">
    @stack('head')
</head>
<body class="apx-feature-page">
@include('optifit_apex.partials.icons')
<a class="apx-skip" href="#feature-main">انتقل إلى المحتوى الرئيسي</a>
@php
    $roleId = (int) auth()->user()->role_id;
    $nav = match ($roleId) {
        2 => [
            ['dashboard', 'apx-home', 'لوحة المالك'],
            ['owner.gyms.index', 'apx-building', 'إدارة النوادي'],
            ['owner.gyms.create', 'apx-plus', 'إضافة نادٍ'],
        ],
        3 => [
            ['dashboard', 'apx-home', 'لوحة الإدارة'],
            ['admin.gyms.index', 'apx-shield', 'مراجعة النوادي'],
        ],
        default => [
            ['dashboard', 'apx-home', 'الرئيسية'],
            ['gyms.index', 'apx-search', 'البحث عن النوادي'],
            ['gyms.compare', 'apx-compare', 'المقارنة'],
            ['gyms.map', 'apx-map', 'الخريطة'],
            ['favorites.index', 'apx-heart', 'المفضلة'],
            ['assistant.index', 'apx-spark', 'المساعد الذكي'],
            ['reminders.index', 'apx-bell', 'التذكيرات'],
        ],
    };
@endphp
<div class="apx-feature-shell">
    <aside class="apx-feature-sidebar">
        <a class="apx-brand" href="{{ route('dashboard') }}"><span class="apx-brand-mark"><svg><use href="#apx-dumbbell"/></svg></span><span>OPTI<span>FIT</span> <small>APEX</small></span></a>
        <nav class="apx-feature-nav" aria-label="التنقل الرئيسي">
            @foreach($nav as [$routeName, $icon, $label])
                <a href="{{ route($routeName) }}" class="{{ request()->routeIs($routeName) || request()->routeIs($routeName.'.*') ? 'is-active' : '' }}">
                    <svg aria-hidden="true"><use href="#{{ $icon }}"/></svg><span>{{ $label }}</span>
                </a>
            @endforeach
        </nav>
        <div class="apx-feature-user">
            <span>{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
            <div><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->email }}</small></div>
        </div>
    </aside>
    <div class="apx-feature-main">
        <header class="apx-feature-topbar">
            <a class="apx-brand apx-feature-mobile-brand" href="{{ route('dashboard') }}"><span class="apx-brand-mark"><svg><use href="#apx-dumbbell"/></svg></span><span>OPTI<span>FIT</span></span></a>
            <div class="apx-feature-crumb"><small>@yield('eyebrow', 'OPTIFIT / APEX')</small><strong>@yield('page-heading', 'OptiFit')</strong></div>
            <div class="apx-feature-actions">
                @yield('actions')
                <button class="apx-icon-button" type="button" data-apx-theme-open aria-label="تغيير مظهر الصفحة"><svg><use href="#apx-palette"/></svg></button>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="apx-icon-button" type="submit" aria-label="تسجيل الخروج"><svg><use href="#apx-logout"/></svg></button></form>
            </div>
        </header>
        <main id="feature-main" class="apx-feature-content" tabindex="-1">
            @if(session('success'))<div class="apx-flash apx-flash-success" role="status"><svg><use href="#apx-check"/></svg><span>{{ session('success') }}</span></div>@endif
            @if($errors->any())<div class="apx-error-summary" role="alert" tabindex="-1"><h2>تعذر إكمال العملية:</h2><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @yield('content')
        </main>
    </div>
</div>
@include('optifit_apex.partials.theme-studio')
<script defer src="{{ asset('optifit-apex-assets/js/apex-shared.js') }}"></script>
@stack('scripts')
</body>
</html>
