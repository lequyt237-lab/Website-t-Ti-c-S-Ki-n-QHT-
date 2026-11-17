<?php /** @var array $sets */ ?>

<!-- Thẻ thông báo -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<style>
    .set-card {
        border: none;
        border-radius: 14px;
        transition: all 0.25s ease;
        overflow: hidden;
    }
    .set-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(44, 74, 53, 0.12) !important;
    }
    .set-card .card-header {
        background: linear-gradient(135deg, #2C4A35 0%, #3b6b4d 100%);
        color: #fff;
        padding: 18px 20px;
        border: none;
    }
    .set-card .set-name {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
    }
    .set-card .set-price {
        font-size: 1.35rem;
        font-weight: 800;
        color: #C4603C;
    }
    .set-card .set-price small {
        font-size: 0.75rem;
        font-weight: 500;
        color: #64748B;
    }
    .set-meta-badge {
        font-size: 0.75rem;
        padding: 5px 10px;
        border-radius: 6px;
    }
    .btn-action {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #E2E8F0;
        background: #fff;
        color: #64748B;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 0.9rem;
    }
    .btn-action:hover {
        background: #F1F5F9;
        color: #0F172A;
        border-color: #CBD5E1;
    }
    .btn-action.danger:hover {
        background: #FEF2F2;
        color: #DC2626;
        border-color: #FECACA;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-state i {
        font-size: 4rem;
        color: #CBD5E1;
    }
</style>

<!-- ═══ HEADER ═══ -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-egg-fried text-success me-2"></i>Thực Đơn Set Tiệc</h4>
        <p class="text-muted mb-0 small">Quản lý các set tiệc và danh sách món ăn trong từng set</p>
    </div>
    <a href="<?= BASE_URL ?>?action=admin-set-tiec-create" class="btn btn-success d-flex align-items-center gap-2" style="border-radius: 8px;">
        <i class="bi bi-plus-lg"></i>
        <span>Thêm Set Tiệc</span>
    </a>
</div>

<!-- ═══ DANH SÁCH SET TIỆC ═══ -->
<?php if (empty($sets)): ?>
    <div class="card border-0 shadow-sm" style="border-radius: 14px;">
        <div class="card-body empty-state">
            <i class="bi bi-box-seam d-block mb-3"></i>
            <h5 class="fw-bold text-secondary">Chưa có Set Tiệc nào</h5>
            <p class="text-muted mb-4">Hãy tạo set tiệc đầu tiên để bắt đầu quản lý thực đơn.</p>
            <a href="<?= BASE_URL ?>?action=admin-set-tiec-create" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i> Tạo Set Tiệc
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($sets as $s): ?>
            <div class="col-xl-4 col-lg-6">
                <div class="card set-card shadow-sm h-100">
                    <!-- Header -->
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="set-name"><?= htmlspecialchars($s['ten']) ?></h5>
                            <span class="d-inline-block mt-1" style="font-size: 0.75rem; opacity: 0.8;">
                                <i class="bi bi-hash"></i> ID: <?= $s['id'] ?>
                            </span>
                        </div>
                        <span class="badge bg-white text-dark fw-bold" style="font-size: 0.8rem;">
                            <i class="bi bi-people-fill me-1 text-success"></i><?= $s['so_nguoi_phu_hop'] ?> người
                        </span>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4">
                        <!-- Giá -->
                        <div class="set-price mb-3">
                            <?= number_format($s['gia'], 0, ',', '.') ?>đ
                            <small>/ người</small>
                        </div>

                        <!-- Mô tả -->
                        <?php if (!empty($s['mo_ta'])): ?>
                            <p class="text-muted small mb-3" style="line-height: 1.6;">
                                <i class="bi bi-info-circle me-1"></i>
                                <?= htmlspecialchars($s['mo_ta']) ?>
                            </p>
                        <?php endif; ?>

                        <!-- Thông tin nhanh -->
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="set-meta-badge bg-primary-subtle text-primary">
                                <i class="bi bi-egg-fried me-1"></i><?= $s['so_mon'] ?> món
                            </span>
                            <span class="set-meta-badge bg-warning-subtle text-dark">
                                <i class="bi bi-currency-dollar me-1"></i>Tổng set: ~<?= number_format($s['gia'] * $s['so_nguoi_phu_hop'], 0, ',', '.') ?>đ
                            </span>
                        </div>
                    </div>

                    <!-- Footer: Actions -->
                    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3 px-4">
                        <a href="<?= BASE_URL ?>?action=admin-set-tiec-detail&id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" style="border-radius: 6px; font-size: 0.8rem;">
                            <i class="bi bi-eye"></i> Xem Chi Tiết
                        </a>
                        <div class="d-flex gap-2">
                            <a href="<?= BASE_URL ?>?action=admin-set-tiec-edit&id=<?= $s['id'] ?>" class="btn-action" title="Chỉnh sửa">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="#" class="btn-action danger" title="Xóa"
                               onclick="if(confirm('Bạn có chắc chắn muốn xóa Set Tiệc \'<?= addslashes(htmlspecialchars($s['ten'])) ?>\'?')) { window.location='<?= BASE_URL ?>?action=admin-set-tiec-delete&id=<?= $s['id'] ?>'; } return false;">
                                <i class="bi bi-trash3"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Thống kê tổng -->
    <div class="mt-4 p-3 bg-white rounded-3 shadow-sm d-flex flex-wrap gap-4 align-items-center justify-content-center small text-muted">
        <span><i class="bi bi-box-seam me-1 text-success"></i> Tổng: <strong class="text-dark"><?= count($sets) ?></strong> set tiệc</span>
        <span class="vr"></span>
        <span><i class="bi bi-egg-fried me-1 text-primary"></i> Tổng: <strong class="text-dark"><?= array_sum(array_column($sets, 'so_mon')) ?></strong> món đã gắn</span>
        <span class="vr"></span>
        <span><i class="bi bi-currency-dollar me-1 text-warning"></i> Giá thấp nhất: <strong class="text-dark"><?= number_format(min(array_column($sets, 'gia')), 0, ',', '.') ?>đ</strong></span>
        <span>– Cao nhất: <strong class="text-dark"><?= number_format(max(array_column($sets, 'gia')), 0, ',', '.') ?>đ</strong></span>
    </div>
<?php endif; ?>
