@extends('layouts.optifit-apex')

@section('title', 'مراجعة '.$gym->gym_name.' | OptiFit APEX')
@section('eyebrow', 'ADMIN / REVIEW #'.$gym->gym_id)
@section('page-heading', $gym->gym_name)

@section('actions')
<a class="apx-secondary" href="{{ route('admin.gyms.index') }}">
    قائمة الطلبات
</a>
@endsection

@section('content')
@php
$documentLabels = [
'profession_license' => 'رخصة المهن',
'establishment_certificate' => 'شهادة تسجيل المنشأة',
'commercial_registration' => 'السجل التجاري',
];

$documentStatuses = [
'pending' => 'قيد المراجعة',
'approved' => 'تمت الموافقة',
'rejected' => 'مرفوضة',
];

$documentsByType = $gym->verificationDocuments->keyBy('document_type');

$allDocumentsApproved = collect(array_keys($documentLabels))
->every(function ($type) use ($documentsByType) {
return $documentsByType->get($type)?->verification_status === 'approved';
});
@endphp

<section class="apx-page-hero">
    <div>
        <span class="apx-eyebrow">VERIFICATION WORKSPACE</span>
        <h1>راجع. تحقق. <em>قرر.</em></h1>
        <p>
            راجع بيانات النادي والوثائق الرسمية الثلاثة قبل اعتماد النادي
            ونشره للمستخدمين.
        </p>
    </div>

    <span class="apx-status-chip {{ $gym->verification_status }}">
        {{ [
            'pending' => 'قيد المراجعة',
            'approved' => 'معتمد',
            'rejected' => 'مرفوض',
        ][$gym->verification_status] ?? $gym->verification_status }}
    </span>
</section>

@if(session('success'))
<div class="apx-panel" style="margin-bottom:16px">
    <div class="apx-panel-body">
        {{ session('success') }}
    </div>
</div>
@endif

@if($errors->any())
<div class="apx-panel" style="margin-bottom:16px">
    <div class="apx-panel-body">
        <strong>يرجى مراجعة الأخطاء التالية:</strong>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="apx-stat-strip">
    <article class="apx-mini-stat">
        <strong>{{ $gym->activities->count() }}</strong>
        <span>نشاط</span>
    </article>

    <article class="apx-mini-stat">
        <strong>{{ $gym->facilities->count() }}</strong>
        <span>مرفق</span>
    </article>

    <article class="apx-mini-stat">
        <strong>{{ $gym->memberships->count() }}</strong>
        <span>اشتراك</span>
    </article>

    <article class="apx-mini-stat">
        <strong>{{ $gym->images->count() }}</strong>
        <span>صورة</span>
    </article>
</div>

<div class="apx-detail-grid">
    <section class="apx-panel">
        <header class="apx-panel-head">
            <div>
                <h2>بيانات الطلب</h2>
            </div>
        </header>

        <div class="apx-panel-body">
            <dl class="apx-detail-list">
                <div>
                    <dt>المالك</dt>
                    <dd>
                        {{ $gym->owner?->name }}
                        <br>
                        <small dir="ltr">{{ $gym->owner?->email }}</small>
                    </dd>
                </div>

                <div>
                    <dt>العنوان</dt>
                    <dd>
                        {{ collect([
                            $gym->city,
                            $gym->area,
                            $gym->address
                        ])->filter()->join('، ') }}
                    </dd>
                </div>

                <div>
                    <dt>الهاتف</dt>
                    <dd dir="ltr">{{ $gym->phone ?: '—' }}</dd>
                </div>

                <div>
                    <dt>الموقع</dt>
                    <dd dir="ltr">
                        {{ $gym->latitude && $gym->longitude
                            ? $gym->latitude.', '.$gym->longitude
                            : 'غير محدد' }}
                    </dd>
                </div>
            </dl>

            <h3>الوصف</h3>
            <p>{{ $gym->description ?: 'لا يوجد وصف.' }}</p>

            <h3>الأنشطة</h3>
            <div class="apx-chips">
                @forelse($gym->activities as $item)
                <span class="apx-chip">
                    {{ $item->activity_name }}
                </span>
                @empty
                —
                @endforelse
            </div>

            <h3>المرافق</h3>
            <div class="apx-chips">
                @forelse($gym->facilities as $item)
                <span class="apx-chip">
                    {{ $item->facility_name }}
                </span>
                @empty
                —
                @endforelse
            </div>
        </div>
    </section>

    <aside>
        <section class="apx-panel">
            <header class="apx-panel-head">
                <div>
                    <h2>قرار المراجعة</h2>
                    <p>اعتماد النادي يتطلب الموافقة على الوثائق الثلاثة.</p>
                </div>
            </header>

            <div class="apx-panel-body">
                @unless($allDocumentsApproved)
                <p>
                    لا يمكن اعتماد النادي حاليًا.
                    يجب مراجعة الوثائق الرسمية الثلاثة والموافقة عليها أولًا.
                </p>
                @endunless

                <form
                    method="POST"
                    action="{{ route('admin.gyms.status', $gym) }}"
                    data-confirm="اعتماد هذا النادي ونشره للمستخدمين؟">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="approved">

                    <button
                        class="apx-primary"
                        type="submit"
                        style="width:100%"
                        @disabled(!$allDocumentsApproved)>
                        اعتماد النادي
                    </button>
                </form>

                <form
                    method="POST"
                    action="{{ route('admin.gyms.status', $gym) }}"
                    data-confirm="رفض هذا النادي وإخفاؤه؟"
                    style="margin-top:10px">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="rejected">

                    <button
                        class="apx-danger"
                        type="submit"
                        style="width:100%">
                        رفض النادي
                    </button>
                </form>

                <form
                    method="POST"
                    action="{{ route('admin.gyms.status', $gym) }}"
                    style="margin-top:10px">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="pending">

                    <button
                        class="apx-secondary"
                        type="submit"
                        style="width:100%">
                        إعادة إلى الانتظار
                    </button>
                </form>
            </div>
        </section>
    </aside>
</div>

<section class="apx-panel" style="margin-top:20px">
    <header class="apx-panel-head">
        <div>
            <h2>التحقق من الوثائق الرسمية</h2>
            <p>
                يجب مراجعة رخصة المهن وشهادة تسجيل المنشأة
                والسجل التجاري بشكل منفصل.
            </p>
        </div>
    </header>

    <div class="apx-panel-body">
        @foreach($documentLabels as $type => $label)
        @php
        $document = $documentsByType->get($type);
        @endphp

        <article style="padding:18px 0; border-bottom:1px solid #8884">
            <h3>{{ $label }}</h3>

            @if($document)
            <p>
                الحالة:
                <span class="apx-status-chip {{ $document->verification_status }}">
                    {{ $documentStatuses[$document->verification_status]
                                ?? $document->verification_status }}
                </span>
            </p>

            <p>
                <a
                    class="apx-secondary"
                    href="{{ route('admin.gyms.documents.show', [
                                'gym' => $gym,
                                'document' => $document->document_id
                            ]) }}"
                    target="_blank"
                    rel="noopener noreferrer">
                    عرض الوثيقة
                </a>
            </p>

            @if($document->reviewed_at)
            <p>
                آخر مراجعة:
                {{ $document->reviewed_at->format('Y-m-d H:i') }}
            </p>
            @endif

            @if($document->rejection_reason)
            <p>
                سبب الرفض:
                {{ $document->rejection_reason }}
            </p>
            @endif

            <form
                method="POST"
                action="{{ route('admin.gyms.documents.status', [
                            'gym' => $gym,
                            'document' => $document->document_id
                        ]) }}"
                style="margin-top:12px">
                @csrf
                @method('PATCH')

                <input
                    type="hidden"
                    name="status"
                    value="approved">

                <button class="apx-primary" type="submit">
                    الموافقة على الوثيقة
                </button>
            </form>

            <form
                method="POST"
                action="{{ route('admin.gyms.documents.status', [
                            'gym' => $gym,
                            'document' => $document->document_id
                        ]) }}"
                style="margin-top:12px">
                @csrf
                @method('PATCH')

                <input
                    type="hidden"
                    name="status"
                    value="rejected">

                <label
                    for="rejection_reason_{{ $document->document_id }}">
                    سبب رفض الوثيقة
                </label>

                <textarea
                    id="rejection_reason_{{ $document->document_id }}"
                    name="rejection_reason"
                    rows="3"
                    maxlength="2000"
                    required
                    style="display:block;width:100%;margin:8px 0"
                    placeholder="اكتب سبب الرفض هنا"></textarea>

                <button class="apx-danger" type="submit">
                    رفض الوثيقة
                </button>
            </form>
            @else
            <p>
                لم يتم رفع هذه الوثيقة لهذا النادي.
            </p>
            @endif
        </article>
        @endforeach
    </div>
</section>

@if($gym->images->isNotEmpty())
<section class="apx-panel" style="margin-top:20px">
    <header class="apx-panel-head">
        <div>
            <h2>صور النادي</h2>
        </div>
    </header>

    <div class="apx-panel-body apx-image-grid">
        @foreach($gym->images as $image)
        <article class="apx-image-card">
            <img
                src="{{ asset('storage/'.$image->image_path) }}"
                alt="صورة {{ $gym->gym_name }}"
                loading="lazy">
        </article>
        @endforeach
    </div>
</section>
@endif
@endsection