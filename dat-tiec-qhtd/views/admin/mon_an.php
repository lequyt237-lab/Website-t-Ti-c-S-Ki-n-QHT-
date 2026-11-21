<?php
// views/admin/mon_an.php
/**
 * @var array $monList  Danh sách tất cả món ăn
 * @var array $nhomList Danh sách các nhóm món distinct
 * @var array|null $editMon Món ăn đang chỉnh sửa nếu có
 */

// Danh sách các nhóm món chuẩn theo hệ thống
$standardCategories = [
    'Món ăn gia đình Việt'       => ['label' => 'Món Gia Đình Việt',        'class' => 'badge-viet',  'icon' => 'bi-house-heart-fill'],
    'Bánh tiệc & Sinh nhật'      => ['label' => 'Bánh Tiệc & Sinh Nhật',    'class' => 'badge-cake',  'icon' => 'bi-cake2-fill'],
    'Đặc sản Bắc - Trung - Nam'  => ['label' => 'Đặc Sản Bắc - Trung - Nam', 'class' => 'badge-spec',  'icon' => 'bi-geo-alt-fill'],
    'Khai vị'                    => ['label' => 'Ăn Nhẹ & Khai Vị',         'class' => 'badge-app',   'icon' => 'bi-cup-straw'],
    'Lẩu'                        => ['label' => 'Lẩu & Món Chính',          'class' => 'badge-hotpot','icon' => 'bi-fire'],
    'Tráng miệng'                => ['label' => 'Tráng Miệng & Chè',        'class' => 'badge-des',   'icon' => 'bi-flower1'],
];

// Helper lấy class badge
function getCategoryBadgeClass($cat) {
    if (stripos($cat, 'sinh nhật') !== false || stripos($cat, 'bánh') !== false) return 'badge-cake';
    if (stripos($cat, 'gia đình') !== false) return 'badge-viet';
    if (stripos($cat, 'đặc sản') !== false) return 'badge-spec';
    if (stripos($cat, 'khai vị') !== false) return 'badge-app';
    if (stripos($cat, 'lẩu') !== false) return 'badge-hotpot';
    if (stripos($cat, 'tráng miệng') !== false) return 'badge-des';
    return 'badge-other';
}
?>

<style>
    .mon-card { border-radius: 12px; }
    .badge-viet   { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
    .badge-cake   { background: #fff3e0; color: #e65100; border: 1px solid #ffcc80; }
    .badge-spec   { background: #ffebee; color: #c62828; border: 1px solid #ef9a9a; }
    .badge-app    { background: #e3f2fd; color: #1565c0; border: 1px solid #90caf9; }
    .badge-hotpot { background: #fbe9e7; color: #d84315; border: 1px solid #ffab91; }
    .badge-des    { background: #f3e5f5; color: #6a1b9a; border: 1px solid #ce93d8; }
    .badge-other  { background: #f5f5f5; color: #424242; border: 1px solid #e0e0e0; }

    .cat-badge {
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }
    .price-tag {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: #C4603C;
        white-space: nowrap;
    }
    .mon-table th {
        background: #f8f9fa;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #6c757d;
        white-space: nowrap;
    }
    .form-mon-card {
        background: linear-gradient(135deg, #f8fffe, #f0f7ff);
        border: 1.5px solid #d0e8ff;
        border-radius: 14px;
    }
    .filter-pill {
        border: 1px solid #dee2e6;
        background: #fff;
        color: #495057;
        border-radius: 20px;
        padding: 5px 14px;
        font-size: 0.82rem;
        font-weight: 600;
        transition: all 0.2s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .filter-pill:hover, .filter-pill.active {
        background: #2C4A35;
        color: #fff;
        border-color: #2C4A35;
        box-shadow: 0 2px 8px rgba(44, 74, 53, 0.2);
    }
    .filter-pill .badge {
        font-size: 0.7rem;
    }
    .btn-quick-price {
        font-size: 0.75rem;
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 6px;
        color: #6c757d;
        border: 1px dashed #ced4da;
        background: #fff;
    }
    .btn-quick-price:hover {
        background: #e9ecef;
        color: #212529;
    }
</style>

<div class="container-fluid px-4 py-3">

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-basket2-fill me-2 text-success"></i>Quản Lý Thực Đơn &amp; Món Ăn
            </h4>
            <p class="text-muted mb-0 small">
                Thêm món mới, phân loại (Gia đình, Sinh nhật, Khai vị...), cập nhật giá bán và xóa món ăn trong thực đơn.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>?action=thuc-don" target="_blank" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-box-arrow-up-right"></i> Xem Trang Khách
            </a>
            <span class="badge bg-success fs-6 px-3 py-2 d-flex align-items-center">
                <?= count($monList) ?> món ăn
            </span>
        </div>
    </div>

    <!-- Thông báo Flash messages -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Bộ lọc theo Loại Danh Mục & Tìm kiếm -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <!-- Search input -->
                <div class="col-lg-4 col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="searchMonInput" class="form-control border-start-0" placeholder="Tìm theo tên món ăn hoặc mô tả...">
                    </div>
                </div>

                <!-- Category quick tabs -->
                <div class="col-lg-8 col-md-6">
                    <div class="d-flex flex-wrap gap-2 align-items-center" id="categoryFilterContainer">
                        <span class="filter-pill active" data-cat="all">
                            <i class="bi bi-grid-fill"></i> Tất cả
                            <span class="badge bg-light text-dark rounded-pill ms-1"><?= count($monList) ?></span>
                        </span>
                        <?php
                        // Đếm số món theo nhóm chuẩn
                        foreach ($standardCategories as $catKey => $catInfo):
                            $countInGroup = count(array_filter($monList, function($m) use ($catKey) {
                                return stripos($m['nhom_mon'], $catKey) !== false || stripos($catKey, $m['nhom_mon']) !== false;
                            }));
                        ?>
                            <span class="filter-pill" data-cat="<?= htmlspecialchars($catKey) ?>">
                                <i class="bi <?= $catInfo['icon'] ?>"></i> <?= $catInfo['label'] ?>
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1"><?= $countInGroup ?></span>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- ═══ BẢNG DANH SÁCH MÓN ĂN (CỘT TRÁI) ═══ -->
        <div class="col-lg-8">
            <div class="card mon-card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-list-ul me-2"></i>Danh Sách Món Ăn Hiện Có
                    </h6>
                    <span class="text-muted small" id="visibleCountText">
                        Đang hiển thị: <?= count($monList) ?> món
                    </span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($monList)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mt-2">Chưa có món ăn nào trong thực đơn. Hãy thêm món mới bên cạnh!</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 mon-table" id="monTable">
                                <thead>
                                    <tr>
                                        <th class="ps-3" style="width: 40px;">#</th>
                                        <th>Tên món ăn</th>
                                        <th>Phân loại nhóm</th>
                                        <th>Giá bán (VNĐ)</th>
                                        <th>Mô tả</th>
                                        <th class="text-center pe-3" style="width: 130px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($monList as $idx => $m): ?>
                                        <tr class="mon-row" 
                                            data-name="<?= htmlspecialchars(mb_strtolower($m['ten'])) ?>"
                                            data-desc="<?= htmlspecialchars(mb_strtolower($m['mo_ta'] ?? '')) ?>"
                                            data-cat="<?= htmlspecialchars($m['nhom_mon']) ?>">
                                            <td class="ps-3 text-muted small"><?= $idx + 1 ?></td>
                                            <td>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($m['ten']) ?></div>
                                                <small class="text-muted">Mã: #<?= $m['id'] ?></small>
                                            </td>
                                            <td>
                                                <span class="cat-badge <?= getCategoryBadgeClass($m['nhom_mon']) ?>">
                                                    <i class="bi bi-tag-fill me-1" style="font-size: 0.7rem;"></i>
                                                    <?= htmlspecialchars($m['nhom_mon']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="price-tag"><?= number_format($m['gia'], 0, ',', '.') ?>đ</span>
                                                    <button type="button" class="btn btn-quick-price btn-edit-price" 
                                                            data-id="<?= $m['id'] ?>"
                                                            data-name="<?= htmlspecialchars($m['ten']) ?>"
                                                            data-price="<?= $m['gia'] ?>"
                                                            title="Cập nhật giá nhanh">
                                                        <i class="bi bi-pencil-fill"></i> Đổi giá
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="small text-muted" style="max-width: 200px;">
                                                <?= !empty($m['mo_ta']) ? htmlspecialchars(mb_strimwidth($m['mo_ta'], 0, 75, '...')) : '<em class="text-secondary opacity-50">—</em>' ?>
                                            </td>
                                            <td class="text-center pe-3">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary btn-edit-mon"
                                                            data-id="<?= $m['id'] ?>"
                                                            data-name="<?= htmlspecialchars($m['ten']) ?>"
                                                            data-cat="<?= htmlspecialchars($m['nhom_mon']) ?>"
                                                            data-price="<?= $m['gia'] ?>"
                                                            data-desc="<?= htmlspecialchars($m['mo_ta'] ?? '') ?>"
                                                            title="Chỉnh sửa toàn bộ">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-delete-mon"
                                                            data-id="<?= $m['id'] ?>"
                                                            data-name="<?= htmlspecialchars($m['ten']) ?>"
                                                            title="Xóa món này">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ═══ FORM THÊM MỚI / CHỈNH SỬA MÓN ĂN (CỘT PHẢI) ═══ -->
        <div class="col-lg-4">
            <div class="form-mon-card p-4 shadow-sm mb-4">
                <h6 class="fw-bold mb-3 d-flex align-items-center justify-content-between">
                    <span>
                        <i class="bi <?= !empty($editMon) ? 'bi-pencil-square text-primary' : 'bi-plus-circle-fill text-success' ?> me-2"></i>
                        <?= !empty($editMon) ? 'Chỉnh Sửa Món Ăn #' . $editMon['id'] : 'Thêm Món Ăn Mới' ?>
                    </span>
                    <?php if (!empty($editMon)): ?>
                        <a href="<?= BASE_URL ?>?action=admin-mon-an" class="btn btn-sm btn-outline-secondary" style="font-size: 0.75rem;">
                            <i class="bi bi-x"></i> Hủy sửa
                        </a>
                    <?php endif; ?>
                </h6>

                <form method="POST" action="<?= BASE_URL ?>?action=<?= !empty($editMon) ? 'admin-mon-an-update' : 'admin-mon-an-store' ?>">
                    <?php if (!empty($editMon)): ?>
                        <input type="hidden" name="id" value="<?= $editMon['id'] ?>">
                    <?php endif; ?>

                    <!-- Tên món -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Tên món ăn <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="ten" class="form-control" 
                               placeholder="VD: Bánh kem dâu tây, Cá chép om dưa..." 
                               value="<?= htmlspecialchars($editMon['ten'] ?? '') ?>" required maxlength="150">
                    </div>

                    <!-- Phân loại nhóm món -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Phân loại nhóm món <span class="text-danger">*</span>
                        </label>
                        <select name="nhom_mon_select" id="nhomMonSelect" class="form-select mb-2" onchange="toggleCustomCategory(this)">
                            <option value="">— Chọn nhóm món chuẩn —</option>
                            <?php foreach ($standardCategories as $catVal => $catData): ?>
                                <option value="<?= htmlspecialchars($catVal) ?>" 
                                    <?= (!empty($editMon) && $editMon['nhom_mon'] === $catVal) ? 'selected' : '' ?>>
                                    <?= $catData['icon'] === 'bi-cake2-fill' ? '🎂' : ($catData['icon'] === 'bi-house-heart-fill' ? '🍲' : '🍽️') ?> 
                                    <?= $catData['label'] ?> (<?= htmlspecialchars($catVal) ?>)
                                </option>
                            <?php endforeach; ?>

                            <!-- Nhóm khác đã có trong database -->
                            <?php 
                            $otherCategories = array_diff($nhomList, array_keys($standardCategories));
                            if (!empty($otherCategories)): ?>
                                <optgroup label="Nhóm khác hiện có">
                                    <?php foreach ($otherCategories as $otherCat): ?>
                                        <option value="<?= htmlspecialchars($otherCat) ?>"
                                            <?= (!empty($editMon) && $editMon['nhom_mon'] === $otherCat) ? 'selected' : '' ?>>
                                            📁 <?= htmlspecialchars($otherCat) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                            <option value="__custom__" <?= (!empty($editMon) && !in_array($editMon['nhom_mon'], array_keys($standardCategories)) && !in_array($editMon['nhom_mon'], $otherCategories)) ? 'selected' : '' ?>>
                                ✍️ Nhập nhóm khác...
                            </option>
                        </select>

                        <!-- Input ẩn hoặc hiện nếu người dùng chọn nhập nhóm mới -->
                        <div id="customCategoryWrapper" style="display: <?= (!empty($editMon) && !in_array($editMon['nhom_mon'], array_keys($standardCategories)) && !in_array($editMon['nhom_mon'], $otherCategories)) ? 'block' : 'none' ?>;">
                            <input type="text" name="nhom_mon_custom" id="nhomMonCustom" class="form-control form-control-sm"
                                   placeholder="Nhập tên nhóm phân loại mới..." 
                                   value="<?= htmlspecialchars($editMon['nhom_mon'] ?? '') ?>">
                            <div class="form-text small text-muted">Nhóm mới sẽ hiển thị trực tiếp lên hệ thống và trang khách.</div>
                        </div>
                        <input type="hidden" name="nhom_mon" id="nhomMonFinal" value="<?= htmlspecialchars($editMon['nhom_mon'] ?? '') ?>">
                    </div>

                    <!-- Giá bán -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Giá bán (VNĐ) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="gia" class="form-control" 
                                   placeholder="VD: 150000" min="1000" step="1000" 
                                   value="<?= $editMon['gia'] ?? '' ?>" required>
                            <span class="input-group-text bg-light text-muted">VNĐ</span>
                        </div>
                        <div class="form-text small text-muted">Giá món hiển thị cho khách và dùng tính tổng khi khách gọi thêm.</div>
                    </div>

                    <!-- Mô tả món -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Mô tả món ăn (tùy chọn)</label>
                        <textarea name="mo_ta" class="form-control" rows="3" 
                                  placeholder="Thành phần nguyên liệu, hương vị đặc trưng..."><?= htmlspecialchars($editMon['mo_ta'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn <?= !empty($editMon) ? 'btn-primary' : 'btn-success' ?> w-100 fw-semibold py-2">
                        <i class="bi <?= !empty($editMon) ? 'bi-save2' : 'bi-plus-lg' ?> me-2"></i>
                        <?= !empty($editMon) ? 'Lưu Thay Đổi' : 'Thêm Món Vào Thực Đơn' ?>
                    </button>
                </form>
            </div>

            <!-- Thẻ hướng dẫn nhanh -->
            <div class="card border-0 bg-light rounded-3 p-3 small text-muted shadow-sm">
                <div class="fw-bold text-dark mb-2">
                    <i class="bi bi-info-circle-fill text-primary me-1"></i> Cách hoạt động với Trang Khách:
                </div>
                <ul class="ps-3 mb-0" style="line-height: 1.7;">
                    <li>Món thuộc nhóm <strong>Bánh tiệc & Sinh nhật</strong> sẽ xuất hiện trong tab <em>Bánh Kem & Sinh Nhật</em> ở trang khách.</li>
                    <li>Món thuộc nhóm <strong>Món ăn gia đình Việt</strong> sẽ vào tab <em>Gia Đình Việt</em>.</li>
                    <li>Mọi món mới thêm đều ngay lập tức nằm trong tab <em>Tất cả</em> bên trang khách.</li>
                    <li>Bạn có thể bấm <strong>"Đổi giá"</strong> trực tiếp ở từng dòng để cập nhật giá tức thì.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- ═══ MODAL CẬP NHẬT GIÁ NHANH ═══ -->
<div class="modal fade" id="modalQuickPrice" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-2">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-currency-dollar text-warning me-1"></i> Cập Nhật Giá Món
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>?action=admin-mon-an-quick-price">
                <div class="modal-body">
                    <input type="hidden" name="id" id="quickPriceId">
                    <div class="mb-2">
                        <span class="small text-muted d-block">Món ăn:</span>
                        <strong id="quickPriceName" class="text-dark"></strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Giá mới (VNĐ):</label>
                        <div class="input-group">
                            <input type="number" name="gia" id="quickPriceInput" class="form-control fw-bold text-danger fs-5" min="1000" step="1000" required>
                            <span class="input-group-text">đ</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">
                        <i class="bi bi-check-lg me-1"></i> Lưu Giá Mới
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══ MODAL XÁC NHẬN XÓA MÓN ĂN ═══ -->
<div class="modal fade" id="modalDeleteMon" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Xác nhận xóa món ăn
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-3">
                Bạn có chắc chắn muốn xóa món <strong id="deleteMonName" class="text-dark"></strong> khỏi hệ thống thực đơn?
                <br>
                <small class="text-muted mt-2 d-block">
                    <i class="bi bi-shield-exclamation me-1 text-warning"></i>
                    Lưu ý: Hệ thống sẽ từ chối xóa nếu món này đang được gắn trong một Set tiệc hoặc có trong đơn đặt bàn.
                </small>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form method="POST" action="<?= BASE_URL ?>?action=admin-mon-an-delete" id="formDeleteMon">
                    <input type="hidden" name="id" id="deleteMonId">
                    <button type="submit" class="btn btn-danger fw-semibold">
                        <i class="bi bi-trash3 me-1"></i> Xác Nhận Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ═══ JAVASCRIPT XỬ LÝ LỌC, TÌM KIẾM, MODAL ═══ -->
<script>
// Xử lý chọn nhóm món tự do hoặc chọn sẵn
function toggleCustomCategory(select) {
    const customWrapper = document.getElementById('customCategoryWrapper');
    const finalInput = document.getElementById('nhomMonFinal');
    const customInput = document.getElementById('nhomMonCustom');

    if (select.value === '__custom__') {
        customWrapper.style.display = 'block';
        customInput.focus();
        finalInput.value = customInput.value;
    } else {
        customWrapper.style.display = 'none';
        finalInput.value = select.value;
    }
}

document.getElementById('nhomMonCustom')?.addEventListener('input', function() {
    document.getElementById('nhomMonFinal').value = this.value.trim();
});

document.querySelector('form[action*="admin-mon-an-store"], form[action*="admin-mon-an-update"]')?.addEventListener('submit', function(e) {
    const select = document.getElementById('nhomMonSelect');
    const customInput = document.getElementById('nhomMonCustom');
    const finalInput = document.getElementById('nhomMonFinal');

    if (select.value === '__custom__') {
        finalInput.value = customInput.value.trim();
    } else {
        finalInput.value = select.value;
    }

    if (!finalInput.value) {
        alert('Vui lòng chọn hoặc nhập nhóm món ăn!');
        e.preventDefault();
    }
});

// Lọc theo danh mục
let currentCategoryFilter = 'all';
let currentSearchTerm = '';

function applyFilters() {
    const rows = document.querySelectorAll('.mon-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const rowName = row.dataset.name || '';
        const rowDesc = row.dataset.desc || '';
        const rowCat  = (row.dataset.cat || '').toLowerCase();

        // Kiểm tra Category
        let matchCat = (currentCategoryFilter === 'all');
        if (!matchCat) {
            const filterLower = currentCategoryFilter.toLowerCase();
            matchCat = rowCat.includes(filterLower) || filterLower.includes(rowCat);
        }

        // Kiểm tra Search
        let matchSearch = true;
        if (currentSearchTerm) {
            matchSearch = rowName.includes(currentSearchTerm) || rowDesc.includes(currentSearchTerm) || rowCat.includes(currentSearchTerm);
        }

        if (matchCat && matchSearch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('visibleCountText').textContent = `Đang hiển thị: ${visibleCount} món`;
}

// Click bộ lọc danh mục
document.querySelectorAll('.filter-pill').forEach(pill => {
    pill.addEventListener('click', function() {
        document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        currentCategoryFilter = this.dataset.cat;
        applyFilters();
    });
});

// Search input
document.getElementById('searchMonInput').addEventListener('input', function() {
    currentSearchTerm = this.value.trim().toLowerCase();
    applyFilters();
});

// Quick Price Modal
document.querySelectorAll('.btn-edit-price').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('quickPriceId').value = this.dataset.id;
        document.getElementById('quickPriceName').textContent = this.dataset.name;
        document.getElementById('quickPriceInput').value = this.dataset.price;
        new bootstrap.Modal(document.getElementById('modalQuickPrice')).show();
    });
});

// Edit Button (Chuyển trang hoặc nạp form)
document.querySelectorAll('.btn-edit-mon').forEach(btn => {
    btn.addEventListener('click', function() {
        window.location.href = '<?= BASE_URL ?>?action=admin-mon-an&edit=' + this.dataset.id;
    });
});

// Delete Modal
document.querySelectorAll('.btn-delete-mon').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('deleteMonId').value = this.dataset.id;
        document.getElementById('deleteMonName').textContent = this.dataset.name;
        new bootstrap.Modal(document.getElementById('modalDeleteMon')).show();
    });
});
</script>
