<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="volt">
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
    <link href="{{ asset('optifit-apex-assets/css/user-dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('optifit-apex-assets/css/apex-features.css') }}" rel="stylesheet">
    <link href="{{ asset('optifit-apex-assets/css/user-shell.css') }}" rel="stylesheet">
    @stack('head')
</head>
<body>
@include('optifit_apex.partials.icons')
<a class="skip-link" href="#dashboard-main">انتقل إلى المحتوى الرئيسي</a>
<div class="dashboard-shell" data-dashboard-shell>
    <button class="mobile-shade" type="button" data-sidebar-close aria-label="إغلاق القائمة"></button>
    @include('optifit_apex.partials.user-sidebar')
    <main class="dashboard-main" id="dashboard-main" tabindex="-1">
        <header class="dashboard-topbar">
            <div class="topbar-welcome">
                <button class="icon-button menu-button" type="button" data-sidebar-open aria-label="فتح القائمة" aria-controls="dashboard-sidebar" aria-expanded="false"><svg aria-hidden="true"><use href="#apx-menu"/></svg></button>
                <div><span class="eyebrow">@yield('eyebrow', 'OPTIFIT / YOUR SPACE')</span><h1>@yield('page-heading', 'مساحتك الرياضية')</h1></div>
            </div>
            <div class="topbar-actions">
                @yield('actions')
                <button class="icon-button theme-button" type="button" data-theme-open aria-label="فتح إعدادات المظهر"><svg aria-hidden="true"><use href="#apx-palette"/></svg></button>
                <a class="user-avatar" href="{{ route('profile.edit') }}" aria-label="فتح إعدادات حساب {{ auth()->user()->name }}">{{ mb_strtoupper(mb_substr(trim(auth()->user()->name), 0, 1)) ?: 'O' }}</a>
            </div>
        </header>
        @if(session('success'))<div class="status-message" role="status"><svg aria-hidden="true"><use href="#apx-check"/></svg><span>{{ session('success') }}</span></div>@endif
        @if(session('status') === 'profile-updated')<div class="status-message" role="status"><svg aria-hidden="true"><use href="#apx-check"/></svg><span>تم تحديث معلومات الحساب.</span></div>@endif
        @if(session('status') === 'password-updated')<div class="status-message" role="status"><svg aria-hidden="true"><use href="#apx-check"/></svg><span>تم تحديث كلمة المرور.</span></div>@endif
        @if($errors->any())<div class="apx-error-summary" role="alert" tabindex="-1"><h2>تعذر إكمال العملية:</h2><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <div class="user-page-content">@yield('content')</div>
    </main>
</div>
@include('optifit_apex.partials.user-theme-studio')
<script defer src="{{ asset('optifit-apex-assets/js/user-dashboard.js') }}"></script>
@stack('scripts')
</body>
</html>
