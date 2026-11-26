<?php
/**
 * View: Trang Đánh Giá & Nhận Xét Của Thực Khách
 * Biến truyền từ ReviewController:
 * - $reviews
 * - $stats
 * - $userBookings
 * - $starFilter
 * - $isLoggedIn
 */

$totalReviews = $stats['total'];
$avgStars = number_format($stats['avg_stars'], 1);
?>

<style>
    .reviews-hero {
        padding: 130px 0 60px;
        background: linear-gradient(135deg, var(--forest-dk) 0%, var(--forest) 100%);
        color: #fff;
        text-align: center;
        position: relative;
    }
    .reviews-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse 60% 60% at 50% 50%, rgba(212,168,67,0.18) 0%, transparent 70%);
        pointer-events: none;
    }
    .review-score-box {
        background: #fff;
        border: 1px solid var(--stone);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        margin-top: -35px;
        position: relative;
        z-index: 10;
    }
    .score-big {
        font-family: 'DM Serif Display', serif;
        font-size: 3.8rem;
        line-height: 1;
        color: var(--charcoal);
    }
    .stars-gold {
        color: #F59E0B;
        letter-spacing: 2px;
    }
    .progress-star {
        height: 8px;
        border-radius: 4px;
        background-color: #E2E8F0;
    }
    .progress-bar-star {
        background: linear-gradient(90deg, #F59E0B, #FBBF24);
        border-radius: 4px;
    }
    .review-filter-btn {
        background: #fff;
        color: var(--charcoal);
        border: 1.5px solid var(--stone);
        border-radius: 30px;
        padding: 6px 18px;
        font-size: 0.82rem;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .review-filter-btn:hover, .review-filter-btn.active {
        background: var(--forest);
        color: #fff;
        border-color: var(--forest);
        box-shadow: 0 4px 12px rgba(44, 74, 53, 0.2);
    }
    .review-card {
        background: #fff;
        border: 1px solid var(--stone);
        border-radius: 14px;
        padding: 24px;
        transition: all 0.25s;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .review-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(44, 74, 53, 0.08);
        border-color: #D4A843;
    }
    .user-avatar-circle {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2C4A35, #C4603C);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    /* Star Rating Selector Widget trong Modal */
    .star-rating-select {
        display: inline-flex;
        flex-direction: row-reverse;
        gap: 8px;
        cursor: pointer;
    }
    .star-rating-select input {
        display: none;
    }
    .star-rating-select label {
        font-size: 2.2rem;
        color: #CBD5E1;
        transition: color 0.2s;
        cursor: pointer;
    }
    .star-rating-select label:hover,
    .star-rating-select label:hover ~ label,
    .star-rating-select input:checked ~ label {
        color: #F59E0B;
    }
</style>

<!-- ═══ HEADER ═══ -->
<section class="reviews-hero">
    <div class="container position-relative" style="z-index: 2;">
        <span class="d-inline-block text-uppercase small fw-semibold" style="letter-spacing: 3px; color: var(--amber-lt);">
            Trải Nghiệm Thực Khách
        </span>
        <h1 class="mt-2 mb-3" style="font-family: 'DM Serif Display', serif; font-size: clamp(2.2rem, 4vw, 3.2rem);">
            Đánh Giá &amp; Nhận Xét Của Thực Khách
        </h1>
        <p class="text-white-50 mx-auto" style="max-width: 620px; font-size: 0.95rem; line-height: 1.8;">
            Lắng nghe từng cảm nhận chân thực nhất từ các gia đình và đối tác đã đồng hành cùng QHT Banquet để chúng tôi không ngừng hoàn thiện.
        </p>
    </div>
</section>

<!-- ═══ KHỐI TỔNG QUAN ĐIỂM ĐÁNH GIÁ ═══ -->
<div class="container">
    <div class="review-score-box p-4 p-md-5 mb-5">
        <div class="row g-4 align-items-center">
            
            <!-- Cột 1: Điểm số trung bình -->
            <div class="col-lg-3 text-center border-end-lg">
                <div class="score-big fw-bold"><?= $avgStars ?></div>
                <div class="stars-gold fs-5 mb-2">
                    <?php 
                    $fullStars = floor($stats['avg_stars']);
                    for ($i = 1; $i <= 5; $i++): 
                    ?>
                        <i class="bi bi-star<?= $i <= $fullStars ? '-fill' : '' ?>"></i>
                    <?php endfor; ?>
                </div>
                <div class="text-muted small">Dựa trên <strong><?= $totalReviews ?></strong> lượt nhận xét</div>
                <span class="badge bg-success-subtle text-success border border-success-subtle mt-2 px-3 py-1">
                    <i class="bi bi-shield-check me-1"></i>100% Khách Hàng Thực Tế
                </span>
            </div>

            <!-- Cột 2: Thanh phân bổ sao -->
            <div class="col-lg-5 border-end-lg px-lg-4">
                <?php for ($s = 5; $s >= 1; $s--): ?>
                    <?php 
                    $cnt = $stats['star_' . $s];
                    $pct = $totalReviews > 0 ? round(($cnt / $totalReviews) * 100) : 0;
                    ?>
                    <div class="d-flex align-items-center gap-2 mb-2 small">
                        <span style="width: 45px;" class="fw-semibold text-secondary"><?= $s ?> sao</span>
                        <div class="progress progress-star flex-grow-1">
                            <div class="progress-bar progress-bar-star" style="width: <?= $pct ?>%;"></div>
                        </div>
                        <span style="width: 40px;" class="text-end text-muted small"><?= $cnt ?></span>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Cột 3: Nút Viết Đánh Giá -->
            <div class="col-lg-4 text-center text-lg-start ps-lg-4">
                <h5 class="fw-bold mb-2">Bạn Đã Trải Nghiệm Tiệc?</h5>
                <p class="text-muted small mb-3">
                    Hãy chia sẻ cảm nhận của bạn về món ăn, không gian và phục vụ để giúp QHT Banquet ngày càng hoàn thiện hơn.
                </p>
                <?php if ($isLoggedIn): ?>
                    <button type="button" class="btn btn-warning fw-bold px-4 py-2 shadow-sm text-dark" onclick="openWriteReviewModal()">
                        <i class="bi bi-pencil-square me-1"></i> Viết Đánh Giá Của Bạn
                    </button>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>?action=dang-nhap" class="btn btn-warning fw-bold px-4 py-2 shadow-sm text-dark">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Đăng Nhập Để Đánh Giá
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<!-- ═══ DANH SÁCH BÌNH LUẬN & ĐÁNH GIÁ ═══ -->
<section class="py-3 mb-5" style="background: var(--cream);">
    <div class="container">

        <!-- THÔNG BÁO FLASH MESSAGE -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i>
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- THANH LỌC THEO SỐ SAO -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 p-3 bg-white rounded-3 border shadow-sm">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <span class="text-muted small fw-semibold me-2"><i class="bi bi-funnel me-1"></i>Lọc theo:</span>
                <a href="<?= BASE_URL ?>?action=danh-gia" class="review-filter-btn <?= empty($starFilter) ? 'active' : '' ?>">
                    Tất cả (<?= $totalReviews ?>)
                </a>
                <a href="<?= BASE_URL ?>?action=danh-gia&star=5" class="review-filter-btn <?= $starFilter === 5 ? 'active' : '' ?>">
                    5 sao (<?= $stats['star_5'] ?>)
                </a>
                <a href="<?= BASE_URL ?>?action=danh-gia&star=4" class="review-filter-btn <?= $starFilter === 4 ? 'active' : '' ?>">
                    4 sao (<?= $stats['star_4'] ?>)
                </a>
                <a href="<?= BASE_URL ?>?action=danh-gia&star=3" class="review-filter-btn <?= $starFilter === 3 ? 'active' : '' ?>">
                    3 sao (<?= $stats['star_3'] ?>)
                </a>
                <a href="<?= BASE_URL ?>?action=danh-gia&star=2" class="review-filter-btn <?= $starFilter === 2 ? 'active' : '' ?>">
                    2 sao (<?= $stats['star_2'] ?>)
                </a>
                <a href="<?= BASE_URL ?>?action=danh-gia&star=1" class="review-filter-btn <?= $starFilter === 1 ? 'active' : '' ?>">
                    1 sao (<?= $stats['star_1'] ?>)
                </a>
            </div>

            <div class="text-muted small">
                Hiển thị <strong><?= count($reviews) ?></strong> đánh giá
            </div>
        </div>

        <!-- GRID CÁC ĐÁNH GIÁ -->
        <?php if (empty($reviews)): ?>
            <div class="text-center py-5 bg-white rounded-3 border shadow-sm">
                <div class="text-muted mb-3">
                    <i class="bi bi-chat-heart" style="font-size: 3.5rem; color: var(--stone);"></i>
                </div>
                <h5 class="fw-bold text-dark">Chưa có đánh giá nào trong mục này</h5>
                <p class="text-muted small mb-4" style="max-width: 450px; margin: 0 auto;">
                    Hãy là người đầu tiên chia sẻ cảm nhận về bữa tiệc của bạn tại QHT Banquet!
                </p>
                <?php if ($isLoggedIn): ?>
                    <button type="button" class="btn btn-warning fw-bold px-4 py-2" onclick="openWriteReviewModal()">
                        <i class="bi bi-star me-1"></i> Gửi Đánh Giá Ngay
                    </button>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>?action=dang-nhap" class="btn btn-warning fw-bold px-4 py-2">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Đăng Nhập Để Đánh Giá
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($reviews as $rev): ?>
                    <?php
                    $initial = mb_strtoupper(mb_substr($rev['ten_khach'] ?? 'K', 0, 1, 'UTF-8'));
                    $isAuthor = ($isLoggedIn && (int)$rev['user_id'] === (int)$_SESSION['user']['id']);
                    $isAdmin  = ($isLoggedIn && ($_SESSION['user']['vai_tro'] ?? '') === 'admin');
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="review-card">
                            <div>
                                <!-- Header Review Card -->
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar-circle">
                                            <?= $initial ?>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($rev['ten_khach']) ?></h6>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.65rem;">
                                                <i class="bi bi-patch-check-fill me-1"></i>Đã trải nghiệm tiệc
                                            </span>
                                        </div>
                                    </div>
                                    <span class="text-muted small">
                                        <?= date('d/m/Y', strtotime($rev['ngay'])) ?>
                                    </span>
                                </div>

                                <!-- Stars -->
                                <div class="stars-gold mb-2 fs-6">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?= $i <= (int)$rev['so_sao'] ? '-fill' : '' ?>"></i>
                                    <?php endfor; ?>
                                    <span class="text-dark fw-bold ms-1 small"><?= $rev['so_sao'] ?>/5</span>
                                </div>

                                <!-- Tiệc đã đặt info -->
                                <div class="p-2 rounded bg-light border small text-secondary mb-3" style="font-size: 0.78rem;">
                                    <i class="bi bi-calendar2-check text-primary me-1"></i>
                                    <strong>Tiệc:</strong> 
                                    <?= !empty($rev['ten_dip']) ? htmlspecialchars($rev['ten_dip']) : 'Tiệc gia đình' ?>
                                    <?php if (!empty($rev['ten_ban'])): ?>
                                        • <?= htmlspecialchars($rev['ten_ban']) ?>
                                    <?php endif; ?>
                                    <?php if (!empty($rev['ten_set'])): ?>
                                        • <?= htmlspecialchars($rev['ten_set']) ?>
                                    <?php endif; ?>
                                </div>

                                <!-- Bình luận -->
                                <p class="text-dark small mb-0" style="line-height: 1.7;">
                                    <?= nl2br(htmlspecialchars($rev['binh_luan'] ?? 'Khách hàng không để lại nhận xét bằng lời.')) ?>
                                </p>
                            </div>

                            <!-- Footer Card nếu là tác giả hoặc admin -->
                            <?php if ($isAuthor || $isAdmin): ?>
                                <div class="pt-3 border-top mt-3 d-flex justify-content-end gap-2">
                                    <?php if ($isAuthor): ?>
                                        <button type="button" class="btn btn-sm btn-outline-warning text-dark py-1 px-2 small" onclick="openEditReviewModal(<?= $rev['datban_id'] ?>, <?= $rev['so_sao'] ?>, '<?= htmlspecialchars(addslashes($rev['binh_luan'] ?? '')) ?>')">
                                            <i class="bi bi-pencil me-1"></i>Sửa
                                        </button>
                                    <?php endif; ?>
                                    <form action="<?= BASE_URL ?>?action=xoa-danh-gia" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?');" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $rev['id'] ?>">
                                        <input type="hidden" name="booking_id" value="<?= $rev['datban_id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2 small">
                                            <i class="bi bi-trash3"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- ═══ MODAL VIẾT / CHỈNH SỬA ĐÁNH GIÁ ═══ -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
      
      <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--forest-dk), var(--forest)); padding: 20px 24px;">
        <h5 class="modal-title fw-bold fs-5 mb-0" id="reviewModalLabel">
          <i class="bi bi-star-fill text-warning me-2"></i>Đánh Giá &amp; Nhận Xét Bữa Tiệc
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="<?= BASE_URL ?>?action=gui-danh-gia" method="POST" id="formSubmitReview">
        <div class="modal-body p-4 bg-white">
          
          <!-- LỰA CHỌN ĐƠN ĐẶT BÀN CẦN ĐÁNH GIÁ -->
          <div class="mb-4" id="bookingSelectGroup">
            <label class="form-label fw-bold small text-secondary text-uppercase" style="letter-spacing: 0.5px;">
              <i class="bi bi-receipt me-1 text-primary"></i>Chọn Bữa Tiệc Cần Đánh Giá:
            </label>
            <?php if (!empty($userBookings)): ?>
              <select name="datban_id" id="modalSelectBookingId" class="form-select form-select-lg shadow-sm" required>
                <?php foreach ($userBookings as $ub): ?>
                  <option value="<?= $ub['id'] ?>" data-has-review="<?= !empty($ub['danh_gia_id']) ? '1' : '0' ?>" data-stars="<?= $ub['so_sao'] ?? 5 ?>" data-comment="<?= htmlspecialchars($ub['binh_luan'] ?? '') ?>">
                    #DB<?= str_pad($ub['id'], 4, '0', STR_PAD_LEFT) ?> - Ngày <?= date('d/m/Y', strtotime($ub['ngay_dat'])) ?> (Ca <?= $ub['ca'] === 'trua' ? 'Trưa' : 'Tối' ?>) • <?= htmlspecialchars($ub['ten_ban'] ?? 'Đang chờ xếp') ?> <?= !empty($ub['danh_gia_id']) ? '★ [Đã đánh giá]' : '' ?>
                  </option>
                <?php endforeach; ?>
              </select>
            <?php else: ?>
              <input type="hidden" name="datban_id" id="modalHiddenBookingId" value="">
              <div id="singleBookingInfoDisplay" class="p-3 bg-light rounded border small">
                <!-- Chèn thông tin đơn cụ thể bằng JS -->
              </div>
            <?php endif; ?>
          </div>

          <!-- BỘ CHỌN SỐ SAO TƯƠNG TÁC (1 - 5 SAO) -->
          <div class="text-center py-2 mb-3 bg-light rounded-3 border">
            <div class="small fw-semibold text-secondary mb-2">Chấm điểm chất lượng trải nghiệm:</div>
            
            <div class="star-rating-select mb-1">
              <input type="radio" id="star5" name="so_sao" value="5" checked onclick="updateStarDesc(5)">
              <label for="star5" title="5 sao - Tuyệt vời"><i class="bi bi-star-fill"></i></label>
              
              <input type="radio" id="star4" name="so_sao" value="4" onclick="updateStarDesc(4)">
              <label for="star4" title="4 sao - Hài lòng"><i class="bi bi-star-fill"></i></label>
              
              <input type="radio" id="star3" name="so_sao" value="3" onclick="updateStarDesc(3)">
              <label for="star3" title="3 sao - Bình thường"><i class="bi bi-star-fill"></i></label>
              
              <input type="radio" id="star2" name="so_sao" value="2" onclick="updateStarDesc(2)">
              <label for="star2" title="2 sao - Chưa hài lòng"><i class="bi bi-star-fill"></i></label>
              
              <input type="radio" id="star1" name="so_sao" value="1" onclick="updateStarDesc(1)">
              <label for="star1" title="1 sao - Rất tệ"><i class="bi bi-star-fill"></i></label>
            </div>

            <div id="starDescText" class="fw-bold text-warning small">
              ⭐⭐⭐⭐⭐ Tuyệt vời! Rất hài lòng về món ăn &amp; dịch vụ
            </div>
          </div>

          <!-- Ô NHẬP BÌNH LUẬN & NHẬN XÉT -->
          <div class="mb-3">
            <label class="form-label fw-bold small text-secondary text-uppercase" style="letter-spacing: 0.5px;">
              <i class="bi bi-chat-quote-fill me-1 text-success"></i>Lời Nhận Xét &amp; Góp Ý Của Bạn:
            </label>
            <textarea name="binh_luan" id="modalReviewComment" class="form-control" rows="4" placeholder="Chia sẻ cảm nhận của bạn về hương vị món ăn, sự bày trí, không gian ấm cúng và thái độ phục vụ của nhân viên..."></textarea>
            <div class="form-text small text-muted">
              Đánh giá của bạn sẽ giúp nhà hàng cải thiện chất lượng phục vụ ngày càng tốt hơn.
            </div>
          </div>

        </div>

        <div class="modal-footer bg-light py-3 px-4 d-flex justify-content-between">
          <button type="button" class="btn btn-secondary px-3 fw-semibold btn-sm" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-warning px-4 fw-bold text-dark shadow-sm" id="modalSubmitBtn">
            <i class="bi bi-send-fill me-1"></i> Gửi Đánh Giá
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- ═══ SCRIPT ĐIỀU KHIỂN MODAL & SAO ═══ -->
<script>
const STAR_TEXTS = {
    5: '⭐⭐⭐⭐⭐ Tuyệt vời! Rất hài lòng về món ăn & dịch vụ',
    4: '⭐⭐⭐⭐☆ Hài lòng! Bữa tiệc diễn ra chu đáo, tốt đẹp',
    3: '⭐⭐⭐☆☆ Tạm ổn! Trải nghiệm ở mức bình thường',
    2: '⭐⭐☆☆☆ Chưa hài lòng! Cần cải thiện thêm chất lượng',
    1: '⭐☆☆☆☆ Rất thất vọng! Không đạt mong đợi'
};

function updateStarDesc(star) {
    const descEl = document.getElementById('starDescText');
    if (descEl) {
        descEl.textContent = STAR_TEXTS[star] || '';
    }
}

/**
 * Mở modal viết đánh giá từ trang reviews
 */
function openWriteReviewModal() {
    const modalEl = document.getElementById('reviewModal');
    if (!modalEl) return;
    
    // Reset form
    setModalStars(5);
    document.getElementById('modalReviewComment').value = '';
    document.getElementById('modalSubmitBtn').innerHTML = '<i class="bi bi-send-fill me-1"></i> Gửi Đánh Giá';

    // Kiểm tra xem option đang chọn có dữ liệu review cũ không
    const selectBooking = document.getElementById('modalSelectBookingId');
    if (selectBooking && selectBooking.selectedOptions[0]) {
        checkPreloadReview(selectBooking.selectedOptions[0]);
    }

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
}

/**
 * Mở modal sửa đánh giá từ thẻ review
 */
function openEditReviewModal(bookingId, stars, comment) {
    const modalEl = document.getElementById('reviewModal');
    if (!modalEl) return;

    const selectBooking = document.getElementById('modalSelectBookingId');
    if (selectBooking) {
        selectBooking.value = bookingId;
    }
    const hiddenBookingId = document.getElementById('modalHiddenBookingId');
    if (hiddenBookingId) {
        hiddenBookingId.value = bookingId;
    }

    setModalStars(stars);
    document.getElementById('modalReviewComment').value = comment || '';
    document.getElementById('modalSubmitBtn').innerHTML = '<i class="bi bi-check2-circle me-1"></i> Cập Nhật Đánh Giá';

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
}

function setModalStars(stars) {
    stars = parseInt(stars) || 5;
    const radio = document.getElementById('star' + stars);
    if (radio) {
        radio.checked = true;
    }
    updateStarDesc(stars);
}

// Khi đổi đơn đặt bàn trong dropdown, tự động load review cũ nếu có
document.getElementById('modalSelectBookingId')?.addEventListener('change', function(e) {
    const opt = e.target.selectedOptions[0];
    checkPreloadReview(opt);
});

function checkPreloadReview(opt) {
    if (!opt) return;
    const hasReview = opt.getAttribute('data-has-review') === '1';
    if (hasReview) {
        const stars = parseInt(opt.getAttribute('data-stars')) || 5;
        const comment = opt.getAttribute('data-comment') || '';
        setModalStars(stars);
        document.getElementById('modalReviewComment').value = comment;
        document.getElementById('modalSubmitBtn').innerHTML = '<i class="bi bi-check2-circle me-1"></i> Cập Nhật Đánh Giá';
    } else {
        setModalStars(5);
        document.getElementById('modalReviewComment').value = '';
        document.getElementById('modalSubmitBtn').innerHTML = '<i class="bi bi-send-fill me-1"></i> Gửi Đánh Giá';
    }
}
</script>
