<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?? 'QHT Banquet - Đặt Tiệc & Sự Kiện' ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #8B0000;
            --primary-light: #B22222;
            --gold-color: #D4AF37;
            --gold-light: #F0D060;
            --dark-bg: #1a1a2e;
            --dark-card: #16213e;
            --text-light: #f5f5f5;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            color: #333;
        }

        h1, h2, h3, h4, h5, .brand-font {
            font-family: 'Playfair Display', serif;
        }

        /* ===== NAVBAR ===== */
        .navbar-custom {
            background: rgba(26, 26, 46, 0.95);
            backdrop-filter: blur(10px);
            padding: 12px 0;
            transition: all 0.3s ease;
        }
        .navbar-custom .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--gold-color) !important;
        }
        .navbar-custom .navbar-brand span {
            color: #fff;
            font-weight: 400;
            font-size: 1rem;
        }
        .navbar-custom .nav-link {
            color: #ddd !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            padding: 8px 16px !important;
            transition: color 0.3s;
        }
        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: var(--gold-color) !important;
        }
        .navbar-custom .btn-dat-ban {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: #fff !important;
            border: none;
            border-radius: 25px;
            padding: 8px 24px !important;
            font-weight: 700;
            transition: all 0.3s;
        }
        .navbar-custom .btn-dat-ban:hover {
            background: linear-gradient(135deg, var(--gold-color), var(--gold-light));
            color: #1a1a2e !important;
            transform: translateY(-2px);
        }

        /* ===== HERO SECTION ===== */
        .hero-section {
            position: relative;
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 50%, #16213e 100%);
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="p" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(212,175,55,0.08)"/></pattern></defs><rect fill="url(%23p)" width="100" height="100"/></svg>');
        }
        .hero-content {
            position: relative;
            z-index: 2;
        }
        .hero-badge {
            display: inline-block;
            background: rgba(212, 175, 55, 0.15);
            border: 1px solid var(--gold-color);
            color: var(--gold-color);
            padding: 6px 20px;
            border-radius: 30px;
            font-size: 0.85rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        .hero-title .highlight {
            color: var(--gold-color);
            display: block;
        }
        .hero-desc {
            font-size: 1.15rem;
            color: rgba(255,255,255,0.7);
            line-height: 1.8;
            max-width: 500px;
            margin-bottom: 35px;
        }
        .btn-hero {
            padding: 14px 36px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        .btn-hero-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: #fff;
            border: none;
        }
        .btn-hero-primary:hover {
            background: linear-gradient(135deg, var(--gold-color), var(--gold-light));
            color: #1a1a2e;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(212,175,55,0.3);
        }
        .btn-hero-outline {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255,255,255,0.3);
        }
        .btn-hero-outline:hover {
            border-color: var(--gold-color);
            color: var(--gold-color);
            transform: translateY(-3px);
        }
        .hero-stats {
            margin-top: 50px;
            display: flex;
            gap: 40px;
        }
        .hero-stat h3 {
            color: var(--gold-color);
            font-size: 2rem;
            margin-bottom: 0;
        }
        .hero-stat p {
            color: rgba(255,255,255,0.5);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .hero-image-wrapper {
            position: relative;
        }
        .hero-image-wrapper .circle-decoration {
            position: absolute;
            width: 450px;
            height: 450px;
            border: 2px solid rgba(212,175,55,0.2);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .hero-image-card {
            background: linear-gradient(145deg, rgba(255,255,255,0.1), rgba(255,255,255,0.02));
            border: 1px solid rgba(212,175,55,0.2);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        .hero-image-card i {
            font-size: 120px;
            background: linear-gradient(135deg, var(--gold-color), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-image-card h4 {
            color: #fff;
            margin-top: 15px;
        }
        .hero-image-card p {
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
        }

        /* ===== SECTION GENERAL ===== */
        .section-padding {
            padding: 80px 0;
        }
        .section-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .section-subtitle {
            color: var(--gold-color);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }
        .section-desc {
            color: #777;
            max-width: 600px;
            margin: 0 auto 50px;
        }
        .divider-gold {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--gold-color), var(--gold-light));
            margin: 15px auto 30px;
            border-radius: 10px;
        }

        /* ===== FEATURES / DỊCH VỤ ===== */
        .bg-light-custom {
            background: #f8f6f0;
        }
        .feature-card {
            background: #fff;
            border-radius: 16px;
            padding: 35px 25px;
            text-align: center;
            transition: all 0.4s ease;
            border: 1px solid rgba(0,0,0,0.05);
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
            border-color: var(--gold-color);
        }
        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
        }
        .feature-icon.icon-1 { background: rgba(139,0,0,0.1); color: var(--primary-color); }
        .feature-icon.icon-2 { background: rgba(212,175,55,0.1); color: var(--gold-color); }
        .feature-icon.icon-3 { background: rgba(15,52,96,0.1); color: #0f3460; }
        .feature-icon.icon-4 { background: rgba(130,179,102,0.1); color: #82b366; }
        .feature-card h5 {
            font-weight: 700;
            margin-bottom: 12px;
        }
        .feature-card p {
            color: #888;
            font-size: 0.9rem;
            line-height: 1.7;
        }

        /* ===== LOẠI DỊP ===== */
        .occasion-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            height: 280px;
            cursor: pointer;
            transition: all 0.4s ease;
        }
        .occasion-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        .occasion-card .occasion-bg {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 30px;
            text-align: center;
            transition: all 0.4s;
        }
        .occasion-card:hover .occasion-bg {
            transform: scale(1.05);
        }
        .occasion-bg-1 { background: linear-gradient(135deg, #ff6b6b, #ee5a24); }
        .occasion-bg-2 { background: linear-gradient(135deg, #f9ca24, #f0932b); }
        .occasion-bg-3 { background: linear-gradient(135deg, #6c5ce7, #a29bfe); }
        .occasion-bg-4 { background: linear-gradient(135deg, #00b894, #55efc4); }
        .occasion-card i {
            font-size: 50px;
            color: rgba(255,255,255,0.9);
            margin-bottom: 15px;
        }
        .occasion-card h5 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .occasion-card p {
            color: rgba(255,255,255,0.85);
            font-size: 0.85rem;
        }

        /* ===== QUY TRÌNH ===== */
        .process-section {
            background: var(--dark-bg);
            color: #fff;
        }
        .process-step {
            text-align: center;
            padding: 30px 20px;
            position: relative;
        }
        .process-number {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold-color), var(--gold-light));
            color: var(--dark-bg);
            font-size: 1.5rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-family: 'Playfair Display', serif;
        }
        .process-step h5 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .process-step p {
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
        }
        .process-arrow {
            position: absolute;
            right: -15px;
            top: 50px;
            color: var(--gold-color);
            font-size: 1.5rem;
        }

        /* ===== CTA ===== */
        .cta-section {
            background: linear-gradient(135deg, var(--primary-color), #5e0000);
            padding: 80px 0;
            text-align: center;
        }
        .cta-section h2 {
            color: #fff;
            font-size: 2.5rem;
            font-weight: 700;
        }
        .cta-section p {
            color: rgba(255,255,255,0.8);
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        .btn-cta {
            background: linear-gradient(135deg, var(--gold-color), var(--gold-light));
            color: var(--dark-bg);
            border: none;
            padding: 14px 40px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1.05rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(212,175,55,0.4);
            color: var(--dark-bg);
        }

        /* ===== FOOTER ===== */
        .footer {
            background: #0d0d1a;
            color: rgba(255,255,255,0.6);
            padding: 60px 0 30px;
        }
        .footer h5 {
            color: var(--gold-color);
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }
        .footer p, .footer li {
            font-size: 0.9rem;
            line-height: 2;
        }
        .footer a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: color 0.3s;
        }
        .footer a:hover {
            color: var(--gold-color);
        }
        .footer ul {
            list-style: none;
            padding: 0;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.08);
            padding-top: 25px;
            margin-top: 40px;
            text-align: center;
            font-size: 0.85rem;
        }
        .social-icons a {
            display: inline-flex;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.15);
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            transition: all 0.3s;
            font-size: 1rem;
        }
        .social-icons a:hover {
            background: var(--gold-color);
            border-color: var(--gold-color);
            color: var(--dark-bg) !important;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hero-title { font-size: 2.2rem; }
            .hero-stats { gap: 20px; }
            .hero-stat h3 { font-size: 1.5rem; }
            .process-arrow { display: none; }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>">
                <i class="bi bi-gem"></i> QHT Banquet
                <span class="d-block" style="font-size:0.7rem; color: rgba(255,255,255,0.5); letter-spacing:2px;">ĐẶT TIỆC & SỰ KIỆN</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= BASE_URL ?>">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=thuc-don">Thực đơn</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=ban-phong">Bàn & Phòng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=set-tiec">Set tiệc</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=lien-he">Liên hệ</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="nav-link btn-dat-ban" href="<?= BASE_URL ?>?action=dat-ban">
                            <i class="bi bi-calendar-check"></i> Đặt bàn ngay
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=dang-nhap">
                            <i class="bi bi-person-circle"></i> Đăng nhập
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <?php
    if (isset($view)) {
        require_once PATH_VIEW . $view . '.php';
    }
    ?>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5><i class="bi bi-gem"></i> QHT Banquet</h5>
                    <p>Nhà hàng tiệc gia đình QHT – nơi mỗi bữa tiệc là một kỷ niệm đáng nhớ. Chúng tôi mang đến trải nghiệm ẩm thực tuyệt vời cho mọi dịp đặc biệt của bạn.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-tiktok"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5>Khám phá</h5>
                    <ul>
                        <li><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                        <li><a href="<?= BASE_URL ?>?action=thuc-don">Thực đơn</a></li>
                        <li><a href="<?= BASE_URL ?>?action=ban-phong">Bàn & Phòng</a></li>
                        <li><a href="<?= BASE_URL ?>?action=set-tiec">Set tiệc</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5>Loại dịp</h5>
                    <ul>
                        <li><a href="#">Tiệc sinh nhật</a></li>
                        <li><a href="#">Tiệc mừng thọ</a></li>
                        <li><a href="#">Họp mặt gia đình</a></li>
                        <li><a href="#">Tiệc công ty</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5>Liên hệ</h5>
                    <ul>
                        <li><i class="bi bi-geo-alt-fill text-warning"></i> 123 Đường ABC, Quận XYZ, TP.HCM</li>
                        <li><i class="bi bi-telephone-fill text-warning"></i> 0123 456 789</li>
                        <li><i class="bi bi-envelope-fill text-warning"></i> info@qhtbanquet.vn</li>
                        <li><i class="bi bi-clock-fill text-warning"></i> 10:00 – 22:00 hàng ngày</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0">&copy; <?= date('Y') ?> QHT Banquet. All rights reserved. | Website Đặt Tiệc & Sự Kiện QHTD</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Navbar scroll effect -->
    <script>
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(26, 26, 46, 0.98)';
                navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.3)';
            } else {
                navbar.style.background = 'rgba(26, 26, 46, 0.95)';
                navbar.style.boxShadow = 'none';
            }
        });
    </script>

</body>

</html>
