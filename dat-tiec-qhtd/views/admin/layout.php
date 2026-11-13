<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Trang Quản Trị - QHT Banquet' ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --admin-bg: #F4F6F9;
            --admin-sidebar: #1E293B;
            --admin-sidebar-hover: #334155;
            --admin-primary: #2C4A35;
            --admin-accent: #C4603C;
            --admin-gold: #D4A843;
            --admin-text: #0F172A;
            --admin-muted: #64748B;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--admin-bg);
            color: var(--admin-text);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* ─── SIDEBAR ─── */
        .admin-sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--admin-sidebar);
            color: #E2E8F0;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .sidebar-brand {
            padding: 22px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #fff;
        }

        .sidebar-brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--admin-primary), #3b6b4d);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--admin-gold);
        }

        .sidebar-brand-text h5 {
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
            letter-spacing: 0.5px;
        }

        .sidebar-brand-text span {
            font-size: 0.65rem;
            color: #94A3B8;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Nút sang trang khách */
        .btn-switch-client {
            margin: 16px 16px 8px;
            background: linear-gradient(135deg, #C4603C, #d97753);
            color: #fff !important;
            padding: 10px 14px;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(196, 96, 60, 0.25);
            transition: all 0.25s;
        }

        .btn-switch-client:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(196, 96, 60, 0.4);
            color: #fff;
        }

        .sidebar-menu {
            list-style: none;
            padding: 12px 14px;
            margin: 0;
            flex-grow: 1;
        }

        .menu-header {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748B;
            padding: 10px 12px 6px;
            font-weight: 700;
        }

        .menu-item {
            margin-bottom: 4px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: #CBD5E1;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .menu-link:hover, .menu-link.active {
            background: var(--admin-sidebar-hover);
            color: #fff;
        }

        .menu-link.active {
            background: rgba(44, 74, 53, 0.4);
            color: #A7F3D0;
            font-weight: 600;
            border-left: 3px solid #34D399;
        }

        .menu-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(15, 23, 42, 0.5);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--admin-primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .user-details h6 {
            margin: 0;
            font-size: 0.82rem;
            font-weight: 600;
            color: #F8FAFC;
        }

        .user-details span {
            font-size: 0.7rem;
            color: #94A3B8;
        }

        .btn-logout {
            color: #EF4444;
            background: rgba(239, 68, 68, 0.1);
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #EF4444;
            color: #fff;
        }

        /* ─── MAIN CONTENT ─── */
        .admin-main {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-topbar {
            background: #fff;
            border-bottom: 1px solid #E2E8F0;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left h4 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--admin-text);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .admin-content {
            padding: 28px;
            flex-grow: 1;
        }

        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- ═══ SIDEBAR ADMIN ═══ -->
    <aside class="admin-sidebar" id="adminSidebar">
        <a href="<?= BASE_URL ?>?action=admin" class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div class="sidebar-brand-text">
                <h5>QHT Banquet</h5>
                <span>Hệ Thống Quản Trị</span>
            </div>
        </a>

        <!-- Nút sang trang khách hàng -->
        <a href="<?= BASE_URL ?>" class="btn-switch-client" title="Chuyển sang xem giao diện người dùng">
            <i class="bi bi-box-arrow-up-right"></i>
            <span>Sang Trang Khách</span>
        </a>

        <!-- Menu các chức năng theo Use Case Sơ Đồ -->
        <ul class="sidebar-menu">
            <li class="menu-header">Tổng Quan</li>
            <li class="menu-item">
                <a href="<?= BASE_URL ?>?action=admin" class="menu-link active">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Bảng Điều Khiển</span>
                </a>
            </li>

            <li class="menu-header">Nghiệp Vụ Đặt Bàn</li>
            <li class="menu-item">
                <a href="<?= BASE_URL ?>?action=admin" class="menu-link">
                    <i class="bi bi-calendar2-check-fill"></i>
                    <span>Duyệt Đặt Bàn</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="<?= BASE_URL ?>?action=admin" class="menu-link">
                    <i class="bi bi-door-open-fill"></i>
                    <span>Bàn & Phòng</span>
                </a>
            </li>

            <li class="menu-header">Ẩm Thực & Sự Kiện</li>
            <li class="menu-item">
                <a href="<?= BASE_URL ?>?action=admin" class="menu-link">
                    <i class="bi bi-egg-fried"></i>
                    <span>Thực Đơn & Set Tiệc</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="<?= BASE_URL ?>?action=admin" class="menu-link">
                    <i class="bi bi-balloon-fill"></i>
                    <span>Loại Dịp</span>
                </a>
            </li>

            <li class="menu-header">Hệ Thống</li>
            <li class="menu-item">
                <a href="<?= BASE_URL ?>?action=admin" class="menu-link">
                    <i class="bi bi-people-fill"></i>
                    <span>Quản Lý Người Dùng</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="<?= BASE_URL ?>?action=admin" class="menu-link">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    <span>Thống Kê Báo Cáo</span>
                </a>
            </li>
        </ul>

        <!-- Thông tin Admin đang đăng nhập -->
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <?= mb_strtoupper(mb_substr($_SESSION['user']['ho_ten'] ?? 'A', 0, 1, 'UTF-8')) ?>
                </div>
                <div class="user-details">
                    <h6><?= htmlspecialchars($_SESSION['user']['ho_ten'] ?? 'Admin') ?></h6>
                    <span>Quản Trị Viên</span>
                </div>
            </div>
            <a href="<?= BASE_URL ?>?action=dang-xuat" class="btn-logout" title="Đăng xuất">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </aside>

    <!-- ═══ KHU VỰC NỘI DUNG CHÍNH ═══ -->
    <div class="admin-main">
        <!-- TOPBAR -->
        <header class="admin-topbar">
            <div class="topbar-left d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h4><?= $title ?? 'Trang Quản Trị' ?></h4>
                    <span class="text-muted small">Cập nhật lúc: <?= date('d/m/Y - H:i') ?></span>
                </div>
            </div>
            <div class="topbar-right">
                <a href="<?= BASE_URL ?>" class="btn btn-sm btn-outline-dark d-flex align-items-center gap-2" style="border-radius: 6px;">
                    <i class="bi bi-eye"></i>
                    <span class="d-none d-sm-inline">Xem Trang Khách</span>
                </a>
                <div class="vr mx-1"></div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                        <i class="bi bi-check-circle me-1"></i> Admin Online
                    </span>
                </div>
            </div>
        </header>

        <!-- MAIN VIEW -->
        <main class="admin-content">
            <?php
            if (isset($adminView)) {
                require_once PATH_VIEW . 'admin/' . $adminView . '.php';
            }
            ?>
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const adminSidebar = document.getElementById('adminSidebar');
        if (sidebarToggle && adminSidebar) {
            sidebarToggle.addEventListener('click', () => {
                adminSidebar.classList.toggle('show');
            });
        }
    </script>
</body>
</html>

