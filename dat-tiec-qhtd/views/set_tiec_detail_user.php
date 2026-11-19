<?php
/**
 * @var array $set           Thông tin set tiệc
 * @var array $monTrongSet   Danh sách món ăn trong set
 * @var array $allDishes     Tất cả món ăn để khách chọn gọi thêm
 * @var array $categories    Danh sách nhóm món
 * @var array $occasions     Danh sách loại dịp tiệc
 * @var array $otherSets     Các set khác (gợi ý)
 */

// Nhóm món trong set theo nhom_mon
$groupedSet = [];
foreach ($monTrongSet as $m) {
    $groupedSet[$m['nhom_mon']][] = $m;
}
$totalGocMon = array_sum(array_column($monTrongSet, 'gia'));

// Lấy thông tin user nếu đã đăng nhập
$currentUser = $_SESSION['user'] ?? null;
?>

<style>
/* ─── HERO CHI TIẾT ─── */
.set-detail-hero {
    padding: 130px 0 60px;
    background: var(--forest-dk);
    color: #fff;
    position: relative;
    overflow: hidden;
}
.set-detail-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 50% 70% at 70% 40%, rgba(196,96,60,0.14) 0%, transparent 65%),
        radial-gradient(ellipse 40% 50% at 20% 70%, rgba(44,74,53,0.5) 0%, transparent 60%);
}
.hero-breadcrumb a {
    color: rgba(255,255,255,0.6);
    text-decoration: none;
    font-size: 0.82rem;
    transition: color 0.3s;
}
.hero-breadcrumb a:hover { color: var(--amber-lt); }
.hero-breadcrumb span { color: rgba(255,255,255,0.3); }
.hero-breadcrumb .current { color: var(--amber-lt); font-weight: 600; }

.hero-price-box {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 16px;
    padding: 24px;
    text-align: center;
}
.hero-price-box .price-main {
    font-family: 'DM Serif Display', serif;
    font-size: 2.4rem;
    color: var(--amber-lt);
    line-height: 1;
}

.info-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    padding: 6px 16px;
    border-radius: 24px;
    font-size: 0.82rem;
    font-weight: 500;
}
.info-pill i { color: var(--amber-lt); }

/* ─── CARD MÓN TRONG SET ─── */
.mon-set-card {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 16px 20px;
    transition: all 0.25s;
    height: 100%;
}
.mon-set-card:hover {
    border-color: var(--forest);
    box-shadow: 0 6px 20px rgba(44,74,53,0.06);
    transform: translateY(-2px);
}

/* ─── CARD MÓN GỌI THÊM ─── */
.dish-order-card {
    background: #fff;
    border: 1px solid var(--stone);
    border-radius: 12px;
    padding: 16px;
    transition: all 0.25s;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.dish-order-card:hover {
    border-color: var(--terra);
    box-shadow: 0 8px 24px rgba(196,96,60,0.08);
    transform: translateY(-2px);
}
.dish-order-card.selected {
    border-color: var(--terra);
    background: #FFFDF9;
    box-shadow: 0 0 0 2px rgba(196,96,60,0.15);
}

/* Bộ đếm số lượng */
.qty-stepper {
    display: inline-flex;
    align-items: center;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}
.qty-stepper button {
    border: none;
    background: #F8FAFC;
    color: var(--charcoal);
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.2s;
}
.qty-stepper button:hover {
    background: var(--forest);
    color: #fff;
}
.qty-stepper input {
    width: 44px;
    height: 32px;
    border: none;
    text-align: center;
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--charcoal);
    outline: none;
}

/* Bộ lọc món */
.category-pill-btn {
    background: #fff;
    border: 1px solid var(--stone);
    border-radius: 20px;
    padding: 6px 14px;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--charcoal);
    cursor: pointer;
    transition: all 0.2s;
}
.category-pill-btn:hover, .category-pill-btn.active {
    background: var(--forest);
    color: #fff;
    border-color: var(--forest);
}

/* ─── STICKY ORDER SIDEBAR ─── */
.order-sidebar-card {
    background: #fff;
    border: 1px solid var(--stone);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 8px 30px rgba(44,74,53,0.08);
    position: sticky;
    top: 90px;
}
.order-sidebar-card .title {
    font-family: 'DM Serif Display', serif;
    font-size: 1.35rem;
    color: var(--forest);
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid #F1F5F9;
}
.selected-dish-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px dashed #F1F5F9;
    font-size: 0.85rem;
}
.btn-remove-dish {
    border: none;
    background: transparent;
    color: #DC2626;
    padding: 0 4px;
    cursor: pointer;
    font-size: 1rem;
}

.btn-book-now-main {
    background: linear-gradient(135deg, var(--forest), #3b6b4d);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 14px;
    font-size: 0.95rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    width: 100%;
    transition: all 0.3s;
    box-shadow: 0 6px 20px rgba(44,74,53,0.25);
}
.btn-book-now-main:hover {
    background: linear-gradient(135deg, var(--terra), #d97753);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(196,96,60,0.35);
}
</style>

<!-- ═══ HERO SECTION ═══ -->
<section class="set-detail-hero">
    <div class="container position-relative" style="z-index: 2;">
        <div class="hero-breadcrumb mb-3">
            <a href="<?= BASE_URL ?>">Trang chủ</a>
            <span class="mx-2">/</span>
            <a href="<?= BASE_URL ?>?action=set-tiec">Set Tiệc</a>
            <span class="mx-2">/</span>
            <span class="current"><?= htmlspecialchars($set['ten']) ?></span>
        </div>

        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="d-inline-block text-uppercase small fw-semibold mb-2" style="letter-spacing: 3px; color: var(--amber-lt);">
                    Set Menu #<?= str_pad($set['id'], 2, '0', STR_PAD_LEFT) ?>
                </span>
                <h1 class="mb-3" style="font-family: 'DM Serif Display', serif; font-size: clamp(2rem, 4vw, 3rem);">
                    <?= htmlspecialchars($set['ten']) ?>
                </h1>
                <?php if (!empty($set['mo_ta'])): ?>
                    <p class="text-white-50 mb-4" style="font-size: 1rem; line-height: 1.8; max-width: 540px;">
                        <?= htmlspecialchars($set['mo_ta']) ?>
                    </p>
                <?php endif; ?>

                <div class="d-flex flex-wrap gap-2">
                    <span class="info-pill"><i class="bi bi-people-fill"></i> Phù hợp <?= $set['so_nguoi_phu_hop'] ?> khách</span>
                    <span class="info-pill"><i class="bi bi-egg-fried"></i> <?= count($monTrongSet) ?> món trong Set</span>
                    <span class="info-pill text-warning"><i class="bi bi-stars"></i> Có thể gọi thêm món ngoài</span>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="hero-price-box">
                    <div class="small text-white-50 text-uppercase mb-1" style="letter-spacing: 1px;">Giá trọn gói Set tiệc</div>
                    <div class="price-main"><?= number_format($set['gia'], 0, ',', '.') ?>đ</div>
                    <div class="text-white-50 small mt-1">Phục vụ trọn gói cho bàn <?= $set['so_nguoi_phu_hop'] ?> người</div>

                    <a href="#dat-set-ngay" class="btn btn-warning w-100 mt-3 py-3 fw-bold text-dark text-uppercase shadow-sm" style="border-radius: 10px; font-size: 0.88rem; letter-spacing: 1px;">
                        <i class="bi bi-calendar2-check-fill me-2"></i> Đặt Set Tiệc &amp; Chọn Món Ngay
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ NỘI DUNG CHỌN MÓN & ĐẶT TIỆC ═══ -->
<section class="py-5" style="background: var(--cream);" id="dat-set-ngay">
    <div class="container">

        <!-- THÔNG BÁO FLASH (NẾU CÓ) -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>?action=xu-ly-dat-ban" method="POST" id="setOrderForm">
            <!-- Hidden inputs -->
            <input type="hidden" name="set_id" value="<?= $set['id'] ?>">
            <input type="hidden" name="from_page" value="set_tiec">

            <div class="row g-4">

                <!-- ════ CỘT TRÁI: DANH SÁCH MÓN TRONG SET & GỌI THÊM MÓN NGOÀI ════ -->
                <div class="col-lg-8">

                    <!-- KHỐI 1: CÁC MÓN ĂN ĐÃ CÓ TRONG SET -->
                    <div class="p-4 bg-white rounded-3 border mb-4 shadow-sm" style="border-radius: 14px !important;">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <h4 class="mb-0 fw-bold" style="font-family: 'DM Serif Display', serif; color: var(--forest); font-size: 1.35rem;">
                                    <i class="bi bi-box2-heart-fill text-success me-2"></i>Các Món Ăn Trong Set Này
                                </h4>
                                <small class="text-muted">Đã bao gồm sẵn trong giá Set tiệc trọn gói (<?= count($monTrongSet) ?> món)</small>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2" style="font-size: 0.8rem;">
                                <i class="bi bi-check-circle me-1"></i>Đã tính trong giá set
                            </span>
                        </div>

                        <?php if (empty($monTrongSet)): ?>
                            <div class="text-center py-4 text-muted small">
                                <i class="bi bi-info-circle fs-4 d-block mb-1"></i>
                                Bếp trưởng đang cập nhật danh sách món cụ thể cho set này.
                            </div>
                        <?php else: ?>
                            <?php foreach ($groupedSet as $nhom => $mons): ?>
                                <div class="mb-3">
                                    <h6 class="fw-bold text-secondary small text-uppercase mb-2" style="letter-spacing: 0.5px;">
                                        <i class="bi bi-tag-fill text-warning me-1"></i><?= htmlspecialchars($nhom) ?>:
                                    </h6>
                                    <div class="row g-2">
                                        <?php foreach ($mons as $m): ?>
                                            <div class="col-md-6">
                                                <div class="mon-set-card">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="fw-bold text-dark" style="font-size: 0.92rem;">
                                                            <?= htmlspecialchars($m['ten']) ?>
                                                        </div>
                                                        <span class="badge bg-light text-success border small">
                                                            <i class="bi bi-check2"></i> Có sẵn
                                                        </span>
                                                    </div>
                                                    <?php if (!empty($m['mo_ta'])): ?>
                                                        <div class="text-muted small mt-1" style="font-size: 0.78rem; line-height: 1.5;">
                                                            <?= htmlspecialchars(mb_strimwidth($m['mo_ta'], 0, 70, '...')) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- KHỐI 2: GỌI THÊM MÓN NGOÀI (TÙY CHỌN & CHỌN SỐ LƯỢNG) -->
                    <div class="p-4 bg-white rounded-3 border mb-4 shadow-sm" style="border-radius: 14px !important;">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <h4 class="mb-0 fw-bold" style="font-family: 'DM Serif Display', serif; color: var(--terra); font-size: 1.35rem;">
                                    <i class="bi bi-cart-plus-fill text-warning me-2"></i>Gọi Thêm Món Ngoài Set (Tùy Chọn)
                                </h4>
                                <small class="text-muted">Chọn thêm các món đặc sản, lẩu, bánh kem sinh nhật, đồ uống cho bữa tiệc thêm trọn vẹn</small>
                            </div>
                        </div>

                        <!-- Bộ lọc danh mục & tìm kiếm -->
                        <div class="row g-2 align-items-center mb-3">
                            <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                    <input type="text" id="searchDishInput" class="form-control border-start-0" placeholder="Tìm tên món ăn...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex flex-wrap gap-1" id="categoryFilterContainer">
                                    <button type="button" class="category-pill-btn active" data-cat="all">Tất cả</button>
                                    <?php foreach ($categories as $cat): ?>
                                        <button type="button" class="category-pill-btn" data-cat="<?= htmlspecialchars($cat) ?>">
                                            <?= htmlspecialchars($cat) ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Lưới món ăn để khách chọn số lượng -->
                        <div class="row g-3" id="dishesListContainer" style="max-height: 520px; overflow-y: auto; padding-right: 4px;">
                            <?php foreach ($allDishes as $dish): ?>
                                <div class="col-md-6 dish-card-wrapper" data-name="<?= htmlspecialchars(mb_strtolower($dish['ten'])) ?>" data-cat="<?= htmlspecialchars($dish['nhom_mon']) ?>">
                                    <div class="dish-order-card" id="dish-card-<?= $dish['id'] ?>">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">
                                                    <?= htmlspecialchars($dish['ten']) ?>
                                                </h6>
                                                <span class="badge bg-light text-secondary border small mt-1" style="font-size: 0.7rem;">
                                                    <?= htmlspecialchars($dish['nhom_mon']) ?>
                                                </span>
                                            </div>
                                            <span class="fw-bold fs-6" style="color: var(--terra);">
                                                <?= number_format($dish['gia'], 0, ',', '.') ?>đ
                                            </span>
                                        </div>

                                        <?php if (!empty($dish['mo_ta'])): ?>
                                            <p class="text-muted small mb-3 flex-grow-1" style="font-size: 0.78rem; line-height: 1.5;">
                                                <?= htmlspecialchars(mb_strimwidth($dish['mo_ta'], 0, 75, '...')) ?>
                                            </p>
                                        <?php endif; ?>

                                        <!-- Bộ nút tăng giảm số lượng -->
                                        <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                            <span class="small text-muted">Số lượng gọi thêm:</span>
                                            <div class="qty-stepper">
                                                <button type="button" onclick="changeQty(<?= $dish['id'] ?>, -1)">-</button>
                                                <input type="number" 
                                                       name="dishes[<?= $dish['id'] ?>]" 
                                                       id="qty-input-<?= $dish['id'] ?>" 
                                                       value="0" 
                                                       min="0" 
                                                       max="50"
                                                       data-id="<?= $dish['id'] ?>"
                                                       data-name="<?= htmlspecialchars($dish['ten']) ?>"
                                                       data-price="<?= $dish['gia'] ?>"
                                                       onchange="onQtyChange(<?= $dish['id'] ?>)">
                                                <button type="button" onclick="changeQty(<?= $dish['id'] ?>, 1)">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- KHỐI 3: THÔNG TIN NGƯỜI ĐẶT BÀN & THỜI GIAN TIỆC -->
                    <div class="p-4 bg-white rounded-3 border shadow-sm" style="border-radius: 14px !important;">
                        <h4 class="mb-3 fw-bold" style="font-family: 'DM Serif Display', serif; color: var(--forest); font-size: 1.35rem;">
                            <i class="bi bi-person-lines-fill text-primary me-2"></i>Thông Tin Đặt Bàn &amp; Người Nhận Tiệc
                        </h4>

                        <div class="row g-3">
                            <!-- Họ tên -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-person me-1"></i> Họ và tên người đặt <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="ho_ten" class="form-control" required
                                       value="<?= htmlspecialchars($currentUser['ho_ten'] ?? '') ?>"
                                       placeholder="Ví dụ: Nguyễn Văn An">
                            </div>

                            <!-- Số điện thoại -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-telephone me-1"></i> Số điện thoại nhận xác nhận <span class="text-danger">*</span>
                                </label>
                                <input type="tel" name="so_dien_thoai" class="form-control" required
                                       value="<?= htmlspecialchars($currentUser['so_dien_thoai'] ?? '') ?>"
                                       placeholder="Ví dụ: 0987654321">
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-envelope me-1"></i> Địa chỉ Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" class="form-control" required
                                       value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>"
                                       placeholder="email@example.com">
                            </div>

                            <!-- Loại dịp tiệc -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-balloon me-1"></i> Loại dịp tiệc
                                </label>
                                <select name="loaidip_id" class="form-select">
                                    <?php foreach ($occasions as $occ): ?>
                                        <option value="<?= $occ['id'] ?>" <?= ($occ['id'] == 1) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($occ['ten']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Ngày tổ chức -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-calendar-event me-1"></i> Ngày tổ chức <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="ngay_dat" class="form-control" required
                                       min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
                            </div>

                            <!-- Giờ đến -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-clock me-1"></i> Giờ đến dự kiến <span class="text-danger">*</span>
                                </label>
                                <input type="time" name="gio_den" class="form-control" required value="18:30">
                            </div>

                            <!-- Số người -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-people me-1"></i> Số lượng khách <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="so_nguoi" class="form-control" required min="1" max="50"
                                       value="<?= $set['so_nguoi_phu_hop'] ?>">
                            </div>

                            <!-- Yêu cầu phòng VIP -->
                            <div class="col-12">
                                <div class="form-check form-switch p-2 bg-light rounded border">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" id="yeuCauVipSwitch" name="yeu_cau_phong_rieng" value="1" onchange="updateLiveSummary()">
                                    <label class="form-check-label fw-semibold text-dark small" for="yeuCauVipSwitch">
                                        <i class="bi bi-door-closed-fill text-warning me-1"></i> Yêu cầu không gian Phòng Riêng (VIP) cho bữa tiệc
                                        <span class="text-muted fw-normal">(Phụ phí phòng VIP riêng tư ~500.000đ)</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Ghi chú cho nhà bếp -->
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-chat-text me-1"></i> Ghi chú đặc biệt cho nhà bếp &amp; phục vụ
                                </label>
                                <textarea name="ghi_chu" class="form-control" rows="2" placeholder="Ví dụ: Ăn ít cay, chuẩn bị nến sinh nhật, dọn món tráng miệng sau cùng..."></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ════ CỘT PHẢI: BẢNG TÓM TẮT THỰC ĐƠN & TỔNG TIỀN (STICKY) ════ -->
                <div class="col-lg-4">
                    <div class="order-sidebar-card">
                        <h5 class="title">
                            <i class="bi bi-receipt me-2"></i>Thực Đơn Đặt Của Bạn
                        </h5>

                        <!-- 1. Set Tiệc -->
                        <div class="p-3 rounded-3 border mb-3" style="background: #F8FAFC;">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <span class="badge bg-primary text-white" style="font-size: 0.7rem;">SET MENU</span>
                                    <div class="fw-bold text-dark mt-1"><?= htmlspecialchars($set['ten']) ?></div>
                                    <small class="text-muted">Trọn gói (<?= count($monTrongSet) ?> món)</small>
                                </div>
                                <span class="fw-bold text-primary fs-6">
                                    <?= number_format($set['gia'], 0, ',', '.') ?>đ
                                </span>
                            </div>
                        </div>

                        <!-- 2. Danh sách món gọi thêm -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-bold text-muted text-uppercase">Món gọi thêm ngoài:</span>
                                <span class="badge bg-warning text-dark" id="extraDishesBadge">0 món</span>
                            </div>

                            <div id="selectedDishesList" style="max-height: 220px; overflow-y: auto; font-size: 0.85rem;">
                                <div class="text-muted text-center py-3 fst-italic small" id="noExtraDishesNotice">
                                    <i class="bi bi-basket me-1"></i>Chưa chọn món gọi thêm nào
                                </div>
                            </div>
                        </div>

                        <!-- 3. Phụ phí phòng VIP (nếu chọn) -->
                        <div class="d-flex justify-content-between align-items-center py-2 border-top small text-muted" id="vipFeeRow" style="display: none !important;">
                            <span><i class="bi bi-door-open me-1 text-warning"></i>Phụ phí phòng VIP:</span>
                            <span class="fw-bold text-dark" id="vipFeeVal">500.000đ</span>
                        </div>

                        <!-- 4. Tổng cộng -->
                        <div class="pt-3 border-top mt-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-uppercase fs-6 text-dark">Tổng Cộng Tạm Tính:</span>
                                <span class="fw-bold fs-4" style="color: #C4603C;" id="liveTotalPrice">
                                    <?= number_format($set['gia'], 0, ',', '.') ?>đ
                                </span>
                            </div>
                            <small class="text-muted d-block text-end mb-3" style="font-size: 0.72rem;">
                                Đã gồm Set tiệc + Món ngoài + Phụ phí (nếu có)
                            </small>

                            <!-- NÚT ĐẶT HÀNG NGAY -->
                            <button type="submit" class="btn-book-now-main">
                                <i class="bi bi-check2-circle me-1"></i> Xác Nhận Đặt Set &amp; Món Ngay
                            </button>

                            <div class="text-center mt-3 small text-muted">
                                <i class="bi bi-shield-check text-success me-1"></i>
                                Không cần thanh toán trước • Nhân viên sẽ gọi xác nhận trong 15 phút
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </form>

    </div>
</section>

<!-- ═══ JAVASCRIPT XỬ LÝ CHỌN MÓN THỜI GIAN THỰC ═══ -->
<script>
const SET_PRICE = <?= (float)$set['gia'] ?>;
const VIP_ROOM_FEE = 500000;

// Thay đổi số lượng qua nút + / -
function changeQty(dishId, delta) {
    const input = document.getElementById('qty-input-' + dishId);
    if (!input) return;
    let val = parseInt(input.value) || 0;
    val = Math.max(0, val + delta);
    input.value = val;
    onQtyChange(dishId);
}

// Xử lý khi số lượng thay đổi
function onQtyChange(dishId) {
    const input = document.getElementById('qty-input-' + dishId);
    const card  = document.getElementById('dish-card-' + dishId);
    const val   = parseInt(input.value) || 0;

    if (val > 0) {
        card.classList.add('selected');
    } else {
        card.classList.remove('selected');
    }

    updateLiveSummary();
}

// Xóa món khỏi danh sách
function removeDish(dishId) {
    const input = document.getElementById('qty-input-' + dishId);
    if (input) {
        input.value = 0;
        onQtyChange(dishId);
    }
}

// Cập nhật bảng tóm tắt & tính tổng tiền thời gian thực
function updateLiveSummary() {
    const inputs = document.querySelectorAll('input[name^="dishes["]');
    const listContainer = document.getElementById('selectedDishesList');
    const badge = document.getElementById('extraDishesBadge');
    const notice = document.getElementById('noExtraDishesNotice');
    const vipSwitch = document.getElementById('yeuCauVipSwitch');
    const vipRow = document.getElementById('vipFeeRow');
    const totalEl = document.getElementById('liveTotalPrice');

    let totalExtra = 0;
    let extraCount = 0;
    let html = '';

    inputs.forEach(input => {
        const qty = parseInt(input.value) || 0;
        if (qty > 0) {
            const dishId = input.dataset.id;
            const name = input.dataset.name;
            const price = parseFloat(input.dataset.price) || 0;
            const subtotal = price * qty;
            totalExtra += subtotal;
            extraCount += qty;

            html += `
                <div class="selected-dish-row">
                    <div>
                        <strong class="text-dark">${name}</strong>
                        <div class="text-muted" style="font-size:0.75rem;">${formatMoney(price)}đ × ${qty}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <strong class="text-danger">${formatMoney(subtotal)}đ</strong>
                        <button type="button" class="btn-remove-dish" title="Xóa món này" onclick="removeDish(${dishId})">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>
                </div>
            `;
        }
    });

    // Hiển thị badge số món
    badge.textContent = extraCount + ' món';

    if (html !== '') {
        listContainer.innerHTML = html;
    } else {
        listContainer.innerHTML = `
            <div class="text-muted text-center py-3 fst-italic small">
                <i class="bi bi-basket me-1"></i>Chưa chọn món gọi thêm nào
            </div>
        `;
    }

    // Phụ phí VIP
    let vipFee = 0;
    if (vipSwitch && vipSwitch.checked) {
        vipFee = VIP_ROOM_FEE;
        vipRow.style.display = 'flex';
    } else if (vipRow) {
        vipRow.style.display = 'none';
    }

    // Tính tổng cộng
    const grandTotal = SET_PRICE + totalExtra + vipFee;
    totalEl.textContent = formatMoney(grandTotal) + 'đ';
}

function formatMoney(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount);
}

// Bộ lọc danh mục & tìm kiếm món
document.addEventListener('DOMContentLoaded', function () {
    const catBtns = document.querySelectorAll('.category-pill-btn');
    const searchInput = document.getElementById('searchDishInput');
    const items = document.querySelectorAll('.dish-card-wrapper');

    let activeCat = 'all';

    function filterDishes() {
        const query = searchInput.value.toLowerCase().trim();
        items.forEach(el => {
            const name = el.getAttribute('data-name');
            const cat  = el.getAttribute('data-cat');
            const matchCat  = (activeCat === 'all' || cat === activeCat);
            const matchName = (query === '' || name.includes(query));

            if (matchCat && matchName) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    }

    catBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            catBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            activeCat = this.getAttribute('data-cat');
            filterDishes();
        });
    });

    searchInput.addEventListener('input', filterDishes);

    // Tính khởi tạo ban đầu
    updateLiveSummary();
});
</script>
