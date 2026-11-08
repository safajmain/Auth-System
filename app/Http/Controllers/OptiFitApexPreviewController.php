<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

/**
 * Design preview ONLY. The preview users/roles are simulated in JavaScript.
 * Do NOT use this controller as an authenticated admin/owner authorization layer.
 */
final class OptiFitApexPreviewController extends Controller
{
    public function index(): View
    {
        // Fictional sample gyms. Replace with your application's approved Gym query
        // only after confirming the actual database schema and authorization rules.
        $gyms = [
            ['id' => 1, 'name' => 'IRON CULT', 'area' => 'عبدون، عمّان', 'price' => 45,
             'rating' => 4.9, 'category' => 'قوة وكمال أجسام',
             'activities' => ['رفع أثقال', 'كارديو', 'تدريب شخصي'],
             'facilities' => ['ساونا', 'مواقف سيارات', 'مدرب شخصي', 'غرف تبديل'],
             'hours' => '06:00–23:00', 'lat' => 31.955, 'lng' => 35.895,
             'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=1100&q=82'],
            ['id' => 2, 'name' => 'ATHLETE LAB', 'area' => 'الرابية، عمّان', 'price' => 39,
             'rating' => 4.8, 'category' => 'لياقة وكروس فت',
             'activities' => ['كروس فت', 'كارديو', 'لياقة عامة'],
             'facilities' => ['حصص جماعية', 'كارديو', 'دُش'],
             'hours' => '07:00–22:00', 'lat' => 31.984, 'lng' => 35.883,
             'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=1100&q=82'],
            ['id' => 3, 'name' => 'FORM STUDIO', 'area' => 'خلدا، عمّان', 'price' => 32,
             'rating' => 4.7, 'category' => 'لياقة متكاملة',
             'activities' => ['سباحة', 'يوغا', 'كارديو'],
             'facilities' => ['مسبح', 'مواقف سيارات', 'غرف تبديل'],
             'hours' => '06:30–22:00', 'lat' => 31.996, 'lng' => 35.850,
             'image' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=1100&q=82'],
            ['id' => 4, 'name' => 'PULSE PERFORMANCE', 'area' => 'الصويفية، عمّان', 'price' => 52,
             'rating' => 4.9, 'category' => 'تدريب وأداء رياضي',
             'activities' => ['رفع أثقال', 'ملاكمة', 'تدريب شخصي'],
             'facilities' => ['ساونا', 'مدرب شخصي', 'دُش'],
             'hours' => '06:00–23:30', 'lat' => 31.962, 'lng' => 35.862,
             'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1100&q=82'],
        ];

        return view('optifit_apex.index', [
            'demoGyms' => $gyms,
            'currentUserName' => auth()->check() ? auth()->user()->name : null,
            'loginUrl' => Route::has('login') ? route('login') : url('/login'),
            'registerUrl' => Route::has('register') ? route('register') : url('/register'),
            'gymOwnerUrl' => url('/gym-owner/register'),
            'googleAuthUrl' => Route::has('google.redirect') ? route('google.redirect') : null,
        ]);
    }
}
