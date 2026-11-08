<button class="apx-theme-backdrop" type="button" data-apx-theme-close aria-label="إغلاق إعدادات المظهر"></button>
<aside class="apx-theme-studio" data-apx-theme-studio role="dialog" aria-modal="true" aria-labelledby="apx-theme-title" aria-hidden="true">
    <div class="apx-studio-handle" aria-hidden="true"></div>
    <div class="apx-studio-head">
        <div><span class="apx-eyebrow">APEX VISUAL SYSTEM</span><h2 id="apx-theme-title">اختر طاقة الواجهة.</h2><p>14 ثيمًا من نظام OptiFit APEX.</p></div>
        <button class="apx-icon-button" type="button" data-apx-theme-close aria-label="إغلاق إعدادات المظهر"><svg><use href="#apx-close"/></svg></button>
    </div>
    <div class="apx-studio-preview"><div><span>ACTIVE THEME</span><strong data-apx-theme-name>أخضر كهربائي</strong><small>تغيير بصري لا يعدّل بيانات حسابك.</small></div><svg><use href="#apx-dumbbell"/></svg></div>
    <div class="apx-studio-content">
        <div class="apx-studio-label"><strong>الثيمات الجاهزة</strong><span>14 THEMES</span></div>
        <div class="apx-theme-grid" role="group" aria-label="اختيار ثيم الواجهة">
            @foreach ([
                ['volt','أخضر كهربائي','#c8ff46','VOLT'], ['glacier','أزرق جليدي','#66dfff','ICE'],
                ['ultraviolet','بنفسجي مستقبلي','#d2a4ff','ULTRA'], ['ember','برتقالي سينمائي','#ffab82','EMBER'],
                ['royal','ذهبي فاخر','#ffdd7b','ROYAL'], ['tidal','تركواز بحري','#63f4e0','TIDAL'],
                ['cobalt','أزرق كهربائي','#8eadff','COBALT'], ['sakura','وردي نيون','#ff9bce','SAKURA'],
                ['crimson','أحمر رياضي','#ff6d78','RED'], ['graphite','فضي كربوني','#e3eaf3','SILVER'],
                ['copper','نحاسي فاخر','#f2b881','COPPER'], ['solar','أصفر شمسي','#f8e869','SUN'],
                ['mint','نعناعي رياضي','#95ffc2','MINT'], ['fuchsia','فوشي مستقبلي','#ff91f5','FUCHSIA']
            ] as [$id,$label,$color,$short])
                <button type="button" class="apx-theme-swatch @if($id === 'volt') is-selected @endif" data-apx-theme="{{ $id }}" data-apx-theme-label="{{ $label }}" style="--swatch:{{ $color }}" aria-pressed="{{ $id === 'volt' ? 'true' : 'false' }}"><i></i><span>{{ $short }}</span></button>
            @endforeach
        </div>
    </div>
    <div class="apx-studio-footer"><button class="apx-reset-theme" type="button" data-apx-theme-reset>استعادة VOLT</button><button class="apx-primary" type="button" data-apx-theme-close>تم</button></div>
</aside>
