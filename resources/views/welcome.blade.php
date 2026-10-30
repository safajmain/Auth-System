<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#080e10">
    <meta name="description" content="OptiFit: ابدأ رحلة اللياقة واكتشف النادي الذي يناسبك.">

    <title>OptiFit | ابدأ رحلتك نحو لياقة أفضل</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body class="home-page">

    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" style="position:absolute;width:0;height:0;overflow:hidden">
        <defs>
            <symbol id="i-logo" viewBox="0 0 24 24">
                <path d="M19 3 34 12v18L19 39 4 30V12z" />
                <path d="M14 20l4 4 8-9" />
            </symbol>
            <symbol id="i-arrow" viewBox="0 0 24 24">
                <path d="M18 12H5m7-7-7 7 7 7" />
            </symbol>
            <symbol id="i-user" viewBox="0 0 24 24">
                <circle cx="12" cy="8" r="4" />
                <path d="M4 21a8 8 0 0 1 16 0" />
            </symbol>
            <symbol id="i-login" viewBox="0 0 24 24">
                <path d="M11 3h7a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3h-7" />
                <path d="M3 12h14m-5-5 5 5-5 5" />
            </symbol>
            <symbol id="i-building" viewBox="0 0 24 24">
                <rect x="4" y="3" width="16" height="18" rx="1" />
                <path d="M9 21v-6h6v6M8 7h1m6 0h1M8 11h1m6 0h1" />
            </symbol>
            <symbol id="i-dumbbell" viewBox="0 0 24 24">
                <path d="M6 5v14m12-14v14M3 8v8m18-8v8M6 12h12" />
            </symbol>
            <symbol id="i-pin" viewBox="0 0 24 24">
                <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z" />
                <circle cx="12" cy="10" r="2.5" />
            </symbol>
            <symbol id="i-chart" viewBox="0 0 24 24">
                <path d="M3 20h18M7 17V9m5 8V4m5 13v-6" />
            </symbol>
            <symbol id="i-heart" viewBox="0 0 24 24">
                <path d="M20.8 4.6a5.7 5.7 0 0 0-8.1 0l-.7.7-.7-.7a5.7 5.7 0 1 0-8.1 8.1l8.8 8.8 8.8-8.8a5.7 5.7 0 0 0 0-8.1Z" />
            </symbol>
            <symbol id="i-clock" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9" />
                <path d="M12 7v5l4 2" />
            </symbol>
            <symbol id="i-fire" viewBox="0 0 24 24">
                <path d="M12 22c5 0 8-3.3 8-8 0-3-2-6-5-8 0 3-1 4-2 5-1-4-3-6-5-8 0 5-4 7-4 12a8 8 0 0 0 8 7Z" />
            </symbol>
            <symbol id="i-check" viewBox="0 0 24 24">
                <path d="m4 12 5 5L20 6" />
            </symbol>
            <symbol id="i-eye" viewBox="0 0 24 24">
                <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z" />
                <circle cx="12" cy="12" r="3" />
            </symbol>
            <symbol id="i-email" viewBox="0 0 24 24">
                <rect x="3" y="5" width="18" height="14" rx="2" />
                <path d="m3 7 9 6 9-6" />
            </symbol>
            <symbol id="i-menu" viewBox="0 0 24 24">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </symbol>
            <symbol id="i-chev" viewBox="0 0 24 24">
                <path d="m9 18 6-6-6-6" />
            </symbol>
            <symbol id="i-shield" viewBox="0 0 24 24">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" />
                <path d="m9 12 2 2 4-4" />
            </symbol>
            <symbol id="i-spark" viewBox="0 0 24 24">
                <path d="m12 2 2.4 7.6L22 12l-7.6 2.4L12 22l-2.4-7.6L2 12l7.6-2.4L12 2Z" />
            </symbol>
            <symbol id="i-people" viewBox="0 0 24 24">
                <path d="M16 20v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M18 9a4 4 0 0 1 0 8m4 3v-2a4 4 0 0 0-3-3.87" />
            </symbol>
        </defs>
    </svg>

    <a class="skip" href="#main">تجاوز القائمة والانتقال للمحتوى</a>

    <header class="site-header floating-header">
        <div class="container header-inner">

            <a class="logo" href="{{ url('/') }}" aria-label="OptiFit - الرئيسية">
                <svg class="brand-icon" viewBox="0 0 44 44" fill="none" aria-hidden="true">
                    <path d="M22 2 39 12v20L22 42 5 32V12z" stroke="currentColor" stroke-width="4" stroke-linejoin="round" />
                    <path d="m13 22 6 6 12-14" stroke="currentColor" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>Opti<span>Fit</span></span>
            </a>

            <nav class="main-nav" id="main-nav" aria-label="القائمة الرئيسية">
                <a href="{{ url('/') }}" aria-current="page">الرئيسية</a>
                <a href="#features">المزايا</a>
                <a href="{{ route('register') }}">الأندية</a>
                <a href="#about">تواصل معنا</a>
            </nav>

            @guest
            <a class="header-action" href="{{ route('login') }}">
                <svg class="ico" aria-hidden="true">
                    <use href="#i-login"></use>
                </svg>
                الدخول
            </a>
            @else
            <a class="header-action" href="{{ route('dashboard') }}">
                <svg class="ico" aria-hidden="true">
                    <use href="#i-user"></use>
                </svg>
                حسابي
            </a>
            @endguest

            <button class="mobile-menu-toggle" id="menu-toggle" type="button"
                aria-label="فتح القائمة" aria-controls="main-nav" aria-expanded="false">
                <svg class="ico" aria-hidden="true">
                    <use href="#i-menu"></use>
                </svg>
            </button>

        </div>
    </header>

    <main id="main">

        <section class="hero-stage" aria-labelledby="hero-title">
            <div class="hero-backdrop" aria-hidden="true"></div>
            <div class="hero-shade" aria-hidden="true"></div>

            <div class="container stage-inner">

                <div class="hero-copy">
                    <span class="eyebrow">لياقة أفضل · حياة أفضل</span>

                    <h1 id="hero-title">ابدأ رحلتك نحو <span>لياقة أفضل</span></h1>

                    <p class="desc">
                        اكتشف أفضل الأندية القريبة منك، واحصل على خطط تدريب تناسب أهدافك،
                        وتابع تقدمك خطوة بخطوة مع <b>OptiFit</b>.
                    </p>

                    <div class="hero-ctas" aria-label="البدء مع OptiFit">

                        @guest
                        <a class="btn btn-quiet" href="{{ route('login') }}">
                            <svg class="ico" aria-hidden="true">
                                <use href="#i-login"></use>
                            </svg>
                            تسجيل الدخول
                        </a>

                        <a class="btn btn-primary" href="{{ route('register') }}">
                            <svg class="ico" aria-hidden="true">
                                <use href="#i-user"></use>
                            </svg>
                            إنشاء حساب
                        </a>

                        <a class="btn btn-quiet" href="{{ route('gym-owner.register') }}">
                            <svg class="ico" aria-hidden="true">
                                <use href="#i-building"></use>
                            </svg>
                            أضف ناديك
                        </a>
                        @else
                        <a class="btn btn-primary" href="{{ route('dashboard') }}">
                            <svg class="ico" aria-hidden="true">
                                <use href="#i-user"></use>
                            </svg>
                            انتقل إلى حسابك
                        </a>
                        @endguest

                    </div>
                </div>

                <div class="floating-dashboard" aria-label="مثال توضيحي لنتائج التمارين">

                    <div class="dash-top">
                        <strong>تقدمك هذا الأسبوع</strong>
                        <span class="dash-more" aria-hidden="true">···</span>
                    </div>

                    <div class="dash-main">

                        <div class="dash-list">
                            <div class="dash-metric">
                                <svg class="ico" aria-hidden="true">
                                    <use href="#i-fire"></use>
                                </svg>
                                <div><strong>1,420</strong><small>سعرة حرارية</small></div>
                            </div>

                            <div class="dash-metric">
                                <svg class="ico" aria-hidden="true">
                                    <use href="#i-dumbbell"></use>
                                </svg>
                                <div><strong>5</strong><small>حصص تدريب</small></div>
                            </div>

                            <div class="dash-metric">
                                <svg class="ico" aria-hidden="true">
                                    <use href="#i-clock"></use>
                                </svg>
                                <div><strong>7.5</strong><small>ساعات نشاط</small></div>
                            </div>
                        </div>

                        <div class="dash-ring">
                            <div><b>72%</b><span>من الهدف</span></div>
                        </div>

                    </div>

                    <div class="dash-gym">
                        <div class="dash-thumb" role="img" aria-label="معدات نادي رياضي"></div>
                        <div>
                            <small>ناديك الحالي</small>
                            <strong>نادي القمة</strong>
                            <span>
                                <svg class="ico" aria-hidden="true">
                                    <use href="#i-pin"></use>
                                </svg>
                                الرياض
                            </span>
                        </div>
                        <span class="dash-circle" aria-hidden="true">‹</span>
                    </div>

                </div>

                <div class="hero-bottom-line" aria-hidden="true"></div>
            </div>
        </section>

        <section class="showcase" id="features" aria-label="مزايا OptiFit">

            <div class="container feature-cards">

                <article class="show-card plans-card">
                    <div class="show-info">
                        <span class="show-icon">
                            <svg class="ico" aria-hidden="true">
                                <use href="#i-dumbbell"></use>
                            </svg>
                        </span>
                        <h2>خطط تدريب تناسبك</h2>
                        <p>برامج تدريب مخصصة لأهدافك ومستواك، تساعدك تتقدم بثقة.</p>
                        <a href="{{ route('register') }}" class="show-link">
                            استعرض الخطط
                            <svg class="ico" aria-hidden="true">
                                <use href="#i-arrow"></use>
                            </svg>
                        </a>
                    </div>
                    <div class="show-visual plan-photo" aria-hidden="true"></div>
                </article>

                <article class="show-card progress-card">
                    <div class="show-info">
                        <span class="show-icon">
                            <svg class="ico" aria-hidden="true">
                                <use href="#i-chart"></use>
                            </svg>
                        </span>
                        <h2>تابع تقدمك</h2>
                        <p>سجّل تدريباتك، راقب إنجازاتك وخلّي هدفك قدامك.</p>
                        <a href="{{ route('register') }}" class="show-link">
                            معرفة المزيد
                            <svg class="ico" aria-hidden="true">
                                <use href="#i-arrow"></use>
                            </svg>
                        </a>
                    </div>
                    <div class="show-visual chart-panel" aria-hidden="true">
                        <span class="chart-tag">↗ 12%</span>
                        <div class="chart-bars"><i></i><i></i><i></i><i></i><i></i></div>
                    </div>
                </article>

                <article class="show-card clubs-card">
                    <div class="show-info">
                        <span class="show-icon">
                            <svg class="ico" aria-hidden="true">
                                <use href="#i-pin"></use>
                            </svg>
                        </span>
                        <h2>اكتشف الأندية</h2>
                        <p>تصفح النوادي الرياضية وقارن المرافق والمواقع والاشتراكات.</p>
                        <a href="{{ route('register') }}" class="show-link">
                            استكشف الأندية
                            <svg class="ico" aria-hidden="true">
                                <use href="#i-arrow"></use>
                            </svg>
                        </a>
                    </div>
                    <div class="show-visual club-photo" aria-hidden="true"></div>
                </article>

            </div>
        </section>

        <section id="about" class="about-strip">
            <div class="container about-inner">
                <span>OptiFit</span>
                <p>رحلتك الرياضية تبدأ بخطوة.</p>
                <div>
                    <a href="{{ route('register') }}">إنشاء حساب</a>
                    <a href="{{ route('gym-owner.register') }}">أضف ناديك</a>
                </div>
            </div>
        </section>

    </main>

    <div class="toast" role="status" aria-live="polite"></div>

    <script src="{{ asset('js/app.js') }}" defer></script>

</body>

</html>