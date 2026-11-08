@php
    $days = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
    $legacyDays = ['Ø§Ù„Ø£Ø­Ø¯'=>'الأحد','Ø§Ù„Ø§Ø«Ù†ÙŠÙ†'=>'الاثنين','Ø§Ù„Ø«Ù„Ø§Ø«Ø§Ø¡'=>'الثلاثاء','Ø§Ù„Ø£Ø±Ø¨Ø¹Ø§Ø¡'=>'الأربعاء','Ø§Ù„Ø®Ù…ÙŠØ³'=>'الخميس','Ø§Ù„Ø¬Ù…Ø¹Ø©'=>'الجمعة','Ø§Ù„Ø³Ø¨Øª'=>'السبت'];
@endphp
@include('optifit_apex.partials.onboarding-top', [
    'title' => 'الأوقات المتاحة', 'step' => 4,
    'heading' => 'اجعل التدريب يناسب يومك.',
    'description' => 'أضف فترات التدريب المناسبة لك. كل فترة تُحفظ في حسابك ويمكن حذفها بشكل مستقل.',
])
<section class="apx-form-card">
    <header class="apx-form-card-header"><div><span class="apx-eyebrow">TRAINING AVAILABILITY</span><h2>جدولك الأسبوعي</h2><p>أضف فترة واحدة على الأقل لإكمال إعداد الحساب.</p></div><span class="apx-count">4 / 4</span></header>
    <div class="apx-form-card-body">
        <form method="POST" action="{{ route('user.times.store') }}" data-apx-submit>
            @csrf
            <div class="apx-form-grid">
                <div class="apx-field apx-span-2 @error('day') has-error @enderror"><label for="day">اليوم</label><select id="day" name="day" required>@foreach($days as $day)<option value="{{ $day }}" @selected(old('day') === $day)>{{ $day }}</option>@endforeach</select>@error('day')<p class="apx-error">{{ $message }}</p>@enderror</div>
                <div class="apx-field @error('start') has-error @enderror"><label for="start">من الساعة</label><input id="start" name="start" type="time" required value="{{ old('start', '08:00') }}">@error('start')<p class="apx-error">{{ $message }}</p>@enderror</div>
                <div class="apx-field @error('end') has-error @enderror"><label for="end">إلى الساعة</label><input id="end" name="end" type="time" required value="{{ old('end', '10:00') }}">@error('end')<p class="apx-error">{{ $message }}</p>@enderror</div>
            </div>
            <div class="apx-form-actions"><a class="apx-secondary" href="{{ route('user.facilities') }}">الخطوة السابقة</a><button class="apx-primary" type="submit"><span data-submit-label>إضافة الفترة</span><svg><use href="#apx-plus"/></svg></button></div>
        </form>
        <div class="apx-time-list" aria-label="الفترات المحفوظة">
            @forelse($times as $time)
                <article class="apx-time-row"><strong>{{ $legacyDays[$time->day_of_week] ?? $time->day_of_week }}</strong><span>{{ substr($time->start_time, 0, 5) }}</span><span>{{ substr($time->end_time, 0, 5) }}</span><form method="POST" action="{{ route('user.times.destroy', $time->available_time_id) }}" onsubmit="return confirm('هل تريد حذف هذه الفترة؟')">@csrf @method('DELETE')<button class="apx-danger" type="submit" aria-label="حذف فترة {{ $legacyDays[$time->day_of_week] ?? $time->day_of_week }}"><svg><use href="#apx-trash"/></svg></button></form></article>
            @empty
                <div class="apx-empty">لم تضف أي فترة بعد. أضف أول وقت مناسب لك أعلاه.</div>
            @endforelse
        </div>
        @if($times->isNotEmpty())<div class="apx-form-actions"><span class="apx-muted">تم حفظ {{ $times->count() }} فترة في حسابك.</span><a class="apx-primary" href="{{ route('dashboard') }}">إكمال الإعداد والانتقال للرئيسية <svg><use href="#apx-arrow"/></svg></a></div>@endif
    </div>
</section>
@include('optifit_apex.partials.onboarding-bottom')
