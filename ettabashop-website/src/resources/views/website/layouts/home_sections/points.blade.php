<!-- Logged-in Customer 6-Card Account Stats Grid (Section 4) -->
<div class="ettaba-account-stats-wrap bg-white p-2 border shadow-sm" style="border-radius: 14px;">
    <div class="row no-gutters">
        <!-- 1. ই-ব্যালেন্স -->
        <div class="col-4 p-1">
            <a href="{{ route('user.profile') }}" class="ettaba-stat-card stat-green text-decoration-none">
                <div class="ettaba-stat-icon icon-green">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="ettaba-stat-body">
                    <div class="ettaba-stat-lbl">ই-ব্যালেন্স</div>
                    <div class="ettaba-stat-val val-green">
                        {{ number_format($statistics['totalEarning'] ?? 0, 2) }} <small>টাকা</small>
                    </div>
                </div>
                <i class="fas fa-chevron-right ettaba-stat-arrow"></i>
            </a>
        </div>

        <!-- 2. পেন্ডিং ব্যালেন্স -->
        <div class="col-4 p-1">
            <a href="{{ route('user.profile') }}" class="ettaba-stat-card stat-red text-decoration-none">
                <div class="ettaba-stat-icon icon-red">
                    <i class="far fa-clock"></i>
                </div>
                <div class="ettaba-stat-body">
                    <div class="ettaba-stat-lbl">পেন্ডিং ব্যালেন্স</div>
                    <div class="ettaba-stat-val val-red">
                        {{ number_format($statistics['pendingEarning'] ?? 0, 2) }} <small>টাকা</small>
                    </div>
                </div>
                <i class="fas fa-chevron-right ettaba-stat-arrow"></i>
            </a>
        </div>

        <!-- 3. মোট পয়েন্ট -->
        <div class="col-4 p-1">
            <a href="{{ route('profile.rewards') }}" class="ettaba-stat-card stat-amber text-decoration-none">
                <div class="ettaba-stat-icon icon-green">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="ettaba-stat-body">
                    <div class="ettaba-stat-lbl">মোট পয়েন্ট</div>
                    <div class="ettaba-stat-val val-green">
                        {{ number_format($statistics['totalPoint'] ?? 0, 2) }}
                    </div>
                </div>
                <i class="fas fa-chevron-right ettaba-stat-arrow"></i>
            </a>
        </div>

        <!-- 4. পেন্ডিং পয়েন্ট -->
        <div class="col-4 p-1">
            <a href="{{ route('user.profile') }}" class="ettaba-stat-card stat-purple text-decoration-none">
                <div class="ettaba-stat-icon icon-purple">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="ettaba-stat-body">
                    <div class="ettaba-stat-lbl">পেন্ডিং পয়েন্ট</div>
                    <div class="ettaba-stat-val val-red">
                        {{ number_format($statistics['pendingPoint'] ?? 0, 2) }}
                    </div>
                </div>
                <i class="fas fa-chevron-right ettaba-stat-arrow"></i>
            </a>
        </div>

        <!-- 5. মোট আয় -->
        <div class="col-4 p-1">
            <a href="{{ route('user.profile') }}" class="ettaba-stat-card stat-green text-decoration-none">
                <div class="ettaba-stat-icon icon-green">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="ettaba-stat-body">
                    <div class="ettaba-stat-lbl">মোট আয়</div>
                    <div class="ettaba-stat-val val-green">
                        {{ number_format(($statistics['totalEarning'] ?? 0) + ($statistics['totalWithdraw'] ?? 0), 2) }} <small>টাকা</small>
                    </div>
                </div>
                <i class="fas fa-chevron-right ettaba-stat-arrow"></i>
            </a>
        </div>

        <!-- 6. মোট উত্তোলন -->
        <div class="col-4 p-1">
            <a href="{{ route('profile.withdrawHistories') }}" class="ettaba-stat-card stat-orange text-decoration-none">
                <div class="ettaba-stat-icon icon-orange">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="ettaba-stat-body">
                    <div class="ettaba-stat-lbl">মোট উত্তোলন</div>
                    <div class="ettaba-stat-val val-red">
                        {{ number_format($statistics['totalWithdraw'] ?? 0, 2) }} <small>টাকা</small>
                    </div>
                </div>
                <i class="fas fa-chevron-right ettaba-stat-arrow"></i>
            </a>
        </div>
    </div>
</div>

<style>
    .ettaba-stat-card {
        display: flex;
        align-items: center;
        border-radius: 10px;
        padding: 8px 8px;
        height: 100%;
        border: 1px solid #e2e8f0;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .ettaba-stat-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .stat-green { background: #f0fdf4; border-color: #dcfce7; }
    .stat-red { background: #fff1f2; border-color: #ffe4e6; }
    .stat-amber { background: #fffbeb; border-color: #fef3c7; }
    .stat-purple { background: #faf5ff; border-color: #f3e8ff; }
    .stat-orange { background: #fff7ed; border-color: #ffedd5; }

    .ettaba-stat-icon {
        width: 30px;
        height: 30px;
        min-width: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        margin-right: 6px;
    }

    .icon-green { color: #15803d; }
    .icon-red { color: #dc2626; }
    .icon-purple { color: #7e22ce; }
    .icon-orange { color: #ea580c; }

    .ettaba-stat-body {
        flex-grow: 1;
        min-width: 0;
        line-height: 1.15;
    }

    .ettaba-stat-lbl {
        font-size: 11px;
        font-weight: 700;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 2px;
    }

    .ettaba-stat-val {
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ettaba-stat-val small {
        font-size: 10px;
        font-weight: 600;
    }

    .val-green { color: #15803d; }
    .val-red { color: #b91c1c; }

    .ettaba-stat-arrow {
        font-size: 10px;
        color: #64748b;
        margin-left: 3px;
    }

    @media (max-width: 480px) {
        .ettaba-stat-card {
            padding: 6px 5px;
        }
        .ettaba-stat-icon {
            width: 22px;
            height: 22px;
            min-width: 22px;
            font-size: 13px;
            margin-right: 4px;
        }
        .ettaba-stat-lbl {
            font-size: 9.5px;
        }
        .ettaba-stat-val {
            font-size: 11px;
        }
        .ettaba-stat-val small {
            font-size: 8.5px;
        }
        .ettaba-stat-arrow {
            font-size: 8px;
        }
    }
</style>
