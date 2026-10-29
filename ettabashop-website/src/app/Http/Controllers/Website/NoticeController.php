<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PublicNotice;
use App\Models\Slider;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Schema;

class NoticeController extends Controller
{
    /**
     * Public Notices & Notifications List Page (accessible to both guests and logged-in users)
     */
    public function index(Request $request)
    {
        $notices = collect([]);
        $userNotifications = collect([]);

        try {
            if (Schema::hasTable('public_notices')) {
                $notices = PublicNotice::where('is_published', 1)
                    ->orderBy('id', 'desc')
                    ->paginate(15);
            }
        } catch (\Throwable $e) {}

        try {
            if (Auth::check() && Schema::hasTable('user_notifications')) {
                $userNotifications = UserNotification::where('user_id', Auth::id())
                    ->orderBy('id', 'desc')
                    ->take(20)
                    ->get();
                UserNotification::where('user_id', Auth::id())
                    ->where('is_read', 0)
                    ->update(['is_read' => 1]);
            }
        } catch (\Throwable $e) {}

        $readIds = session('read_public_notices', []);
        if (!is_array($readIds)) {
            $readIds = [];
        }
        $cookieIds = json_decode($request->cookie('read_public_notices', '[]'), true);
        if (is_array($cookieIds)) {
            $readIds = array_unique(array_merge($readIds, $cookieIds));
        }

        return view('website.notices.index', [
            'notices' => $notices,
            'userNotifications' => $userNotifications,
            'readIds' => $readIds,
        ]);
    }

    /**
     * Public Notice Detail Page (marks the notice as read and shows full content)
     */
    public function show(Request $request, $id)
    {
        $notice = PublicNotice::where('id', $id)
            ->where('is_published', 1)
            ->firstOrFail();

        $readIds = session('read_public_notices', []);
        if (!is_array($readIds)) {
            $readIds = [];
        }
        $cookieIds = json_decode($request->cookie('read_public_notices', '[]'), true);
        if (is_array($cookieIds)) {
            $readIds = array_unique(array_merge($readIds, $cookieIds));
        }

        if (!in_array((int)$notice->id, $readIds)) {
            $readIds[] = (int)$notice->id;
        }
        session(['read_public_notices' => $readIds]);
        Cookie::queue('read_public_notices', json_encode(array_values($readIds)), 60 * 24 * 60);

        $recentNotices = PublicNotice::where('is_published', 1)
            ->where('id', '!=', $notice->id)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return view('website.notices.show', [
            'notice' => $notice,
            'recentNotices' => $recentNotices,
        ]);
    }

    /**
     * Promotional Banner / Offer Detail Page
     */
    public function offerDetail($id)
    {
        $slider = Slider::findOrFail($id);

        return view('website.notices.offer_detail', [
            'slider' => $slider,
        ]);
    }
}

