<!DOCTYPE html>
<html lang="ar" dir="rtl" data-apx-theme="volt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0d120f">

    <script src="{{ asset('optifit-apex-assets/js/apex-theme.js') }}"></script>

    <title>تسجيل النادي | OptiFit APEX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link href="{{ asset('optifit-apex-assets/css/apex-shared.css') }}" rel="stylesheet">
    <link href="{{ asset('optifit-apex-assets/css/apex-register.css') }}" rel="stylesheet">
</head>

<body class="apx-auth-page">

    @include('optifit_apex.partials.icons')

    <a class="apx-skip" href="#owner-register-form">
        انتقل إلى نموذج تسجيل النادي
    </a>

    <header class="apx-auth-top">
        <a class="apx-brand" href="{{ url('/') }}">
            <span class="apx-brand-mark">
                <svg>
                    <use href="#apx-dumbbell" />
                </svg>
            </span>
            <span>OPTI<span>FIT</span> <small>APEX</small></span>
        </a>

        <div class="apx-auth-actions">
            <button
                class="apx-icon-button"
                type="button"
                data-apx-theme-open
                aria-label="تغيير مظهر الصفحة">
                <svg>
                    <use href="#apx-palette" />
                </svg>
            </button>

            <a class="apx-secondary" href="{{ route('register') }}">
                حساب مستخدم
            </a>

            <a class="apx-primary" href="{{ route('login') }}">
                تسجيل الدخول
            </a>
        </div>
    </header>

    <main class="apx-auth-layout">

        <section class="apx-auth-art" aria-labelledby="owner-heading">
            <div>
                <span class="apx-eyebrow">GROW WITH OPTIFIT</span>

                <h1 id="owner-heading">
                    ناديك يستحق <em>مساحة أقوى.</em>
                </h1>

                <p>
                    أنشئ حساب المالك وسجّل بيانات النادي في معاملة MySQL واحدة؛
                    بعدها يظهر الطلب بحالة قيد المراجعة.
                </p>
            </div>

            <div class="apx-auth-facts">
                <div class="apx-auth-fact">
                    <svg>
                        <use href="#apx-building" />
                    </svg>
                    حساب المالك والنادي يُحفظان معًا
                </div>

                <div class="apx-auth-fact">
                    <svg>
                        <use href="#apx-shield" />
                    </svg>
                    حالة البداية: قيد المراجعة
                </div>
            </div>
        </section>

        <section class="apx-auth-form-side">
            <div class="apx-card apx-auth-card">

                <header>
                    <span class="apx-eyebrow">GYM OWNER / REGISTRATION</span>

                    <h2>أضف ناديك.</h2>

                    <p>
                        الحقول المطلوبة تُرسل مباشرة إلى Laravel،
                        بدون حفظ متصفح بديل.
                    </p>
                </header>

                @if($errors->any())
                <div class="apx-error-summary" role="alert" tabindex="-1">
                    <h2>راجع الحقول التالية:</h2>

                    <ul>
                        @foreach($errors->keys() as $field)
                        <li>
                            <a href="#{{ $field }}">
                                {{ $errors->first($field) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form
                    id="owner-register-form"
                    method="POST"
                    action="{{ route('gym-owner.register.store') }}"
                    enctype="multipart/form-data"
                    data-apx-submit>
                    @csrf

                    <div class="apx-form-grid">

                        <!-- بيانات صاحب النادي -->

                        <div class="apx-form-section">
                            <h3>بيانات صاحب النادي</h3>
                            <p>تُستخدم لإنشاء حساب المالك.</p>
                        </div>

                        <div class="apx-field apx-span-2 @error('name') has-error @enderror">
                            <label for="name">الاسم الكامل</label>

                            <input
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                autocomplete="name"
                                required
                                autofocus>

                            @error('name')
                            <p class="apx-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="apx-field apx-span-2 @error('email') has-error @enderror">
                            <label for="email">البريد الإلكتروني</label>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                autocomplete="username"
                                dir="ltr"
                                required>

                            @error('email')
                            <p class="apx-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="apx-field @error('password') has-error @enderror">
                            <label for="password">كلمة المرور</label>

                            <div class="apx-password">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="new-password"
                                    required>

                                <button
                                    class="apx-password-toggle"
                                    type="button"
                                    data-password-toggle="password"
                                    aria-label="إظهار كلمة المرور">
                                    <svg>
                                        <use href="#apx-eye" />
                                    </svg>
                                </button>
                            </div>

                            @error('password')
                            <p class="apx-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="apx-field">
                            <label for="password_confirmation">
                                تأكيد كلمة المرور
                            </label>

                            <div class="apx-password">
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    autocomplete="new-password"
                                    required>

                                <button
                                    class="apx-password-toggle"
                                    type="button"
                                    data-password-toggle="password_confirmation"
                                    aria-label="إظهار تأكيد كلمة المرور">
                                    <svg>
                                        <use href="#apx-eye" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- بيانات النادي -->

                        <div class="apx-form-section">
                            <h3>بيانات النادي</h3>
                            <p>سيُحفظ النادي بحالة قيد المراجعة.</p>
                        </div>

                        <div class="apx-field apx-span-2 @error('gym_name') has-error @enderror">
                            <label for="gym_name">اسم النادي</label>

                            <input
                                id="gym_name"
                                name="gym_name"
                                value="{{ old('gym_name') }}"
                                maxlength="255"
                                required>

                            @error('gym_name')
                            <p class="apx-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="apx-field @error('city') has-error @enderror">
                            <label for="city">المدينة</label>

                            <input
                                id="city"
                                name="city"
                                value="{{ old('city') }}"
                                maxlength="100"
                                required>

                            @error('city')
                            <p class="apx-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="apx-field @error('area') has-error @enderror">
                            <label for="area">المنطقة (اختياري)</label>

                            <input
                                id="area"
                                name="area"
                                value="{{ old('area') }}"
                                maxlength="100">

                            @error('area')
                            <p class="apx-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="apx-field apx-span-2 @error('address') has-error @enderror">
                            <label for="address">العنوان التفصيلي</label>

                            <input
                                id="address"
                                name="address"
                                value="{{ old('address') }}"
                                maxlength="255"
                                required>

                            @error('address')
                            <p class="apx-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="apx-field @error('phone') has-error @enderror">
                            <label for="phone">الهاتف (اختياري)</label>

                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                value="{{ old('phone') }}"
                                maxlength="30"
                                dir="ltr">

                            @error('phone')
                            <p class="apx-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="apx-field @error('website_url') has-error @enderror">
                            <label for="website_url">الموقع الإلكتروني (اختياري)</label>

                            <input
                                id="website_url"
                                name="website_url"
                                type="url"
                                value="{{ old('website_url') }}"
                                maxlength="255"
                                dir="ltr">

                            @error('website_url')
                            <p class="apx-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="apx-field apx-span-2 @error('description') has-error @enderror">
                            <label for="description">وصف النادي (اختياري)</label>

                            <textarea
                                id="description"
                                name="description"
                                maxlength="2000">{{ old('description') }}</textarea>

                            @error('description')
                            <p class="apx-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- وثائق التحقق الرسمية -->

                        <div class="apx-form-section">
                            <h3>وثائق التحقق الرسمية</h3>

                            <p>
                                يجب رفع الوثائق الثلاثة لإثبات تسجيل النادي رسميًا.
                                ستراجعها الإدارة قبل اعتماد النادي.
                            </p>
                        </div>

                        <div class="apx-field apx-span-2 @error('profession_license') has-error @enderror">
                            <label for="profession_license">
                                رخصة المهن (إلزامي)
                            </label>

                            <input
                                id="profession_license"
                                name="profession_license"
                                type="file"
                                accept=".pdf,.jpg,.jpeg,.png"
                                required>

                            @error('profession_license')
                            <p class="apx-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="apx-field apx-span-2 @error('establishment_certificate') has-error @enderror">
                            <label for="establishment_certificate">
                                شهادة تسجيل المنشأة (إلزامي)
                            </label>

                            <input
                                id="establishment_certificate"
                                name="establishment_certificate"
                                type="file"
                                accept=".pdf,.jpg,.jpeg,.png"
                                required>

                            @error('establishment_certificate')
                            <p class="apx-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="apx-field apx-span-2 @error('commercial_registration') has-error @enderror">
                            <label for="commercial_registration">
                                السجل التجاري (إلزامي)
                            </label>

                            <input
                                id="commercial_registration"
                                name="commercial_registration"
                                type="file"
                                accept=".pdf,.jpg,.jpeg,.png"
                                required>

                            @error('commercial_registration')
                            <p class="apx-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="apx-field apx-span-2">
                            <p>
                                الصيغ المسموحة: PDF وJPG وPNG.
                                الحد الأقصى: 5MB لكل وثيقة.
                            </p>
                        </div>

                    </div>

                    <button class="apx-primary apx-submit" type="submit">
                        <span data-submit-label>
                            إنشاء الحساب وإرسال النادي
                        </span>

                        <svg>
                            <use href="#apx-arrow" />
                        </svg>
                    </button>

                    <p class="apx-owner-note">
                        <strong>مهم:</strong>
                        الإرسال لا يعني اعتماد النادي؛
                        يبقى طلبه قيد المراجعة وفق الصلاحيات الحالية.
                    </p>

                </form>

                <p class="apx-switch">
                    تريد حساب مستخدم؟
                    <a href="{{ route('register') }}">سجّل من هنا</a>
                    · لديك حساب؟
                    <a href="{{ route('login') }}">تسجيل الدخول</a>
                </p>

            </div>
        </section>

    </main>

    @include('optifit_apex.partials.theme-studio')

    <script
        defer
        src="{{ asset('optifit-apex-assets/js/apex-shared.js') }}"></script>

</body>

</html>