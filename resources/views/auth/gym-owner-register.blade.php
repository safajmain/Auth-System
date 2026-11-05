<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#080e10">
    <title>تسجيل صاحب النادي | OptiFit</title>

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
                <a href="{{ route('register') }}">إنشاء حساب مستخدم</a>
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
                    <span class="kicker">OPTIFIT / أضف ناديك</span>

                    <h1>ناديك معنا، <span>يبدأ بخطوة.</span></h1>

                    <p>
                        سجّل حساب صاحب النادي وبيانات ناديك،
                        ليتم إرسال طلب النادي إلى الإدارة للمراجعة.
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
                        <h2 id="signup-title">تسجيل صاحب النادي</h2>
                        <p>نفس نموذج إنشاء الحساب، مع بيانات النادي المطلوبة.</p>
                    </div>

                    <form method="POST" action="{{ route('gym-owner.register.store') }}">
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


                        <div class="form-divider">بيانات النادي</div>

                        <div class="field">
                            <label for="gym_name">اسم النادي</label>
                            <input id="gym_name" type="text" name="gym_name"
                                value="{{ old('gym_name') }}"
                                placeholder="اسم النادي" maxlength="255" required>
                            @error('gym_name')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="city">المدينة</label>
                            <input id="city" type="text" name="city"
                                value="{{ old('city') }}"
                                placeholder="مثال: عمّان" maxlength="100" required>
                            @error('city')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="area">المنطقة (اختياري)</label>
                            <input id="area" type="text" name="area"
                                value="{{ old('area') }}"
                                placeholder="مثال: الجبيهة" maxlength="100">
                            @error('area')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="address">عنوان النادي</label>
                            <input id="address" type="text" name="address"
                                value="{{ old('address') }}"
                                placeholder="العنوان التفصيلي للنادي" maxlength="255" required>
                            @error('address')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="description">وصف النادي (اختياري)</label>
                            <textarea id="description" name="description" maxlength="2000"
                                placeholder="نبذة قصيرة عن النادي">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="phone">رقم هاتف النادي (اختياري)</label>
                            <input id="phone" type="tel" name="phone"
                                value="{{ old('phone') }}" dir="ltr" style="text-align:left"
                                placeholder="07XXXXXXXX" maxlength="30">
                            @error('phone')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="website_url">موقع النادي الإلكتروني (اختياري)</label>
                            <input id="website_url" type="url" name="website_url"
                                value="{{ old('website_url') }}" dir="ltr" style="text-align:left"
                                placeholder="https://example.com" maxlength="255">
                            @error('website_url')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-full">
                            تسجيل صاحب النادي وإرسال الطلب
                        </button>

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