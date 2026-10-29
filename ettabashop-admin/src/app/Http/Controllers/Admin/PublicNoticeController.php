<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicNotice;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PublicNoticeController extends Controller
{
    use CommonFunctions;

    public function index()
    {
        $notices = Schema::hasTable('public_notices')
            ? PublicNotice::orderBy('id', 'desc')->get()
            : collect([]);

        return view('admin.notices.index', compact('notices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->UploadImage($request->file('image'), 'notices');
        }

        $isPublished = $request->has('is_published') ? 1 : 0;

        PublicNotice::create([
            'title' => $request->title,
            'summary' => $request->summary,
            'content' => $request->content,
            'image' => $imagePath,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now() : null,
        ]);

        return redirect()->back()->with('success', 'পাবলিক নোটিশ সফলভাবে তৈরি ও প্রকাশ করা হয়েছে!');
    }

    public function update(Request $request, $id)
    {
        $notice = PublicNotice::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $data = [
            'title' => $request->title,
            'summary' => $request->summary,
            'content' => $request->content,
            'is_published' => $request->has('is_published') ? 1 : 0,
        ];

        if ($data['is_published'] && !$notice->published_at) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $data['image'] = $this->UploadImage($request->file('image'), 'notices');
        }

        $notice->update($data);

        return redirect()->back()->with('success', 'নোটিশ সফলভাবে আপডেট করা হয়েছে!');
    }

    public function togglePublish($id)
    {
        $notice = PublicNotice::findOrFail($id);
        $notice->is_published = !$notice->is_published;
        if ($notice->is_published) {
            $notice->published_at = now();
        }
        $notice->save();

        $status = $notice->is_published ? 'প্রকাশিত (Published)' : 'অপ্রকাশিত (Draft)';
        return redirect()->back()->with('success', "নোটিশটি এখন {$status} অবস্থায় রয়েছে।");
    }

    public function destroy($id)
    {
        $notice = PublicNotice::findOrFail($id);
        $notice->delete();

        return redirect()->back()->with('success', 'নোটিশটি সফলভাবে মুছে ফেলা হয়েছে!');
    }
}

