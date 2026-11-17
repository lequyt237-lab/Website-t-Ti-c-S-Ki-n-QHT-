<?php
/**
 * @var array|null $set           Dữ liệu set (null nếu thêm mới)
 * @var string     $formAction    URL action của form
 * @var array      $allMonAn      Tất cả món ăn
 * @var array      $nhomMon       Danh sách nhóm món
 * @var array      $selectedMonIds Danh sách mon_id đã chọn
 */
$isEdit = !empty($set);
?>

<!-- Thẻ thông báo lỗi -->
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<style>
    .form-section {
        background: #fff;
        border-radius: 14px;
        border: none;
        box-shadow: 0 1px 6px rgba(0,0,0,0.06);
        padding: 28px;
        margin-bottom: 24px;
    }
    .form-section h6 {
        font-weight: 700;
        color: #2C4A35;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #E2E8F0;
    }
    .form-section h6 i {
        margin-right: 8px;
    }
    .form-floating .form-control,
    .form-floating .form-select {
        border-radius: 8px;
        border: 1px solid #E2E8F0;
    }
    .form-floating .form-control:focus,
    .form-floating .form-select:focus {
        border-color: #2C4A35;
        box-shadow: 0 0 0 3px rgba(44,74,53,0.1);
    }

    /* Danh sách chọn món */
    .mon-filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 16px;
    }
    .mon-filter-btn {
        padding: 5px 14px;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        background: #fff;
        color: #64748B;
        font-size: 0.78rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .mon-filter-btn:hover,
    .mon-filter-btn.active {
        background: #2C4A35;
        color: #fff;
        border-color: #2C4A35;
    }

    .mon-list {
        max-height: 420px;
        overflow-y: auto;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 0;
    }
    .mon-list::-webkit-scrollbar {
        width: 6px;
    }
    .mon-list::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 3px;
    }
    .mon-item {
        display: flex;
        align-items: center;
        padding: 10px 16px;
        border-bottom: 1px solid #F1F5F9;
        transition: background 0.15s;
        cursor: pointer;
    }
    .mon-item:last-child {
        border-bottom: none;
    }
    .mon-item:hover {
        background: #F8FAFC;
    }
    .mon-item.selected {
        background: #F0FDF4;
    }
    .mon-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin-right: 12px;
        accent-color: #2C4A35;
        cursor: pointer;
    }
    .mon-item .mon-info {
        flex: 1;
    }
    .mon-item .mon-name {
        font-size: 0.88rem;
        font-weight: 600;
        color: #0F172A;
    }
    .mon-item .mon-group {
        font-size: 0.72rem;
        color: #94A3B8;
    }
    .mon-item .mon-price {
        font-weight: 700;
        font-size: 0.85rem;
        color: #C4603C;
        white-space: nowrap;
    }

    .selected-summary {
        background: linear-gradient(135deg, #F0FDF4, #ECFDF5);
        border: 1px solid #BBF7D0;
        border-radius: 10px;
        padding: 14px 18px;
        margin-top: 12px;
    }
</style>

<!-- ═══ HEADER ═══ -->
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?= BASE_URL ?>?action=admin-set-tiec" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" style="border-radius: 6px;">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
    <div>
        <h4 class="fw-bold mb-0">
            <i class="bi bi-<?= $isEdit ? 'pencil-square text-warning' : 'plus-circle-fill text-success' ?> me-2"></i>
            <?= $isEdit ? 'Chỉnh Sửa Set Tiệc' : 'Thêm Set Tiệc Mới' ?>
        </h4>
    </div>
</div>

<form method="POST" action="<?= $formAction ?>" id="setTiecForm">
    <div class="row g-4">
        <!-- CỘT TRÁI: Thông tin Set Tiệc -->
        <div class="col-lg-5">
            <div class="form-section">
                <h6><i class="bi bi-info-circle-fill"></i>Thông Tin Set Tiệc</h6>

                <!-- Tên Set -->
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="ten" name="ten" placeholder="Tên set tiệc"
                           value="<?= htmlspecialchars($set['ten'] ?? '') ?>" required>
                    <label for="ten"><i class="bi bi-bookmark-star me-1"></i>Tên Set Tiệc *</label>
                </div>

                <!-- Giá -->
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="gia" name="gia" placeholder="Giá" min="1000" step="1000"
                           value="<?= $set['gia'] ?? '' ?>" required>
                    <label for="gia"><i class="bi bi-currency-dollar me-1"></i>Giá (VNĐ / người) *</label>
                </div>

                <!-- Số người phù hợp -->
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="so_nguoi_phu_hop" name="so_nguoi_phu_hop" placeholder="Số người" min="1"
                           value="<?= $set['so_nguoi_phu_hop'] ?? '' ?>" required>
                    <label for="so_nguoi_phu_hop"><i class="bi bi-people me-1"></i>Số người phù hợp *</label>
                </div>

                <!-- Mô tả -->
                <div class="form-floating mb-3">
                    <textarea class="form-control" id="mo_ta" name="mo_ta" placeholder="Mô tả" style="height: 100px;"><?= htmlspecialchars($set['mo_ta'] ?? '') ?></textarea>
                    <label for="mo_ta"><i class="bi bi-text-paragraph me-1"></i>Mô tả ngắn</label>
                </div>
            </div>

            <!-- Tổng kết -->
            <div class="selected-summary" id="summaryBox">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-success small"><i class="bi bi-check2-circle me-1"></i>Tổng kết</span>
                </div>
                <div class="small">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Số món đã chọn:</span>
                        <strong id="countSelected">0</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Tổng giá gốc các món:</span>
                        <strong class="text-danger" id="totalPrice">0đ</strong>
                    </div>
                </div>
            </div>

            <!-- Nút Submit -->
            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn btn-success flex-fill d-flex align-items-center justify-content-center gap-2" style="border-radius: 8px; padding: 12px;">
                    <i class="bi bi-<?= $isEdit ? 'check-lg' : 'plus-lg' ?>"></i>
                    <?= $isEdit ? 'Cập Nhật Set Tiệc' : 'Tạo Set Tiệc' ?>
                </button>
                <a href="<?= BASE_URL ?>?action=admin-set-tiec" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2" style="border-radius: 8px; padding: 12px;">
                    <i class="bi bi-x-lg"></i> Hủy
                </a>
            </div>
        </div>

        <!-- CỘT PHẢI: Chọn Món Ăn -->
        <div class="col-lg-7">
            <div class="form-section">
                <h6><i class="bi bi-egg-fried"></i>Chọn Món Ăn Cho Set</h6>

                <!-- Thanh tìm kiếm -->
                <div class="input-group mb-3">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 8px 0 0 8px;"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" id="searchMon" placeholder="Tìm kiếm món ăn..." style="border-radius: 0 8px 8px 0;">
                </div>

                <!-- Bộ lọc theo nhóm món -->
                <div class="mon-filter-group">
                    <button type="button" class="mon-filter-btn active" data-group="all">Tất cả</button>
                    <?php foreach ($nhomMon as $nhom): ?>
                        <button type="button" class="mon-filter-btn" data-group="<?= htmlspecialchars($nhom) ?>"><?= htmlspecialchars($nhom) ?></button>
                    <?php endforeach; ?>
                </div>

                <!-- Nút chọn / bỏ nhanh -->
                <div class="d-flex gap-2 mb-2">
                    <button type="button" class="btn btn-sm btn-outline-success" id="btnSelectAll" style="font-size: 0.75rem; border-radius: 6px;">
                        <i class="bi bi-check-all"></i> Chọn tất cả
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnDeselectAll" style="font-size: 0.75rem; border-radius: 6px;">
                        <i class="bi bi-x-circle"></i> Bỏ chọn tất cả
                    </button>
                </div>

                <!-- Danh sách món ăn -->
                <div class="mon-list" id="monList">
                    <?php foreach ($allMonAn as $mon): ?>
                        <?php $isSelected = in_array($mon['id'], $selectedMonIds); ?>
                        <label class="mon-item <?= $isSelected ? 'selected' : '' ?>" data-group="<?= htmlspecialchars($mon['nhom_mon']) ?>" data-name="<?= htmlspecialchars(mb_strtolower($mon['ten'])) ?>">
                            <input type="checkbox" name="mon_ids[]" value="<?= $mon['id'] ?>" <?= $isSelected ? 'checked' : '' ?>
                                   data-price="<?= $mon['gia'] ?>">
                            <div class="mon-info">
                                <div class="mon-name"><?= htmlspecialchars($mon['ten']) ?></div>
                                <div class="mon-group">
                                    <i class="bi bi-tag me-1"></i><?= htmlspecialchars($mon['nhom_mon']) ?>
                                </div>
                            </div>
                            <div class="mon-price"><?= number_format($mon['gia'], 0, ',', '.') ?>đ</div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const monList = document.getElementById('monList');
    const searchInput = document.getElementById('searchMon');
    const filterBtns = document.querySelectorAll('.mon-filter-btn');
    const countEl = document.getElementById('countSelected');
    const totalEl = document.getElementById('totalPrice');

    let activeGroup = 'all';

    // Cập nhật tổng kết
    function updateSummary() {
        const checked = monList.querySelectorAll('input[type="checkbox"]:checked');
        let total = 0;
        checked.forEach(cb => { total += parseFloat(cb.dataset.price) || 0; });
        countEl.textContent = checked.length;
        totalEl.textContent = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
    }

    // Toggle highlight khi chọn/bỏ
    monList.addEventListener('change', function(e) {
        if (e.target.type === 'checkbox') {
            e.target.closest('.mon-item').classList.toggle('selected', e.target.checked);
            updateSummary();
        }
    });

    // Tìm kiếm
    searchInput.addEventListener('input', function() {
        const keyword = this.value.toLowerCase().trim();
        filterItems(keyword, activeGroup);
    });

    // Lọc nhóm
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            activeGroup = this.dataset.group;
            filterItems(searchInput.value.toLowerCase().trim(), activeGroup);
        });
    });

    function filterItems(keyword, group) {
        monList.querySelectorAll('.mon-item').forEach(item => {
            const matchGroup = group === 'all' || item.dataset.group === group;
            const matchSearch = !keyword || item.dataset.name.includes(keyword);
            item.style.display = (matchGroup && matchSearch) ? 'flex' : 'none';
        });
    }

    // Chọn / Bỏ tất cả (chỉ ảnh hưởng các item đang hiện)
    document.getElementById('btnSelectAll').addEventListener('click', function() {
        monList.querySelectorAll('.mon-item').forEach(item => {
            if (item.style.display !== 'none') {
                const cb = item.querySelector('input[type="checkbox"]');
                cb.checked = true;
                item.classList.add('selected');
            }
        });
        updateSummary();
    });

    document.getElementById('btnDeselectAll').addEventListener('click', function() {
        monList.querySelectorAll('.mon-item').forEach(item => {
            if (item.style.display !== 'none') {
                const cb = item.querySelector('input[type="checkbox"]');
                cb.checked = false;
                item.classList.remove('selected');
            }
        });
        updateSummary();
    });

    // Khởi tạo tổng kết
    updateSummary();
});
</script>
