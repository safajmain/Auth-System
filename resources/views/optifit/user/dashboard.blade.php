<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="volt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0d120f">
    <script src="{{ asset('optifit-apex-assets/js/apex-theme.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة المستخدم | OptiFit APEX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('optifit-apex-assets/css/user-dashboard.css') }}" rel="stylesheet">
</head>
<body>
    <a class="skip-link" href="#dashboard-main">انتقل إلى المحتوى الرئيسي</a>

    <svg class="icon-library" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
        <symbol id="ap-dumbbell" viewBox="0 0 24 24"><path d="M6 6v12M3 9v6m15-9v12m3-9v6M6 12h12"/></symbol>
        <symbol id="ap-home" viewBox="0 0 24 24"><path d="m3 11 9-8 9 8"/><path d="M5 10v10h14V10M9 20v-6h6v6"/></symbol>
        <symbol id="ap-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></symbol>
        <symbol id="ap-target" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/></symbol>
        <symbol id="ap-check" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/></symbol>
        <symbol id="ap-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
        <symbol id="ap-heart" viewBox="0 0 24 24"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"/></symbol>
        <symbol id="ap-star" viewBox="0 0 24 24"><path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-2.9-5.6 2.9 1.1-6.2L3 9.6l6.2-.9Z"/></symbol>
        <symbol id="ap-map" viewBox="0 0 24 24"><path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3Z"/><path d="M9 3v15M15 6v15"/></symbol>
        <symbol id="ap-pin" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></symbol>
        <symbol id="ap-palette" viewBox="0 0 24 24"><path d="M12 3a9 9 0 0 0 0 18h1.5a2 2 0 0 0 0-4H12a1.5 1.5 0 0 1 0-3h3a6 6 0 0 0 0-12Z"/><circle cx="7.5" cy="10.5" r=".8"/><circle cx="10" cy="7" r=".8"/><circle cx="14" cy="6.5" r=".8"/></symbol>
        <symbol id="ap-menu" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></symbol>
        <symbol id="ap-chevron" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></symbol>
        <symbol id="ap-close" viewBox="0 0 24 24"><path d="m5 5 14 14M19 5 5 19"/></symbol>
        <symbol id="ap-arrow" viewBox="0 0 24 24"><path d="M19 12H5m7-7-7 7 7 7"/></symbol>
        <symbol id="ap-logout" viewBox="0 0 24 24"><path d="M10 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v2M15 17l5-5-5-5M20 12H8"/></symbol>
    </svg>
    @include('optifit_apex.partials.icons')

    <div class="dashboard-shell" data-dashboard-shell>
        <button class="mobile-shade" type="button" data-sidebar-close aria-label="إغلاق القائمة"></button>

        @include('optifit_apex.partials.user-sidebar')

        <main class="dashboard-main" id="dashboard-main" tabindex="-1">
            <header class="dashboard-topbar">
                <div class="topbar-welcome">
                    <button class="icon-button menu-button" type="button" data-sidebar-open aria-label="فتح القائمة" aria-controls="dashboard-sidebar" aria-expanded="false"><svg><use href="#ap-menu"/></svg></button>
                    <div><span class="eyebrow">OPTIFIT / YOUR SPACE</span><h1>أهلًا، {{ $dashboard['user']['name'] }}.</h1></div>
                </div>
                <div class="topbar-actions">
                    <button class="icon-button theme-button" type="button" data-theme-open aria-label="فتح إعدادات المظهر"><svg><use href="#ap-palette"/></svg></button>
                    <a class="user-avatar" href="{{ route('profile.edit') }}" aria-label="فتح إعدادات حساب {{ $dashboard['user']['name'] }}">{{ $dashboard['user']['initial'] ?: 'O' }}</a>
                </div>
            </header>

            @if (session('success'))
                <div class="status-message" role="status"><svg><use href="#ap-check"/></svg><span>{{ session('success') }}</span></div>
            @endif

            <section class="dashboard-hero" aria-labelledby="hero-title">
                <svg class="hero-watermark" aria-hidden="true"><use href="#ap-dumbbell"/></svg>
                <div class="hero-copy">
                    <span class="eyebrow">DISCOVER. TRAIN. EVOLVE.</span>
                    <h2 id="hero-title">هدفك أكبر من<br><em>مجرد اشتراك.</em></h2>
                    <p>بياناتك الرياضية محفوظة في حسابك، والنوادي أدناه قادمة مباشرة من OptiFit حسب التفضيلات المتاحة.</p>
                    <div class="hero-actions">
                        <a class="primary-button" href="{{ route('gyms.index') }}">استكشف كل النوادي <svg><use href="#ap-arrow"/></svg></a>
                        <a class="secondary-button" href="{{ route('user.personal') }}">حدّث تفضيلاتك</a>
                    </div>
                </div>
                <div class="hero-profile" aria-label="ملخص ملفك الرياضي">
                    <span>ملفك الرياضي</span>
                    <strong>{{ $dashboard['preference']['location'] ?: 'لم تُحدد المنطقة' }}</strong>
                    <small>
                        @if ($dashboard['preference']['budget'] !== null)
                            ميزانية حتى {{ number_format($dashboard['preference']['budget'], 0) }} د.أ شهريًا
                        @else
                            لم تُحدد الميزانية
                        @endif
                    </small>
                </div>
            </section>

            <section class="metrics-grid" aria-label="إحصاءات الحساب">
                <article class="metric-card"><svg><use href="#ap-dumbbell"/></svg><strong>{{ $dashboard['stats']['approved_gyms'] }}</strong><span>نوادٍ معتمدة</span></article>
                <article class="metric-card"><svg><use href="#ap-heart"/></svg><strong>{{ $dashboard['stats']['favorites'] }}</strong><span>في المفضلة</span></article>
                <article class="metric-card"><svg><use href="#ap-clock"/></svg><strong>{{ $dashboard['stats']['times'] }}</strong><span>أوقات تدريب</span></article>
                <article class="metric-card"><svg><use href="#ap-star"/></svg><strong>{{ $dashboard['stats']['reviews'] }}</strong><span>تقييماتك</span></article>
            </section>

            <section class="profile-grid" aria-labelledby="profile-summary-title">
                <article class="profile-panel profile-panel-main">
                    <div class="section-heading">
                        <div><span class="eyebrow">YOUR FITNESS PROFILE</span><h2 id="profile-summary-title">تفضيلاتك <em>الحقيقية.</em></h2></div>
                        <a class="text-link" href="{{ route('user.personal') }}">تعديل الملف <svg><use href="#ap-arrow"/></svg></a>
                    </div>
                    <div class="preference-columns">
                        <div>
                            <h3>الأنشطة</h3>
                            <div class="chip-list">
                                @forelse ($dashboard['activities'] as $activity)<span class="data-chip">{{ $activity }}</span>@empty<span class="empty-inline">لا توجد أنشطة محفوظة.</span>@endforelse
                            </div>
                        </div>
                        <div>
                            <h3>المرافق</h3>
                            <div class="chip-list">
                                @forelse ($dashboard['facilities'] as $facility)<span class="data-chip">{{ $facility }}</span>@empty<span class="empty-inline">لا توجد مرافق محفوظة.</span>@endforelse
                            </div>
                        </div>
                    </div>
                </article>

                <article class="profile-panel schedule-panel">
                    <div class="schedule-title"><span class="schedule-icon"><svg><use href="#ap-clock"/></svg></span><div><span class="eyebrow">TRAINING SCHEDULE</span><h2>أوقاتك المتاحة</h2></div></div>
                    <div class="schedule-list">
                        @forelse ($dashboard['times'] as $time)
                            <div class="schedule-row"><strong>{{ $time['day'] }}</strong><span dir="ltr">{{ $time['start'] }} — {{ $time['end'] }}</span></div>
                        @empty
                            <p class="empty-inline">لا توجد أوقات محفوظة.</p>
                        @endforelse
                    </div>
                    <a class="secondary-button full-width" href="{{ route('user.times') }}">إدارة الأوقات</a>
                </article>
            </section>

            <section class="gyms-section" id="recommended-gyms" aria-labelledby="gyms-title">
                <div class="section-heading gyms-heading">
                    <div><span class="eyebrow">CURATED FROM MYSQL</span><h2 id="gyms-title">نوادٍ على <em>مقاسك.</em></h2><p>مرتبة بعدد نقاط الالتقاء الفعلية بين بيانات النادي وتفضيلات حسابك.</p></div>
                    <span class="results-count">{{ count($dashboard['gyms']) }} نتيجة</span>
                </div>

                <div class="gym-grid">
                    @forelse ($dashboard['gyms'] as $gym)
                        <article class="gym-card">
                            <div class="gym-media">
                                @if ($gym['image'])
                                    <img src="{{ $gym['image'] }}" alt="صورة {{ $gym['name'] }}" width="640" height="400" loading="lazy">
                                @else
                                    <div class="gym-placeholder" role="img" aria-label="لا توجد صورة محفوظة لنادي {{ $gym['name'] }}"><svg><use href="#ap-dumbbell"/></svg></div>
                                @endif
                                <span class="match-badge">{{ $gym['match_count'] }} نقاط توافق</span>
                                @if ($gym['favorite'])<span class="favorite-badge"><svg><use href="#ap-heart"/></svg> محفوظ</span>@endif
                            </div>
                            <div class="gym-content">
                                <span class="gym-location"><svg><use href="#ap-pin"/></svg>{{ collect([$gym['city'], $gym['area']])->filter()->join('، ') ?: 'الموقع غير محدد' }}</span>
                                <h3>{{ $gym['name'] }}</h3>
                                <p>{{ $gym['description'] ?: 'لا يوجد وصف مضاف لهذا النادي بعد.' }}</p>
                                <div class="gym-match-list" aria-label="أسباب التوافق">
                                    @foreach (array_slice($gym['matches']['activities'], 0, 2) as $match)<span><svg><use href="#ap-check"/></svg>{{ $match }}</span>@endforeach
                                    @foreach (array_slice($gym['matches']['facilities'], 0, 2) as $match)<span><svg><use href="#ap-check"/></svg>{{ $match }}</span>@endforeach
                                    @if ($gym['matches']['location'])<span><svg><use href="#ap-map"/></svg>ضمن منطقتك</span>@endif
                                    @if ($gym['matches']['budget'])<span><svg><use href="#ap-check"/></svg>ضمن الميزانية</span>@endif
                                </div>
                                <div class="gym-footer">
                                    <div>
                                        @if ($gym['price'] !== null)<strong>{{ number_format($gym['price'], 0) }} د.أ</strong><small>/ شهر</small>@else<strong class="price-unavailable">السعر غير محدد</strong>@endif
                                    </div>
                                    <a class="details-button" href="{{ route('gyms.show', $gym['id']) }}">التفاصيل والتقييم <svg><use href="#ap-arrow"/></svg></a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="empty-state"><span><svg><use href="#ap-dumbbell"/></svg></span><h3>لا توجد نوادٍ معتمدة حاليًا</h3><p>ستظهر هنا النوادي المعتمدة من قاعدة البيانات فور توفرها.</p></div>
                    @endforelse
                </div>
            </section>

            <footer class="dashboard-footer"><span>OptiFit APEX</span><p>كل الأرقام والنوادي والتفضيلات المعروضة مقروءة من Laravel وMySQL.</p></footer>
        </main>
    </div>

    @foreach ($dashboard['gyms'] as $gym)
        <dialog class="gym-dialog" id="gym-dialog-{{ $gym['id'] }}" aria-labelledby="gym-dialog-title-{{ $gym['id'] }}">
            <div class="dialog-head">
                <div><span class="eyebrow">GYM DETAILS</span><h2 id="gym-dialog-title-{{ $gym['id'] }}">{{ $gym['name'] }}</h2></div>
                <button class="dialog-close" type="button" data-dialog-close aria-label="إغلاق تفاصيل {{ $gym['name'] }}"><svg><use href="#ap-close"/></svg></button>
            </div>
            <div class="dialog-body">
                <p>{{ $gym['description'] ?: 'لا يوجد وصف مضاف لهذا النادي بعد.' }}</p>
                <dl class="details-list">
                    <div><dt>الموقع</dt><dd>{{ collect([$gym['city'], $gym['area'], $gym['address']])->filter()->join('، ') ?: 'غير محدد' }}</dd></div>
                    <div><dt>الهاتف</dt><dd>{{ $gym['phone'] ?: 'غير محدد' }}</dd></div>
                    <div><dt>الاشتراك الشهري</dt><dd>{{ $gym['price'] !== null ? number_format($gym['price'], 0).' د.أ' : 'غير محدد' }}</dd></div>
                    <div><dt>نقاط التوافق</dt><dd>{{ $gym['match_count'] }}</dd></div>
                </dl>
                <div class="dialog-grid">
                    <section><h3>الأنشطة</h3><div class="chip-list">@forelse ($gym['activities'] as $activity)<span class="data-chip">{{ $activity }}</span>@empty<span class="empty-inline">غير محددة</span>@endforelse</div></section>
                    <section><h3>المرافق</h3><div class="chip-list">@forelse ($gym['facilities'] as $facility)<span class="data-chip">{{ $facility }}</span>@empty<span class="empty-inline">غير محددة</span>@endforelse</div></section>
                </div>
                @if (count($gym['hours']))
                    <section class="dialog-hours"><h3>ساعات العمل</h3>@foreach ($gym['hours'] as $hour)<span>{{ $hour['day'] }}: <b dir="ltr">{{ substr($hour['open'], 0, 5) }} — {{ substr($hour['close'], 0, 5) }}</b></span>@endforeach</section>
                @endif
                @if ($gym['website_url'])<a class="primary-button dialog-link" href="{{ $gym['website_url'] }}" target="_blank" rel="noopener noreferrer">زيارة موقع النادي</a>@endif
            </div>
        </dialog>
    @endforeach

    @include('optifit_apex.partials.user-theme-studio')

    <script defer src="{{ asset('optifit-apex-assets/js/user-dashboard.js') }}"></script>
</body>
</html>
