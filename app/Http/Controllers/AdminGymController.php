<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGymStatusRequest;
use App\Models\AuditLog;
use App\Models\Gym;
use App\Models\GymVerificationDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminGymController extends Controller
{
    private const REQUIRED_DOCUMENTS = [
        'profession_license',
        'establishment_certificate',
        'commercial_registration',
    ];

    public function index(Request $request): View
    {
        abort_unless((int) $request->user()->role_id === 3, 403);

        $status = $request->validate([
            'status' => [
                'nullable',
                Rule::in(['pending', 'approved', 'rejected']),
            ],
        ])['status'] ?? null;

        $gyms = Gym::query()
            ->with([
                'owner',
                'activities',
                'facilities',
                'memberships',
                'images',
            ])
            ->withCount(['reviews', 'favoritedByUsers'])
            ->withAvg('reviews', 'rating')
            ->when(
                $status,
                fn($query) => $query->where(
                    'verification_status',
                    $status
                )
            )
            ->latest('gym_id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'optifit.admin.gyms.index',
            compact('gyms', 'status')
        );
    }

    public function show(Request $request, Gym $gym): View
    {
        abort_unless((int) $request->user()->role_id === 3, 403);

        $gym->load([
            'owner',
            'activities',
            'facilities',
            'memberships',
            'workingHours',
            'images',
            'reviews.user',
            'verificationDocuments',
        ])
            ->loadCount('favoritedByUsers')
            ->loadAvg('reviews', 'rating');

        return view(
            'optifit.admin.gyms.show',
            compact('gym')
        );
    }

    /**
     * عرض الوثيقة للأدمن فقط.
     */
    public function showDocument(
        Request $request,
        Gym $gym,
        GymVerificationDocument $document
    ): BinaryFileResponse {

        // التأكد أن المستخدم أدمن.
        abort_unless(
            (int) $request->user()->role_id === 3,
            403
        );

        // التأكد أن الوثيقة تابعة للنادي المطلوب.
        abort_unless(
            (int) $document->gym_id === (int) $gym->gym_id,
            404
        );

        // التأكد من وجود الملف في التخزين الخاص.
        abort_unless(
            Storage::disk('local')->exists($document->file_path),
            404
        );

        // عرض الوثيقة دون نقلها إلى التخزين العام.
        return response()->file(
            Storage::disk('local')->path($document->file_path)
        );
    }


    public function updateDocumentStatus(
        Request $request,
        Gym $gym,
        GymVerificationDocument $document
    ): RedirectResponse {

        abort_unless(
            (int) $request->user()->role_id === 3,
            403
        );

        abort_unless(
            (int) $document->gym_id === (int) $gym->gym_id,
            404
        );

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['approved', 'rejected']),
            ],
            'rejection_reason' => [
                'nullable',
                'required_if:status,rejected',
                'string',
                'max:2000',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $gym,
            $document,
            $validated
        ): void {

            $oldStatus = $document->verification_status;
            $newStatus = $validated['status'];

            $document->update([
                'verification_status' => $newStatus,
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
                'rejection_reason' => $newStatus === 'rejected'
                    ? $validated['rejection_reason']
                    : null,
            ]);

            // إذا رُفضت وثيقة لنادٍ معتمد،
            // يُعاد النادي إلى قيد المراجعة.
            if (
                $newStatus === 'rejected'
                && $gym->verification_status === 'approved'
            ) {
                $gym->update([
                    'verification_status' => 'pending',
                ]);
            }

            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => "gym.document.{$oldStatus}.to.{$newStatus}",
                'entity_type' => 'gym',
                'entity_id' => $gym->gym_id,
            ]);
        });

        return back()->with(
            'success',
            'تم تحديث حالة الوثيقة بنجاح.'
        );
    }

    /**
     * اعتماد النادي أو رفضه أو إعادته إلى قيد المراجعة.
     */
    public function updateStatus(
        UpdateGymStatusRequest $request,
        Gym $gym
    ): RedirectResponse {

        $newStatus = $request->validated('status');

        // لا يمكن اعتماد النادي قبل اعتماد الوثائق الثلاثة.
        if ($newStatus === 'approved') {

            $approvedTypes = $gym->verificationDocuments()
                ->where('verification_status', 'approved')
                ->whereIn(
                    'document_type',
                    self::REQUIRED_DOCUMENTS
                )
                ->pluck('document_type')
                ->all();

            $missingDocuments = array_diff(
                self::REQUIRED_DOCUMENTS,
                $approvedTypes
            );

            if (!empty($missingDocuments)) {
                return back()->withErrors([
                    'status' => 'لا يمكن اعتماد النادي قبل الموافقة على الوثائق الثلاثة المطلوبة.',
                ]);
            }
        }

        $oldStatus = $gym->verification_status;

        DB::transaction(function () use (
            $request,
            $gym,
            $newStatus,
            $oldStatus
        ): void {

            $gym->update([
                'verification_status' => $newStatus,
            ]);

            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => "gym.status.{$oldStatus}.to.{$newStatus}",
                'entity_type' => 'gym',
                'entity_id' => $gym->gym_id,
            ]);
        });

        return back()->with(
            'success',
            'تم تحديث حالة اعتماد النادي.'
        );
    }
}
