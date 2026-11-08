<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="volt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0d120f">
    <script src="{{ asset('optifit-apex-assets/js/apex-theme.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | OptiFit APEX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('optifit-apex-assets/css/apex-shared.css') }}" rel="stylesheet">
    <link href="{{ asset('optifit-apex-assets/css/user-dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('optifit-apex-assets/css/apex-onboarding.css') }}" rel="stylesheet">
    <link href="{{ asset('optifit-apex-assets/css/user-shell.css') }}" rel="stylesheet">
</head>
<body>
@include('optifit_apex.partials.icons')
<a class="skip-link" href="#onboarding-content">انتقل إلى محتوى الخطوة</a>
<div class="dashboard-shell" data-dashboard-shell>
    <button class="mobile-shade" type="button" data-sidebar-close aria-label="إغلاق القائمة"></button>
    @include('optifit_apex.partials.user-sidebar')
    <main class="dashboard-main" id="dashboard-main" tabindex="-1">
        <header class="dashboard-topbar">
            <div class="topbar-welcome"><button class="icon-button menu-button" type="button" data-sidebar-open aria-label="فتح القائمة" aria-controls="dashboard-sidebar" aria-expanded="false"><svg aria-hidden="true"><use href="#apx-menu"/></svg></button><div><span class="eyebrow">PROFILE SETUP / 0{{ $step }}</span><h1>{{ $title }}</h1></div></div>
            <div class="topbar-actions"><button class="icon-button theme-button" type="button" data-theme-open aria-label="فتح إعدادات المظهر"><svg aria-hidden="true"><use href="#apx-palette"/></svg></button><a class="user-avatar" href="{{ route('profile.edit') }}" aria-label="إعدادات الحساب">{{ mb_strtoupper(mb_substr(trim(auth()->user()->name), 0, 1)) ?: 'O' }}</a></div>
        </header>
        @if(session('success'))<div class="status-message" role="status"><svg aria-hidden="true"><use href="#apx-check"/></svg><span>{{ session('success') }}</span></div>@endif
        @if($errors->any())<div class="apx-error-summary" role="alert" tabindex="-1"><h2>يرجى مراجعة البيانات:</h2><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <div class="apx-onboarding-shell">
            <aside class="apx-onboarding-aside">
                <span class="apx-eyebrow">PROFILE SETUP / 0{{ $step }}</span>
                <h1>{{ $heading }}</h1><p>{{ $description }}</p>
                <div class="apx-progress-label"><span>اكتمال الملف</span><strong>{{ $step * 25 }}%</strong></div>
                <div class="apx-progress" aria-label="نسبة اكتمال الإعداد"><span style="width: {{ $step * 25 }}%"></span></div>
                <nav class="apx-step-list" aria-label="خطوات إعداد الحساب">
                    @foreach([[1,'user.personal','المعلومات الشخصية','apx-user'],[2,'user.activities','الأنشطة المفضلة','apx-dumbbell'],[3,'user.facilities','المرافق المفضلة','apx-check'],[4,'user.times','الأوقات المتاحة','apx-clock']] as [$number,$routeName,$label,$icon])
                        <a href="{{ route($routeName) }}" class="{{ $number === $step ? 'is-active' : '' }} {{ $number < $step ? 'is-complete' : '' }}" @if($number === $step) aria-current="step" @endif><span><svg aria-hidden="true"><use href="#{{ $number < $step ? 'apx-check' : $icon }}"/></svg></span><small>الخطوة {{ $number }}</small><strong>{{ $label }}</strong></a>
                    @endforeach
                </nav>
            </aside>
            <div id="onboarding-content" class="apx-onboarding-content">
