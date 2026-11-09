<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class GymOwnerRegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.gym-owner-register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],

            'gym_name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'area' => ['nullable', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'phone' => ['nullable', 'string', 'max:30'],
            'website_url' => ['nullable', 'url', 'max:255'],

            // الوثائق الثلاثة مطلوبة عند تسجيل النادي.
            'profession_license' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
            'establishment_certificate' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
            'commercial_registration' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ], [], [
            'profession_license' => 'رخصة المهن',
            'establishment_certificate' => 'شهادة تسجيل المنشأة',
            'commercial_registration' => 'السجل التجاري',
        ]);

        $documentTypes = [
            'profession_license',
            'establishment_certificate',
            'commercial_registration',
        ];

        $uploadedPaths = [];

        try {
            // إنشاء المالك والنادي وسجلات الوثائق ضمن معاملة واحدة.
            $user = DB::transaction(function () use (
                $request,
                $data,
                $documentTypes,
                &$uploadedPaths
            ): User {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);

                // role_id غير موجود ضمن fillable.
                $user->role_id = 2;
                $user->save();

                $gym = Gym::create([
                    'owner_id' => $user->id,
                    'gym_name' => $data['gym_name'],
                    'city' => $data['city'],
                    'area' => $data['area'] ?? null,
                    'address' => $data['address'],
                    'description' => $data['description'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'website_url' => $data['website_url'] ?? null,
                    'verification_status' => 'pending',
                ]);

                foreach ($documentTypes as $type) {
                    $path = $request->file($type)->store(
                        "gym-verification/{$gym->gym_id}",
                        'local'
                    );

                    if ($path === false) {
                        throw new \RuntimeException(
                            'تعذر حفظ وثيقة التحقق.'
                        );
                    }

                    $uploadedPaths[] = $path;

                    $gym->verificationDocuments()->create([
                        'document_type' => $type,
                        'file_path' => $path,
                        'verification_status' => 'pending',
                    ]);
                }

                return $user;
            });
        } catch (\Throwable $e) {
            // تنظيف الملفات إذا فشل إنشاء الحساب أو النادي.
            Storage::disk('local')->delete($uploadedPaths);

            throw $e;
        }

        // نفس خطوات التسجيل الأصلية.
        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect(route('dashboard', absolute: false));
    }
}
