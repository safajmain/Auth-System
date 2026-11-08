@extends('layouts.optifit-user')
@section('title', 'مقارنة النوادي | OptiFit APEX')
@section('eyebrow', 'USER / COMPARE')
@section('page-heading', 'مقارنة النوادي')
@section('content')
<section class="apx-page-hero"><div><span class="apx-eyebrow">SIDE BY SIDE</span><h1>قارن قبل أن <em>تقرر.</em></h1><p>اختر حتى ثلاثة نوادٍ معتمدة وقارن السعر والتقييم والمرافق والأنشطة.</p></div></section>
<section class="apx-panel"><form class="apx-panel-body" method="GET" action="{{ route('gyms.compare') }}"><div class="apx-grid-3">@for($slot=0;$slot<3;$slot++)<div class="apx-field"><label for="gym-{{ $slot }}">النادي {{ $slot+1 }}</label><select id="gym-{{ $slot }}" name="gyms[]"><option value="">— اختر —</option>@foreach($availableGyms as $option)<option value="{{ $option->gym_id }}" @selected(($ids[$slot]??null)==$option->gym_id)>{{ $option->gym_name }}</option>@endforeach</select></div>@endfor</div><div class="apx-form-actions"><button class="apx-primary" type="submit"><svg><use href="#apx-compare"/></svg>عرض المقارنة</button></div></form></section>
@if($gyms->isNotEmpty())<section class="apx-panel"><div class="apx-table-wrap"><table class="apx-table apx-compare-table"><thead><tr><th>المعيار</th>@foreach($gyms as $gym)<th><a href="{{ route('gyms.show',$gym) }}">{{ $gym->gym_name }}</a></th>@endforeach</tr></thead><tbody>
<tr><th>الموقع</th>@foreach($gyms as $gym)<td>{{ $gym->city }}{{ $gym->area?' · '.$gym->area:'' }}</td>@endforeach</tr>
<tr><th>أقل سعر</th>@foreach($gyms as $gym)<td>{{ $gym->memberships_min_price_jod!==null?number_format($gym->memberships_min_price_jod,2).' د.أ':'غير محدد' }}</td>@endforeach</tr>
<tr><th>التقييم</th>@foreach($gyms as $gym)<td>{{ number_format((float)($gym->reviews_avg_rating??0),1) }} / 5</td>@endforeach</tr>
<tr><th>الأنشطة</th>@foreach($gyms as $gym)<td><div class="apx-chips">@forelse($gym->activities as $item)<span class="apx-chip">{{ $item->activity_name }}</span>@empty—@endforelse</div></td>@endforeach</tr>
<tr><th>المرافق</th>@foreach($gyms as $gym)<td><div class="apx-chips">@forelse($gym->facilities as $item)<span class="apx-chip">{{ $item->facility_name }}</span>@empty—@endforelse</div></td>@endforeach</tr>
</tbody></table></div></section>@endif
@endsection
