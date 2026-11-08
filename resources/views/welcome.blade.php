<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0d120f">
    <meta name="description" content="OptiFit يساعدك على تحديد تفضيلاتك الرياضية والوصول إلى النادي الأنسب لك.">

    <title>OptiFit | مكانك، قوتك، مستواك</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('optifit-apex-assets/css/welcome.css') }}">
</head>
<body>
    <svg class="icon-sprite" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
        <symbol id="welcome-dumbbell" viewBox="0 0 24 24">
            <path d="M7 10 10 7l7 7-3 3-7-7M4 7l3-3 3 3-3 3-3-3m10 10 3-3 3 3-3 3-3-3M2 9l7-7M15 22l7-7"/>
        </symbol>
        <symbol id="welcome-arrow" viewBox="0 0 24 24">
            <path d="M20 12H5m6-6-6 6 6 6"/>
        </symbol>
        <symbol id="welcome-user" viewBox="0 0 24 24">
            <circle cx="12" cy="7" r="4"/><path d="M4 22v-2a8 8 0 0 1 16 0v2"/>
        </symbol>
        <symbol id="welcome-login" viewBox="0 0 24 24">
            <path d="M10 4H4v16h6m5-16 6 8-6 8M8 12h13"/>
        </symbol>
        <symbol id="welcome-building" viewBox="0 0 24 24">
            <path d="M4 21V4h16v17M8 8h2m4 0h2M8 12h2m4 0h2M9 21v-5h6v5"/>
        </symbol>
        <symbol id="welcome-target" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4"/><path d="m15 9 6-6m-1 0h1v1"/>
        </symbol>
        <symbol id="welcome-clock" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9"/><path d="M12 7v5l4 2"/>
        </symbol>
        <symbol id="welcome-check" viewBox="0 0 24 24">
            <path d="m4 12 5 5L20 6"/>
        </symbol>
        <symbol id="welcome-compass" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9"/><path d="m9 15 2-6 5-2-2 6z"/>
        </symbol>
        <symbol id="welcome-menu" viewBox="0 0 24 24">
            <path d="M4 6h16M4 12h16M4 18h16"/>
        </symbol>
    </svg>

    <a class="skip-link" href="#main-content">تجاوز القائمة والانتقال إلى المحتوى</a>

    <header class="welcome-header">
        <div class="welcome-container header-inner">
            <a class="brand" href="{{ url('/') }}" aria-label="OptiFit - الصفحة الرئيسية">
                <span class="brand-mark" aria-hidden="true">
                    <svg><use href="#welcome-dumbbell"></use></svg>
                </span>
                <span class="brand-word">OPTI<span>FIT</span> <small>APEX</small></span>
            </a>

            <nav class="desktop-nav" aria-label="القائمة الرئيسية">
                <a href="{{ url('/') }}" aria-current="page">الرئيسية</a>
                <a href="#journey">كيف تعمل المنصة</a>
                <a href="#gym-owners">لأصحاب النوادي</a>
            </nav>

            <div class="header-actions">
                @auth
                    <a class="button button-primary" href="{{ route('dashboard') }}">
                        <svg aria-hidden="true"><use href="#welcome-user"></use></svg>
                        حسابي
                    </a>
                @else
                    <a class="button button-ghost" href="{{ route('login') }}">تسجيل الدخول</a>
                    <a class="button button-primary" href="{{ route('register') }}">ابدأ رحلتك</a>
                @endauth
            </div>

            <details class="mobile-menu">
                <summary aria-label="فتح القائمة">
                    <svg aria-hidden="true"><use href="#welcome-menu"></use></svg>
                </summary>
                <nav aria-label="قائمة الهاتف">
                    <a href="{{ url('/') }}" aria-current="page">الرئيسية</a>
                    <a href="#journey">كيف تعمل المنصة</a>
                    <a href="#gym-owners">لأصحاب النوادي</a>
                    @auth
                        <a href="{{ route('dashboard') }}">حسابي</a>
                    @else
                        <a href="{{ route('login') }}">تسجيل الدخول</a>
                        <a href="{{ route('register') }}">إنشاء حساب</a>
                    @endauth
                    <a href="{{ route('gym-owner.register') }}">أضف ناديك</a>
                </nav>
            </details>
        </div>
    </header>

    <main id="main-content">
        <section class="hero" aria-labelledby="hero-title">
            <div class="hero-glow" aria-hidden="true"></div>

            <div class="welcome-container hero-grid">
                <div class="hero-copy">
                    <p class="eyebrow">THE NEXT ERA OF FITNESS</p>
                    <h1 id="hero-title">
                        مكانك.<br>
                        قوتك.<br>
                        <span>مستواك.</span>
                    </h1>
                    <p class="hero-description">
                        OptiFit تجمع هدفك الرياضي، أنشطتك المفضلة، المرافق التي تحتاجها ووقتك المتاح
                        لتبني لك رحلة بحث أوضح عن النادي المناسب.
                    </p>

                    <div class="hero-actions">
                        @auth
                            <a class="button button-primary button-large" href="{{ route('dashboard') }}">
                                انتقل إلى حسابك
                                <svg aria-hidden="true"><use href="#welcome-arrow"></use></svg>
                            </a>
                        @else
                            <a class="button button-primary button-large" href="{{ route('register') }}">
                                ابدأ الآن
                                <svg aria-hidden="true"><use href="#welcome-arrow"></use></svg>
                            </a>
                            <a class="button button-ghost button-large" href="{{ route('login') }}">
                                تسجيل الدخول
                                <svg aria-hidden="true"><use href="#welcome-login"></use></svg>
                            </a>
                        @endauth
                        <a class="button button-ghost button-large" href="{{ route('gym-owner.register') }}">
                            أضف ناديك
                            <svg aria-hidden="true"><use href="#welcome-building"></use></svg>
                        </a>
                    </div>

                    <p class="hero-note">
                        أربع خطوات واضحة، ثم تبدأ تجربتك داخل OptiFit.
                    </p>
                </div>

                <aside class="journey-card" aria-labelledby="journey-card-title">
                    <div class="journey-card-heading">
                        <div>
                            <p>FITNESS PROFILE</p>
                            <h2 id="journey-card-title">رحلتك تبدأ منك.</h2>
                        </div>
                        <span class="journey-card-icon" aria-hidden="true">
                            <svg><use href="#welcome-target"></use></svg>
                        </span>
                    </div>

                    <ol class="profile-steps">
                        <li>
                            <span>01</span>
                            <div><strong>بياناتك وهدفك</strong><small>حدّد موقعك وهدفك وميزانيتك.</small></div>
                        </li>
                        <li>
                            <span>02</span>
                            <div><strong>الأنشطة المفضلة</strong><small>اختر الأنشطة التي تريد ممارستها.</small></div>
                        </li>
                        <li>
                            <span>03</span>
                            <div><strong>المرافق المهمة</strong><small>حدّد المرافق التي تحتاجها في النادي.</small></div>
                        </li>
                        <li>
                            <span>04</span>
                            <div><strong>الأوقات المتاحة</strong><small>أضف الأيام والفترات المناسبة لك.</small></div>
                        </li>
                    </ol>
                </aside>
            </div>
        </section>

        <div class="energy-band" aria-hidden="true">
            <span>FIND YOUR EDGE</span>
            <i></i>
            <span>OWN YOUR JOURNEY</span>
            <i></i>
            <span>OPTIFIT APEX</span>
        </div>

        <section class="journey-section" id="journey" aria-labelledby="journey-title">
            <div class="welcome-container">
                <div class="section-heading">
                    <p class="eyebrow">BUILT AROUND YOU</p>
                    <h2 id="journey-title">من تفضيلاتك إلى <span>اختيار أوضح.</span></h2>
                    <p>كل خطوة في البداية مرتبطة بحسابك وتُحفظ داخل نظام OptiFit الحالي.</p>
                </div>

                <div class="feature-grid">
                    <article class="feature-card">
                        <span class="feature-number">01</span>
                        <svg aria-hidden="true"><use href="#welcome-user"></use></svg>
                        <h3>أنشئ ملفك الرياضي</h3>
                        <p>أدخل بياناتك الأساسية وحدّد الهدف والميزانية التي تناسبك.</p>
                    </article>

                    <article class="feature-card">
                        <span class="feature-number">02</span>
                        <svg aria-hidden="true"><use href="#welcome-check"></use></svg>
                        <h3>اختر ما يهمك</h3>
                        <p>سجّل الأنشطة والمرافق والأوقات التي تريدها ضمن تسلسل الإعداد الحالي.</p>
                    </article>

                    <article class="feature-card">
                        <span class="feature-number">03</span>
                        <svg aria-hidden="true"><use href="#welcome-compass"></use></svg>
                        <h3>ابدأ الاستكشاف</h3>
                        <p>انتقل إلى لوحة حسابك واستخدم أدوات OptiFit للوصول إلى خياراتك.</p>
                    </article>
                </div>

                @guest
                    <div class="section-action">
                        <a class="button button-primary button-large" href="{{ route('register') }}">
                            أنشئ حسابك
                            <svg aria-hidden="true"><use href="#welcome-arrow"></use></svg>
                        </a>
                    </div>
                @endguest
            </div>
        </section>

        <section class="owner-section" id="gym-owners" aria-labelledby="owner-title">
            <div class="welcome-container owner-panel">
                <div class="owner-copy">
                    <p class="eyebrow">FOR GYM OWNERS</p>
                    <h2 id="owner-title">ناديك يستحق حضورًا <span>أقوى.</span></h2>
                    <p>
                        قدّم طلب تسجيل ناديك من خلال النموذج المخصص لأصحاب النوادي،
                        وتابع حالة الطلب من خلال حسابك.
                    </p>
                </div>
                <a class="button button-primary button-large" href="{{ route('gym-owner.register') }}">
                    أضف ناديك
                    <svg aria-hidden="true"><use href="#welcome-building"></use></svg>
                </a>
            </div>
        </section>
    </main>

    <footer class="welcome-footer">
        <div class="welcome-container footer-inner">
            <a class="brand brand-footer" href="{{ url('/') }}" aria-label="OptiFit - الصفحة الرئيسية">
                <span class="brand-word">OPTI<span>FIT</span></span>
            </a>
            <p>رحلتك الرياضية تبدأ بخطوة.</p>
            <nav aria-label="روابط الحساب">
                @guest
                    <a href="{{ route('login') }}">تسجيل الدخول</a>
                    <a href="{{ route('register') }}">إنشاء حساب</a>
                @else
                    <a href="{{ route('dashboard') }}">حسابي</a>
                @endguest
                <a href="{{ route('gym-owner.register') }}">أضف ناديك</a>
            </nav>
        </div>
    </footer>
</body>
</html>
