<style>
    .menu-header-sec {
        padding: 130px 0 60px;
        background: var(--forest-dk);
        color: #fff;
        text-align: center;
        position: relative;
    }
    .menu-header-sec::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse 60% 60% at 50% 50%, rgba(196,96,60,0.15) 0%, transparent 70%);
    }
    .category-filter-btn {
        background: #fff;
        color: var(--charcoal);
        border: 1.5px solid var(--stone);
        border-radius: 30px;
        padding: 8px 22px;
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.25s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .category-filter-btn:hover, .category-filter-btn.active {
        background: var(--forest);
        color: #fff;
        border-color: var(--forest);
        box-shadow: 0 4px 12px rgba(44, 74, 53, 0.2);
    }
    .dish-card {
        background: #fff;
        border: 1px solid var(--stone);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .dish-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 30px rgba(44, 74, 53, 0.08);
        border-color: var(--terra);
    }
    .dish-badge {
        font-size: 0.68rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
    }
    .badge-viet { background: rgba(44, 74, 53, 0.12); color: var(--forest); }
    .badge-cake { background: rgba(196, 96, 60, 0.12); color: var(--terra); }
    .badge-main { background: rgba(212, 168, 67, 0.15); color: #8C6A18; }
    .badge-other { background: rgba(30, 80, 140, 0.1); color: #1E508C; }
</style>

<!-- ═══ HEADER THỰC ĐƠN ═══ -->
<section class="menu-header-sec">
    <div class="container position-relative" style="z-index: 2;">
        <span class="d-inline-block text-uppercase small fw-semibold" style="letter-spacing: 3px; color: var(--amber-lt);">
            Ẩm Thực QHT Banquet
        </span>
        <h1 class="mt-2 mb-3" style="font-family: 'DM Serif Display', serif; font-size: clamp(2.2rem, 4vw, 3.2rem);">
            Thực Đơn Đa Dạng – <em>Trọn Vị Yêu Thương</em>
        </h1>
        <p class="text-white-50 mx-auto" style="max-width: 600px; font-size: 0.95rem; line-height: 1.8;">
            Từ mâm cơm gia đình Việt mộc mạc, món tiệc sang trọng đến bánh kem sinh nhật và mừng thọ ngọt ngào.
        </p>
    </div>
</section>

<!-- ═══ NỘI DUNG THỰC ĐƠN & BỘ LỌC ═══ -->
<section class="py-5" style="background: var(--cream);">
    <div class="container">

        <!-- Bộ lọc danh mục -->
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
            <button class="category-filter-btn active" data-filter="all">
                <i class="bi bi-grid-fill"></i> Tất cả món (<?= count($dishes) ?>)
            </button>
            <button class="category-filter-btn" data-filter="Món ăn gia đình Việt">
                <i class="bi bi-house-heart-fill text-success"></i> Món Gia Đình Việt
            </button>
            <button class="category-filter-btn" data-filter="Đặc sản Bắc - Trung - Nam">
                <i class="bi bi-geo-alt-fill text-danger"></i> Đặc Sản Bắc - Trung - Nam
            </button>
            <button class="category-filter-btn" data-filter="Khai vị">
                <i class="bi bi-cup-straw text-primary"></i> Ăn Nhẹ &amp; Khai Vị
            </button>
            <button class="category-filter-btn" data-filter="Bánh tiệc & Sinh nhật">
                <i class="bi bi-cake2-fill text-warning"></i> Bánh Kem &amp; Sinh Nhật
            </button>
            <button class="category-filter-btn" data-filter="Lẩu">
                <i class="bi bi-fire text-danger"></i> Lẩu &amp; Món Chính
            </button>
            <button class="category-filter-btn" data-filter="Tráng miệng">
                <i class="bi bi-flower1 text-info"></i> Tráng Miệng &amp; Chè
            </button>
        </div>

        <!-- Danh sách món ăn -->
        <div class="row g-4" id="dishesGrid">
            <?php foreach ($dishes as $d): ?>
                <?php
                // Phân loại nhóm badge
                $badgeClass = 'badge-other';
                if ($d['nhom_mon'] === 'Món ăn gia đình Việt') $badgeClass = 'badge-viet';
                elseif ($d['nhom_mon'] === 'Bánh tiệc & Sinh nhật') $badgeClass = 'badge-cake';
                elseif ($d['nhom_mon'] === 'Món chính') $badgeClass = 'badge-main';
                ?>
                <div class="col-lg-4 col-md-6 dish-item" data-category="<?= htmlspecialchars($d['nhom_mon']) ?>">
                    <div class="dish-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="dish-badge <?= $badgeClass ?>">
                                <?= htmlspecialchars($d['nhom_mon']) ?>
                            </span>
                            <span class="fw-bold" style="font-family: 'DM Serif Display', serif; font-size: 1.25rem; color: var(--terra);">
                                <?= number_format($d['gia']) ?> <small style="font-size: 0.8rem;">đ</small>
                            </span>
                        </div>

                        <h4 class="mt-2 mb-2" style="font-family: 'DM Serif Display', serif; font-size: 1.35rem; color: var(--charcoal); line-height: 1.3;">
                            <?= htmlspecialchars($d['ten']) ?>
                        </h4>

                        <p class="text-muted small flex-grow-1" style="line-height: 1.7;">
                            <?= !empty($d['mo_ta']) ? htmlspecialchars($d['mo_ta']) : 'Món ăn được chế biến từ nguyên liệu tươi sống chọn lọc mỗi ngày bởi bếp trưởng QHT Banquet.' ?>
                        </p>

                        <div class="pt-3 border-top mt-auto d-flex justify-content-between align-items-center">
                            <span class="small text-secondary"><i class="bi bi-check2 text-success me-1"></i>Có sẵn phục vụ</span>
                            <a href="<?= BASE_URL ?>#dat-ban" class="btn btn-sm btn-outline-dark" style="border-radius: 6px; font-size: 0.78rem; font-weight: 600;">
                                <i class="bi bi-calendar2-plus me-1"></i> Đặt bàn kèm món
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Banner mời đặt bàn bên dưới -->
        <div class="card mt-5 border-0 shadow-sm" style="border-radius: 14px; background: linear-gradient(135deg, var(--forest), var(--forest-dk)); color: #fff;">
            <div class="card-body p-4 p-md-5 text-center">
                <h3 style="font-family: 'DM Serif Display', serif; font-size: 2rem;">Bạn muốn thiết kế thực đơn riêng cho ngày đặc biệt?</h3>
                <p class="text-white-50 mx-auto mb-4" style="max-width: 650px;">
                    Dù là tiệc sinh nhật ấm cúng với bánh kem, họp mặt gia đình cuối tuần hay tiếp đón đối tác, QHT Banquet luôn sẵn sàng chuẩn bị theo yêu cầu riêng của bạn.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="<?= BASE_URL ?>#dat-ban" class="btn btn-light px-4 py-2 fw-bold text-uppercase" style="letter-spacing: 1px; color: var(--forest); border-radius: 6px;">
                        <i class="bi bi-calendar-check me-1"></i> Đặt Bàn Ngay
                    </a>
                    <a href="tel:0123456789" class="btn btn-outline-light px-4 py-2 fw-semibold" style="border-radius: 6px;">
                        <i class="bi bi-telephone me-1"></i> Hotline: 0123 456 789
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Script lọc danh mục mượt mà -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterBtns = document.querySelectorAll('.category-filter-btn');
        const dishItems  = document.querySelectorAll('.dish-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const selectedFilter = this.getAttribute('data-filter');

                dishItems.forEach(item => {
                    const itemCategory = item.getAttribute('data-category');
                    if (selectedFilter === 'all' || itemCategory.includes(selectedFilter)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>

