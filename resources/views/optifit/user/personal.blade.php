<!DOCTYPE html>


<html data-role="user" data-static-pages="1" data-view="personal" dir="rtl" lang="ar">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta content="#0b1219" name="theme-color"/>
<title>المعلومات الشخصية | OptiFit</title>
<link href="{{ asset('optifit/css/user/personal.css') }}" rel="stylesheet">
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet"/>
</head>
<body>
<!-- All SVG icons are embedded locally. No external JavaScript or build tools required. -->
<svg aria-hidden="true" style="display:none" xmlns="http://www.w3.org/2000/svg">
<symbol id="i-grid" viewbox="0 0 24 24"><rect height="7" rx="1" width="7" x="3" y="3"></rect><rect height="7" rx="1" width="7" x="14" y="3"></rect><rect height="7" rx="1" width="7" x="3" y="14"></rect><rect height="7" rx="1" width="7" x="14" y="14"></rect></symbol>
<symbol id="i-shield" viewbox="0 0 24 24"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"></path><path d="m9 12 2 2 4-4"></path></symbol>
<symbol id="i-users" viewbox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path></symbol>
<symbol id="i-building" viewbox="0 0 24 24"><rect height="15" rx="2" width="18" x="3" y="6"></rect><path d="M8 21V3h8v18M7 10h2m-2 4h2m6-4h2m-2 4h2"></path></symbol>
<symbol id="i-list" viewbox="0 0 24 24"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"></path></symbol>
<symbol id="i-dumbbell" viewbox="0 0 24 24"><path d="m6.5 6.5 11 11M2.5 9 9 2.5M15 21.5 21.5 15M4.5 12l8-8M12 19.5l8-8"></path></symbol>
<symbol id="i-dumbbell2" viewbox="0 0 24 24"><path d="M6 6v12M3 9v6m15-9v12m3-9v6M6 12h12"></path></symbol>
<symbol id="i-check" viewbox="0 0 24 24"><path d="m20 6-11 11-5-5"></path></symbol>
<symbol id="i-checkcircle" viewbox="0 0 24 24"><circle cx="12" cy="12" r="9"></circle><path d="m8 12 3 3 5-6"></path></symbol>
<symbol id="i-clock" viewbox="0 0 24 24"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></symbol>
<symbol id="i-settings" viewbox="0 0 24 24"><path d="M4 7h16M4 17h16"></path><circle cx="9" cy="7" fill="#14212a" r="2"></circle><circle cx="15" cy="17" fill="#14212a" r="2"></circle></symbol>
<symbol id="i-calendar" viewbox="0 0 24 24"><rect height="16" rx="2" width="18" x="3" y="5"></rect><path d="M7 3v4m10-4v4M3 10h18"></path></symbol>
<symbol id="i-pin" viewbox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"></path><circle cx="12" cy="10" r="2.5"></circle></symbol>
<symbol id="i-search" viewbox="0 0 24 24"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></symbol>
<symbol id="i-download" viewbox="0 0 24 24"><path d="M12 3v12m-5-5 5 5 5-5M4 17v4h16v-4"></path></symbol>
<symbol id="i-close" viewbox="0 0 24 24"><path d="M5 5 19 19M19 5 5 19"></path></symbol>
<symbol id="i-pencil" viewbox="0 0 24 24"><path d="m15 5 4 4M4 20l5-.9L20 8a3 3 0 0 0-4-4L5 15l-1 5Z"></path></symbol>
<symbol id="i-alert" viewbox="0 0 24 24"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v6m0 4h.01"></path></symbol>
<symbol id="i-file" viewbox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path><path d="M14 2v6h6M8 13h8M8 17h8"></path></symbol>
<symbol id="i-plus" viewbox="0 0 24 24"><path d="M12 4v16M4 12h16"></path></symbol>
<symbol id="i-arrow" viewbox="0 0 24 24"><path d="M19 12H5m7-7-7 7 7 7"></path></symbol>
<symbol id="i-spark" viewbox="0 0 24 24"><path d="m12 3 1.8 6.2L20 11l-6.2 1.8L12 19l-1.8-6.2L4 11l6.2-1.8Z"></path></symbol>
<symbol id="i-eye" viewbox="0 0 24 24"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></symbol>
<symbol id="i-rotate" viewbox="0 0 24 24"><path d="M3 11a9 9 0 1 1 2 7M3 4v7h7"></path></symbol>
</svg>
<header class="topbar">
<div class="top-start">
<div class="experience"><span class="dot"></span> تجربة المنصة التفاعلية <span class="demo-label">نسخة تجريبية</span></div>
<span class="top-divider"></span>
<div aria-label="تبديل الواجهة التجريبية" class="role-switch" role="group">
<button aria-pressed="true" class="role active" data-role="user" type="button"><svg class="icon"><use href="#i-users"></use></svg> مستخدم</button>
<button aria-pressed="false" class="role" data-role="owner" type="button"><svg class="icon"><use href="#i-building"></use></svg> صاحب جيم</button>
<button aria-pressed="false" class="role" data-role="admin" type="button"><svg class="icon"><use href="#i-shield"></use></svg> مدير النظام</button>
</div>
</div>
<button class="catalog" data-action="catalog" type="button"><svg class="icon"><use href="#i-grid"></use></svg> فهرس الشاشات</button>
</header>
<nav aria-label="التنقل الرئيسي" class="navbar">
<div class="brand"><span class="logo-mark"><svg class="icon"><use href="#i-pin"></use></svg></span><span class="brand-name">Opti<span>Fit</span></span></div>
<div class="nav-items" id="navItems"><button class="nav-item active" data-view="personal" type="button"><svg aria-hidden="true" class="icon"><use href="#i-users"></use></svg> المعلومات الشخصية</button><button class="nav-item" data-view="activities" type="button"><svg aria-hidden="true" class="icon"><use href="#i-dumbbell2"></use></svg> الأنشطة المفضلة</button><button class="nav-item" data-view="facilities" type="button"><svg aria-hidden="true" class="icon"><use href="#i-checkcircle"></use></svg> المرافق المفضلة</button><button class="nav-item" data-view="times" type="button"><svg aria-hidden="true" class="icon"><use href="#i-clock"></use></svg> الأوقات المتاحة</button><button class="nav-item" data-view="dashboard" type="button"><svg aria-hidden="true" class="icon"><use href="#i-grid"></use></svg> الرئيسية</button><button class="nav-item" data-view="find" type="button"><svg aria-hidden="true" class="icon"><use href="#i-search"></use></svg> بحث النوادي</button><button class="nav-item" data-view="results" type="button"><svg aria-hidden="true" class="icon"><use href="#i-list"></use></svg> نتائج البحث</button><button class="nav-item" data-view="details" type="button"><svg aria-hidden="true" class="icon"><use href="#i-eye"></use></svg> تفاصيل النادي</button><button class="nav-item" data-view="compare" type="button"><svg aria-hidden="true" class="icon"><use href="#i-settings"></use></svg> مقارنة النوادي</button><button class="nav-item" data-view="map" type="button"><svg aria-hidden="true" class="icon"><use href="#i-pin"></use></svg> الخريطة</button><button class="nav-item" data-view="favorites" type="button"><svg aria-hidden="true" class="icon"><use href="#i-checkcircle"></use></svg> المفضلة</button><button class="nav-item" data-view="myreviews" type="button"><svg aria-hidden="true" class="icon"><use href="#i-file"></use></svg> تقييماتي</button><button class="nav-item" data-view="ai" type="button"><svg aria-hidden="true" class="icon"><use href="#i-spark"></use></svg> المساعد الذكي</button><button class="nav-item" data-view="notifications" type="button"><svg aria-hidden="true" class="icon"><use href="#i-alert"></use></svg> الإشعارات</button><button class="nav-item" data-view="profile" type="button"><svg aria-hidden="true" class="icon"><use href="#i-users"></use></svg> حسابي</button><button class="nav-item" data-view="notifsettings" type="button"><svg aria-hidden="true" class="icon"><use href="#i-settings"></use></svg> إعدادات الإشعارات</button></div>
<button class="primary permission-btn" data-action="permissions" type="button"><svg class="icon"><use href="#i-shield"></use></svg> صلاحيات النظام</button>
</nav><main aria-live="polite" class="main" id="main"><div class="hero"><div class="hero-title"><span class="hero-icon"><svg aria-hidden="true" class="icon"><use href="#i-users"></use></svg></span><div><h1>المعلومات الشخصية</h1><p class="subtitle">حدد بياناتك الرياضية وموقعك وميزانيتك للبحث المناسب.</p></div></div></div><div class="app-steps"><button class="app-step active" data-go="personal" type="button">المعلومات الشخصية</button><button class="app-step" data-go="activities" type="button">الأنشطة</button><button class="app-step" data-go="facilities" type="button">المرافق</button><button class="app-step" data-go="times" type="button">الأوقات</button></div><section class="panel app-panel"><div class="panel-head"><h2 class="panel-title">البيانات والتفضيلات</h2></div>
<form method="POST" action="{{ route('user.personal.store') }}">
    @csrf
<div class="app-form-grid"><div class="field"><label for="fld-location">المنطقة / الموقع</label><input id="fld-location" name="location" required="" type="text" value=""/></div><div class="field"><label for="fld-gender">الجنس</label><select id="fld-gender" name="gender"><option selected="" value="">اختر الجنس</option><option value="male">ذكر</option><option value="female">أنثى</option></select></div><div class="field"><label for="fld-height">الطول (سم)</label><input id="fld-height" max="260" min="50" name="height" step="0.1" type="number" value=""/></div><div class="field"><label for="fld-weight">الوزن (كغ)</label><input id="fld-weight" max="450" min="15" name="weight" step="0.1" type="number" value=""/></div><div class="field"><label for="fld-fitnessGoal">الهدف الرياضي</label><select id="fld-fitnessGoal" name="fitnessGoal"><option selected="" value="">اختر الهدف</option><option value="bulk">تضخيم</option><option value="cut">تنشيف</option><option value="fitness">لياقة عامة</option></select></div><div class="field"><label for="fld-maxBudget">الميزانية القصوى (دينار)</label><input id="fld-maxBudget" min="0" name="maxBudget" step="0.01" type="number" value=""/></div></div><div class="app-inline-actions"><button class="primary" type="submit"><svg aria-hidden="true" class="icon"><use href="#i-check"></use></svg> حفظ والمتابعة</button></div></form></section></main>
<div class="footer">OptiFit — نموذج واجهة تفاعلية. البيانات والإجراءات محفوظة محليًا في هذا المتصفح فقط؛ غير متصلة بسيرفر Laravel.</div>
<div class="modal-backdrop" id="backdrop" role="presentation"><section aria-labelledby="modalTitle" aria-modal="true" class="modal" role="dialog"><div class="modal-header"><h2 id="modalTitle">تفاصيل</h2><button aria-label="إغلاق" class="close-btn" data-action="close" type="button"><svg class="icon"><use href="#i-close"></use></svg></button></div><div class="modal-content" id="modalContent"></div></section></div>
<div class="toast" id="toast" role="status"><svg class="icon"><use href="#i-checkcircle"></use></svg><span id="toastText"></span></div>
<script defer="" src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</body>
</html>

