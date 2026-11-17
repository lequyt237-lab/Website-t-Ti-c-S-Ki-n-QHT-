<?php
/**
 * @var array $set         Thông tin set tiệc
 * @var array $monTrongSet Danh sách món ăn trong set
 */
?>

<style>
    .detail-hero {
        background: linear-gradient(135deg, #2C4A35 0%, #3b6b4d 60%, #4a8c5c 100%);
        color: #fff;
        border-radius: 14px;
        padding: 32px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    .detail-hero::after {
        content: '';
        position: absolute;
        right: -40px;
        top: -40px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .detail-hero .set-title {
        font-size: 1.6rem;
        font-weight: 800;
    }
    .detail-hero .meta-pill {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 6px 16px;
        font-size: 0.82rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .detail-hero .big-price {
        font-size: 2rem;
        font-weight: 800;
        color: #EFC96A;
    }
    .detail-hero .big-price small {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.7);
        font-weight: 400;
    }

    .mon-table-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
    }
    .mon-table-card .card-header {
        background: #fff;
        border-bottom: 2px solid #F1F5F9;
        padding: 18px 24px;
    }
    .table-mon th {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #64748B;
        font-weight: 700;
        padding: 12px 16px;
        background: #F8FAFC;
        border: none;
    }
    .table-mon td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #F1F5F9;
    }
    .table-mon tr:last-child td {
        border-bottom: none;
    }
    .nhom-badge {
        font-size: 0.72rem;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 500;
    }
    .empty-mon {
        text-align: center;
        padding: 50px 20px;
    }
</style>

<!-- ═══ HEADER ACTIONS ═══ -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <a href="<?= BASE_URL ?>?action=admin-set-tiec" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" style="border-radius: 6px;">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách
    </a>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>?action=admin-set-tiec-edit&id=<?= $set['id'] ?>" class="btn btn-sm btn-warning d-flex align-items-center gap-1" style="border-radius: 6px;">
            <i class="bi bi-pencil-square"></i> Chỉnh sửa
        </a>
        <a href="#" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1" style="border-radius: 6px;"
           onclick="if(confirm('Bạn có chắc chắn muốn xóa set này?')) { window.location='<?= BASE_URL ?>?action=admin-set-tiec-delete&id=<?= $set['id'] ?>'; } return false;">
            <i class="bi bi-trash3"></i> Xóa
        </a>
    </div>
</div>

<!-- ═══ HERO THÔNG TIN SET ═══ -->
<div class="detail-hero">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <span class="meta-pill"><i class="bi bi-hash"></i> ID: <?= $set['id'] ?></span>
                <span class="meta-pill"><i class="bi bi-people-fill"></i> <?= $set['so_nguoi_phu_hop'] ?> người</span>
                <span class="meta-pill"><i class="bi bi-egg-fried"></i> <?= count($monTrongSet) ?> món</span>
            </div>
            <h2 class="set-title mb-2"><?= htmlspecialchars($set['ten']) ?></h2>
            <?php if (!empty($set['mo_ta'])): ?>
                <p class="mb-0" style="opacity: 0.85; font-size: 0.95rem;">
                    <i class="bi bi-quote me-1"></i><?= htmlspecialchars($set['mo_ta']) ?>
                </p>
            <?php endif; ?>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <div class="big-price">
                <?= number_format($set['gia'], 0, ',', '.') ?>đ
                <small>/ người</small>
            </div>
            <div style="font-size: 0.85rem; opacity: 0.7;">
                Tổng ước tính cho <?= $set['so_nguoi_phu_hop'] ?> người:
                <strong style="color: #EFC96A;"><?= number_format($set['gia'] * $set['so_nguoi_phu_hop'], 0, ',', '.') ?>đ</strong>
            </div>
        </div>
    </div>
</div>

<!-- ═══ BẢNG MÓN ĂN TRONG SET ═══ -->
<div class="card mon-table-card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold fs-6">
            <i class="bi bi-list-ul text-success me-2"></i>Danh Sách Món Ăn Trong Set
        </h5>
        <a href="<?= BASE_URL ?>?action=admin-set-tiec-edit&id=<?= $set['id'] ?>" class="btn btn-sm btn-outline-success" style="border-radius: 6px; font-size: 0.78rem;">
            <i class="bi bi-pencil me-1"></i> Chỉnh sửa món
        </a>
    </div>

    <?php if (empty($monTrongSet)): ?>
        <div class="card-body empty-mon">
            <i class="bi bi-egg fs-1 text-muted d-block mb-3"></i>
            <h6 class="fw-bold text-secondary">Chưa có món ăn nào</h6>
            <p class="text-muted small mb-3">Hãy chỉnh sửa set tiệc này để thêm các món ăn vào.</p>
            <a href="<?= BASE_URL ?>?action=admin-set-tiec-edit&id=<?= $set['id'] ?>" class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Thêm món ngay
            </a>
        </div>
    <?php else: ?>
        <?php
        // Nhóm theo nhom_mon
        $grouped = [];
        foreach ($monTrongSet as $m) {
            $grouped[$m['nhom_mon']][] = $m;
        }
        $colors = ['primary', 'success', 'warning', 'danger', 'info', 'secondary'];
        $colorIdx = 0;
        ?>
        <div class="table-responsive">
            <table class="table table-mon mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Tên Món Ăn</th>
                        <th>Nhóm Món</th>
                        <th class="text-end">Giá Gốc</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $stt = 1; ?>
                    <?php foreach ($grouped as $nhom => $mons): ?>
                        <?php $color = $colors[$colorIdx % count($colors)]; $colorIdx++; ?>
                        <?php foreach ($mons as $m): ?>
                            <tr>
                                <td class="text-muted fw-bold"><?= $stt++ ?></td>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($m['ten']) ?></div>
                                    <?php if (!empty($m['mo_ta'])): ?>
                                        <div class="text-muted" style="font-size: 0.75rem; max-width: 400px;">
                                            <?= htmlspecialchars(mb_strimwidth($m['mo_ta'], 0, 100, '...')) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="nhom-badge bg-<?= $color ?>-subtle text-<?= $color ?>">
                                        <?= htmlspecialchars($m['nhom_mon']) ?>
                                    </span>
                                </td>
                                <td class="text-end fw-bold" style="color: #C4603C;">
                                    <?= number_format($m['gia'], 0, ',', '.') ?>đ
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <td colspan="3" class="text-end fw-bold small text-uppercase" style="letter-spacing: 1px;">
                            Tổng giá gốc các món:
                        </td>
                        <td class="text-end fw-bold fs-5" style="color: #C4603C;">
                            <?= number_format(array_sum(array_column($monTrongSet, 'gia')), 0, ',', '.') ?>đ
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Thống kê nhóm món -->
<?php if (!empty($grouped)): ?>
<div class="mt-4 p-4 bg-white rounded-3 shadow-sm">
    <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart me-2 text-primary"></i>Phân Bổ Theo Nhóm Món</h6>
    <div class="row g-3">
        <?php $colorIdx = 0; ?>
        <?php foreach ($grouped as $nhom => $mons): ?>
            <?php $color = $colors[$colorIdx % count($colors)]; $colorIdx++; ?>
            <div class="col-md-4 col-sm-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-<?= $color ?>-subtle">
                    <div class="p-2 rounded-3 bg-<?= $color ?> text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <strong><?= count($mons) ?></strong>
                    </div>
                    <div>
                        <div class="fw-semibold small"><?= htmlspecialchars($nhom) ?></div>
                        <div class="text-muted" style="font-size: 0.75rem;">
                            <?= number_format(array_sum(array_column($mons, 'gia')), 0, ',', '.') ?>đ
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
