<?php
// views/admin/reviews.php - Quản lý đánh giá & bình luận
$totalReviews = (int)($stats['total_reviews'] ?? 0);
$avgStars = (float)($stats['avg_stars'] ?? 5.0);
$positiveRate = $totalReviews > 0 ? round((($stats['star_5'] + $stats['star_4']) / $totalReviews) * 100, 1) : 100;
?>

<style>
.stat-widget {
    border-radius: 14px;
    border: 1px solid #eef2f6;
    background: #fff;
    padding: 1.25rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.stat-widget:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}
.table-review th {
    background: #f8fafc;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    font-weight: 600;
    white-space: nowrap;
    border-bottom: 2px solid #e2e8f0;
}
.star-rating-badge {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 4px 10px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.85rem;
}
.comment-bubble {
    background: #f8fafc;
    border-left: 3px solid #f59e0b;
    border-radius: 0 10px 10px 0;
    padding: 10px 14px;
    font-size: 0.92rem;
    color: #334155;
    position: relative;
    max-width: 480px;
}
.filter-star-btn.active {
    background: #f59e0b !important;
    color: #fff !important;
    border-color: #f59e0b !important;
}
</style>

<div class="container-fluid px-4 py-3">

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="bi bi-star-half text-warning me-2"></i>Quản lý Đánh Giá & Nhận Xét
            </h4>
            <p class="text-muted mb-0 small">Theo dõi phản hồi, mức độ hài lòng và quản lý bình luận từ thực khách đã đặt tiệc</p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>?action=danh-gia" target="_blank" class="btn btn-outline-warning text-dark fw-bold btn-sm shadow-sm">
                <i class="bi bi-box-arrow-up-right me-1"></i>Xem trang đánh giá công khai
            </a>
        </div>
    </div>

    <!-- Flash message -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
            <div><?= $_SESSION['success'] ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
            <div><?= $_SESSION['error'] ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Thống kê tổng quan -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-widget d-flex align-items-center">
                <div class="rounded-circle bg-warning-subtle text-warning p-3 me-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="bi bi-chat-heart fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Tổng lượt đánh giá</div>
                    <div class="fs-4 fw-bold text-dark"><?= number_format($totalReviews) ?></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-widget d-flex align-items-center">
                <div class="rounded-circle bg-warning-subtle text-warning p-3 me-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="bi bi-star-fill fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Điểm đánh giá TB</div>
                    <div class="fs-4 fw-bold text-warning"><?= number_format($avgStars, 1) ?> <span class="text-muted fs-6">/ 5.0</span></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-widget d-flex align-items-center">
                <div class="rounded-circle bg-success-subtle text-success p-3 me-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="bi bi-hand-thumbs-up fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Tỷ lệ hài lòng (4-5★)</div>
                    <div class="fs-4 fw-bold text-success"><?= $positiveRate ?>%</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-widget">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small fw-semibold text-muted">5★ Tuyệt vời</span>
                    <span class="small fw-bold text-dark"><?= $stats['star_5'] ?></span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-warning" style="width: <?= $totalReviews > 0 ? ($stats['star_5'] / $totalReviews * 100) : 0 ?>%"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2 mb-1">
                    <span class="small fw-semibold text-muted">1-2★ Cần cải thiện</span>
                    <span class="small fw-bold text-dark"><?= $stats['star_1'] + $stats['star_2'] ?></span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-danger" style="width: <?= $totalReviews > 0 ? (($stats['star_1'] + $stats['star_2']) / $totalReviews * 100) : 0 ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thanh công cụ tìm kiếm và bộ lọc sao -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <!-- Lọc sao -->
                <div class="d-flex flex-wrap gap-1 align-items-center" id="starFilterGroup">
                    <span class="text-muted small fw-semibold me-2"><i class="bi bi-funnel me-1"></i>Lọc sao:</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-star-btn active rounded-pill px-3" data-star="all">Tất cả (<?= $totalReviews ?>)</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-star-btn rounded-pill px-3" data-star="5">5★ (<?= $stats['star_5'] ?>)</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-star-btn rounded-pill px-3" data-star="4">4★ (<?= $stats['star_4'] ?>)</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-star-btn rounded-pill px-3" data-star="3">3★ (<?= $stats['star_3'] ?>)</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary filter-star-btn rounded-pill px-3" data-star="low">1-2★ (<?= $stats['star_1'] + $stats['star_2'] ?>)</button>
                </div>

                <!-- Ô tìm kiếm -->
                <div style="min-width: 280px; max-width: 380px;" class="w-100 flex-grow-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="reviewSearchInput" class="form-control border-start-0 bg-light" placeholder="Tìm tên khách, số điện thoại, mã đơn, nội dung...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng danh sách đánh giá -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-review align-middle mb-0" id="reviewTable">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 80px;">Mã</th>
                            <th style="width: 200px;">Khách hàng</th>
                            <th style="width: 210px;">Đơn đặt tiệc</th>
                            <th style="width: 140px;">Đánh giá</th>
                            <th>Nội dung nhận xét</th>
                            <th style="width: 150px;">Thời gian</th>
                            <th class="text-end pe-4" style="width: 110px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reviews)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-chat-square-dots fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                    Chưa có nhận xét hoặc đánh giá nào trong hệ thống.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reviews as $rev): ?>
                                <?php
                                $sao = (int)$rev['so_sao'];
                                $badgeClass = match(true) {
                                    $sao >= 5 => 'bg-success text-white',
                                    $sao === 4 => 'bg-primary text-white',
                                    $sao === 3 => 'bg-warning text-dark',
                                    default => 'bg-danger text-white'
                                };
                                $searchData = mb_strtolower(
                                    ($rev['user_name'] ?? '') . ' ' . 
                                    ($rev['user_phone'] ?? '') . ' ' . 
                                    '#db' . str_pad($rev['booking_id'], 4, '0', STR_PAD_LEFT) . ' ' . 
                                    ($rev['ban_ten'] ?? '') . ' ' . 
                                    ($rev['set_ten'] ?? '') . ' ' . 
                                    ($rev['binh_luan'] ?? '')
                                );
                                ?>
                                <tr class="review-row-item" data-stars="<?= $sao ?>" data-search="<?= htmlspecialchars($searchData, ENT_QUOTES, 'UTF-8') ?>">
                                    <td class="ps-4 fw-bold text-muted">#<?= $rev['id'] ?></td>
                                    <td>
                                        <div class="fw-semibold text-dark"><?= htmlspecialchars($rev['user_name'] ?? 'Khách hàng') ?></div>
                                        <div class="small text-muted"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($rev['user_phone'] ?? '---') ?></div>
                                        <?php if (!empty($rev['user_email'])): ?>
                                            <div class="small text-muted text-truncate" style="max-width: 180px;"><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($rev['user_email']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?action=admin-dat-ban-chi-tiet&id=<?= $rev['booking_id'] ?>" class="fw-bold text-primary text-decoration-none" title="Xem chi tiết đơn đặt bàn">
                                            #DB<?= str_pad($rev['booking_id'], 4, '0', STR_PAD_LEFT) ?>
                                        </a>
                                        <div class="small text-secondary mt-1">
                                            <?php if (!empty($rev['ban_ten'])): ?>
                                                <span class="badge bg-light text-dark border me-1"><i class="bi bi-door-open me-1"></i><?= htmlspecialchars($rev['ban_ten']) ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($rev['set_ten'])): ?>
                                                <span class="badge bg-light text-dark border"><i class="bi bi-stars me-1"></i><?= htmlspecialchars($rev['set_ten']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="small text-muted mt-1">
                                            <i class="bi bi-calendar-event me-1"></i><?= date('d/m/Y', strtotime($rev['ngay_dat'])) ?> 
                                            (<?= ($rev['ca'] ?? '') === 'trua' ? '☀️ Trưa' : '🌙 Tối' ?>)
                                        </div>
                                    </td>
                                    <td>
                                        <span class="star-rating-badge <?= $badgeClass ?> mb-1">
                                            <i class="bi bi-star-fill text-warning"></i> <?= $sao ?> / 5
                                        </span>
                                        <div class="text-warning small" style="letter-spacing: 2px;">
                                            <?= str_repeat('★', $sao) . str_repeat('☆', 5 - $sao) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($rev['binh_luan'])): ?>
                                            <div class="comment-bubble">
                                                <i class="bi bi-quote fs-5 text-warning opacity-75"></i>
                                                <?= nl2br(htmlspecialchars($rev['binh_luan'])) ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small fst-italic">Khách không để lại lời bình, chỉ chấm điểm sao.</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small text-muted">
                                        <div><i class="bi bi-clock me-1"></i><?= date('H:i', strtotime($rev['ngay'])) ?></div>
                                        <div><i class="bi bi-calendar-check me-1"></i><?= date('d/m/Y', strtotime($rev['ngay'])) ?></div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= BASE_URL ?>?action=admin-dat-ban-chi-tiet&id=<?= $rev['booking_id'] ?>" class="btn btn-outline-secondary" title="Xem đơn đặt bàn">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" title="Xóa đánh giá này" onclick="confirmDeleteReview(<?= $rev['id'] ?>, '<?= htmlspecialchars(addslashes($rev['user_name'] ?? 'Khách'), ENT_QUOTES) ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Khi lọc không có kết quả -->
            <div id="noMatchMessage" class="text-center py-5 text-muted" style="display: none;">
                <i class="bi bi-search fs-2 d-block mb-2 text-secondary opacity-50"></i>
                Không tìm thấy đánh giá nào phù hợp với bộ lọc hiện tại.
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteReviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-circle-fill" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Xóa đánh giá này?</h5>
                <p class="text-muted small mb-4">
                    Bạn có chắc chắn muốn xóa đánh giá của khách hàng <strong id="delCustomerName"></strong>? Thao tác này không thể khôi phục.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-3 fw-semibold" data-bs-dismiss="modal">Hủy</button>
                    <a id="delConfirmBtn" href="#" class="btn btn-danger px-3 fw-semibold">Xóa vĩnh viễn</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentStarFilter = 'all';
let currentSearchText = '';

function applyFilters() {
    const rows = document.querySelectorAll('.review-row-item');
    let visibleCount = 0;

    rows.forEach(row => {
        const star = parseInt(row.getAttribute('data-stars')) || 5;
        const search = row.getAttribute('data-search') || '';

        // Kiểm tra sao
        let matchStar = true;
        if (currentStarFilter === '5') matchStar = (star === 5);
        else if (currentStarFilter === '4') matchStar = (star === 4);
        else if (currentStarFilter === '3') matchStar = (star === 3);
        else if (currentStarFilter === 'low') matchStar = (star <= 2);

        // Kiểm tra tìm kiếm
        let matchSearch = true;
        if (currentSearchText !== '') {
            matchSearch = search.includes(currentSearchText);
        }

        if (matchStar && matchSearch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const noMatch = document.getElementById('noMatchMessage');
    if (noMatch) {
        noMatch.style.display = (visibleCount === 0 && rows.length > 0) ? 'block' : 'none';
    }
}

// Bắt sự kiện lọc sao
document.querySelectorAll('.filter-star-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-star-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        currentStarFilter = this.getAttribute('data-star');
        applyFilters();
    });
});

// Bắt sự kiện tìm kiếm
document.getElementById('reviewSearchInput')?.addEventListener('input', function(e) {
    currentSearchText = e.target.value.toLowerCase().trim();
    applyFilters();
});

// Modal xác nhận xóa
function confirmDeleteReview(id, customerName) {
    document.getElementById('delCustomerName').textContent = customerName;
    document.getElementById('delConfirmBtn').href = '<?= BASE_URL ?>?action=admin-danh-gia-xoa&id=' + id;
    const modal = new bootstrap.Modal(document.getElementById('deleteReviewModal'));
    modal.show();
}
</script>
