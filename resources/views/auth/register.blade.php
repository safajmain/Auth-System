<!DOCTYPE html>
<html lang="ar" dir="rtl" data-apx-theme="volt">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#0d120f">
    <script src="{{ asset('optifit-apex-assets/js/apex-theme.js') }}"></script>
    <title>إنشاء حساب | OptiFit APEX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('optifit-apex-assets/css/apex-shared.css') }}" rel="stylesheet">
    <link href="{{ asset('optifit-apex-assets/css/apex-register.css') }}" rel="stylesheet">
</head>
<body class="apx-auth-page">
@include('optifit_apex.partials.icons')
<a class="apx-skip" href="#register-form">انتقل إلى نموذج التسجيل</a>
<header class="apx-auth-top">
    <a class="apx-brand" href="{{ url('/') }}"><span class="apx-brand-mark"><svg><use href="#apx-dumbbell"/></svg></span><span>OPTI<span>FIT</span> <small>APEX</small></span></a>
    <div class="apx-auth-actions"><button class="apx-icon-button" type="button" data-apx-theme-open aria-label="تغيير مظهر الصفحة"><svg><use href="#apx-palette"/></svg></button><a class="apx-secondary" href="{{ route('gym-owner.register') }}">سجّل ناديك</a><a class="apx-primary" href="{{ route('login') }}">تسجيل الدخول</a></div>
</header>
<main class="apx-auth-layout">
    <section class="apx-auth-art" aria-labelledby="register-heading">
        <div><span class="apx-eyebrow">YOUR NEXT LEVEL</span><h1 id="register-heading">النسخة الأقوى منك <em>بتبدأ هون.</em></h1><p>حسابك يربط هدفك ووقتك وميزانيتك بالنوادي المعتمدة داخل OptiFit.</p></div>
        <div class="apx-auth-facts"><div class="apx-auth-fact"><svg><use href="#apx-shield"/></svg>تسجيل وحفظ فعلي عبر Laravel وMySQL</div><div class="apx-auth-fact"><svg><use href="#apx-target"/></svg>أربع خطوات لتخصيص تجربتك الرياضية</div></div>
    </section>
    <section class="apx-auth-form-side">
        <div class="apx-card apx-auth-card">
            <header><span class="apx-eyebrow">JOIN OPTIFIT</span><h2>ابدأ رحلتك.</h2><p>أدخل بيانات حسابك، وبعدها نكمل ملفك الرياضي.</p></header>
            @if ($errors->any())
                <div class="apx-error-summary" role="alert" tabindex="-1" data-error-summary><h2>راجع الحقول التالية:</h2><ul>@foreach ($errors->keys() as $field)<li><a href="#{{ $field }}">{{ $errors->first($field) }}</a></li>@endforeach</ul></div>
            @endif
            <form id="register-form" method="POST" action="{{ route('register') }}" data-apx-submit>
                @csrf
                <div class="apx-form-grid">
                    <div class="apx-field apx-span-2 @error('name') has-error @enderror"><label for="name">الاسم الكامل</label><input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required autofocus @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>@error('name')<p class="apx-error" id="name-error">{{ $message }}</p>@enderror</div>
                    <div class="apx-field apx-span-2 @error('email') has-error @enderror"><label for="email">البريد الإلكتروني</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" dir="ltr" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>@error('email')<p class="apx-error" id="email-error">{{ $message }}</p>@enderror</div>
                    <div class="apx-field @error('password') has-error @enderror"><label for="password">كلمة المرور</label><div class="apx-password"><input id="password" name="password" type="password" autocomplete="new-password" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror><button class="apx-password-toggle" type="button" data-password-toggle="password" aria-label="إظهار كلمة المرور"><svg><use href="#apx-eye"/></svg></button></div>@error('password')<p class="apx-error" id="password-error">{{ $message }}</p>@enderror</div>
                    <div class="apx-field"><label for="password_confirmation">تأكيد كلمة المرور</label><div class="apx-password"><input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required><button class="apx-password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="إظهار تأكيد كلمة المرور"><svg><use href="#apx-eye"/></svg></button></div></div>
                </div>
                <button class="apx-primary apx-submit" type="submit"><span data-submit-label>إنشاء حساب المستخدم</span><svg><use href="#apx-arrow"/></svg></button>
            </form>
            <div class="apx-divider">أو</div>
            <a class="apx-google" href="{{ route('google.redirect') }}"><svg viewBox="0 0 48 48" aria-hidden="true"><path fill="#EA4335" stroke="none" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" stroke="none" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.17 7.09-10.32 7.09-17.65z"/><path fill="#FBBC05" stroke="none" d="M10.53 28.59A14.5 14.5 0 0 1 9.78 24c0-1.57.27-3.13.74-4.59l-7.98-6.19A24 24 0 0 0 0 24c0 3.88.92 7.54 2.56 10.78z"/><path fill="#34A853" stroke="none" d="M24 48c6.48 0 11.92-2.13 15.89-5.8l-7.73-6a14.5 14.5 0 0 1-8.16 2.3c-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>المتابعة باستخدام Google</a>
            <p class="apx-switch">عندك حساب؟ <a href="{{ route('login') }}">سجّل دخولك</a> · صاحب نادي؟ <a href="{{ route('gym-owner.register') }}">سجّل ناديك</a></p>
        </div>
    </section>
</main>
@include('optifit_apex.partials.theme-studio')
<script defer src="{{ asset('optifit-apex-assets/js/apex-shared.js') }}"></script>
</body></html>
