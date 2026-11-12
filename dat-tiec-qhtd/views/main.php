<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'QHT Banquet – Đặt Tiệc Gia Đình' ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --burgundy:     #6B1E2E;
            --burgundy-dk:  #4A1020;
            --gold:         #C9A257;
            --gold-lt:      #E8C97A;
            --cream:        #FBF7F0;
            --cream-dk:     #F2EBE0;
            --charcoal:     #2C1A14;
            --warm-gray:    #8A7D74;
            --white:        #FFFFFF;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Jost', sans-serif;
            background: var(--cream);
            color: var(--charcoal);
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Cormorant Garamond', serif;
        }

        /* ─── NAVBAR ─── */
        .navbar-qht {
            position: fixed; top: 0; width: 100%; z-index: 999;
            padding: 0;
            transition: all 0.4s;
        }
        .navbar-qht .nav-inner {
            background: rgba(251, 247, 240, 0.97);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(201, 162, 87, 0.25);
            padding: 14px 0;
        }
        .navbar-qht.scrolled .nav-inner {
            box-shadow: 0 4px 30px rgba(107, 30, 46, 0.1);
        }
        .brand-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.7rem;
            font-weight: 700;
            color: var(--burgundy);
            text-decoration: none;
            line-height: 1;
        }
        .brand-logo .brand-sub {
            display: block;
            font-size: 0.6rem;
            font-family: 'Jost', sans-serif;
            font-weight: 500;
            letter-spacing: 4px;
            color: var(--gold);
            text-transform: uppercase;
            margin-top: 2px;
        }
        .nav-link-qht {
            font-family: 'Jost', sans-serif;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--charcoal) !important;
            padding: 6px 14px !important;
            text-decoration: none;
            transition: color 0.3s;
        }
        .nav-link-qht:hover { color: var(--burgundy) !important; }
        .nav-divider {
            width: 1px; height: 20px;
            background: rgba(201, 162, 87, 0.3);
            margin: auto 8px;
        }
        .btn-nav-book {
            background: var(--burgundy);
            color: var(--white) !important;
            border: none;
            padding: 10px 24px !important;
            border-radius: 2px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: all 0.3s;
        }
        .btn-nav-book:hover {
            background: var(--gold);
            color: var(--charcoal) !important;
        }
        .navbar-toggler { border: none; padding: 0; }
        .navbar-toggler:focus { box-shadow: none; }

        /* ─── FOOTER ─── */
        footer {
            background: var(--burgundy-dk);
            color: rgba(255,255,255,0.65);
            padding: 70px 0 0;
        }
        footer .footer-brand {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold-lt);
        }
        footer h6 {
            font-family: 'Jost', sans-serif;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 18px;
        }
        footer p, footer li { font-size: 0.88rem; line-height: 2; }
        footer ul { list-style: none; padding: 0; }
        footer a { color: rgba(255,255,255,0.65); text-decoration: none; transition: color 0.3s; }
        footer a:hover { color: var(--gold-lt); }
        .footer-line {
            border-top: 1px solid rgba(201,162,87,0.15);
            padding: 24px 0;
            margin-top: 50px;
            font-size: 0.8rem;
            text-align: center;
        }
        .social-btn {
            display: inline-flex; width: 38px; height: 38px;
            border-radius: 50%; border: 1px solid rgba(201,162,87,0.3);
            align-items: center; justify-content: center;
            color: rgba(255,255,255,0.65); font-size: 0.95rem;
            margin-right: 8px; text-decoration: none;
            transition: all 0.3s;
        }
        .social-btn:hover {
            background: var(--gold); border-color: var(--gold);
            color: var(--charcoal);
        }
    </style>
</head>
<body>

<!-- ═══ NAVBAR ═══ -->
<nav class="navbar-qht" id="mainNav">
    <div class="nav-inner">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="<?= BASE_URL ?>" class="brand-logo">
                QHT Banquet
                <span class="brand-sub">Đặt Tiệc &amp; Sự Kiện</span>
            </a>

            <!-- Desktop menu -->
            <div class="d-none d-lg-flex align-items-center gap-1">
                <a href="<?= BASE_URL ?>" class="nav-link-qht">Trang Chủ</a>
                <a href="<?= BASE_URL ?>?action=thuc-don" class="nav-link-qht">Thực Đơn</a>
                <a href="<?= BASE_URL ?>?action=ban-phong" class="nav-link-qht">Bàn &amp; Phòng</a>
                <a href="<?= BASE_URL ?>?action=set-tiec" class="nav-link-qht">Set Tiệc</a>
                <a href="<?= BASE_URL ?>?action=lien-he" class="nav-link-qht">Liên Hệ</a>
                <div class="nav-divider"></div>
                <a href="<?= BASE_URL ?>?action=dang-nhap" class="nav-link-qht"><i class="bi bi-person"></i></a>
                <a href="<?= BASE_URL ?>?action=dat-ban" class="nav-link-qht btn-nav-book ms-2">
                    <i class="bi bi-calendar2-check"></i> Đặt Bàn
                </a>
            </div>

            <!-- Mobile toggle -->
            <button class="navbar-toggler d-lg-none" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
                <i class="bi bi-list fs-4" style="color:var(--burgundy)"></i>
            </button>
        </div>
        <!-- Mobile menu -->
        <div class="collapse" id="mobileMenu">
            <div class="container py-3 d-flex flex-column gap-2">
                <a href="<?= BASE_URL ?>" class="nav-link-qht">Trang Chủ</a>
                <a href="<?= BASE_URL ?>?action=thuc-don" class="nav-link-qht">Thực Đơn</a>
                <a href="<?= BASE_URL ?>?action=ban-phong" class="nav-link-qht">Bàn &amp; Phòng</a>
                <a href="<?= BASE_URL ?>?action=set-tiec" class="nav-link-qht">Set Tiệc</a>
                <a href="<?= BASE_URL ?>?action=lien-he" class="nav-link-qht">Liên Hệ</a>
                <a href="<?= BASE_URL ?>?action=dang-nhap" class="nav-link-qht">Đăng Nhập</a>
                <a href="<?= BASE_URL ?>?action=dat-ban" class="nav-link-qht btn-nav-book d-inline-block w-auto mt-1">Đặt Bàn Ngay</a>
            </div>
        </div>
    </div>
</nav>

<!-- ═══ PAGE CONTENT ═══ -->
<?php if (isset($view)) require_once PATH_VIEW . $view . '.php'; ?>

<!-- ═══ FOOTER ═══ -->
<footer>
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="footer-brand mb-3">QHT Banquet</div>
                <p style="max-width:300px">Nhà hàng tiệc gia đình QHT – không gian ấm cúng, ẩm thực tinh tế, dành trọn tâm huyết cho từng bữa tiệc của bạn.</p>
                <div class="mt-4">
                    <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-tiktok"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <h6>Khám Phá</h6>
                <ul>
                    <li><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                    <li><a href="<?= BASE_URL ?>?action=thuc-don">Thực đơn</a></li>
                    <li><a href="<?= BASE_URL ?>?action=ban-phong">Bàn &amp; Phòng</a></li>
                    <li><a href="<?= BASE_URL ?>?action=set-tiec">Set tiệc</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-4">
                <h6>Loại Dịp</h6>
                <ul>
                    <li><a href="#">Tiệc Sinh Nhật</a></li>
                    <li><a href="#">Tiệc Mừng Thọ</a></li>
                    <li><a href="#">Họp Mặt Gia Đình</a></li>
                    <li><a href="#">Tiệc Công Ty</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-4">
                <h6>Liên Hệ</h6>
                <ul>
                    <li><i class="bi bi-geo-alt" style="color:var(--gold)"></i>&nbsp; 123 Đường ABC, Quận 1, TP.HCM</li>
                    <li><i class="bi bi-telephone" style="color:var(--gold)"></i>&nbsp; 0123 456 789</li>
                    <li><i class="bi bi-envelope" style="color:var(--gold)"></i>&nbsp; info@qhtbanquet.vn</li>
                    <li><i class="bi bi-clock" style="color:var(--gold)"></i>&nbsp; 10:00 – 22:00 hàng ngày</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-line">
        <p class="mb-0">&copy; <?= date('Y') ?> QHT Banquet. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const nav = document.getElementById('mainNav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 40);
    });
</script>
</body>
</html>
