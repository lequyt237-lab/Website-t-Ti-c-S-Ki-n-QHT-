<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'QHT Banquet – Đặt Tiệc Gia Đình' ?></title>

    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --forest:    #2C4A35;
            --forest-dk: #1A3024;
            --terra:     #C4603C;
            --terra-lt:  #E07A55;
            --amber:     #D4A843;
            --amber-lt:  #EFC96A;
            --cream:     #FDF8F2;
            --cream-dk:  #F4EDE2;
            --stone:     #E8E0D6;
            --charcoal:  #2A2420;
            --muted:     #857870;
            --white:     #FFFFFF;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--charcoal);
        }

        h1, h2, h3, h4, h5 {
            font-family: 'DM Serif Display', serif;
        }

        /* ─── NAVBAR ─── */
        .navbar-qht {
            position: fixed; top: 0; width: 100%; z-index: 999;
            transition: all 0.4s;
        }
        .nav-inner {
            background: rgba(253, 248, 242, 0.96);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--stone);
            padding: 16px 0;
            transition: all 0.4s;
        }
        .navbar-qht.scrolled .nav-inner {
            padding: 10px 0;
            box-shadow: 0 4px 24px rgba(44,74,53,0.08);
        }
        .brand-logo {
            font-family: 'DM Serif Display', serif;
            font-size: 1.5rem;
            color: var(--forest);
            text-decoration: none; line-height: 1;
        }
        .brand-logo .sub {
            display: block; font-family: 'DM Sans', sans-serif;
            font-size: 0.58rem; letter-spacing: 3.5px;
            color: var(--terra); text-transform: uppercase;
            font-weight: 500; margin-top: 3px;
        }
        .nav-link-q {
            font-size: 0.78rem; font-weight: 500; letter-spacing: 1.5px;
            text-transform: uppercase; color: var(--charcoal) !important;
            text-decoration: none; padding: 6px 12px !important;
            transition: color 0.3s;
        }
        .nav-link-q:hover { color: var(--terra) !important; }
        .btn-book {
            background: var(--forest);
            color: var(--white) !important;
            border-radius: 3px; padding: 10px 22px !important;
            font-size: 0.75rem; font-weight: 600; letter-spacing: 1.5px;
            text-transform: uppercase; text-decoration: none;
            transition: all 0.3s; border: none;
        }
        .btn-book:hover { background: var(--terra); color: var(--white) !important; }
        .vr-nav {
            width: 1px; height: 18px;
            background: var(--stone); margin: auto 6px;
        }
        .navbar-toggler { border: none; }
        .navbar-toggler:focus { box-shadow: none; }

        /* ─── FOOTER ─── */
        footer {
            background: var(--forest-dk);
            color: rgba(255,255,255,0.6);
            padding: 70px 0 0;
        }
        footer .f-brand {
            font-family: 'DM Serif Display', serif;
            font-size: 1.9rem; color: var(--amber-lt);
        }
        footer h6 {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.68rem; font-weight: 600;
            letter-spacing: 3px; text-transform: uppercase;
            color: var(--amber); margin-bottom: 18px;
        }
        footer p, footer li { font-size: 0.87rem; line-height: 2.1; }
        footer ul { list-style: none; padding: 0; }
        footer a { color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s; }
        footer a:hover { color: var(--amber-lt); }
        .f-bottom {
            border-top: 1px solid rgba(255,255,255,0.07);
            padding: 22px 0; margin-top: 50px;
            text-align: center; font-size: 0.8rem;
        }
        .soc-btn {
            display: inline-flex; width: 36px; height: 36px;
            border-radius: 50%; border: 1px solid rgba(255,255,255,0.15);
            align-items: center; justify-content: center;
            color: rgba(255,255,255,0.6); font-size: 0.9rem;
            margin-right: 8px; text-decoration: none; transition: all 0.3s;
        }
        .soc-btn:hover { background: var(--terra); border-color: var(--terra); color: #fff; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar-qht" id="mainNav">
  <div class="nav-inner">
    <div class="container d-flex align-items-center justify-content-between">
      <a href="<?= BASE_URL ?>" class="brand-logo">
        QHT Banquet
        <span class="sub">Đặt Tiệc &amp; Sự Kiện</span>
      </a>

      <div class="d-none d-lg-flex align-items-center gap-1">
        <a href="<?= BASE_URL ?>" class="nav-link-q">Trang Chủ</a>
        <a href="<?= BASE_URL ?>?action=thuc-don" class="nav-link-q">Thực Đơn</a>
        <a href="<?= BASE_URL ?>?action=ban-phong" class="nav-link-q">Bàn &amp; Phòng</a>
        <a href="<?= BASE_URL ?>?action=set-tiec" class="nav-link-q">Set Tiệc</a>
        <a href="<?= BASE_URL ?>?action=lien-he" class="nav-link-q">Liên Hệ</a>
        <div class="vr-nav"></div>
        <?php if (isset($_SESSION['user'])): ?>
          <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="nav-link-q <?= (($_GET['action'] ?? '') === 'lich-su-dat-ban') ? 'fw-bold text-success' : '' ?>" title="Xem lịch sử đặt bàn">
            <i class="bi bi-clock-history me-1"></i>Lịch Sử
          </a>
          <?php if ($_SESSION['user']['vai_tro'] === 'admin'): ?>
            <a href="<?= BASE_URL ?>?action=admin" class="btn btn-sm btn-outline-warning text-dark fw-bold me-1" style="font-size: 0.72rem; letter-spacing: 1px; border-radius: 4px;">
              <i class="bi bi-shield-lock-fill me-1 text-danger"></i> Quản Trị
            </a>
          <?php endif; ?>
          <span class="small fw-semibold text-secondary me-2">
            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['user']['ho_ten']) ?>
          </span>
          <a href="<?= BASE_URL ?>?action=dang-xuat" class="nav-link-q text-danger" title="Đăng xuất" style="padding: 4px 8px !important;">
            <i class="bi bi-box-arrow-right"></i>
          </a>
        <?php else: ?>
          <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="nav-link-q" title="Tra cứu đơn đặt bàn">
            <i class="bi bi-search me-1"></i>Tra Cứu
          </a>
          <a href="<?= BASE_URL ?>?action=dang-nhap" class="nav-link-q">Đăng Nhập</a>
          <a href="<?= BASE_URL ?>?action=dang-ky" class="nav-link-q">Đăng Ký</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>?action=dat-ban" class="btn-book ms-2">
          <i class="bi bi-calendar2-check me-1"></i> Đặt Bàn
        </a>
      </div>

      <button class="navbar-toggler d-lg-none" data-bs-toggle="collapse" data-bs-target="#mMenu">
        <i class="bi bi-list fs-3" style="color:var(--forest)"></i>
      </button>
    </div>
    <div class="collapse" id="mMenu">
      <div class="container py-3 d-flex flex-column gap-2 border-top" style="border-color:var(--stone)!important">
        <a href="<?= BASE_URL ?>" class="nav-link-q">Trang Chủ</a>
        <a href="<?= BASE_URL ?>?action=thuc-don" class="nav-link-q">Thực Đơn</a>
        <a href="<?= BASE_URL ?>?action=ban-phong" class="nav-link-q">Bàn &amp; Phòng</a>
        <a href="<?= BASE_URL ?>?action=set-tiec" class="nav-link-q">Set Tiệc</a>
        <a href="<?= BASE_URL ?>?action=lien-he" class="nav-link-q">Liên Hệ</a>
        <?php if (isset($_SESSION['user'])): ?>
          <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="nav-link-q fw-bold text-success">
            <i class="bi bi-clock-history me-1"></i> Lịch Sử Đặt Bàn
          </a>
          <?php if ($_SESSION['user']['vai_tro'] === 'admin'): ?>
            <a href="<?= BASE_URL ?>?action=admin" class="nav-link-q text-primary fw-bold">
              <i class="bi bi-shield-lock-fill"></i> Trang Quản Trị (Admin)
            </a>
          <?php endif; ?>
          <span class="nav-link-q text-muted">Xin chào, <?= htmlspecialchars($_SESSION['user']['ho_ten']) ?></span>
          <a href="<?= BASE_URL ?>?action=dang-xuat" class="nav-link-q text-danger">Đăng Xuất</a>
        <?php else: ?>
          <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="nav-link-q">
            <i class="bi bi-search me-1"></i> Tra Cứu Đặt Bàn
          </a>
          <a href="<?= BASE_URL ?>?action=dang-nhap" class="nav-link-q">Đăng Nhập</a>
          <a href="<?= BASE_URL ?>?action=dang-ky" class="nav-link-q">Đăng Ký</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>?action=dat-ban" class="btn-book d-inline-block w-auto mt-1">Đặt Bàn Ngay</a>
      </div>
    </div>
  </div>
</nav>

<!-- PAGE CONTENT -->
<?php if (isset($view)) require_once PATH_VIEW . $view . '.php'; ?>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4">
        <div class="f-brand mb-3">QHT Banquet</div>
        <p style="max-width:290px">Nhà hàng tiệc gia đình QHT – không gian ấm cúng, ẩm thực tinh tế, tận tâm cho từng khoảnh khắc đặc biệt của bạn.</p>
        <div class="mt-4">
          <a href="#" class="soc-btn"><i class="bi bi-facebook"></i></a>
          <a href="#" class="soc-btn"><i class="bi bi-instagram"></i></a>
          <a href="#" class="soc-btn"><i class="bi bi-tiktok"></i></a>
          <a href="#" class="soc-btn"><i class="bi bi-youtube"></i></a>
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
          <li><i class="bi bi-geo-alt" style="color:var(--terra)"></i>&nbsp; 123 Đường ABC, Quận 1, TP.HCM</li>
          <li><i class="bi bi-telephone" style="color:var(--terra)"></i>&nbsp; 0123 456 789</li>
          <li><i class="bi bi-envelope" style="color:var(--terra)"></i>&nbsp; info@qhtbanquet.vn</li>
          <li><i class="bi bi-clock" style="color:var(--terra)"></i>&nbsp; 10:00 – 22:00 hàng ngày</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="f-bottom">
    <p>&copy; <?= date('Y') ?> QHT Banquet. All rights reserved.</p>
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
