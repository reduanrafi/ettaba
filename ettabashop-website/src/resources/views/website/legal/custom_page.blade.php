@extends('website.layouts.layout')

@section('content')
<div class="container" style="max-width: 900px; margin: 40px auto 60px; padding: 0 15px;">
    <div style="background: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); padding: 35px 30px; border: 1px solid #e2e8f0;">
        <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 20px; border-bottom: 2px solid #22c55e; padding-bottom: 12px;">
            {{ $page->title }}
        </h1>
        
        <div style="font-size: 15px; line-height: 1.8; color: #334155; white-space: pre-wrap; font-family: inherit;">
            {!! nl2br(e($page->content)) !!}
        </div>
        
        <div style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #f1f5f9; font-size: 13px; color: #64748b; display: flex; justify-content: space-between;">
            <span>Ettaba Shop Limited</span>
            <span>সর্বশেষ আপডেট: {{ $page->updated_at ? $page->updated_at->format('d M Y') : '' }}</span>
        </div>
    </div>
</div>
@endsection

