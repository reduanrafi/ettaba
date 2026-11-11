<!-- Logged-in Customer 6-Card Account Stats Grid (Section 4) -->
<div class="ettaba-account-stats-wrap bg-white p-2 border shadow-sm" style="border-radius: 12px; border-color: #e2e8f0;">
    <div class="row no-gutters">
        <!-- 1. ই-ব্যালেন্স -->
        <div class="col-4 p-1">
            <a href="{{ route('user.profile') }}" class="ettaba-stat-card text-decoration-none"
               style="display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; border-radius:8px; padding:8px 4px; height:100%; background:#f8fafc; border:1px solid #e2e8f0;">
                <div class="ettaba-stat-lbl" style="font-size:11px; font-weight:600; color:#475569; margin-bottom:3px; text-align:center; width:100%;">ই-ব্যালেন্স</div>
                <div class="ettaba-stat-val" style="font-size:12.5px; font-weight:700; color:#15803d; text-align:center; width:100%;">
                    {{ number_format($statistics['totalEarning'] ?? 0, 2) }} <small style="font-size:10px; font-weight:500;">টাকা</small>
                </div>
            </a>
        </div>

        <!-- 2. পেন্ডিং ব্যালেন্স -->
        <div class="col-4 p-1">
            <a href="{{ route('user.profile') }}" class="ettaba-stat-card text-decoration-none"
               style="display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; border-radius:8px; padding:8px 4px; height:100%; background:#f8fafc; border:1px solid #e2e8f0;">
                <div class="ettaba-stat-lbl" style="font-size:11px; font-weight:600; color:#475569; margin-bottom:3px; text-align:center; width:100%;">পেন্ডিং ব্যালেন্স</div>
                <div class="ettaba-stat-val" style="font-size:12.5px; font-weight:700; color:#dc2626; text-align:center; width:100%;">
                    {{ number_format($statistics['pendingEarning'] ?? 0, 2) }} <small style="font-size:10px; font-weight:500;">টাকা</small>
                </div>
            </a>
        </div>

        <!-- 3. মোট পয়েন্ট -->
        <div class="col-4 p-1">
            <a href="{{ route('profile.rewards') }}" class="ettaba-stat-card text-decoration-none"
               style="display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; border-radius:8px; padding:8px 4px; height:100%; background:#f8fafc; border:1px solid #e2e8f0;">
                <div class="ettaba-stat-lbl" style="font-size:11px; font-weight:600; color:#475569; margin-bottom:3px; text-align:center; width:100%;">মোট পয়েন্ট</div>
                <div class="ettaba-stat-val" style="font-size:12.5px; font-weight:700; color:#15803d; text-align:center; width:100%;">
                    {{ number_format($statistics['totalPoint'] ?? 0, 2) }}
                </div>
            </a>
        </div>

        <!-- 4. পেন্ডিং পয়েন্ট -->
        <div class="col-4 p-1">
            <a href="{{ route('user.profile') }}" class="ettaba-stat-card text-decoration-none"
               style="display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; border-radius:8px; padding:8px 4px; height:100%; background:#f8fafc; border:1px solid #e2e8f0;">
                <div class="ettaba-stat-lbl" style="font-size:11px; font-weight:600; color:#475569; margin-bottom:3px; text-align:center; width:100%;">পেন্ডিং পয়েন্ট</div>
                <div class="ettaba-stat-val" style="font-size:12.5px; font-weight:700; color:#dc2626; text-align:center; width:100%;">
                    {{ number_format($statistics['pendingPoint'] ?? 0, 2) }}
                </div>
            </a>
        </div>

        <!-- 5. মোট আয় -->
        <div class="col-4 p-1">
            <a href="{{ route('user.profile') }}" class="ettaba-stat-card text-decoration-none"
               style="display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; border-radius:8px; padding:8px 4px; height:100%; background:#f8fafc; border:1px solid #e2e8f0;">
                <div class="ettaba-stat-lbl" style="font-size:11px; font-weight:600; color:#475569; margin-bottom:3px; text-align:center; width:100%;">মোট আয়</div>
                <div class="ettaba-stat-val" style="font-size:12.5px; font-weight:700; color:#15803d; text-align:center; width:100%;">
                    {{ number_format(($statistics['totalEarning'] ?? 0) + ($statistics['totalWithdraw'] ?? 0), 2) }} <small style="font-size:10px; font-weight:500;">টাকা</small>
                </div>
            </a>
        </div>

        <!-- 6. মোট উত্তোলন -->
        <div class="col-4 p-1">
            <a href="{{ route('profile.withdrawHistories') }}" class="ettaba-stat-card text-decoration-none"
               style="display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; border-radius:8px; padding:8px 4px; height:100%; background:#f8fafc; border:1px solid #e2e8f0;">
                <div class="ettaba-stat-lbl" style="font-size:11px; font-weight:600; color:#475569; margin-bottom:3px; text-align:center; width:100%;">মোট উত্তোলন</div>
                <div class="ettaba-stat-val" style="font-size:12.5px; font-weight:700; color:#dc2626; text-align:center; width:100%;">
                    {{ number_format($statistics['totalWithdraw'] ?? 0, 2) }} <small style="font-size:10px; font-weight:500;">টাকা</small>
                </div>
            </a>
        </div>
    </div>
</div>
