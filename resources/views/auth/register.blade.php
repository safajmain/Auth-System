<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#080e10">
    <title>إنشاء حساب | OptiFit</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <a class="skip" href="#main">الانتقال إلى المحتوى</a>

    <header class="site-header">
        <div class="container header-inner">

            <a class="logo" href="{{ url('/') }}">
                <span>Opti<span>Fit</span></span>
            </a>

            <nav class="main-nav" id="main-nav">
                <a href="{{ url('/') }}">الرئيسية</a>
                <a href="{{ url('/#features') }}">المزايا</a>
                <a href="{{ route('gym-owner.register') }}">أضف ناديك</a>
                <a href="{{ url('/#about') }}">عن المنصة</a>
            </nav>

            <a class="header-action" href="{{ route('login') }}">
                الدخول
            </a>

            <button class="mobile-menu-toggle" id="menu-toggle"
                type="button" aria-label="فتح القائمة"
                aria-controls="main-nav" aria-expanded="false">
                ☰
            </button>

        </div>
    </header>

    <main id="main">
        <div class="container inner-page">

            <a class="back-link" href="{{ url('/') }}">
                العودة للرئيسية
            </a>

            <div class="auth-layout">

                <div class="auth-copy">
                    <span class="kicker">OPTIFIT / البداية هنا</span>

                    <h1>كل إنجاز كبير، <span>يبدأ بخطوة.</span></h1>

                    <p>
                        أنشئ حسابك اليوم واستعد لرحلة رياضية على طريقتك.
                        اكتشف الأندية المناسبة لك.
                    </p>

                    <div class="auth-media">
                        <img src="{{ asset('assets/athlete.webp') }}" alt="رياضي في صالة تمارين">
                        <span class="auth-caption">
                            خطوتك اليوم، قوتك غدًا.
                        </span>
                    </div>
                </div>

                <section class="glass-form" aria-labelledby="signup-title">

                    <div class="form-intro">
                        <h2 id="signup-title">إنشاء حساب جديد</h2>
                        <p>معلومات أساسية فقط لتبدأ.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="field">
                            <label for="name">الاسم الكامل</label>

                            <input id="name" type="text" name="name"
                                value="{{ old('name') }}"
                                placeholder="اسمك الكامل"
                                autocomplete="name" required autofocus>

                            @error('name')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="email">البريد الإلكتروني</label>

                            <input id="email" type="email" name="email"
                                value="{{ old('email') }}"
                                placeholder="name@example.com"
                                autocomplete="email" dir="ltr"
                                style="text-align:left" required>

                            @error('email')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="password">كلمة المرور</label>

                            <div class="input-wrap">
                                <input id="password" name="password"
                                    type="password"
                                    autocomplete="new-password"
                                    placeholder="8 أحرف على الأقل"
                                    required>

                                <button class="password-toggle" type="button"
                                    data-password-toggle="password"
                                    aria-label="إظهار كلمة المرور">
                                    <svg class="ico">
                                        <use href="#i-eye"></use>
                                    </svg>
                                </button>
                            </div>

                            @error('password')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="password_confirmation">
                                تأكيد كلمة المرور
                            </label>

                            <div class="input-wrap">
                                <input id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    autocomplete="new-password"
                                    placeholder="أعد إدخال كلمة المرور"
                                    required>

                                <button class="password-toggle" type="button"
                                    data-password-toggle="password_confirmation"
                                    aria-label="إظهار كلمة المرور">
                                    <svg class="ico">
                                        <use href="#i-eye"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-full">
                            إنشاء حساب
                        </button>

                        <div class="form-divider">أو</div>

                        <a href="{{ route('google.redirect') }}"
                            class="google-btn">
                            <span class="google-logo">G</span>
                            التسجيل باستخدام Google
                        </a>

                        <p class="switch-auth">
                            عندك حساب بالفعل؟
                            <a class="link-lime" href="{{ route('login') }}">
                                سجّل دخولك
                            </a>
                        </p>

                    </form>

                </section>

            </div>
        </div>
    </main>

    <footer class="foot" id="about">
        <div class="container foot-inner">
            <a class="logo" href="{{ url('/') }}">
                <span>Opti<span>Fit</span></span>
            </a>

            <span>جميع الحقوق محفوظة © 2026</span>

            <div class="foot-links">
                <a href="{{ route('login') }}">تسجيل الدخول</a>
                <a href="{{ route('register') }}">إنشاء حساب</a>
                <a href="{{ route('gym-owner.register') }}">تسجيل نادي</a>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}" defer></script>

</body>

</html>