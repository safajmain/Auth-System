@extends('layouts.optifit-user')
@section('title', 'إعدادات الحساب | OptiFit APEX')
@section('eyebrow', 'USER / ACCOUNT')
@section('page-heading', 'إعدادات الحساب')
@section('content')
<section class="apx-page-hero"><div><span class="apx-eyebrow">ACCOUNT CONTROL</span><h1>حسابك، <em>بأمان.</em></h1><p>حدّث الاسم والبريد وكلمة المرور عبر Laravel Breeze نفسه، دون تغيير نظام المصادقة.</p></div></section>
<div class="profile-settings-grid">
    <section class="apx-panel">
        <header class="apx-panel-head"><div><h2>معلومات الحساب</h2><p>الاسم والبريد الإلكتروني المرتبطان بتسجيل الدخول.</p></div><svg aria-hidden="true"><use href="#apx-user"/></svg></header>
        <div class="apx-panel-body">
            <form method="POST" action="{{ route('profile.update') }}" data-apx-submit>
                @csrf @method('PATCH')
                <div class="apx-grid-2">
                    <div class="apx-field apx-span-all @error('name') has-error @enderror"><label for="name">الاسم</label><input id="name" name="name" type="text" required autofocus autocomplete="name" maxlength="255" value="{{ old('name', $user->name) }}">@error('name')<p class="apx-error">{{ $message }}</p>@enderror</div>
                    <div class="apx-field apx-span-all @error('email') has-error @enderror"><label for="email">البريد الإلكتروني</label><input id="email" name="email" type="email" required autocomplete="username" maxlength="255" value="{{ old('email', $user->email) }}">@error('email')<p class="apx-error">{{ $message }}</p>@enderror</div>
                </div>
                @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())<p class="apx-help">البريد غير موثّق. استخدم نموذج إعادة إرسال رابط التوثيق أدناه.</p>@endif
                <div class="apx-form-actions"><button class="apx-primary" type="submit"><span data-submit-label>حفظ معلومات الحساب</span></button></div>
            </form>
            @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <form method="POST" action="{{ route('verification.send') }}">@csrf<button class="apx-secondary" type="submit">إعادة إرسال رابط التوثيق</button></form>
            @endif
        </div>
    </section>
    <section class="apx-panel">
        <header class="apx-panel-head"><div><h2>تغيير كلمة المرور</h2><p>استخدم كلمة مرور طويلة وفريدة لحماية الحساب.</p></div><svg aria-hidden="true"><use href="#apx-lock"/></svg></header>
        <form class="apx-panel-body" method="POST" action="{{ route('password.update') }}" data-apx-submit>
            @csrf @method('PUT')
            <div class="apx-grid-2">
                <div class="apx-field apx-span-all"><label for="update_password_current_password">كلمة المرور الحالية</label><input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password">@if($errors->updatePassword->has('current_password'))<p class="apx-error">{{ $errors->updatePassword->first('current_password') }}</p>@endif</div>
                <div class="apx-field"><label for="update_password_password">كلمة المرور الجديدة</label><input id="update_password_password" name="password" type="password" autocomplete="new-password">@if($errors->updatePassword->has('password'))<p class="apx-error">{{ $errors->updatePassword->first('password') }}</p>@endif</div>
                <div class="apx-field"><label for="update_password_password_confirmation">تأكيد كلمة المرور</label><input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">@if($errors->updatePassword->has('password_confirmation'))<p class="apx-error">{{ $errors->updatePassword->first('password_confirmation') }}</p>@endif</div>
            </div>
            <div class="apx-form-actions"><button class="apx-primary" type="submit"><span data-submit-label>تحديث كلمة المرور</span></button></div>
        </form>
    </section>
    <section class="apx-panel apx-danger-zone">
        <header class="apx-panel-head"><div><h2>حذف الحساب</h2><p>إجراء نهائي يحذف الحساب وبياناته وفق العلاقات الموجودة في قاعدة البيانات.</p></div><svg aria-hidden="true"><use href="#apx-alert"/></svg></header>
        <form class="apx-panel-body" method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('هذا الإجراء نهائي. هل تريد حذف حسابك فعلًا؟')">
            @csrf @method('DELETE')
            <div class="apx-field"><label for="delete_account_password">كلمة المرور الحالية للتأكيد</label><input id="delete_account_password" name="password" type="password" autocomplete="current-password">@if($errors->userDeletion->has('password'))<p class="apx-error">{{ $errors->userDeletion->first('password') }}</p>@endif</div>
            <div class="apx-form-actions"><button class="apx-danger" type="submit">حذف الحساب نهائيًا</button></div>
        </form>
    </section>
</div>
@endsection
