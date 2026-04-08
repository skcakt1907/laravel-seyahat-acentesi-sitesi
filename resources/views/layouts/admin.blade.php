<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Yönetim Paneli') — Marmaris Travel Center</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('tema/img/favicon.svg') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('tema/css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --admin-primary: #0066cc;
            --admin-primary-dark: #004999;
            --admin-secondary: #ff6b00;
            --admin-dark: #0b1d33;
            --admin-sidebar: #0f2440;
            --admin-text: #334155;
            --admin-text-light: #64748b;
            --admin-bg: #f1f5f9;
            --admin-border: #e2e8f0;
            --admin-white: #ffffff;
            --admin-radius: 12px;
            --admin-shadow: 0 1px 3px rgba(0,0,0,0.08);
            --admin-shadow-lg: 0 4px 20px rgba(0,0,0,0.1);
            --sidebar-width: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--admin-bg);
            color: var(--admin-text);
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--admin-sidebar);
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .sidebar-brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--admin-secondary), #ff8c33);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
        }

        .sidebar-brand-text {
            font-size: 17px;
            color: #fff;
            font-weight: 400;
        }

        .sidebar-brand-text strong {
            font-weight: 800;
            color: var(--admin-secondary);
        }

        .sidebar-nav {
            padding: 16px 0;
        }

        .sidebar-category {
            padding: 20px 24px 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 24px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.05);
            text-decoration: none;
        }

        .sidebar-link.active {
            color: #fff;
            background: rgba(0,102,204,0.15);
            border-left-color: var(--admin-secondary);
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }

        .sidebar-link .badge {
            margin-left: auto;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 50px;
        }

        /* ===== TOPBAR ===== */
        .admin-topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: 64px;
            background: var(--admin-white);
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            z-index: 999;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 22px;
            color: var(--admin-text);
            cursor: pointer;
        }

        .topbar-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--admin-dark);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-link {
            color: var(--admin-text-light);
            font-size: 14px;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .topbar-link:hover {
            color: var(--admin-primary);
            text-decoration: none;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--admin-dark);
            font-weight: 600;
            font-size: 14px;
        }

        .topbar-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--admin-primary);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
        }

        /* ===== MAIN CONTENT ===== */
        .admin-main {
            margin-left: var(--sidebar-width);
            padding: 88px 28px 28px;
            min-height: 100vh;
        }

        /* ===== CARDS ===== */
        .stat-card {
            background: var(--admin-white);
            border-radius: var(--admin-radius);
            padding: 24px;
            box-shadow: var(--admin-shadow);
            border: 1px solid var(--admin-border);
            transition: all 0.2s;
        }

        .stat-card:hover {
            box-shadow: var(--admin-shadow-lg);
            transform: translateY(-2px);
        }

        .stat-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 14px;
        }

        .stat-card h3 {
            font-size: 28px;
            font-weight: 800;
            color: var(--admin-dark);
            margin-bottom: 4px;
        }

        .stat-card p {
            font-size: 13px;
            color: var(--admin-text-light);
            margin: 0;
            font-weight: 500;
        }

        /* ===== TABLE ===== */
        .admin-table-card {
            background: var(--admin-white);
            border-radius: var(--admin-radius);
            box-shadow: var(--admin-shadow);
            border: 1px solid var(--admin-border);
            overflow: hidden;
        }

        .admin-table-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-table-header h5 {
            font-size: 16px;
            font-weight: 700;
            color: var(--admin-dark);
            margin: 0;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th {
            background: #f8fafc;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 700;
            color: var(--admin-text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--admin-border);
        }

        .admin-table td {
            padding: 14px 16px;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--admin-text);
        }

        .admin-table tr:hover td {
            background: #f8fafc;
        }

        .type-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }

        .type-badge.transfer {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .type-badge.activity {
            background: #fef3c7;
            color: #b45309;
        }

        /* ===== BUTTONS ===== */
        .btn-admin {
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-admin-primary {
            background: var(--admin-primary);
            color: #fff;
        }

        /* ===== ADMIN PAGE HERO (reusable) ===== */
        .admin-page-hero {
            background: linear-gradient(135deg, #0f2440 0%, #1e3a8a 50%, #6d28d9 100%);
            border-radius: 20px;
            padding: 24px 28px;
            color: #fff;
            position: relative;
            overflow: hidden;
            margin-bottom: 22px;
            box-shadow: 0 16px 50px rgba(15,36,64,0.22);
        }
        .admin-page-hero::before {
            content: ''; position: absolute; top: -80px; right: -80px;
            width: 240px; height: 240px;
            background: radial-gradient(circle, rgba(255,107,0,0.3), transparent 70%);
            border-radius: 50%;
        }
        .admin-page-hero::after {
            content: ''; position: absolute; bottom: -100px; left: -50px;
            width: 220px; height: 220px;
            background: radial-gradient(circle, rgba(16,185,129,0.2), transparent 70%);
            border-radius: 50%;
        }
        .admin-page-hero-inner {
            position: relative; z-index: 2;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 16px;
        }
        .admin-page-hero h1 {
            font-size: 22px; font-weight: 800; margin: 0;
            display: flex; align-items: center; gap: 12px;
        }
        .admin-page-hero h1 .ph-icon {
            width: 44px; height: 44px; border-radius: 12px;
            background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 20px;
        }
        .admin-page-hero .ph-sub { font-size: 13px; opacity: 0.75; margin-top: 4px; }
        .admin-page-hero-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .admin-page-hero-actions .btn-admin {
            background: rgba(255,255,255,0.95); color: #0f2440;
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
        }
        .admin-page-hero-actions .btn-admin:hover { background: #fff; color: #0f2440; transform: translateY(-1px); }
        .admin-page-hero-actions .btn-admin.alt { background: rgba(255,107,0,0.95); color: #fff; }
        .admin-page-hero-actions .btn-admin.alt:hover { background: #ff6b00; color: #fff; }

        /* ===== CUSTOM FILE INPUT (global) ===== */
        input[type="file"] {
            position: absolute !important;
            width: 1px; height: 1px; padding: 0; margin: -1px;
            overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0;
        }
        input[type="file"] + label,
        .file-drop {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 22px 24px;
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            color: #64748b;
            cursor: pointer;
            transition: all 0.25s ease;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-align: center;
        }
        input[type="file"] + label:hover,
        .file-drop:hover {
            border-color: var(--admin-primary);
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: var(--admin-primary);
            transform: translateY(-1px);
        }
        input[type="file"] + label::before,
        .file-drop::before {
            content: '\f093';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 22px;
            color: var(--admin-primary);
            display: inline-block;
        }
        input[type="file"] + label.has-file,
        .file-drop.has-file {
            border-style: solid;
            border-color: #10b981;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            color: #065f46;
        }
        input[type="file"] + label.has-file::before,
        .file-drop.has-file::before {
            content: '\f00c';
            color: #10b981;
        }

        .btn-admin-primary:hover {
            background: var(--admin-primary-dark);
            color: #fff;
            text-decoration: none;
        }

        .btn-admin-success {
            background: #10b981;
            color: #fff;
        }

        .btn-admin-success:hover {
            background: #059669;
            color: #fff;
            text-decoration: none;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.open {
                transform: translateX(0);
            }

            .admin-topbar {
                left: 0;
            }

            .admin-main {
                margin-left: 0;
            }

            .topbar-toggle {
                display: block;
            }
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 999;
        }

        .sidebar-overlay.show {
            display: block;
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <span class="sidebar-brand-icon"><i class="fas fa-plane-departure"></i></span>
            <span class="sidebar-brand-text">Travel Center <strong>Marmaris</strong></span>
        </a>

        <nav class="sidebar-nav">
            <div class="sidebar-category">Ana Menü</div>

            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Kontrol Paneli
            </a>

            <a href="{{ route('admin.transfers.index') }}" class="sidebar-link {{ request()->routeIs('admin.transfers.*') ? 'active' : '' }}">
                <i class="fas fa-shuttle-van"></i> Transferler
            </a>

            <a href="{{ route('admin.activities.index') }}" class="sidebar-link {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
                <i class="fas fa-star"></i> Aktiviteler
            </a>

            <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Müşteriler
                <span class="badge bg-primary text-white" id="sidebarCustomerBadge" style="display:none;">0</span>
            </a>

            <a href="{{ route('admin.contacts.index') }}" class="sidebar-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i> Mesajlar
                <span class="badge bg-danger text-white" id="sidebarMsgBadge" style="display:none;">0</span>
            </a>

            <a href="{{ route('admin.reviews.index') }}" class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="fas fa-comment-dots"></i> Yorumlar
                <span class="badge bg-warning text-dark" id="sidebarReviewBadge" style="display:none;">0</span>
            </a>

            <div class="sidebar-category">İçerik</div>

            <a href="{{ route('admin.slider.index') }}" class="sidebar-link {{ request()->routeIs('admin.slider.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i> Slider
            </a>

            <div class="sidebar-category">Sistem</div>

            <a href="{{ route('admin.earnings.index') }}" class="sidebar-link {{ request()->routeIs('admin.earnings.*') ? 'active' : '' }}">
                <i class="fas fa-coins"></i> Kazançlarım
            </a>

            <a href="{{ route('admin.notes.index') }}" class="sidebar-link {{ request()->routeIs('admin.notes.*') ? 'active' : '' }}">
                <i class="fas fa-sticky-note"></i> Notlarım
            </a>

            <a href="{{ route('admin.page-order.index') }}" class="sidebar-link {{ request()->routeIs('admin.page-order.*') ? 'active' : '' }}">
                <i class="fas fa-sort"></i> Sayfa Sırası
            </a>

            <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Ayarlar
            </a>

            <a href="{{ url('/') }}" target="_blank" class="sidebar-link">
                <i class="fas fa-external-link-alt"></i> Siteyi Gör
            </a>

            <a href="{{ route('admin.cikis') }}" class="sidebar-link">
                <i class="fas fa-sign-out-alt"></i> Çıkış
            </a>
        </nav>
    </aside>

    {{-- OVERLAY --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- TOPBAR --}}
    <header class="admin-topbar">
        <div class="topbar-left">
            <button class="topbar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <span class="topbar-title">@yield('title', 'Kontrol Paneli')</span>
        </div>
        <div class="topbar-right">
            <a href="{{ url('/') }}" target="_blank" class="topbar-link"><i class="fas fa-globe"></i> Siteyi Gör</a>

            {{-- Notification Bell --}}
            <div class="topbar-notif" id="notifBell" style="position:relative;cursor:pointer;">
                <i class="fas fa-bell" style="font-size:18px;color:var(--admin-text-light);transition:color 0.2s;"></i>
                <span id="notifBadge" style="display:none;position:absolute;top:-6px;right:-8px;width:20px;height:20px;border-radius:50%;background:#ef4444;color:#fff;font-size:10px;font-weight:700;line-height:20px;text-align:center;">0</span>
            </div>

            {{-- Notification Dropdown --}}
            <div id="notifDropdown" style="display:none;position:absolute;top:54px;right:80px;width:300px;background:#fff;border-radius:12px;box-shadow:0 12px 40px rgba(0,0,0,0.15);border:1px solid var(--admin-border);z-index:1001;overflow:hidden;">
                <div style="padding:14px 18px;border-bottom:1px solid var(--admin-border);font-size:14px;font-weight:700;color:var(--admin-dark);">
                    <i class="fas fa-bell" style="color:var(--admin-primary);margin-right:6px;"></i> Bildirimler
                </div>
                <div id="notifList" style="max-height:280px;overflow-y:auto;"></div>
            </div>

            <div class="topbar-user">
                <span class="topbar-avatar">{{ strtoupper(substr(session('admin_adi', 'A'), 0, 1)) }}</span>
                {{ session('admin_adi', 'Admin') }}
            </div>
        </div>
    </header>

    {{-- MAIN --}}
    <main class="admin-main">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:var(--admin-radius);border:none;font-size:14px;">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:var(--admin-radius);border:none;font-size:14px;">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="{{ asset('tema/js/jquery.min.js') }}"></script>
    <script src="{{ asset('tema/js/bootstrap.min.js') }}"></script>
    <script>
    (function() {
        var sidebar = document.getElementById('adminSidebar');
        var overlay = document.getElementById('sidebarOverlay');
        var toggle = document.getElementById('sidebarToggle');

        toggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        });

        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });

        // ===== NOTIFICATION BELL =====
        var bell = document.getElementById('notifBell');
        var dropdown = document.getElementById('notifDropdown');
        var bellOpen = false;

        bell.addEventListener('click', function(e) {
            e.stopPropagation();
            bellOpen = !bellOpen;
            dropdown.style.display = bellOpen ? 'block' : 'none';
        });

        document.addEventListener('click', function() {
            bellOpen = false;
            dropdown.style.display = 'none';
        });

        dropdown.addEventListener('click', function(e) { e.stopPropagation(); });

        // ===== LIVE POLLING (every 10s) =====
        var prevData = {};
        var notifSound = null;
        var currentSoundUrl = null;

        function updateBadge(el, count) {
            if (count > 0) {
                el.textContent = count;
                el.style.display = 'inline-block';
            } else {
                el.style.display = 'none';
            }
        }

        function fetchNotifications() {
            fetch('{{ route("admin.notifications.count") }}')
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    var totalNew = data.pending_reviews + data.unread_messages + data.new_customers;

                    // Update sidebar badges
                    updateBadge(document.getElementById('sidebarMsgBadge'), data.unread_messages);
                    updateBadge(document.getElementById('sidebarReviewBadge'), data.pending_reviews);
                    updateBadge(document.getElementById('sidebarCustomerBadge'), data.new_customers);

                    // Update bell badge
                    var bellBadge = document.getElementById('notifBadge');
                    updateBadge(bellBadge, totalNew);

                    // Update sound from settings
                    if (data.sound_url && data.sound_url !== currentSoundUrl) {
                        currentSoundUrl = data.sound_url;
                        try { notifSound = new Audio(data.sound_url); } catch(e) {}
                    }

                    // Bell icon color
                    bell.querySelector('i').style.color = totalNew > 0 ? '#ef4444' : '';

                    // Play sound if new notification arrived
                    if (prevData.pending_reviews !== undefined) {
                        if (data.pending_reviews > prevData.pending_reviews ||
                            data.unread_messages > prevData.unread_messages ||
                            data.new_customers > prevData.new_customers) {
                            if (notifSound) { notifSound.play().catch(function(){}); }
                            showToast('Yeni bildirim alındı!');
                        }
                    }

                    // Update dropdown list
                    var list = document.getElementById('notifList');
                    var html = '';
                    if (data.new_customers > 0) {
                        html += '<a href="{{ route("admin.customers.index") }}" style="display:flex;align-items:center;gap:12px;padding:14px 18px;border-bottom:1px solid #f1f5f9;text-decoration:none;color:var(--admin-text);transition:background 0.15s;">' +
                            '<div style="width:36px;height:36px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-user-plus" style="color:#059669;font-size:14px;"></i></div>' +
                            '<div><strong style="font-size:13px;color:var(--admin-dark);">' + data.new_customers + ' yeni rezervasyon</strong><div style="font-size:12px;color:#64748b;">Görüntüle</div></div></a>';
                    }
                    if (data.unread_messages > 0) {
                        html += '<a href="{{ route("admin.contacts.index") }}" style="display:flex;align-items:center;gap:12px;padding:14px 18px;border-bottom:1px solid #f1f5f9;text-decoration:none;color:var(--admin-text);transition:background 0.15s;">' +
                            '<div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-envelope" style="color:#1d4ed8;font-size:14px;"></i></div>' +
                            '<div><strong style="font-size:13px;color:var(--admin-dark);">' + data.unread_messages + ' yeni mesaj</strong><div style="font-size:12px;color:#64748b;">Görüntüle</div></div></a>';
                    }
                    if (data.pending_reviews > 0) {
                        html += '<a href="{{ route("admin.reviews.index") }}" style="display:flex;align-items:center;gap:12px;padding:14px 18px;border-bottom:1px solid #f1f5f9;text-decoration:none;color:var(--admin-text);transition:background 0.15s;">' +
                            '<div style="width:36px;height:36px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-star" style="color:#b45309;font-size:14px;"></i></div>' +
                            '<div><strong style="font-size:13px;color:var(--admin-dark);">' + data.pending_reviews + ' bekleyen yorum</strong><div style="font-size:12px;color:#64748b;">Görüntüle</div></div></a>';
                    }
                    if (html === '') {
                        html = '<div style="padding:30px;text-align:center;color:#94a3b8;font-size:13px;"><i class="fas fa-check-circle" style="font-size:24px;display:block;margin-bottom:8px;opacity:0.4;"></i>Tüm bildirimler okundu!</div>';
                    }
                    list.innerHTML = html;

                    prevData = data;
                })
                .catch(function() {});
        }

        // Toast notification
        function showToast(msg) {
            var toast = document.createElement('div');
            toast.style.cssText = 'position:fixed;top:20px;right:20px;background:var(--admin-dark,#0b1d33);color:#fff;padding:14px 22px;border-radius:10px;font-size:14px;font-weight:600;z-index:9999;box-shadow:0 10px 30px rgba(0,0,0,0.2);animation:toastIn 0.3s ease;font-family:Poppins,sans-serif;display:flex;align-items:center;gap:10px;';
            toast.innerHTML = '<i class="fas fa-bell" style="color:#f59e0b;"></i> ' + msg;
            document.body.appendChild(toast);
            setTimeout(function() {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(30px)';
                toast.style.transition = 'all 0.3s';
                setTimeout(function() { toast.remove(); }, 300);
            }, 4000);
        }

        // Run immediately + every 10 seconds
        fetchNotifications();
        setInterval(fetchNotifications, 10000);
    })();
    </script>
    <style>
        @keyframes toastIn {
            from { opacity:0; transform:translateX(30px); }
            to { opacity:1; transform:translateX(0); }
        }
    </style>
    @stack('scripts')

    <script>
    // Auto-enhance all file inputs with custom drop zone label
    (function(){
        document.querySelectorAll('input[type="file"]').forEach(function(input, i){
            if (input.dataset.enhanced) return;
            input.dataset.enhanced = '1';
            var id = input.id || ('file_' + Date.now() + '_' + i);
            input.id = id;
            var label = document.createElement('label');
            label.setAttribute('for', id);
            var multiple = input.multiple ? ' (birden fazla seçilebilir)' : '';
            var defaultText = 'Dosya seçmek için tıkla veya sürükle' + multiple;
            label.innerHTML = '<span>' + defaultText + '</span>';
            input.parentNode.insertBefore(label, input.nextSibling);
            input.addEventListener('change', function(){
                var span = label.querySelector('span');
                if (input.files && input.files.length > 0) {
                    if (input.files.length === 1) {
                        span.textContent = input.files[0].name;
                    } else {
                        span.textContent = input.files.length + ' dosya seçildi';
                    }
                    label.classList.add('has-file');
                } else {
                    span.textContent = defaultText;
                    label.classList.remove('has-file');
                }
            });
        });
    })();
    </script>
</body>
</html>
