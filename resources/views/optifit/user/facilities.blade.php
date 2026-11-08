@php
    $facilityOptions = [
        ['Swimming Pool', 'مسبح', 'مساحة للسباحة والتدريب', 'apx-target'], ['Sauna', 'ساونا', 'استشفاء واسترخاء', 'apx-star'],
        ['Parking', 'موقف سيارات', 'وصول أسهل للنادي', 'apx-pin'], ['Showers', 'دُش', 'مرافق ما بعد التمرين', 'apx-check'],
        ['Lockers', 'خزائن', 'حفظ المتعلقات بأمان', 'apx-lock'], ['Free Weights Area', 'منطقة أوزان حرة', 'مساحة تدريب متخصصة', 'apx-dumbbell'],
        ['Women-Only Section', 'قسم للسيدات', 'خصوصية أكبر للتمرين', 'apx-users'], ['Jacuzzi', 'جاكوزي', 'استرخاء بعد التمرين', 'apx-star'],
    ];
    $chosenFacilities = old('choices', $selectedFacilities ?? []);
@endphp
@include('optifit_apex.partials.onboarding-top', [
    'title' => 'المرافق المفضلة', 'step' => 3,
    'heading' => 'التفاصيل تصنع الفرق.',
    'description' => 'حدد المرافق المهمة لتجربتك، وسنعرض مدى تطابقها مع البيانات الحقيقية المسجلة لكل نادٍ.',
])
<section class="apx-form-card">
    <header class="apx-form-card-header"><div><span class="apx-eyebrow">FACILITY PREFERENCES</span><h2>ما المرافق التي تحتاجها؟</h2><p>اختر مرفقًا واحدًا على الأقل لإكمال المطابقة.</p></div><span class="apx-count">3 / 4</span></header>
    <form class="apx-form-card-body" method="POST" action="{{ route('user.facilities.store') }}" data-apx-submit>
        @csrf
        <fieldset style="border:0;padding:0;margin:0"><legend class="apx-muted" style="margin-bottom:16px;font-size:.82rem">المرافق المفضلة</legend><div class="apx-option-grid">
            @foreach($facilityOptions as [$value, $label, $hint, $icon])
                <div class="apx-choice"><input id="facility-{{ $loop->index }}" name="choices[]" type="checkbox" value="{{ $value }}" @checked(in_array($value, $chosenFacilities, true))><label for="facility-{{ $loop->index }}"><span><svg><use href="#{{ $icon }}"/></svg></span><span><strong>{{ $label }}</strong><small>{{ $hint }}</small></span></label></div>
            @endforeach
        </div></fieldset>
        @error('choices')<p class="apx-error" style="margin-top:12px">{{ $message }}</p>@enderror
        <div class="apx-form-actions"><a class="apx-secondary" href="{{ route('user.activities') }}">الخطوة السابقة</a><button class="apx-primary" type="submit"><span data-submit-label>حفظ والمتابعة</span><svg><use href="#apx-arrow"/></svg></button></div>
    </form>
</section>
@include('optifit_apex.partials.onboarding-bottom')
