@php
    $activityOptions = [
        ['Yoga', 'يوغا', 'مرونة وتوازن', 'apx-user'], ['Swimming', 'سباحة', 'تحمّل لكامل الجسم', 'apx-target'],
        ['Boxing', 'ملاكمة', 'قوة ورد فعل', 'apx-shield'], ['Weightlifting', 'رفع أثقال', 'بناء القوة والعضلات', 'apx-dumbbell'],
        ['Pilates', 'بيلاتس', 'ثبات وتحكم', 'apx-check'], ['Cardio', 'تمارين كارديو', 'رفع اللياقة والتحمّل', 'apx-clock'],
    ];
    $chosenActivities = old('choices', $selectedActivities ?? []);
@endphp
@include('optifit_apex.partials.onboarding-top', [
    'title' => 'الأنشطة المفضلة', 'step' => 2,
    'heading' => 'تدرّب بطريقتك.',
    'description' => 'اختر نشاطًا واحدًا على الأقل. سنستخدم اختياراتك لمطابقة الأندية المعتمدة التي توفر ما تحبه.',
])
<section class="apx-form-card">
    <header class="apx-form-card-header"><div><span class="apx-eyebrow">ACTIVITY PREFERENCES</span><h2>ما الأنشطة التي تفضلها؟</h2><p>يمكنك اختيار أكثر من نشاط وتعديل اختياراتك لاحقًا.</p></div><span class="apx-count">2 / 4</span></header>
    <form class="apx-form-card-body" method="POST" action="{{ route('user.activities.store') }}" data-apx-submit>
        @csrf
        <fieldset style="border:0;padding:0;margin:0"><legend class="apx-muted" style="margin-bottom:16px;font-size:.82rem">اختر نشاطًا واحدًا على الأقل</legend><div class="apx-option-grid">
            @foreach($activityOptions as [$value, $label, $hint, $icon])
                <div class="apx-choice"><input id="activity-{{ $loop->index }}" name="choices[]" type="checkbox" value="{{ $value }}" @checked(in_array($value, $chosenActivities, true))><label for="activity-{{ $loop->index }}"><span><svg><use href="#{{ $icon }}"/></svg></span><span><strong>{{ $label }}</strong><small>{{ $hint }}</small></span></label></div>
            @endforeach
        </div></fieldset>
        @error('choices')<p class="apx-error" style="margin-top:12px">{{ $message }}</p>@enderror
        <div class="apx-form-actions"><a class="apx-secondary" href="{{ route('user.personal') }}">الخطوة السابقة</a><button class="apx-primary" type="submit"><span data-submit-label>حفظ والمتابعة</span><svg><use href="#apx-arrow"/></svg></button></div>
    </form>
</section>
@include('optifit_apex.partials.onboarding-bottom')
