<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
  <meta name="theme-color" content="#0d120f">
  <script src="{{ asset('optifit-apex-assets/js/apex-theme.js') }}"></script>
  <meta name="description" content="OptiFit APEX — واجهة تجريبية لاكتشاف النوادي الرياضية ومقارنة خدماتها.">
  <title>OptiFit APEX | Fitness Experience</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('optifit-apex-assets/css/apex.css') }}">
  <script>
    window.OPTIFIT_APEX_BOOT = {
      gyms: @json($demoGyms),
      currentUserName: @json($currentUserName),
      preview: true,
      loginUrl: @json($loginUrl),
      registerUrl: @json($registerUrl),
      gymOwnerUrl: @json($gymOwnerUrl),
      googleAuthUrl: @json($googleAuthUrl)
    };
  </script>
  <script src="{{ asset('optifit-apex-assets/js/apex.js') }}" defer></script>
</head>
<body>
{{-- Inline SVG icon sprites: avoids icon CDN dependency. --}}
<svg class="sr-sprite" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
  <symbol id="ap-home" viewBox="0 0 24 24"><path d="m3 10 9-7 9 7v10H3zM9 20v-7h6v7"/></symbol>
  <symbol id="ap-compass" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m9 15 2-6 5-2-2 6z"/></symbol>
  <symbol id="ap-map" viewBox="0 0 24 24"><path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3zM9 3v15M15 6v15"/></symbol>
  <symbol id="ap-sparkles" viewBox="0 0 24 24"><path d="m12 2 2.1 7.9L22 12l-7.9 2.1L12 22l-2.1-7.9L2 12l7.9-2.1z"/></symbol>
  <symbol id="ap-compare" viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/><circle cx="8" cy="6" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="10" cy="18" r="2"/></symbol>
  <symbol id="ap-bell" viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></symbol>
  <symbol id="ap-heart" viewBox="0 0 24 24"><path d="M20.8 5.6C17.7 2 13.9 4.1 12 6.1 10.1 4.1 6.3 2 3.2 5.7-.1 9.5 5.7 15.3 12 21c6.3-5.7 12.1-11.5 8.8-15.4z"/></symbol>
  <symbol id="ap-person" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M4 22v-2a8 8 0 0 1 16 0v2"/></symbol>
  <symbol id="ap-palette" viewBox="0 0 24 24"><path d="M12 3C6.5 3 2 7.3 2 12.5S6 22 11 22h2c1.8 0 2.5-2 1.3-3.2-.7-.7-.5-1.8.5-2.2 1.8-.7 5.2 0 6.4-2.4C24 8.8 18.2 3 12 3z"/><circle cx="7" cy="12" r="1"/><circle cx="10" cy="7.5" r="1"/><circle cx="15" cy="8" r="1"/></symbol>
  <symbol id="ap-dumbbell" viewBox="0 0 24 24"><path d="M7 10 10 7l7 7-3 3-7-7M4 7l3-3 3 3-3 3-3-3m10 10 3-3 3 3-3 3-3-3M2 9l7-7M15 22l7-7"/></symbol>
  <symbol id="ap-chevron" viewBox="0 0 24 24"><path d="m9 4 8 8-8 8"/></symbol>
  <symbol id="ap-menu" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></symbol>
  <symbol id="ap-x" viewBox="0 0 24 24"><path d="M5 5 19 19M19 5 5 19"/></symbol>
  <symbol id="ap-logout" viewBox="0 0 24 24"><path d="M10 4H4v16h6m5-16 6 8-6 8M8 12h13"/></symbol>
  <symbol id="ap-star" viewBox="0 0 24 24"><path d="m12 2 3.1 6.7 7.2.9-5.3 5.2 1.4 7.2L12 18.6 5.6 22l1.4-7.2L1.7 9.6l7.2-.9z"/></symbol>
  <symbol id="ap-reset" viewBox="0 0 24 24"><path d="M4 11a8 8 0 1 1 2 6m-2 1v-6h6"/></symbol>
  <symbol id="ap-check" viewBox="0 0 24 24"><path d="m4 12 5 5 11-11"/></symbol>
  <symbol id="ap-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 6v6l4 3"/></symbol>
  <symbol id="ap-search" viewBox="0 0 24 24"><circle cx="10" cy="10" r="7"/><path d="m15 15 6 6"/></symbol>
  <symbol id="ap-shield" viewBox="0 0 24 24"><path d="m12 2 9 4v6c0 7-4.7 9.5-9 11-4.3-1.5-9-4-9-11V6z"/><path d="m8 12 3 3 5-6"/></symbol>
  <symbol id="ap-plus" viewBox="0 0 24 24"><path d="M12 4v16M4 12h16"/></symbol>
  <symbol id="ap-send" viewBox="0 0 24 24"><path d="m3 3 18 9-18 9 4-9zM7 12h14"/></symbol>
  <symbol id="ap-camera" viewBox="0 0 24 24"><path d="M3 7h5l2-3h4l2 3h5v14H3z"/><circle cx="12" cy="14" r="4"/></symbol>
  <symbol id="ap-eye" viewBox="0 0 24 24"><path d="M2 12s3.7-7 10-7 10 7 10 7-3.7 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></symbol>
</svg>
<div id="apex-app" aria-live="polite"></div>
<div class="studio-backdrop" id="apex-studio-backdrop"></div>
<section class="studio" id="apex-studio" role="dialog" aria-modal="true" aria-label="تخصيص ألوان OptiFit" aria-hidden="true" dir="rtl">
  <div class="mobile-drag"></div>
  <div class="studio-top"><div><div class="studio-kicker">APEX / PERSONALIZATION</div><h2>مظهر على ذوقك.</h2><div class="studio-sub">14 ثيم + لون خاص في لوحة زجاجية أنيقة.</div></div><button class="close" id="apex-close-studio" aria-label="إغلاق لوحة الألوان"><svg><use href="#ap-x"/></svg></button></div>
  <div class="studio-scroll"><div class="theme-preview"><div><div class="sample-top">OPTIFIT APEX</div><div class="sample-main">هدفك. طاقتك. لونك.</div><span class="sample-btn">جرّب قوتك</span></div><div class="sample-pattern"><span></span><span></span><span></span></div></div>
    <div class="studio-label">الثيمات الجاهزة <span class="count">14 THEMES</span></div><div class="swatches" id="apex-swatches"></div>
    <div class="current-theme"><span class="current-dot"></span><span>الثيم الحالي: <b id="apex-active-theme-name">أخضر كهربائي</b></span></div>
    <div class="custom-box"><div class="custom-row"><div class="custom-info"><b>اصنع لونك الخاص</b><p>أي لون تختاره بيظهر مباشرة.</p></div><input type="color" class="colorpicker" id="apex-custom-color" value="#7df8e6" aria-label="اختيار لون مخصص"></div><button class="custom-apply" id="apex-apply-custom">تطبيق اللون المخصص</button></div>
  </div>
  <div class="studio-bottom"><button id="apex-reset-theme" class="reset">استعادة اللون الأصلي</button><button id="apex-done-theme" class="done">تم، عجبني</button></div>
</section>
<div id="apex-toast" class="toast" role="status" aria-live="polite"></div>
</body>
</html>
