<!-- Logged-in Customer 6-Card Account Stats Grid (Section 4) -->
<div class="ettaba-account-stats-wrap bg-white p-2 border shadow-sm" style="border-radius: 14px;">
    <div class="row no-gutters">
        <!-- 1. ই-ব্যালেন্স -->
        <div class="col-4 p-1">
            <a href="{{ route('user.profile') }}" class="ettaba-stat-card stat-green text-decoration-none"
               style="display:flex; flex-direction:row; align-items:center; border-radius:10px; padding:7px 8px; height:100%; background:#f0fdf4; border:1px solid #dcfce7;">
                <div class="ettaba-stat-icon icon-green d-none d-md-flex" style="width:30px; height:30px; min-width:30px; border-radius:8px; align-items:center; justify-content:center; font-size:16px; margin-right:6px; color:#15803d;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="ettaba-stat-body" style="flex-grow:1; min-width:0; line-height:1.2;">
                    <div class="ettaba-stat-lbl" style="font-size:11px; font-weight:700; color:#1e293b; white-space:nowrap; margin-bottom:2px;">ই-ব্যালেন্স</div>
                    <div class="ettaba-stat-val val-green" style="font-size:12.5px; font-weight:800; color:#15803d; white-space:nowrap;">
                        {{ number_format($statistics['totalEarning'] ?? 0, 2) }} <small style="font-size:9.5px; font-weight:600;">টাকা</small>
                    </div>
                </div>
                <i class="fas fa-chevron-right ettaba-stat-arrow d-none d-sm-inline-block" style="font-size:9px; color:#64748b; margin-left:2px;"></i>
            </a>
        </div>

        <!-- 2. পেন্ডিং ব্যালেন্স -->
        <div class="col-4 p-1">
            <a href="{{ route('user.profile') }}" class="ettaba-stat-card stat-red text-decoration-none"
               style="display:flex; flex-direction:row; align-items:center; border-radius:10px; padding:7px 8px; height:100%; background:#fff1f2; border:1px solid #ffe4e6;">
                <div class="ettaba-stat-icon icon-red d-none d-md-flex" style="width:30px; height:30px; min-width:30px; border-radius:8px; align-items:center; justify-content:center; font-size:16px; margin-right:6px; color:#dc2626;">
                    <i class="far fa-clock"></i>
                </div>
                <div class="ettaba-stat-body" style="flex-grow:1; min-width:0; line-height:1.2;">
                    <div class="ettaba-stat-lbl" style="font-size:11px; font-weight:700; color:#1e293b; white-space:nowrap; margin-bottom:2px;">পেন্ডিং ব্যালেন্স</div>
                    <div class="ettaba-stat-val val-red" style="font-size:12.5px; font-weight:800; color:#b91c1c; white-space:nowrap;">
                        {{ number_format($statistics['pendingEarning'] ?? 0, 2) }} <small style="font-size:9.5px; font-weight:600;">টাকা</small>
                    </div>
                </div>
                <i class="fas fa-chevron-right ettaba-stat-arrow d-none d-sm-inline-block" style="font-size:9px; color:#64748b; margin-left:2px;"></i>
            </a>
        </div>

        <!-- 3. মোট পয়েন্ট -->
        <div class="col-4 p-1">
            <a href="{{ route('profile.rewards') }}" class="ettaba-stat-card stat-amber text-decoration-none"
               style="display:flex; flex-direction:row; align-items:center; border-radius:10px; padding:7px 8px; height:100%; background:#fffbeb; border:1px solid #fef3c7;">
                <div class="ettaba-stat-icon icon-green d-none d-md-flex" style="width:30px; height:30px; min-width:30px; border-radius:8px; align-items:center; justify-content:center; font-size:16px; margin-right:6px; color:#15803d;">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="ettaba-stat-body" style="flex-grow:1; min-width:0; line-height:1.2;">
                    <div class="ettaba-stat-lbl" style="font-size:11px; font-weight:700; color:#1e293b; white-space:nowrap; margin-bottom:2px;">মোট পয়েন্ট</div>
                    <div class="ettaba-stat-val val-green" style="font-size:12.5px; font-weight:800; color:#15803d; white-space:nowrap;">
                        {{ number_format($statistics['totalPoint'] ?? 0, 2) }}
                    </div>
                </div>
                <i class="fas fa-chevron-right ettaba-stat-arrow d-none d-sm-inline-block" style="font-size:9px; color:#64748b; margin-left:2px;"></i>
            </a>
        </div>

        <!-- 4. পেন্ডিং পয়েন্ট -->
        <div class="col-4 p-1">
            <a href="{{ route('user.profile') }}" class="ettaba-stat-card stat-purple text-decoration-none"
               style="display:flex; flex-direction:row; align-items:center; border-radius:10px; padding:7px 8px; height:100%; background:#faf5ff; border:1px solid #f3e8ff;">
                <div class="ettaba-stat-icon icon-purple d-none d-md-flex" style="width:30px; height:30px; min-width:30px; border-radius:8px; align-items:center; justify-content:center; font-size:16px; margin-right:6px; color:#7e22ce;">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="ettaba-stat-body" style="flex-grow:1; min-width:0; line-height:1.2;">
                    <div class="ettaba-stat-lbl" style="font-size:11px; font-weight:700; color:#1e293b; white-space:nowrap; margin-bottom:2px;">পেন্ডিং পয়েন্ট</div>
                    <div class="ettaba-stat-val val-red" style="font-size:12.5px; font-weight:800; color:#b91c1c; white-space:nowrap;">
                        {{ number_format($statistics['pendingPoint'] ?? 0, 2) }}
                    </div>
                </div>
                <i class="fas fa-chevron-right ettaba-stat-arrow d-none d-sm-inline-block" style="font-size:9px; color:#64748b; margin-left:2px;"></i>
            </a>
        </div>

        <!-- 5. মোট আয় -->
        <div class="col-4 p-1">
            <a href="{{ route('user.profile') }}" class="ettaba-stat-card stat-green text-decoration-none"
               style="display:flex; flex-direction:row; align-items:center; border-radius:10px; padding:7px 8px; height:100%; background:#f0fdf4; border:1px solid #dcfce7;">
                <div class="ettaba-stat-icon icon-green d-none d-md-flex" style="width:30px; height:30px; min-width:30px; border-radius:8px; align-items:center; justify-content:center; font-size:16px; margin-right:6px; color:#15803d;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="ettaba-stat-body" style="flex-grow:1; min-width:0; line-height:1.2;">
                    <div class="ettaba-stat-lbl" style="font-size:11px; font-weight:700; color:#1e293b; white-space:nowrap; margin-bottom:2px;">মোট আয়</div>
                    <div class="ettaba-stat-val val-green" style="font-size:12.5px; font-weight:800; color:#15803d; white-space:nowrap;">
                        {{ number_format(($statistics['totalEarning'] ?? 0) + ($statistics['totalWithdraw'] ?? 0), 2) }} <small style="font-size:9.5px; font-weight:600;">টাকা</small>
                    </div>
                </div>
                <i class="fas fa-chevron-right ettaba-stat-arrow d-none d-sm-inline-block" style="font-size:9px; color:#64748b; margin-left:2px;"></i>
            </a>
        </div>

        <!-- 6. মোট উত্তোলন -->
        <div class="col-4 p-1">
            <a href="{{ route('profile.withdrawHistories') }}" class="ettaba-stat-card stat-orange text-decoration-none"
               style="display:flex; flex-direction:row; align-items:center; border-radius:10px; padding:7px 8px; height:100%; background:#fff7ed; border:1px solid #ffedd5;">
                <div class="ettaba-stat-icon icon-orange d-none d-md-flex" style="width:30px; height:30px; min-width:30px; border-radius:8px; align-items:center; justify-content:center; font-size:16px; margin-right:6px; color:#ea580c;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="ettaba-stat-body" style="flex-grow:1; min-width:0; line-height:1.2;">
                    <div class="ettaba-stat-lbl" style="font-size:11px; font-weight:700; color:#1e293b; white-space:nowrap; margin-bottom:2px;">মোট উত্তোলন</div>
                    <div class="ettaba-stat-val val-red" style="font-size:12.5px; font-weight:800; color:#b91c1c; white-space:nowrap;">
                        {{ number_format($statistics['totalWithdraw'] ?? 0, 2) }} <small style="font-size:9.5px; font-weight:600;">টাকা</small>
                    </div>
                </div>
                <i class="fas fa-chevron-right ettaba-stat-arrow d-none d-sm-inline-block" style="font-size:9px; color:#64748b; margin-left:2px;"></i>
            </a>
        </div>
    </div>
</div>
