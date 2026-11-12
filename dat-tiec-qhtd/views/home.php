<style>
    /* ─── HERO ─── */
    .hero {
        min-height: 100vh;
        background: linear-gradient(160deg, var(--burgundy-dk) 0%, var(--burgundy) 55%, #9B3A50 100%);
        display: flex; align-items: center;
        padding-top: 90px;
        position: relative; overflow: hidden;
    }
    .hero::before {
        content: '';
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse 60% 60% at 80% 50%, rgba(201,162,87,0.08) 0%, transparent 70%),
            repeating-linear-gradient(45deg, transparent, transparent 40px, rgba(255,255,255,0.01) 40px, rgba(255,255,255,0.01) 41px);
    }
    .hero-ornament {
        position: absolute;
        right: 8%; top: 50%; transform: translateY(-50%);
        width: 420px; height: 420px;
        border: 1px solid rgba(201,162,87,0.15);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }
    .hero-ornament::before {
        content: '';
        width: 340px; height: 340px;
        border: 1px solid rgba(201,162,87,0.1);
        border-radius: 50%;
        position: absolute;
    }
    .hero-ornament .inner-icon {
        font-size: 90px;
        color: rgba(201,162,87,0.25);
        font-family: 'Cormorant Garamond', serif;
        font-style: italic;
        font-size: 5rem;
        color: rgba(255,255,255,0.08);
        text-align: center;
        line-height: 1;
    }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 10px;
        font-size: 0.72rem; letter-spacing: 4px; text-transform: uppercase;
        color: var(--gold-lt); font-weight: 500; margin-bottom: 24px;
    }
    .hero-eyebrow::before, .hero-eyebrow::after {
        content: ''; flex: 1; height: 1px;
        background: linear-gradient(to right, transparent, var(--gold));
        width: 30px;
    }
    .hero h1 {
        font-size: clamp(2.8rem, 5vw, 4.5rem);
        font-weight: 700; color: var(--white); line-height: 1.1;
        margin-bottom: 24px;
    }
    .hero h1 em {
        font-style: italic; color: var(--gold-lt);
        display: block;
    }
    .hero-desc {
        font-size: 1rem; color: rgba(255,255,255,0.65);
        line-height: 1.9; max-width: 480px; margin-bottom: 40px;
    }
    .btn-primary-qht {
        background: var(--gold);
        color: var(--charcoal);
        border: none; border-radius: 2px;
        padding: 14px 36px;
        font-family: 'Jost', sans-serif;
        font-size: 0.78rem; font-weight: 600;
        letter-spacing: 2.5px; text-transform: uppercase;
        text-decoration: none; display: inline-block;
        transition: all 0.3s;
    }
    .btn-primary-qht:hover {
        background: var(--gold-lt); color: var(--charcoal);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(201,162,87,0.35);
    }
    .btn-outline-qht {
        background: transparent;
        color: rgba(255,255,255,0.8);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 2px; padding: 13px 36px;
        font-family: 'Jost', sans-serif;
        font-size: 0.78rem; font-weight: 500;
        letter-spacing: 2px; text-transform: uppercase;
        text-decoration: none; display: inline-block;
        transition: all 0.3s;
    }
    .btn-outline-qht:hover {
        border-color: var(--gold-lt); color: var(--gold-lt);
    }
    .hero-stats {
        display: flex; gap: 48px; margin-top: 56px;
        padding-top: 36px;
        border-top: 1px solid rgba(255,255,255,0.08);
    }
    .hero-stat-num {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem; font-weight: 700; color: var(--gold-lt); line-height: 1;
    }
    .hero-stat-label {
        font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase;
        color: rgba(255,255,255,0.4); margin-top: 4px;
    }

    /* ─── SECTION GENERAL ─── */
    .sec-label {
        display: inline-flex; align-items: center; gap: 10px;
        font-size: 0.7rem; letter-spacing: 4px; text-transform: uppercase;
        color: var(--gold); font-weight: 600; margin-bottom: 14px;
    }
    .sec-label::before, .sec-label::after {
        content: ''; width: 24px; height: 1px; background: var(--gold);
    }
    .sec-title {
        font-size: clamp(2rem, 3vw, 2.8rem);
        font-weight: 700; color: var(--charcoal); line-height: 1.2;
    }
    .sec-title.light { color: var(--white); }
    .sec-desc { color: var(--warm-gray); font-size: 0.95rem; line-height: 1.9; }

    /* ─── FEATURES ─── */
    .features-sec {
        padding: 100px 0;
        background: var(--white);
    }
    .feature-box {
        padding: 40px 30px;
        border: 1px solid rgba(201,162,87,0.15);
        border-radius: 4px;
        text-align: center; height: 100%;
        transition: all 0.4s;
        background: var(--white);
    }
    .feature-box:hover {
        border-color: var(--gold);
        box-shadow: 0 20px 50px rgba(107,30,46,0.07);
        transform: translateY(-6px);
    }
    .feature-icon-wrap {
        width: 64px; height: 64px; border-radius: 50%;
        margin: 0 auto 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    .fi-burgundy { background: rgba(107,30,46,0.08); color: var(--burgundy); }
    .fi-gold     { background: rgba(201,162,87,0.12); color: #A07820; }
    .fi-green    { background: rgba(60,120,60,0.08); color: #3C783C; }
    .fi-blue     { background: rgba(30,80,140,0.08); color: #1E508C; }
    .feature-box h5 {
        font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;
        color: var(--charcoal);
    }
    .feature-box p { font-size: 0.88rem; color: var(--warm-gray); line-height: 1.8; }

    /* ─── OCCASIONS ─── */
    .occasions-sec {
        padding: 100px 0;
        background: var(--cream);
    }
    .occ-card {
        position: relative; border-radius: 4px; overflow: hidden;
        height: 320px; cursor: pointer;
    }
    .occ-card-bg {
        width: 100%; height: 100%;
        display: flex; align-items: flex-end;
        padding: 30px;
        transition: transform 0.5s ease;
    }
    .occ-card:hover .occ-card-bg { transform: scale(1.05); }
    .occ-bg-1 { background: linear-gradient(to top, rgba(74,16,32,0.9) 0%, rgba(74,16,32,0.2) 60%), linear-gradient(135deg, #C2185B, #880E4F); }
    .occ-bg-2 { background: linear-gradient(to top, rgba(74,16,32,0.9) 0%, rgba(74,16,32,0.2) 60%), linear-gradient(135deg, #E65100, #BF360C); }
    .occ-bg-3 { background: linear-gradient(to top, rgba(74,16,32,0.9) 0%, rgba(74,16,32,0.2) 60%), linear-gradient(135deg, #4A148C, #311B92); }
    .occ-bg-4 { background: linear-gradient(to top, rgba(74,16,32,0.9) 0%, rgba(74,16,32,0.2) 60%), linear-gradient(135deg, #1B5E20, #33691E); }
    .occ-info { position: relative; z-index: 2; }
    .occ-icon {
        font-size: 2.2rem; color: var(--gold-lt); margin-bottom: 10px;
        display: block;
    }
    .occ-card h5 {
        color: var(--white); font-size: 1.5rem; font-weight: 700; margin-bottom: 4px;
    }
    .occ-card p { color: rgba(255,255,255,0.75); font-size: 0.85rem; }

    /* ─── PROCESS ─── */
    .process-sec {
        padding: 100px 0;
        background: var(--burgundy-dk);
    }
    .process-step {
        text-align: center; padding: 0 20px;
        position: relative;
    }
    .process-num {
        font-family: 'Cormorant Garamond', serif;
        font-size: 4rem; font-weight: 700;
        color: rgba(201,162,87,0.15); line-height: 1;
        margin-bottom: -10px;
    }
    .process-icon {
        width: 60px; height: 60px; border-radius: 50%;
        border: 1px solid rgba(201,162,87,0.4);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; color: var(--gold-lt);
        margin: 0 auto 16px;
        background: rgba(201,162,87,0.05);
    }
    .process-step h5 { color: var(--white); font-size: 1.25rem; font-weight: 700; margin-bottom: 10px; }
    .process-step p { color: rgba(255,255,255,0.5); font-size: 0.85rem; line-height: 1.8; }
    .process-sep {
        color: rgba(201,162,87,0.25); font-size: 1.5rem;
        display: flex; align-items: center; padding-top: 20px;
    }

    /* ─── BANNER CTA ─── */
    .cta-sec {
        padding: 100px 0;
        background: var(--cream-dk);
        text-align: center;
    }
    .cta-sec h2 { font-size: clamp(2rem, 4vw, 3.2rem); font-weight: 700; color: var(--charcoal); margin-bottom: 16px; }
    .cta-sec h2 em { font-style: italic; color: var(--burgundy); }
    .cta-sec p { color: var(--warm-gray); font-size: 0.95rem; margin-bottom: 36px; }
    .btn-cta-main {
        background: var(--burgundy); color: var(--white);
        border: none; border-radius: 2px;
        padding: 16px 44px;
        font-family: 'Jost', sans-serif;
        font-size: 0.8rem; font-weight: 600;
        letter-spacing: 2.5px; text-transform: uppercase;
        text-decoration: none; display: inline-block;
        transition: all 0.3s;
    }
    .btn-cta-main:hover {
        background: var(--burgundy-dk); color: var(--white);
        box-shadow: 0 10px 30px rgba(107,30,46,0.3);
        transform: translateY(-2px);
    }
    .phone-cta {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2rem; font-weight: 600;
        color: var(--burgundy); text-decoration: none;
        display: block; margin-top: 20px;
    }
    .phone-cta:hover { color: var(--gold); }
</style>

<!-- ═══ HERO ═══ -->
<section class="hero">
    <div class="container position-relative" style="z-index:2">
        <div class="row">
            <div class="col-lg-7">
                <div class="hero-eyebrow">
                    <span>Nhà Hàng Tiệc Gia Đình</span>
                </div>
                <h1>
                    Nơi lưu giữ
                    <em>những kỷ niệm đẹp</em>
                    của gia đình bạn
                </h1>
                <p class="hero-desc">
                    QHT Banquet mang đến không gian ấm cúng và dịch vụ tiệc chuyên nghiệp
                    cho mọi dịp đặc biệt — sinh nhật, mừng thọ, hay họp mặt gia đình.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= BASE_URL ?>?action=dat-ban" class="btn-primary-qht">
                        <i class="bi bi-calendar2-check me-2"></i>Đặt Bàn Ngay
                    </a>
                    <a href="<?= BASE_URL ?>?action=thuc-don" class="btn-outline-qht">
                        Xem Thực Đơn
                    </a>
                </div>
                <div class="hero-stats">
                    <div>
                        <div class="hero-stat-num">500+</div>
                        <div class="hero-stat-label">Bữa tiệc</div>
                    </div>
                    <div>
                        <div class="hero-stat-num">50+</div>
                        <div class="hero-stat-label">Món ăn</div>
                    </div>
                    <div>
                        <div class="hero-stat-num">20+</div>
                        <div class="hero-stat-label">Bàn & Phòng</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-ornament d-none d-lg-flex">
        <div class="inner-icon">
            <i class="bi bi-cup-hot" style="font-size:5rem; color:rgba(201,162,87,0.2)"></i>
        </div>
    </div>
</section>

<!-- ═══ FEATURES ═══ -->
<section class="features-sec">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-6">
                <div class="sec-label">Dịch Vụ</div>
                <h2 class="sec-title">Tại sao chọn<br><em style="font-style:italic; color:var(--burgundy)">QHT Banquet?</em></h2>
            </div>
            <div class="col-lg-6 d-flex align-items-end">
                <p class="sec-desc">Mỗi bữa tiệc tại QHT Banquet đều được chăm chút từ những chi tiết nhỏ nhất — từ thực đơn, không gian đến dịch vụ phục vụ.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-wrap fi-burgundy"><i class="bi bi-book-half"></i></div>
                    <h5>Thực đơn đa dạng</h5>
                    <p>Hơn 50 món ăn từ nguyên liệu tươi ngon, được bếp trưởng lành nghề chế biến tinh tế</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-wrap fi-gold"><i class="bi bi-gift"></i></div>
                    <h5>Set tiệc trọn gói</h5>
                    <p>Các set tiệc thiết kế sẵn theo số người, giúp bạn tiết kiệm thời gian và chi phí</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-wrap fi-green"><i class="bi bi-door-open"></i></div>
                    <h5>Phòng riêng sang trọng</h5>
                    <p>Không gian riêng tư, trang trí theo yêu cầu, phù hợp từng loại dịp đặc biệt</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-wrap fi-blue"><i class="bi bi-phone"></i></div>
                    <h5>Đặt bàn tiện lợi</h5>
                    <p>Chọn ngày, chọn bàn, chọn set tiệc ngay trên web chỉ trong vài phút</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ OCCASIONS ═══ -->
<section class="occasions-sec">
    <div class="container">
        <div class="text-center mb-5">
            <div class="sec-label">Loại Dịp</div>
            <h2 class="sec-title">Phục vụ <em style="font-style:italic; color:var(--burgundy)">mọi dịp</em> đặc biệt</h2>
        </div>
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="occ-card">
                    <div class="occ-card-bg occ-bg-1">
                        <div class="occ-info">
                            <i class="bi bi-cake2 occ-icon"></i>
                            <h5>Sinh Nhật</h5>
                            <p>Tổ chức sinh nhật ấm cúng cho người thân</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="occ-card">
                    <div class="occ-card-bg occ-bg-2">
                        <div class="occ-info">
                            <i class="bi bi-heart occ-icon"></i>
                            <h5>Mừng Thọ</h5>
                            <p>Tri ân công đức ông bà, cha mẹ</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="occ-card">
                    <div class="occ-card-bg occ-bg-3">
                        <div class="occ-info">
                            <i class="bi bi-people occ-icon"></i>
                            <h5>Họp Mặt</h5>
                            <p>Sum vầy gia đình trong không gian ấm áp</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="occ-card">
                    <div class="occ-card-bg occ-bg-4">
                        <div class="occ-info">
                            <i class="bi bi-building occ-icon"></i>
                            <h5>Tiệc Công Ty</h5>
                            <p>Liên hoan, gặp mặt đối tác chuyên nghiệp</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ PROCESS ═══ -->
<section class="process-sec">
    <div class="container">
        <div class="text-center mb-5">
            <div class="sec-label" style="color:var(--gold-lt)">Quy Trình</div>
            <h2 class="sec-title light">Đặt bàn chỉ <em style="font-style:italic; color:var(--gold-lt)">4 bước</em></h2>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="process-num">01</div>
                    <div class="process-icon"><i class="bi bi-calendar3"></i></div>
                    <h5>Chọn dịp & ngày</h5>
                    <p>Chọn loại dịp và ngày bạn muốn tổ chức tiệc</p>
                </div>
            </div>
            <div class="col-auto d-none d-lg-flex process-sep"><i class="bi bi-arrow-right"></i></div>
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="process-num">02</div>
                    <div class="process-icon"><i class="bi bi-grid"></i></div>
                    <h5>Chọn bàn / phòng</h5>
                    <p>Xem bàn trống và chọn vị trí phù hợp</p>
                </div>
            </div>
            <div class="col-auto d-none d-lg-flex process-sep"><i class="bi bi-arrow-right"></i></div>
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="process-num">03</div>
                    <div class="process-icon"><i class="bi bi-journals"></i></div>
                    <h5>Chọn set / món</h5>
                    <p>Chọn set tiệc sẵn hoặc gọi món theo ý muốn</p>
                </div>
            </div>
            <div class="col-auto d-none d-lg-flex process-sep"><i class="bi bi-arrow-right"></i></div>
            <div class="col-lg-2 col-md-6">
                <div class="process-step">
                    <div class="process-num">04</div>
                    <div class="process-icon"><i class="bi bi-check2-circle"></i></div>
                    <h5>Xác nhận</h5>
                    <p>Gửi yêu cầu và nhà hàng sẽ duyệt cho bạn</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ CTA ═══ -->
<section class="cta-sec">
    <div class="container">
        <div class="sec-label justify-content-center">Đặt Bàn Ngay Hôm Nay</div>
        <h2>Sẵn sàng cho<br><em>bữa tiệc tiếp theo?</em></h2>
        <p>Liên hệ với chúng tôi để được tư vấn và nhận ưu đãi đặc biệt dành cho khách hàng mới.</p>
        <a href="<?= BASE_URL ?>?action=dat-ban" class="btn-cta-main">
            <i class="bi bi-calendar2-check me-2"></i> Đặt Bàn Ngay
        </a>
        <a href="tel:0123456789" class="phone-cta">0123 456 789</a>
    </div>
</section>
