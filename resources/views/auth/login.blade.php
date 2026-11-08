<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0d120f">
    <meta name="description" content="سجّل الدخول إلى حسابك الحقيقي في OptiFit.">

    <title>تسجيل الدخول | OptiFit APEX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('optifit-apex-assets/css/login.css') }}">
</head>
<body>
    <svg class="icon-sprite" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
        <symbol id="login-dumbbell" viewBox="0 0 24 24">
            <path d="M7 10 10 7l7 7-3 3-7-7M4 7l3-3 3 3-3 3-3-3m10 10 3-3 3 3-3 3-3-3M2 9l7-7M15 22l7-7"/>
        </symbol>
        <symbol id="login-arrow" viewBox="0 0 24 24">
            <path d="M20 12H5m6-6-6 6 6 6"/>
        </symbol>
        <symbol id="login-enter" viewBox="0 0 24 24">
            <path d="M10 4H4v16h6m5-16 6 8-6 8M8 12h13"/>
        </symbol>
        <symbol id="login-mail" viewBox="0 0 24 24">
            <rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>
        </symbol>
        <symbol id="login-lock" viewBox="0 0 24 24">
            <rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>
        </symbol>
        <symbol id="login-check" viewBox="0 0 24 24">
            <path d="m4 12 5 5L20 6"/>
        </symbol>
        <symbol id="login-shield" viewBox="0 0 24 24">
            <path d="m12 2 9 4v6c0 7-4.7 9.5-9 11-4.3-1.5-9-4-9-11V6z"/><path d="m8 12 3 3 5-6"/>
        </symbol>
    </svg>

    <a class="skip-link" href="#login-form">الانتقال إلى نموذج تسجيل الدخول</a>

    <main class="auth-shell">
        <section class="auth-visual" aria-labelledby="visual-title">
            <a class="brand" href="{{ url('/') }}" aria-label="OptiFit - الصفحة الرئيسية">
                <span class="brand-mark" aria-hidden="true">
                    <svg><use href="#login-dumbbell"></use></svg>
                </span>
                <span class="brand-word">OPTI<span>FIT</span> <small>APEX</small></span>
            </a>

            <div class="visual-copy">
                <p class="eyebrow">YOUR NEXT LEVEL</p>
                <h1 id="visual-title">جاهز ترجع<br><span>أقوى؟</span></h1>
                <p>
                    ارجع إلى حسابك وأكمل تفضيلاتك ورحلتك الرياضية من المكان الذي توقفت عنده.
                </p>

                <ul class="auth-benefits" aria-label="مزايا تسجيل الدخول">
                    <li>
                        <svg aria-hidden="true"><use href="#login-check"></use></svg>
                        الوصول إلى ملفك وتفضيلاتك المحفوظة
                    </li>
                    <li>
                        <svg aria-hidden="true"><use href="#login-check"></use></svg>
                        متابعة خطوات الإعداد الأربع
                    </li>
                    <li>
                        <svg aria-hidden="true"><use href="#login-check"></use></svg>
                        الانتقال إلى لوحة الحساب المناسبة لدورك
                    </li>
                </ul>
            </div>

            <p class="visual-footnote">
                تسجيل الدخول يتم بأمان من خلال Laravel Authentication.
            </p>
        </section>

        <section class="auth-panel" aria-labelledby="login-title">
            <div class="form-shell">
                <a class="back-link" href="{{ url('/') }}">
                    <svg aria-hidden="true"><use href="#login-arrow"></use></svg>
                    العودة للرئيسية
                </a>

                <div class="form-heading">
                    <p class="eyebrow">WELCOME BACK</p>
                    <h2 id="login-title">أهلًا بعودتك.</h2>
                    <p>أدخل بيانات حسابك للمتابعة من حيث توقفت.</p>
                </div>

                @if (session('status'))
                    <div class="status-message" role="status">
                        <svg aria-hidden="true"><use href="#login-check"></use></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="error-summary" role="alert" aria-labelledby="login-error-title" tabindex="-1" autofocus>
                        <div>
                            <svg aria-hidden="true"><use href="#login-shield"></use></svg>
                        </div>
                        <div>
                            <h3 id="login-error-title">تعذّر تسجيل الدخول</h3>
                            <p>راجع البيانات الموضحة أدناه ثم حاول مرة أخرى.</p>
                            <ul>
                                @error('email')
                                    <li><a href="#email">{{ $message }}</a></li>
                                @enderror
                                @error('password')
                                    <li><a href="#password">{{ $message }}</a></li>
                                @enderror
                            </ul>
                        </div>
                    </div>
                @endif

                <form id="login-form" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="field-group">
                        <label for="email">البريد الإلكتروني</label>
                        <div class="input-shell @error('email') has-error @enderror">
                            <svg aria-hidden="true"><use href="#login-mail"></use></svg>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="name@example.com"
                                autocomplete="username"
                                inputmode="email"
                                dir="ltr"
                                required
                                @if (!$errors->any()) autofocus @endif
                                @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                            >
                        </div>
                        @error('email')
                            <p class="field-error" id="email-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label for="password">كلمة المرور</label>
                        <div class="input-shell @error('password') has-error @enderror">
                            <svg aria-hidden="true"><use href="#login-lock"></use></svg>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="أدخل كلمة المرور"
                                autocomplete="current-password"
                                required
                                @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                            >
                        </div>
                        @error('password')
                            <p class="field-error" id="password-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-options">
                        <label class="remember-option" for="remember">
                            <input id="remember" type="checkbox" name="remember" value="1" @checked(old('remember'))>
                            <span aria-hidden="true">
                                <svg><use href="#login-check"></use></svg>
                            </span>
                            تذكّرني
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">نسيت كلمة المرور؟</a>
                        @endif
                    </div>

                    <button class="submit-button" type="submit">
                        تسجيل الدخول
                        <svg aria-hidden="true"><use href="#login-enter"></use></svg>
                    </button>
                </form>

                <div class="form-divider" role="presentation">
                    <span>أو المتابعة باستخدام</span>
                </div>

                <a class="google-button" href="{{ route('google.redirect') }}">
                    <svg class="google-logo" viewBox="0 0 48 48" aria-hidden="true">
                        <path fill="#EA4335" stroke="none" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" stroke="none" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6C44.4 38.03 46.98 31.88 46.98 24.55z"/>
                        <path fill="#FBBC05" stroke="none" d="M10.53 28.59A14.7 14.7 0 0 1 9.78 24c0-1.57.27-3.13.74-4.59l-7.98-6.19A23.9 23.9 0 0 0 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" stroke="none" d="M24 48c6.48 0 11.92-2.13 15.89-5.8l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                    <span>المتابعة باستخدام Google</span>
                    <svg class="google-arrow" aria-hidden="true"><use href="#login-arrow"></use></svg>
                </a>

                @if (Route::has('register'))
                    <p class="register-prompt">
                        ليس لديك حساب؟
                        <a href="{{ route('register') }}">أنشئ حسابك الآن</a>
                    </p>
                @endif

                <p class="auth-security-note">
                    لن يطلب منك OptiFit مشاركة كلمة المرور خارج هذه الصفحة.
                </p>
            </div>
        </section>
    </main>
</body>
</html>
