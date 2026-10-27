<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Models\RewardApplication;
use App\Services\RewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardApplicationController extends Controller
{
    protected $rewardService;

    public function __construct(RewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }

    /**
     * Pending reward applications list
     */
    public function index(Request $request)
    {
        $query = RewardApplication::with(['user.profile', 'reward'])
            ->where('status', 'pending');

        if ($request->filled('account_type')) {
            $query->where('account_type', $request->account_type);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('application_no', 'like', "%{$s}%")
                    ->orWhere('user_name', 'like', "%{$s}%")
                    ->orWhere('user_phone', 'like', "%{$s}%")
                    ->orWhere('reward_name', 'like', "%{$s}%");
            });
        }

        $applications = $query->orderBy('id', 'desc')->paginate(20);

        return view('admin.reward_applications.index', [
            'applications' => $applications,
            'currentFilter' => $request->account_type ?? 'all',
        ]);
    }

    /**
     * All applications history (Accepted, Received, Rejected)
     */
    public function history(Request $request)
    {
        $query = RewardApplication::with(['user.profile', 'reward', 'processor'])
            ->where('status', '!=', 'pending');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('account_type')) {
            $query->where('account_type', $request->account_type);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('application_no', 'like', "%{$s}%")
                    ->orWhere('user_name', 'like', "%{$s}%")
                    ->orWhere('user_phone', 'like', "%{$s}%")
                    ->orWhere('reward_name', 'like', "%{$s}%");
            });
        }

        $applications = $query->orderBy('id', 'desc')->paginate(25);

        return view('admin.reward_applications.history', [
            'applications' => $applications,
            'currentStatus' => $request->status ?? 'all',
            'currentType' => $request->account_type ?? 'all',
        ]);
    }

    /**
     * Detailed application review
     */
    public function show($id)
    {
        $application = RewardApplication::with(['user.profile', 'reward', 'processor'])->findOrFail($id);

        $currentUserPoints = Point::where('user_id', $application->user_id)->value('amount') ?? 0;
        $profileCheck = $this->rewardService->checkProfileCompleteness($application->user);

        return view('admin.reward_applications.show', [
            'application' => $application,
            'currentUserPoints' => $currentUserPoints,
            'profileCheck' => $profileCheck,
        ]);
    }

    /**
     * Admin Accept: Deducts points and records collection details
     */
    public function accept(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'required|string|max:1000',
            'collection_date' => 'nullable|string|max:255',
            'collection_time' => 'nullable|string|max:255',
            'collection_place' => 'nullable|string|max:255',
        ]);

        try {
            $this->rewardService->acceptApplication(
                $id,
                Auth::user(),
                $request->admin_note,
                $request->collection_date,
                $request->collection_time,
                $request->collection_place
            );

            return redirect()->route('admin.rewardApplications.show', $id)
                ->with(['success' => 'আবেদনটি সফলভাবে গ্রহণ (Accept) করা হয়েছে এবং কাস্টমারের অ্যাকাউন্ট থেকে পয়েন্ট কর্তন সম্পন্ন হয়েছে!']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Admin Reject: Rejects request with note, no points deducted
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'required|string|max:1000',
        ]);

        try {
            $this->rewardService->rejectApplication($id, Auth::user(), $request->admin_note);

            return redirect()->route('admin.rewardApplications.show', $id)
                ->with(['success' => 'আবেদনটি সফলভাবে বাতিল (Reject) করা হয়েছে। কোনো পয়েন্ট কর্তন করা হয়নি।']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Admin Update Note / Collection Details (even after accepted)
     */
    public function updateDetails(Request $request, $id)
    {
        $app = RewardApplication::findOrFail($id);

        $request->validate([
            'admin_note' => 'nullable|string|max:1000',
            'collection_date' => 'nullable|string|max:255',
            'collection_time' => 'nullable|string|max:255',
            'collection_place' => 'nullable|string|max:255',
        ]);

        $app->update([
            'admin_note' => $request->admin_note,
            'collection_date' => $request->collection_date,
            'collection_time' => $request->collection_time,
            'collection_place' => $request->collection_place,
        ]);

        return redirect()->back()->with(['success' => 'পুরস্কার গ্রহণের তথ্য ও এডমিন নোট সফলভাবে আপডেট হয়েছে!']);
    }

    /**
     * Mark as Received
     */
    public function markReceived(Request $request, $id)
    {
        try {
            $this->rewardService->markAsReceived($id, Auth::user(), $request->admin_note);

            return redirect()->back()->with(['success' => 'পুরস্কারটি সফলভাবে হস্তান্তর (Received) হিসেবে চিহ্নিত করা হয়েছে!']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
