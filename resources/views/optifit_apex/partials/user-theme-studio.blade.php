<button class="theme-backdrop" type="button" data-theme-close aria-label="إغلاق إعدادات المظهر"></button>
<aside class="theme-studio" data-theme-studio role="dialog" aria-modal="true" aria-labelledby="theme-title" aria-hidden="true">
    <div class="studio-handle" aria-hidden="true"></div>
    <div class="studio-head">
        <div><span class="eyebrow">APEX VISUAL SYSTEM</span><h2 id="theme-title">اختر طاقة الواجهة.</h2><p>14 ثيمًا موحدًا في جميع صفحات المستخدم.</p></div>
        <button class="dialog-close" type="button" data-theme-close aria-label="إغلاق إعدادات المظهر"><svg aria-hidden="true"><use href="#apx-close"/></svg></button>
    </div>
    <div class="studio-preview"><div><span>ACTIVE THEME</span><strong data-theme-name>أخضر كهربائي</strong><small>تغيير بصري لا يعدّل بيانات حسابك.</small></div><svg aria-hidden="true"><use href="#apx-dumbbell"/></svg></div>
    <div class="studio-content">
        <div class="studio-label"><strong>الثيمات الجاهزة</strong><span>14 THEMES</span></div>
        <div class="theme-grid" role="group" aria-label="اختيار ثيم الواجهة">
            @foreach ([
                ['volt','أخضر كهربائي','#c8ff46','VOLT'], ['glacier','أزرق جليدي','#66dfff','ICE'],
                ['ultraviolet','بنفسجي مستقبلي','#d2a4ff','ULTRA'], ['ember','برتقالي سينمائي','#ffab82','EMBER'],
                ['royal','ذهبي فاخر','#ffdd7b','ROYAL'], ['tidal','تركواز بحري','#63f4e0','TIDAL'],
                ['cobalt','أزرق كهربائي','#8eadff','COBALT'], ['sakura','وردي نيون','#ff9bce','SAKURA'],
                ['crimson','أحمر رياضي','#ff6d78','RED'], ['graphite','فضي كربوني','#e3eaf3','SILVER'],
                ['copper','نحاسي فاخر','#f2b881','COPPER'], ['solar','أصفر شمسي','#f8e869','SUN'],
                ['mint','نعناعي رياضي','#95ffc2','MINT'], ['fuchsia','فوشي مستقبلي','#ff91f5','FUCHSIA']
            ] as [$id,$label,$color,$short])
                <button type="button" class="theme-swatch {{ $id === 'volt' ? 'is-selected' : '' }}" data-theme="{{ $id }}" data-theme-label="{{ $label }}" style="--swatch:{{ $color }}" aria-pressed="{{ $id === 'volt' ? 'true' : 'false' }}"><i></i><span>{{ $short }}</span></button>
            @endforeach
        </div>
    </div>
    <div class="studio-footer"><button class="reset-theme" type="button" data-theme-reset>استعادة VOLT</button><button class="primary-button" type="button" data-theme-close>تم</button></div>
</aside>
