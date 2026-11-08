@php
    $userNavigation = [
        [['dashboard'], 'dashboard', 'apx-home', 'الرئيسية'],
        [['gyms.index', 'gyms.show'], 'gyms.index', 'apx-search', 'البحث عن النوادي'],
        [['gyms.compare'], 'gyms.compare', 'apx-compare', 'المقارنة'],
        [['favorites.*'], 'favorites.index', 'apx-heart', 'المفضلة'],
        [['gyms.map'], 'gyms.map', 'apx-map', 'الخريطة'],
        [['assistant.*'], 'assistant.index', 'apx-spark', 'المساعد الذكي'],
        [['reminders.*'], 'reminders.index', 'apx-bell', 'التذكيرات'],
        [['user.personal*'], 'user.personal', 'apx-user', 'المعلومات الشخصية'],
        [['user.activities*'], 'user.activities', 'apx-target', 'الأنشطة المفضلة'],
        [['user.facilities*'], 'user.facilities', 'apx-check', 'المرافق المفضلة'],
        [['user.times*'], 'user.times', 'apx-clock', 'الأوقات المتاحة'],
        [['profile.*'], 'profile.edit', 'apx-settings', 'إعدادات الحساب'],
    ];
@endphp
<aside class="sidebar is-expanded" id="dashboard-sidebar" data-sidebar aria-label="التنقل الرئيسي">
    <div class="sidebar-head">
        <a class="sidebar-brand" href="{{ route('dashboard') }}" aria-label="OptiFit APEX - لوحة المستخدم">
            <span class="brand-mark"><svg aria-hidden="true"><use href="#apx-dumbbell"/></svg></span>
            <span class="brand-name">OPTI<span>FIT</span> <small>APEX</small></span>
        </a>
        <button class="sidebar-collapse" type="button" data-sidebar-collapse aria-label="طي القائمة" aria-expanded="true">
            <svg aria-hidden="true"><use href="#apx-chevron"/></svg>
        </button>
    </div>
    <p class="sidebar-label">YOUR SPACE / مساحتك</p>
    <nav class="sidebar-nav">
        @foreach($userNavigation as [$patterns, $routeName, $icon, $label])
            @php($active = request()->routeIs(...$patterns))
            <a class="sidebar-link {{ $active ? 'is-active' : '' }}" href="{{ route($routeName) }}" @if($active) aria-current="page" @endif>
                <svg aria-hidden="true"><use href="#{{ $icon }}"/></svg><span>{{ $label }}</span>
            </a>
        @endforeach
    </nav>
    <div class="sidebar-footer">
        <button class="sidebar-link palette-trigger" type="button" data-theme-open><svg aria-hidden="true"><use href="#apx-palette"/></svg><span>المظهر والألوان</span><i></i></button>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="sidebar-link logout-link" type="submit"><svg aria-hidden="true"><use href="#apx-logout"/></svg><span>تسجيل الخروج</span></button>
        </form>
    </div>
</aside>
