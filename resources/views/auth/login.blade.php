 <x-guest-layout>

     <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
         style="position:absolute;width:0;height:0;overflow:hidden">
         <symbol id="i-login" viewBox="0 0 24 24">
             <path d="M11 3h7a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3h-7" />
             <path d="M3 12h14m-5-5 5 5-5 5" />
         </symbol>
         <symbol id="i-arrow" viewBox="0 0 24 24">
             <path d="M18 12H5m7-7-7 7 7 7" />
         </symbol>
         <symbol id="i-menu" viewBox="0 0 24 24">
             <path d="M4 6h16M4 12h16M4 18h16" />
         </symbol>
         <symbol id="i-eye" viewBox="0 0 24 24">
             <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z" />
             <circle cx="12" cy="12" r="3" />
         </symbol>
         <symbol id="i-logo" viewBox="0 0 24 24">
             <path d="M22 2 39 12v20L22 42 5 32V12z" />
             <path d="m13 22 6 6 12-14" />
         </symbol>
     </svg>

     <a class="skip" href="#main">الانتقال إلى المحتوى</a>

     <header class="site-header">
         <div class="container header-inner">

             <a class="logo" href="{{ url('/') }}" aria-label="OptiFit الرئيسية">
                 <svg class="brand-icon" viewBox="0 0 44 44" fill="none">
                     <path d="M22 2 39 12v20L22 42 5 32V12z"
                         stroke="currentColor" stroke-width="4" stroke-linejoin="round" />
                     <path d="m13 22 6 6 12-14"
                         stroke="currentColor" stroke-width="4.5"
                         stroke-linecap="round" stroke-linejoin="round" />
                 </svg>
                 <span>Opti<span>Fit</span></span>
             </a>

             <nav class="main-nav" id="main-nav">
                 <a href="{{ url('/') }}">الرئيسية</a>
                 <a href="{{ url('/') }}#features">المزايا</a>
                 <a href="{{ url('/') }}#about">عن المنصة</a>
             </nav>

             <a class="header-action" href="{{ route('login') }}">
                 <svg class="ico" aria-hidden="true">
                     <use href="#i-login"></use>
                 </svg>
                 الدخول
             </a>

             <button class="mobile-menu-toggle" id="menu-toggle"
                 type="button" aria-label="فتح القائمة"
                 aria-controls="main-nav" aria-expanded="false">
                 <svg class="ico" aria-hidden="true">
                     <use href="#i-menu"></use>
                 </svg>
             </button>

         </div>
     </header>

     <main id="main">
         <div class="container inner-page">

             <a class="back-link" href="{{ url('/') }}">
                 <svg class="ico" aria-hidden="true">
                     <use href="#i-arrow"></use>
                 </svg>
                 العودة للرئيسية
             </a>

             <div class="auth-layout">

                 <div class="auth-copy">
                     <span class="kicker">OPTIFIT / البداية هنا</span>

                     <h1>
                         أهلاً بعودتك.
                         <span>كمّل رحلتك.</span>
                     </h1>

                     <p>
                         سجّل دخولك للوصول إلى تجربتك الرياضية.
                         واجهة بسيطة تركز على أهدافك، بدون أي تعقيد.
                     </p>

                     <div class="auth-media">
                         <img src="{{ asset('assets/athlete.webp') }}"
                             alt="رياضي في صالة تمارين">
                         <span class="auth-caption">
                             خطوتك اليوم، قوتك غدًا.
                         </span>
                     </div>
                 </div>

                 <section class="glass-form" aria-labelledby="login-title">

                     <div class="form-intro">
                         <h2 id="login-title">تسجيل الدخول</h2>
                         <p>اكتب بيانات حسابك للمتابعة.</p>
                     </div>

                     <x-auth-session-status class="mb-4"
                         :status="session('status')" />

                     <form method="POST" action="{{ route('login') }}">
                         @csrf

                         <div class="field">
                             <label for="email">البريد الإلكتروني</label>

                             <input id="email"
                                 type="email"
                                 name="email"
                                 value="{{ old('email') }}"
                                 placeholder="name@example.com"
                                 autocomplete="username"
                                 dir="ltr"
                                 style="text-align:left"
                                 required autofocus>

                             <x-input-error :messages="$errors->get('email')"
                                 class="mt-2" />
                         </div>

                         <div class="field">
                             <label for="password">كلمة المرور</label>

                             <div class="input-wrap">
                                 <input id="password"
                                     name="password"
                                     type="password"
                                     autocomplete="current-password"
                                     placeholder="أدخل كلمة المرور"
                                     required>

                                 <button class="password-toggle"
                                     type="button"
                                     data-password-toggle="password"
                                     aria-label="إظهار كلمة المرور"
                                     title="إظهار كلمة المرور">

                                     <svg class="ico" aria-hidden="true">
                                         <use href="#i-eye"></use>
                                     </svg>
                                 </button>
                             </div>

                             <x-input-error :messages="$errors->get('password')"
                                 class="mt-2" />
                         </div>

                         <div class="options-row">

                             <label class="check" for="remember_me">
                                 <input id="remember_me"
                                     type="checkbox"
                                     name="remember">
                                 تذكرني
                             </label>

                             @if (Route::has('password.request'))
                             <a class="link-lime"
                                 href="{{ route('password.request') }}">
                                 نسيت كلمة المرور؟
                             </a>
                             @endif

                         </div>

                         <button class="btn btn-primary btn-full" type="submit">
                             <svg class="ico" aria-hidden="true">
                                 <use href="#i-login"></use>
                             </svg>
                             تسجيل الدخول
                         </button>

                         <div class="form-divider">أو</div>

                         <a class="google-btn"
                             href="{{ route('google.redirect') }}">
                             <span class="google-logo">G</span>
                             المتابعة باستخدام Google
                         </a>

                         <p class="switch-auth">
                             ليس لديك حساب؟
                             <a class="link-lime" href="{{ route('register') }}">
                                 أنشئ حسابك الآن
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
                 <svg class="brand-icon" viewBox="0 0 44 44" fill="none">
                     <path d="M22 2 39 12v20L22 42 5 32V12z"
                         stroke="currentColor" stroke-width="4"
                         stroke-linejoin="round" />
                     <path d="m13 22 6 6 12-14"
                         stroke="currentColor" stroke-width="4.5"
                         stroke-linecap="round" stroke-linejoin="round" />
                 </svg>
                 <span>Opti<span>Fit</span></span>
             </a>

             <span>
                 تصميم تجريبي مستقل لواجهة OptiFit — جميع الحقوق محفوظة © 2026
             </span>

             <div class="foot-links">
                 <a href="{{ route('login') }}">تسجيل الدخول</a>
                 <a href="{{ route('register') }}">إنشاء حساب</a>
             </div>

         </div>
     </footer>

     <script>
         document.querySelectorAll('[data-password-toggle]').forEach(button => {
             button.addEventListener('click', () => {
                 const input = document.getElementById(
                     button.dataset.passwordToggle
                 );

                 input.type = input.type === 'password' ? 'text' : 'password';
             });
         });
     </script>

 </x-guest-layout>