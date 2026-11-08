@include('optifit_apex.partials.onboarding-top', [
    'title' => 'المعلومات الشخصية', 'step' => 1,
    'heading' => 'ابدأ من بياناتك.',
    'description' => 'نستخدم موقعك وهدفك وميزانيتك لعرض أندية أقرب إلى احتياجاتك. يمكنك العودة وتعديل هذه المعلومات في أي وقت.',
])
<section class="apx-form-card">
    <header class="apx-form-card-header"><div><span class="apx-eyebrow">PERSONAL PROFILE</span><h2>المعلومات الأساسية</h2><p>جميع الحقول مطلوبة وتُحفظ في ملفك داخل قاعدة البيانات.</p></div><span class="apx-count">1 / 4</span></header>
    <form class="apx-form-card-body" method="POST" action="{{ route('user.personal.store') }}" data-apx-submit>
        @csrf
        <div class="apx-form-grid">
            <div class="apx-field apx-span-2 @error('location') has-error @enderror"><label for="location">المنطقة أو الموقع</label><input id="location" name="location" type="text" maxlength="255" required autocomplete="address-level2" placeholder="مثال: عمّان، الجبيهة" value="{{ old('location', $preference?->location) }}">@error('location')<p class="apx-error">{{ $message }}</p>@enderror</div>
            <div class="apx-field @error('gender') has-error @enderror"><label for="gender">الجنس</label><select id="gender" name="gender" required><option value="">اختر</option><option value="male" @selected(old('gender', $preference?->gender) === 'male')>ذكر</option><option value="female" @selected(old('gender', $preference?->gender) === 'female')>أنثى</option></select>@error('gender')<p class="apx-error">{{ $message }}</p>@enderror</div>
            <div class="apx-field @error('fitnessGoal') has-error @enderror"><label for="fitnessGoal">الهدف الرياضي</label><select id="fitnessGoal" name="fitnessGoal" required><option value="">اختر هدفك</option><option value="bulk" @selected(old('fitnessGoal', $preference?->fitness_goal) === 'bulk')>زيادة الكتلة العضلية</option><option value="cut" @selected(old('fitnessGoal', $preference?->fitness_goal) === 'cut')>خفض الدهون</option><option value="fitness" @selected(old('fitnessGoal', $preference?->fitness_goal) === 'fitness')>لياقة عامة</option></select>@error('fitnessGoal')<p class="apx-error">{{ $message }}</p>@enderror</div>
            <div class="apx-field @error('height') has-error @enderror"><label for="height">الطول (سم)</label><input id="height" name="height" type="number" min="50" max="260" step="0.1" required inputmode="decimal" value="{{ old('height', $preference?->height_cm) }}">@error('height')<p class="apx-error">{{ $message }}</p>@enderror</div>
            <div class="apx-field @error('weight') has-error @enderror"><label for="weight">الوزن (كغ)</label><input id="weight" name="weight" type="number" min="15" max="450" step="0.1" required inputmode="decimal" value="{{ old('weight', $preference?->weight_kg) }}">@error('weight')<p class="apx-error">{{ $message }}</p>@enderror</div>
            <div class="apx-field apx-span-2 @error('maxBudget') has-error @enderror"><label for="maxBudget">الميزانية الشهرية القصوى (دينار)</label><input id="maxBudget" name="maxBudget" type="number" min="0" step="0.01" required inputmode="decimal" value="{{ old('maxBudget', $preference?->max_budget) }}">@error('maxBudget')<p class="apx-error">{{ $message }}</p>@enderror</div>
        </div>
        <div class="apx-form-actions"><a class="apx-secondary" href="{{ route('dashboard') }}">العودة للرئيسية</a><button class="apx-primary" type="submit"><span data-submit-label>حفظ والمتابعة</span><svg><use href="#apx-arrow"/></svg></button></div>
    </form>
</section>
@include('optifit_apex.partials.onboarding-bottom')
