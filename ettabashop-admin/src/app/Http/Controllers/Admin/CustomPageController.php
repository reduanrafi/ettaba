<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomPageController extends Controller
{
    public function index()
    {
        $pages = CustomPage::orderByDesc('id')->get();
        return view('admin.custom_pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.custom_pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:custom_pages,slug',
            'content' => 'required|string',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
        // Ensure uniqueness
        $orig = $slug;
        $i = 1;
        while (CustomPage::where('slug', $slug)->exists()) {
            $slug = $orig . '-' . $i++;
        }

        CustomPage::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'is_published' => $request->has('is_published') ? 1 : 0,
        ]);

        return redirect()->route('admin.customPages.index')->with('success', 'নতুন পেজ সফলভাবে তৈরি হয়েছে।');
    }

    public function edit($id)
    {
        $page = CustomPage::findOrFail($id);
        return view('admin.custom_pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = CustomPage::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:custom_pages,slug,' . $id,
            'content' => 'required|string',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);

        $page->update([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'is_published' => $request->has('is_published') ? 1 : 0,
        ]);

        return redirect()->route('admin.customPages.index')->with('success', 'পেজ সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroy($id)
    {
        $page = CustomPage::findOrFail($id);
        $page->delete();
        return redirect()->route('admin.customPages.index')->with('success', 'পেজ সফলভাবে মুছে ফেলা হয়েছে।');
    }
}

