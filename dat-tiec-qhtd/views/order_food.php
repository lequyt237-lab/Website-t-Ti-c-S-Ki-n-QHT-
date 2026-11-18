<?php
/**
 * View: Đặt món cho đơn đặt bàn
 * Biến truyền từ BookingController:
 * - $booking
 * - $currentDishes
 * - $currentDishesMap
 * - $dishes
 * - $categories
 * - $sets
 * - $setDishes
 */

$bookingIdPad = str_pad($booking['id'], 4, '0', STR_PAD_LEFT);
$vipPhuPhi = 0;
if (!empty($booking['banphong_id']) && ($booking['loai_ban'] ?? '') === 'phong_rieng') {
    $vipPhuPhi = (float)($booking['phu_phi'] ?? 0);
}
?>

<style>
    .order-food-header {
        padding: 120px 0 50px;
        background: linear-gradient(135deg, var(--forest-dk) 0%, var(--forest) 100%);
        color: #fff;
        position: relative;
    }
    .order-food-header::after {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse 60% 60% at 50% 50%, rgba(196,96,60,0.18) 0%, transparent 70%);
        pointer-events: none;
    }
    .booking-summary-strip {
        background: #fff;
        border: 1px solid var(--stone);
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        margin-top: -30px;
        position: relative;
        z-index: 10;
    }
    .menu-filter-btn {
        background: #fff;
        color: var(--charcoal);
        border: 1.5px solid var(--stone);
        border-radius: 25px;
        padding: 6px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .menu-filter-btn:hover, .menu-filter-btn.active {
        background: var(--forest);
        color: #fff;
        border-color: var(--forest);
        box-shadow: 0 4px 10px rgba(44,74,53,0.15);
    }
    .nav-tabs-order {
        border-bottom: 2px solid var(--stone);
    }
    .nav-tabs-order .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: var(--charcoal);
        font-weight: 600;
        padding: 12px 20px;
        font-size: 0.95rem;
    }
    .nav-tabs-order .nav-link.active {
        color: var(--forest);
        border-bottom-color: var(--forest);
        background: transparent;
    }
    .dish-card-order {
        background: #fff;
        border: 1px solid var(--stone);
        border-radius: 12px;
        transition: all 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 16px;
    }
    .dish-card-order:hover {
        border-color: var(--terra);
        box-shadow: 0 8px 24px rgba(44, 74, 53, 0.08);
        transform: translateY(-2px);
    }
    .dish-card-order.selected-in-cart {
        border-color: var(--forest);
        background: #fbfdfb;
    }
    .cart-sticky-box {
        position: sticky;
        top: 90px;
        background: #fff;
        border: 1px solid var(--stone);
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .cart-items-scroll {
        max-height: 380px;
        overflow-y: auto;
    }
    .qty-btn {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        background: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.15s;
    }
    .qty-btn:hover {
        background: var(--forest);
        color: #fff;
        border-color: var(--forest);
    }
    .set-card-order {
        background: #fff;
        border: 1.5px solid var(--stone);
        border-radius: 14px;
        transition: all 0.25s;
    }
    .set-card-order:hover {
        border-color: var(--terra);
        box-shadow: 0 6px 20px rgba(196,96,60,0.1);
    }
    .set-card-order.active-set {
        border-color: var(--forest);
        background: #f4f8f5;
        box-shadow: 0 4px 16px rgba(44,74,53,0.12);
    }
</style>

<!-- ═══ HEADER BANNER ═══ -->
<section class="order-food-header">
    <div class="container position-relative" style="z-index: 2;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2" style="font-size: 0.8rem;">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-white-50 text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="text-white-50 text-decoration-none">Lịch sử đặt bàn</a></li>
                <li class="breadcrumb-item active text-warning" aria-current="page">Đặt món #DB<?= $bookingIdPad ?></li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="badge bg-warning text-dark px-3 py-1 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.72rem;">
                    <i class="bi bi-cart-plus-fill me-1"></i>Thực Đơn Đặt Bàn
                </span>
                <h1 class="h2 fw-bold mt-2 mb-1">Đặt Món Ăn Cho Bàn Tiệc #DB<?= $bookingIdPad ?></h1>
                <p class="text-white-50 small mb-0">Quý khách có thể chọn món lẻ hoặc set tiệc trọn gói để nhà hàng chuẩn bị sẵn sàng, chu đáo nhất.</p>
            </div>
            <div>
                <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="btn btn-outline-light btn-sm px-3 py-2 fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại lịch sử
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ═══ STRIP TÓM TẮT THÔNG TIN ĐƠN ĐẶT BÀN ═══ -->
<div class="container">
    <div class="booking-summary-strip p-4 mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-6 col-md-3 border-end">
                <span class="text-muted small d-block"><i class="bi bi-calendar-event text-danger me-1"></i>Ngày tổ chức</span>
                <strong class="text-dark fs-6"><?= date('d/m/Y', strtotime($booking['ngay_dat'])) ?></strong>
                <span class="badge bg-light text-dark border ms-1 small">
                    <?= $booking['ca'] === 'trua' ? '☀️ Ca Trưa' : '🌙 Ca Tối' ?>
                </span>
            </div>
            <div class="col-6 col-md-3 border-end">
                <span class="text-muted small d-block"><i class="bi bi-geo-alt text-success me-1"></i>Bàn / Phòng</span>
                <strong class="text-dark fs-6">
                    <?= !empty($booking['ten_ban']) ? htmlspecialchars($booking['ten_ban']) : '<span class="text-warning fst-italic">Đang chờ xếp bàn</span>' ?>
                </strong>
                <?php if (!empty($booking['yeu_cau_phong_rieng'])): ?>
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle small ms-1">VIP</span>
                <?php endif; ?>
            </div>
            <div class="col-6 col-md-3 border-end">
                <span class="text-muted small d-block"><i class="bi bi-people text-primary me-1"></i>Khách &amp; Dịp</span>
                <strong class="text-dark fs-6"><?= $booking['so_nguoi'] ?> người</strong>
                <div class="text-muted small text-truncate"><?= !empty($booking['ten_dip']) ? htmlspecialchars($booking['ten_dip']) : 'Bình thường' ?></div>
            </div>
            <div class="col-6 col-md-3">
                <span class="text-muted small d-block"><i class="bi bi-person-check text-info me-1"></i>Người đặt</span>
                <strong class="text-dark fs-6"><?= htmlspecialchars($booking['ten_khach'] ?? 'Quý khách') ?></strong>
                <div class="text-muted small"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($booking['sdt_khach'] ?? '') ?></div>
            </div>
        </div>
    </div>
</div>

<!-- ═══ KHÔNG GIAN ĐẶT MÓN (2 CỘT) ═══ -->
<div class="container mb-5">
    
    <!-- THÔNG BÁO FLASH MESSAGE -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        
        <!-- CỘT TRÁI: DANH SÁCH MÓN ĂN & SET TIỆC (8 COLS) -->
        <div class="col-lg-8">
            
            <!-- TABS LỰA CHỌN: MÓN LẺ HOẶC SET TIỆC -->
            <ul class="nav nav-tabs nav-tabs-order mb-4" id="orderMenuTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-dishes-btn" data-bs-toggle="tab" data-bs-target="#tab-dishes" type="button" role="tab">
                        <i class="bi bi-egg-fried me-1 text-primary"></i> Món Lẻ Tự Chọn (<?= count($dishes) ?> món)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-sets-btn" data-bs-toggle="tab" data-bs-target="#tab-sets" type="button" role="tab">
                        <i class="bi bi-box2-heart me-1 text-danger"></i> Set Tiệc Trọn Gói (<?= count($sets) ?> set)
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="orderMenuTabsContent">
                
                <!-- TAB 1: DANH SÁCH MÓN LẺ -->
                <div class="tab-pane fade show active" id="tab-dishes" role="tabpanel">
                    
                    <!-- BỘ LỌC DANH MỤC & TÌM KIẾM NHANH -->
                    <div class="bg-white p-3 rounded-3 border mb-4 shadow-sm">
                        <div class="row g-2 align-items-center mb-3">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                    <input type="text" id="dishSearchInput" class="form-control border-start-0" placeholder="Tìm tên món ăn (ví dụ: cá chẽm, bò, lẩu, bánh kem...)">
                                </div>
                            </div>
                            <div class="col-md-5 text-md-end text-muted small">
                                Đang hiển thị <span id="displayDishCount" class="fw-bold text-dark"><?= count($dishes) ?></span> món
                            </div>
                        </div>

                        <!-- Pill filters -->
                        <div class="d-flex flex-wrap gap-2" id="categoryFilterContainer">
                            <button type="button" class="menu-filter-btn active" data-cat="all">
                                <i class="bi bi-grid-fill"></i> Tất cả
                            </button>
                            <?php foreach ($categories as $cat): ?>
                                <button type="button" class="menu-filter-btn" data-cat="<?= htmlspecialchars($cat) ?>">
                                    <?= htmlspecialchars($cat) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- GRID DANH SÁCH MÓN ĂN -->
                    <div class="row g-3" id="dishesGridList">
                        <?php foreach ($dishes as $d): ?>
                            <?php
                            $dishId = (int)$d['id'];
                            $currentQty = $currentDishesMap[$dishId]['so_luong'] ?? 0;
                            ?>
                            <div class="col-md-6 dish-card-col" 
                                 data-id="<?= $dishId ?>"
                                 data-name="<?= htmlspecialchars(strtolower($d['ten'])) ?>"
                                 data-category="<?= htmlspecialchars($d['nhom_mon']) ?>"
                                 data-price="<?= (float)$d['gia'] ?>">
                                
                                <div class="dish-card-order <?= $currentQty > 0 ? 'selected-in-cart' : '' ?>" id="dishCard_<?= $dishId ?>">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-light text-secondary border small">
                                                <?= htmlspecialchars($d['nhom_mon']) ?>
                                            </span>
                                            <span class="fw-bold fs-6" style="color: var(--terra);">
                                                <?= number_format($d['gia'], 0, ',', '.') ?> đ
                                            </span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1 dish-title-text" style="line-height: 1.4;">
                                            <?= htmlspecialchars($d['ten']) ?>
                                        </h6>
                                        <p class="text-muted small mb-3" style="font-size: 0.8rem; line-height: 1.5;">
                                            <?= !empty($d['mo_ta']) ? htmlspecialchars(mb_strimwidth($d['mo_ta'], 0, 75, '...')) : 'Món ăn đặc sắc chuẩn vị từ bếp trưởng QHT.' ?>
                                        </p>
                                    </div>

                                    <!-- NÚT THÊM / ĐIỀU CHỈNH SỐ LƯỢNG -->
                                    <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                        <div class="action-btn-wrapper" id="btnWrapper_<?= $dishId ?>">
                                            <?php if ($currentQty > 0): ?>
                                                <div class="d-flex align-items-center gap-1">
                                                    <button type="button" class="qty-btn" onclick="updateDishQty(<?= $dishId ?>, -1)">-</button>
                                                    <span class="fw-bold px-2 text-dark fs-6" id="cardQty_<?= $dishId ?>"><?= $currentQty ?></span>
                                                    <button type="button" class="qty-btn" onclick="updateDishQty(<?= $dishId ?>, 1)">+</button>
                                                </div>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-outline-dark px-3 fw-semibold" onclick="updateDishQty(<?= $dishId ?>, 1)">
                                                    <i class="bi bi-plus-lg me-1"></i>Thêm món
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        <span class="small text-muted" id="dishSubtotal_<?= $dishId ?>" style="font-size: 0.78rem;">
                                            <?= $currentQty > 0 ? number_format($currentQty * $d['gia'], 0, ',', '.') . ' đ' : '' ?>
                                        </span>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- EMPTY STATE KHI TÌM KIẾM KHÔNG THẤY -->
                    <div id="noDishFoundAlert" class="alert alert-light text-center py-5 border shadow-sm" style="display: none;">
                        <i class="bi bi-search text-muted fs-1 d-block mb-2"></i>
                        <h6 class="fw-bold text-secondary">Không tìm thấy món ăn nào phù hợp</h6>
                        <p class="text-muted small mb-0">Thử tìm kiếm với từ khóa khác hoặc bấm nút "Tất cả" để xem toàn bộ thực đơn.</p>
                    </div>

                </div>

                <!-- TAB 2: DANH SÁCH SET TIỆC -->
                <div class="tab-pane fade" id="tab-sets" role="tabpanel">
                    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
                        <div class="small">
                            <strong>Mẹo tiết kiệm:</strong> Chọn 1 Set Tiệc trọn gói phù hợp với số lượng khách của bạn. Bạn vẫn có thể chọn thêm các món lẻ, bánh kem hay nước uống ở tab bên cạnh!
                        </div>
                    </div>

                    <div class="row g-4">
                        <?php foreach ($sets as $st): ?>
                            <?php
                            $isSetSelected = (!empty($booking['set_id']) && (int)$booking['set_id'] === (int)$st['id']);
                            $itemsInSet = $setDishes[$st['id']] ?? [];
                            ?>
                            <div class="col-md-6">
                                <div class="set-card-order p-4 h-100 d-flex flex-column justify-content-between <?= $isSetSelected ? 'active-set' : '' ?>" id="setCard_<?= $st['id'] ?>">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-primary text-white px-2 py-1 small">
                                                <i class="bi bi-people-fill me-1"></i>Phù hợp <?= $st['so_nguoi_phu_hop'] ?> khách
                                            </span>
                                            <span class="fw-bold fs-5" style="color: var(--terra);">
                                                <?= number_format($st['gia'], 0, ',', '.') ?> đ
                                            </span>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-2"><?= htmlspecialchars($st['ten']) ?></h5>
                                        <p class="text-muted small mb-3"><?= htmlspecialchars($st['mo_ta'] ?? '') ?></p>

                                        <?php if (!empty($itemsInSet)): ?>
                                            <div class="p-2 rounded bg-light border mb-3">
                                                <div class="small fw-bold text-secondary mb-1">Món ăn trong set:</div>
                                                <ul class="list-unstyled mb-0 small text-dark ps-2">
                                                    <?php foreach ($itemsInSet as $si): ?>
                                                        <li><i class="bi bi-check2 text-success me-1"></i><?= htmlspecialchars($si['ten']) ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="pt-3 border-top mt-auto text-end">
                                        <button type="button" 
                                                class="btn btn-sm <?= $isSetSelected ? 'btn-success' : 'btn-outline-forest' ?> fw-semibold px-4" 
                                                id="setSelectBtn_<?= $st['id'] ?>"
                                                onclick="toggleSetSelection(<?= $st['id'] ?>, '<?= htmlspecialchars(addslashes($st['ten'])) ?>', <?= (float)$st['gia'] ?>)">
                                            <?= $isSetSelected ? '<i class="bi bi-check-circle-fill me-1"></i>Đang Chọn Set Này' : '<i class="bi bi-plus-circle me-1"></i>Chọn Set Này' ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

        </div>

        <!-- CỘT PHẢI: GIỎ HÀNG / MÂM CỖ CỦA BẠN (4 COLS - STICKY) -->
        <div class="col-lg-4">
            <div class="cart-sticky-box">
                
                <form action="<?= BASE_URL ?>?action=xu-ly-dat-mon" method="POST" id="orderFoodForm">
                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                    <input type="hidden" name="set_id" id="formInputSetId" value="<?= $booking['set_id'] ?? '' ?>">
                    <div id="formHiddenDishesInputs"></div>

                    <!-- Cart Header -->
                    <div class="p-3 bg-dark text-white d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-basket3-fill text-warning fs-5"></i>
                            <h6 class="fw-bold mb-0">Mâm Cỗ Của Bạn</h6>
                        </div>
                        <span class="badge bg-warning text-dark fw-bold rounded-pill" id="cartTotalItemsBadge">0 món</span>
                    </div>

                    <!-- Cart Content -->
                    <div class="p-3">

                        <!-- KHỐI SET TIỆC ĐÃ CHỌN TRONG GIỎ -->
                        <div id="cartSetBox" class="p-2 mb-3 rounded border bg-light" style="display: <?= !empty($booking['set_id']) ? 'block' : 'none' ?>;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="badge bg-primary-subtle text-primary border small mb-1">Set Menu</span>
                                    <h6 class="fw-bold mb-0 text-dark small" id="cartSetName"><?= htmlspecialchars($booking['ten_set'] ?? '') ?></h6>
                                    <span class="text-danger fw-bold small" id="cartSetPrice">
                                        <?= !empty($booking['gia_set_luc_dat']) ? number_format($booking['gia_set_luc_dat'], 0, ',', '.') . ' đ' : '' ?>
                                    </span>
                                </div>
                                <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" onclick="removeSetSelection()" title="Bỏ chọn set này">
                                    <i class="bi bi-x-circle-fill fs-5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- DANH SÁCH MÓN LẺ TRONG GIỎ (SCROLLABLE) -->
                        <div class="cart-items-scroll mb-3 pe-1" id="cartItemsContainer">
                            <!-- JS tự render các món lẻ ở đây -->
                        </div>

                        <!-- EMPTY CART STATE -->
                        <div id="cartEmptyState" class="text-center py-4 text-muted" style="display: none;">
                            <i class="bi bi-cart-x fs-1 text-secondary opacity-50 d-block mb-2"></i>
                            <p class="small mb-0">Mâm cỗ chưa có món nào.<br>Hãy bấm "+ Thêm món" từ danh sách bên trái!</p>
                        </div>

                        <!-- GHI CHÚ CHẾ BIẾN / ĐẶC BIỆT -->
                        <div class="mb-3 pt-2 border-top">
                            <label class="form-label small fw-semibold text-secondary mb-1">
                                <i class="bi bi-chat-left-text me-1"></i>Ghi chú cho nhà bếp / phục vụ:
                            </label>
                            <textarea name="ghi_chu" class="form-control form-control-sm" rows="2" placeholder="Ví dụ: Ít cay, chuẩn bị bánh kem lúc 19h30, chuẩn bị thêm ghế trẻ em..."><?= htmlspecialchars($booking['ghi_chu'] ?? '') ?></textarea>
                        </div>

                        <!-- BẢNG TÍNH CHI PHÍ TẠM TÍNH -->
                        <div class="p-3 rounded bg-light border mb-3">
                            <div class="d-flex justify-content-between text-muted small mb-1">
                                <span>Tiền món lẻ:</span>
                                <span class="fw-semibold text-dark" id="billDishesTotal">0 đ</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted small mb-1">
                                <span>Tiền set tiệc:</span>
                                <span class="fw-semibold text-dark" id="billSetTotal">0 đ</span>
                            </div>
                            <?php if ($vipPhuPhi > 0): ?>
                                <div class="d-flex justify-content-between text-muted small mb-1">
                                    <span>Phụ phí phòng VIP:</span>
                                    <span class="fw-semibold text-dark"><?= number_format($vipPhuPhi, 0, ',', '.') ?> đ</span>
                                </div>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
                                <strong class="text-dark">Tổng tạm tính:</strong>
                                <strong class="fs-5 text-danger" id="billGrandTotal">0 đ</strong>
                            </div>
                        </div>

                        <!-- NÚT XÁC NHẬN ĐẶT MÓN -->
                        <button type="submit" class="btn btn-warning w-100 py-3 fw-bold shadow-sm text-dark fs-6" id="btnSubmitOrder">
                            <i class="bi bi-check2-circle me-1"></i> Lưu &amp; Xác Nhận Đặt Món
                        </button>

                        <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="btn btn-outline-secondary w-100 mt-2 btn-sm py-2">
                            Quay lại lịch sử đặt bàn
                        </a>

                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

<!-- ═══ SCRIPT ĐIỀU KHIỂN ĐẶT MÓN THEO THỜI GIAN THỰC ═══ -->
<script>
// Dữ liệu món ăn từ server
const ALL_DISHES = <?= json_encode(array_column($dishes, null, 'id'), JSON_UNESCAPED_UNICODE) ?>;
const ALL_SETS = <?= json_encode(array_column($sets, null, 'id'), JSON_UNESCAPED_UNICODE) ?>;
const VIP_SURCHARGE = <?= $vipPhuPhi ?>;

// State giỏ hàng
let cartDishes = {}; // { dishId: { qty: X, price: Y, name: 'Z', category: '...' } }
let selectedSet = null; // { id: X, name: '...', price: Y } or null

// Khởi tạo state từ dữ liệu hiện có của đơn đặt bàn
<?php if (!empty($currentDishesMap)): ?>
    <?php foreach ($currentDishesMap as $mId => $info): ?>
        if (ALL_DISHES[<?= $mId ?>]) {
            cartDishes[<?= $mId ?>] = {
                qty: <?= $info['so_luong'] ?>,
                price: <?= $info['don_gia'] ?>,
                name: <?= json_encode($info['ten_mon'], JSON_UNESCAPED_UNICODE) ?>,
                category: ALL_DISHES[<?= $mId ?>].nhom_mon || ''
            };
        }
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($booking['set_id']) && isset($sets)): ?>
    <?php foreach ($sets as $st): ?>
        <?php if ((int)$st['id'] === (int)$booking['set_id']): ?>
            selectedSet = {
                id: <?= (int)$st['id'] ?>,
                name: <?= json_encode($st['ten'], JSON_UNESCAPED_UNICODE) ?>,
                price: <?= (float)($booking['gia_set_luc_dat'] ?? $st['gia']) ?>
            };
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

// Khi trang load xong
document.addEventListener('DOMContentLoaded', function() {
    renderCart();
    initFiltersAndSearch();
});

/**
 * Cập nhật số lượng của một món ăn (+1 hoặc -1)
 */
function updateDishQty(dishId, change) {
    dishId = parseInt(dishId);
    const dishData = ALL_DISHES[dishId];
    if (!dishData) return;

    if (!cartDishes[dishId]) {
        if (change > 0) {
            cartDishes[dishId] = {
                qty: change,
                price: parseFloat(dishData.gia),
                name: dishData.ten,
                category: dishData.nhom_mon
            };
        }
    } else {
        cartDishes[dishId].qty += change;
        if (cartDishes[dishId].qty <= 0) {
            delete cartDishes[dishId];
        }
    }

    updateCardUI(dishId);
    renderCart();
}

/**
 * Cập nhật giao diện của 1 thẻ món ăn trên cột trái
 */
function updateCardUI(dishId) {
    const card = document.getElementById('dishCard_' + dishId);
    const btnWrapper = document.getElementById('btnWrapper_' + dishId);
    const subtotalEl = document.getElementById('dishSubtotal_' + dishId);
    if (!card || !btnWrapper) return;

    const inCart = cartDishes[dishId];
    if (inCart && inCart.qty > 0) {
        card.classList.add('selected-in-cart');
        btnWrapper.innerHTML = `
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="qty-btn" onclick="updateDishQty(${dishId}, -1)">-</button>
                <span class="fw-bold px-2 text-dark fs-6" id="cardQty_${dishId}">${inCart.qty}</span>
                <button type="button" class="qty-btn" onclick="updateDishQty(${dishId}, 1)">+</button>
            </div>
        `;
        if (subtotalEl) {
            subtotalEl.textContent = (inCart.qty * inCart.price).toLocaleString('vi-VN') + ' đ';
        }
    } else {
        card.classList.remove('selected-in-cart');
        btnWrapper.innerHTML = `
            <button type="button" class="btn btn-sm btn-outline-dark px-3 fw-semibold" onclick="updateDishQty(${dishId}, 1)">
                <i class="bi bi-plus-lg me-1"></i>Thêm món
            </button>
        `;
        if (subtotalEl) {
            subtotalEl.textContent = '';
        }
    }
}

/**
 * Chọn hoặc bỏ chọn Set tiệc
 */
function toggleSetSelection(setId, setName, setPrice) {
    setId = parseInt(setId);
    if (selectedSet && selectedSet.id === setId) {
        removeSetSelection();
    } else {
        selectedSet = { id: setId, name: setName, price: setPrice };
        renderSetUI();
        renderCart();
    }
}

function removeSetSelection() {
    selectedSet = null;
    renderSetUI();
    renderCart();
}

function renderSetUI() {
    document.querySelectorAll('.set-card-order').forEach(card => card.classList.remove('active-set'));
    document.querySelectorAll('[id^="setSelectBtn_"]').forEach(btn => {
        btn.className = 'btn btn-sm btn-outline-forest fw-semibold px-4';
        btn.innerHTML = '<i class="bi bi-plus-circle me-1"></i>Chọn Set Này';
    });

    if (selectedSet) {
        const activeCard = document.getElementById('setCard_' + selectedSet.id);
        const activeBtn = document.getElementById('setSelectBtn_' + selectedSet.id);
        if (activeCard) activeCard.classList.add('active-set');
        if (activeBtn) {
            activeBtn.className = 'btn btn-sm btn-success fw-semibold px-4';
            activeBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Đang Chọn Set Này';
        }
    }
}

/**
 * Render toàn bộ giỏ hàng (cột phải)
 */
function renderCart() {
    const container = document.getElementById('cartItemsContainer');
    const emptyState = document.getElementById('cartEmptyState');
    const setBox = document.getElementById('cartSetBox');
    const badge = document.getElementById('cartTotalItemsBadge');
    const hiddenInputs = document.getElementById('formHiddenDishesInputs');
    const formInputSetId = document.getElementById('formInputSetId');

    let totalDishesPrice = 0;
    let totalDishesCount = 0;
    container.innerHTML = '';
    hiddenInputs.innerHTML = '';

    // Render món lẻ
    const dishIds = Object.keys(cartDishes);
    dishIds.forEach(id => {
        const item = cartDishes[id];
        totalDishesPrice += item.qty * item.price;
        totalDishesCount += item.qty;

        const row = document.createElement('div');
        row.className = 'p-2 rounded bg-light border mb-2';
        row.innerHTML = `
            <div class="d-flex justify-content-between align-items-start mb-1">
                <div class="pe-2" style="max-width: 70%;">
                    <div class="fw-semibold text-dark small" style="line-height: 1.3;">${item.name}</div>
                    <div class="text-muted" style="font-size: 0.72rem;">${item.price.toLocaleString('vi-VN')} đ / phần</div>
                </div>
                <button type="button" class="btn btn-sm text-danger p-0 border-0" onclick="updateDishQty(${id}, -${item.qty})" title="Xóa món này">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-1 border-top" style="font-size: 0.8rem;">
                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="qty-btn" style="width:22px;height:22px;font-size:0.75rem;" onclick="updateDishQty(${id}, -1)">-</button>
                    <span class="fw-bold px-1 text-dark">${item.qty}</span>
                    <button type="button" class="qty-btn" style="width:22px;height:22px;font-size:0.75rem;" onclick="updateDishQty(${id}, 1)">+</button>
                </div>
                <strong class="text-danger">${(item.qty * item.price).toLocaleString('vi-VN')} đ</strong>
            </div>
        `;
        container.appendChild(row);

        // Chèn hidden input cho form
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `dishes[${id}]`;
        input.value = item.qty;
        hiddenInputs.appendChild(input);
    });

    // Render Set tiệc
    let totalSetPrice = 0;
    if (selectedSet) {
        setBox.style.display = 'block';
        document.getElementById('cartSetName').textContent = selectedSet.name;
        document.getElementById('cartSetPrice').textContent = selectedSet.price.toLocaleString('vi-VN') + ' đ';
        formInputSetId.value = selectedSet.id;
        totalSetPrice = selectedSet.price;
    } else {
        setBox.style.display = 'none';
        formInputSetId.value = '';
    }

    // Hiển thị rỗng nếu không có gì
    if (dishIds.length === 0 && !selectedSet) {
        emptyState.style.display = 'block';
    } else {
        emptyState.style.display = 'none';
    }

    // Cập nhật số lượng món trên badge
    const totalItems = totalDishesCount + (selectedSet ? 1 : 0);
    badge.textContent = `${totalItems} mục`;

    // Cập nhật các dòng tổng tiền
    document.getElementById('billDishesTotal').textContent = totalDishesPrice.toLocaleString('vi-VN') + ' đ';
    document.getElementById('billSetTotal').textContent = totalSetPrice.toLocaleString('vi-VN') + ' đ';
    
    const grandTotal = totalDishesPrice + totalSetPrice + VIP_SURCHARGE;
    document.getElementById('billGrandTotal').textContent = grandTotal.toLocaleString('vi-VN') + ' đ';
}

/**
 * Tìm kiếm & Lọc danh mục món ăn
 */
function initFiltersAndSearch() {
    const searchInput = document.getElementById('dishSearchInput');
    const filterBtns = document.querySelectorAll('.menu-filter-btn');
    const dishCols = document.querySelectorAll('.dish-card-col');
    const countEl = document.getElementById('displayDishCount');
    const noResult = document.getElementById('noDishFoundAlert');

    let currentCat = 'all';
    let currentKeyword = '';

    function applyFilter() {
        let visibleCount = 0;
        dishCols.forEach(col => {
            const name = col.getAttribute('data-name') || '';
            const cat = col.getAttribute('data-category') || '';

            const matchCat = (currentCat === 'all' || cat.toLowerCase() === currentCat.toLowerCase());
            const matchKeyword = (!currentKeyword || name.includes(currentKeyword));

            if (matchCat && matchKeyword) {
                col.style.display = '';
                visibleCount++;
            } else {
                col.style.display = 'none';
            }
        });

        countEl.textContent = visibleCount;
        noResult.style.display = (visibleCount === 0) ? 'block' : 'none';
    }

    // Lọc theo từ khóa
    searchInput.addEventListener('input', function(e) {
        currentKeyword = e.target.value.toLowerCase().trim();
        applyFilter();
    });

    // Lọc theo danh mục
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentCat = this.getAttribute('data-cat') || 'all';
            applyFilter();
        });
    });
}
</script>
