<style>
/* ─── HERO ─── */
.hero {
    min-height: 100vh;
    background: var(--forest-dk);
    display: flex; align-items: center;
    padding-top: 90px; position: relative; overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 55% 70% at 70% 50%, rgba(196,96,60,0.12) 0%, transparent 65%),
        radial-gradient(ellipse 40% 50% at 20% 80%, rgba(44,74,53,0.6) 0%, transparent 60%);
}
.hero-tag {
    display: inline-flex; align-items: center; gap: 10px;
    font-size: 0.72rem; letter-spacing: 3.5px; text-transform: uppercase;
    color: var(--amber); font-weight: 500; margin-bottom: 20px;
}
.hero-tag::before {
    content: ''; width: 32px; height: 1px; background: var(--amber);
}
.hero h1 {
    font-size: clamp(2.6rem, 5vw, 4.2rem);
    color: var(--white); line-height: 1.12; margin-bottom: 22px;
}
.hero h1 em {
    font-style: italic; color: var(--amber-lt); display: block;
}
.hero-desc {
    font-size: 0.95rem; color: rgba(255,255,255,0.6);
    line-height: 1.95; max-width: 460px; margin-bottom: 38px;
}
.btn-primary-q {
    display: inline-block; background: var(--terra);
    color: var(--white); border-radius: 3px;
    padding: 14px 34px; text-decoration: none;
    font-size: 0.78rem; font-weight: 600; letter-spacing: 2px;
    text-transform: uppercase; transition: all 0.3s;
    border: none;
}
.btn-primary-q:hover {
    background: var(--terra-lt); color: var(--white);
    transform: translateY(-2px); box-shadow: 0 8px 24px rgba(196,96,60,0.35);
}
.btn-ghost-q {
    display: inline-block; background: transparent;
    color: rgba(255,255,255,0.75);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 3px; padding: 13px 34px; text-decoration: none;
    font-size: 0.78rem; font-weight: 500; letter-spacing: 2px;
    text-transform: uppercase; transition: all 0.3s;
}
.btn-ghost-q:hover {
    border-color: var(--amber); color: var(--amber-lt);
}
.hero-stats {
    display: flex; gap: 40px; margin-top: 52px;
    padding-top: 32px; border-top: 1px solid rgba(255,255,255,0.08);
}
.stat-num {
    font-family: 'DM Serif Display', serif;
    font-size: 2.3rem; color: var(--amber-lt); line-height: 1;
}
.stat-lbl {
    font-size: 0.7rem; letter-spacing: 2px; text-transform: uppercase;
    color: rgba(255,255,255,0.35); margin-top: 4px;
}
/* Hero visual panel */
.hero-panel {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px; padding: 32px;
    backdrop-filter: blur(10px);
}
.hero-panel-title {
    font-size: 0.7rem; letter-spacing: 3px; text-transform: uppercase;
    color: var(--amber); margin-bottom: 20px;
}
.mini-occasion {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px; border-radius: 8px;
    background: rgba(255,255,255,0.04); margin-bottom: 10px;
    border: 1px solid rgba(255,255,255,0.05);
    transition: all 0.3s; cursor: pointer;
}
.mini-occasion:hover { background: rgba(196,96,60,0.12); border-color: rgba(196,96,60,0.3); }
.mini-occ-icon {
    width: 42px; height: 42px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.moi-1 { background: rgba(194,24,91,0.2); color: #F48FB1; }
.moi-2 { background: rgba(230,81,0,0.2); color: #FFCC80; }
.moi-3 { background: rgba(44,74,53,0.4); color: #A5D6A7; }
.moi-4 { background: rgba(21,101,192,0.2); color: #90CAF9; }
.mini-occ-name { font-size: 0.88rem; color: rgba(255,255,255,0.8); font-weight: 500; }
.mini-occ-sub { font-size: 0.72rem; color: rgba(255,255,255,0.35); }
.mini-occ-arrow { margin-left: auto; color: rgba(255,255,255,0.2); font-size: 0.8rem; }

/* ─── SECTION ─── */
.sec-eyebrow {
    font-size: 0.7rem; letter-spacing: 3.5px; text-transform: uppercase;
    color: var(--terra); font-weight: 600; margin-bottom: 10px;
    display: flex; align-items: center; gap: 10px;
}
.sec-eyebrow::before { content: ''; width: 24px; height: 2px; background: var(--terra); border-radius: 2px; }
.sec-title { font-size: clamp(1.9rem, 3vw, 2.6rem); color: var(--charcoal); line-height: 1.2; }
.sec-title.lt { color: var(--white); }
.sec-title em { font-style: italic; color: var(--terra); }
.sec-title.lt em { color: var(--amber-lt); }
.sec-body { color: var(--muted); font-size: 0.9rem; line-height: 1.9; }

/* ─── FEATURES ─── */
.feat-sec { padding: 100px 0; background: var(--white); }
.feat-card {
    padding: 36px 28px; border-radius: 10px; height: 100%;
    border: 1.5px solid var(--stone); background: var(--white);
    transition: all 0.35s;
}
.feat-card:hover {
    border-color: var(--terra);
    box-shadow: 0 16px 40px rgba(44,74,53,0.07);
    transform: translateY(-5px);
}
.feat-icon {
    width: 52px; height: 52px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; margin-bottom: 18px;
}
.fi-1 { background: rgba(44,74,53,0.09); color: var(--forest); }
.fi-2 { background: rgba(196,96,60,0.1); color: var(--terra); }
.fi-3 { background: rgba(212,168,67,0.12); color: #A07820; }
.fi-4 { background: rgba(21,101,192,0.09); color: #1565C0; }
.feat-card h5 { font-size: 1.25rem; font-weight: 400; margin-bottom: 10px; color: var(--charcoal); }
.feat-card p { font-size: 0.87rem; color: var(--muted); line-height: 1.8; }

/* ─── OCCASIONS ─── */
.occ-sec { padding: 100px 0; background: var(--cream-dk); }
.occ-card {
    border-radius: 10px; overflow: hidden;
    height: 300px; cursor: pointer; position: relative;
}
.occ-card-bg {
    width: 100%; height: 100%;
    display: flex; align-items: flex-end; padding: 28px;
    transition: transform 0.5s ease;
}
.occ-card:hover .occ-card-bg { transform: scale(1.04); }
.occ-bg-1 { background: linear-gradient(to top, rgba(26,48,36,0.92) 0%, rgba(26,48,36,0.3) 55%), linear-gradient(135deg, #C2185B 0%, #E91E63 100%); }
.occ-bg-2 { background: linear-gradient(to top, rgba(26,48,36,0.92) 0%, rgba(26,48,36,0.3) 55%), linear-gradient(135deg, #BF360C 0%, #E64A19 100%); }
.occ-bg-3 { background: linear-gradient(to top, rgba(26,48,36,0.92) 0%, rgba(26,48,36,0.3) 55%), linear-gradient(135deg, #2E7D32 0%, #388E3C 100%); }
.occ-bg-4 { background: linear-gradient(to top, rgba(26,48,36,0.92) 0%, rgba(26,48,36,0.3) 55%), linear-gradient(135deg, #1565C0 0%, #1976D2 100%); }
.occ-tag {
    position: absolute; top: 18px; left: 18px;
    background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.85); border-radius: 20px;
    padding: 4px 14px; font-size: 0.7rem; letter-spacing: 1.5px; text-transform: uppercase;
}
.occ-icon-big { font-size: 1.8rem; color: rgba(255,255,255,0.7); margin-bottom: 8px; display: block; }
.occ-card h5 { color: var(--white); font-size: 1.5rem; margin-bottom: 4px; }
.occ-card p { color: rgba(255,255,255,0.65); font-size: 0.83rem; }
.occ-arrow {
    margin-left: auto; color: rgba(255,255,255,0.4);
    font-size: 1.1rem; align-self: flex-end;
}

/* ─── PROCESS ─── */
.proc-sec { padding: 100px 0; background: var(--forest); }
.proc-card {
    padding: 36px 24px; text-align: center;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.07);
    background: rgba(255,255,255,0.03);
    height: 100%; transition: all 0.35s;
}
.proc-card:hover {
    background: rgba(255,255,255,0.06);
    border-color: rgba(212,168,67,0.3);
}
.proc-num {
    font-family: 'DM Serif Display', serif;
    font-size: 3rem; color: rgba(212,168,67,0.2); line-height: 1; margin-bottom: -8px;
}
.proc-ico {
    width: 54px; height: 54px; border-radius: 50%;
    border: 1px solid rgba(212,168,67,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; color: var(--amber-lt);
    margin: 0 auto 16px;
}
.proc-card h5 { color: var(--white); font-size: 1.15rem; font-weight: 400; margin-bottom: 10px; }
.proc-card p { color: rgba(255,255,255,0.45); font-size: 0.84rem; line-height: 1.8; }
.proc-arrow { color: rgba(212,168,67,0.25); font-size: 1.3rem; padding-top: 60px; }

/* ─── CTA ─── */
.cta-sec {
    padding: 100px 0; background: var(--cream);
    text-align: center;
}
.cta-sec h2 { font-size: clamp(2rem, 4vw, 3rem); color: var(--charcoal); margin-bottom: 14px; }
.cta-sec h2 em { font-style: italic; color: var(--forest); }
.cta-sec p { color: var(--muted); font-size: 0.92rem; margin-bottom: 36px; }
.btn-cta {
    display: inline-block; background: var(--forest);
    color: var(--white); border-radius: 3px;
    padding: 15px 42px; text-decoration: none;
    font-size: 0.78rem; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase;
    transition: all 0.3s; margin-right: 12px;
}
.btn-cta:hover {
    background: var(--terra); color: var(--white);
    transform: translateY(-2px); box-shadow: 0 8px 24px rgba(44,74,53,0.2);
}
.btn-cta-out {
    display: inline-block; background: transparent;
    color: var(--forest);
    border: 1.5px solid var(--forest); border-radius: 3px;
    padding: 14px 42px; text-decoration: none;
    font-size: 0.78rem; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase;
    transition: all 0.3s;
}
.btn-cta-out:hover { background: var(--forest); color: var(--white); }
.hotline {
    font-family: 'DM Serif Display', serif;
    font-size: 1.9rem; color: var(--terra);
    text-decoration: none; display: block; margin-top: 22px;
}
.hotline:hover { color: var(--forest); }
</style>

<!-- ═══ HERO ═══ -->
<section class="hero">
    <div class="container position-relative" style="z-index:2">
        <div class="row align-items-center g-5">

            <!-- Left -->
            <div class="col-lg-6">
                <div class="hero-tag">Nhà Hàng Tiệc Gia Đình</div>
                <h1>
                    Mỗi khoảnh khắc
                    <em>đáng được trân trọng</em>
                </h1>
                <p class="hero-desc">
                    QHT Banquet mang đến không gian ấm cúng, thực đơn phong phú và dịch vụ
                    tận tâm cho sinh nhật, mừng thọ, họp mặt gia đình hay tiệc công ty.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= BASE_URL ?>?action=dat-ban" class="btn-primary-q">
                        <i class="bi bi-calendar2-check me-2"></i>Đặt Bàn Ngay
                    </a>
                    <a href="<?= BASE_URL ?>?action=thuc-don" class="btn-ghost-q">
                        Xem Thực Đơn
                    </a>
                </div>
                <div class="hero-stats">
                    <div>
                        <div class="stat-num">500+</div>
                        <div class="stat-lbl">Bữa tiệc</div>
                    </div>
                    <div>
                        <div class="stat-num">50+</div>
                        <div class="stat-lbl">Món ăn</div>
                    </div>
                    <div>
                        <div class="stat-num">20+</div>
                        <div class="stat-lbl">Bàn &amp; Phòng</div>
                    </div>
                </div>
            </div>

            <!-- Right panel -->
            <div class="col-lg-5 offset-lg-1 d-none d-lg-block">
                <div class="hero-panel">
                    <div class="hero-panel-title">Chọn loại tiệc</div>
                    <div class="mini-occasion">
                        <div class="mini-occ-icon moi-1"><i class="bi bi-cake2"></i></div>
                        <div>
                            <div class="mini-occ-name">Tiệc Sinh Nhật</div>
                            <div class="mini-occ-sub">Không gian ấm cúng & bánh kem</div>
                        </div>
                        <div class="mini-occ-arrow"><i class="bi bi-chevron-right"></i></div>
                    </div>
                    <div class="mini-occasion">
                        <div class="mini-occ-icon moi-2"><i class="bi bi-heart"></i></div>
                        <div>
                            <div class="mini-occ-name">Tiệc Mừng Thọ</div>
                            <div class="mini-occ-sub">Tri ân ông bà, cha mẹ</div>
                        </div>
                        <div class="mini-occ-arrow"><i class="bi bi-chevron-right"></i></div>
                    </div>
                    <div class="mini-occasion">
                        <div class="mini-occ-icon moi-3"><i class="bi bi-people"></i></div>
                        <div>
                            <div class="mini-occ-name">Họp Mặt Gia Đình</div>
                            <div class="mini-occ-sub">Sum vầy trong không gian riêng</div>
                        </div>
                        <div class="mini-occ-arrow"><i class="bi bi-chevron-right"></i></div>
                    </div>
                    <div class="mini-occasion">
                        <div class="mini-occ-icon moi-4"><i class="bi bi-building"></i></div>
                        <div>
                            <div class="mini-occ-name">Tiệc Công Ty</div>
                            <div class="mini-occ-sub">Liên hoan, gặp mặt đối tác</div>
                        </div>
                        <div class="mini-occ-arrow"><i class="bi bi-chevron-right"></i></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ═══ FEATURES ═══ -->
<section class="feat-sec">
    <div class="container">
        <div class="row mb-5 align-items-end">
            <div class="col-lg-6">
                <div class="sec-eyebrow">Dịch Vụ</div>
                <h2 class="sec-title">Tại sao chọn<br><em>QHT Banquet?</em></h2>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <p class="sec-body mt-3">Chúng tôi chú trọng từng chi tiết nhỏ nhất — từ món ăn, không gian đến phong cách phục vụ — để bữa tiệc của bạn thực sự đáng nhớ.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feat-card">
                    <div class="feat-icon fi-1"><i class="bi bi-book-half"></i></div>
                    <h5>Thực đơn đa dạng</h5>
                    <p>Hơn 50 món ăn từ nguyên liệu tươi ngon, bếp trưởng lành nghề chế biến tinh tế</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feat-card">
                    <div class="feat-icon fi-2"><i class="bi bi-gift"></i></div>
                    <h5>Set tiệc trọn gói</h5>
                    <p>Các set tiệc thiết kế sẵn theo số người, tiết kiệm thời gian và tối ưu chi phí</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feat-card">
                    <div class="feat-icon fi-3"><i class="bi bi-door-open"></i></div>
                    <h5>Phòng riêng tư</h5>
                    <p>Không gian riêng sang trọng, trang trí theo yêu cầu cho từng loại dịp</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feat-card">
                    <div class="feat-icon fi-4"><i class="bi bi-phone"></i></div>
                    <h5>Đặt bàn tiện lợi</h5>
                    <p>Chọn ngày, bàn, set tiệc ngay trên web — nhanh chóng chỉ trong vài phút</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ OCCASIONS ═══ -->
<section class="occ-sec">
    <div class="container">
        <div class="text-center mb-5">
            <div class="sec-eyebrow justify-content-center">Loại Dịp</div>
            <h2 class="sec-title">Phục vụ <em>mọi dịp</em> đặc biệt</h2>
        </div>
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="occ-card">
                    <span class="occ-tag">Sinh Nhật</span>
                    <div class="occ-card-bg occ-bg-1">
                        <div>
                            <i class="bi bi-cake2 occ-icon-big"></i>
                            <h5>Tiệc Sinh Nhật</h5>
                            <p>Kỷ niệm ngày đặc biệt cùng người thân</p>
                        </div>
                        <div class="occ-arrow"><i class="bi bi-arrow-up-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="occ-card">
                    <span class="occ-tag">Mừng Thọ</span>
                    <div class="occ-card-bg occ-bg-2">
                        <div>
                            <i class="bi bi-heart occ-icon-big"></i>
                            <h5>Tiệc Mừng Thọ</h5>
                            <p>Tri ân công đức ông bà, cha mẹ</p>
                        </div>
                        <div class="occ-arrow"><i class="bi bi-arrow-up-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="occ-card">
                    <span class="occ-tag">Họp Mặt</span>
                    <div class="occ-card-bg occ-bg-3">
                        <div>
                            <i class="bi bi-people occ-icon-big"></i>
                            <h5>Họp Mặt Gia Đình</h5>
                            <p>Sum vầy trong không gian ấm áp</p>
                        </div>
                        <div class="occ-arrow"><i class="bi bi-arrow-up-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="occ-card">
                    <span class="occ-tag">Công Ty</span>
                    <div class="occ-card-bg occ-bg-4">
                        <div>
                            <i class="bi bi-building occ-icon-big"></i>
                            <h5>Tiệc Công Ty</h5>
                            <p>Liên hoan, gặp mặt đối tác</p>
                        </div>
                        <div class="occ-arrow"><i class="bi bi-arrow-up-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ PROCESS ═══ -->
<section class="proc-sec">
    <div class="container">
        <div class="text-center mb-5">
            <div class="sec-eyebrow justify-content-center" style="color:var(--amber)">Quy Trình</div>
            <h2 class="sec-title lt">Đặt bàn chỉ <em>4 bước đơn giản</em></h2>
        </div>
        <div class="row align-items-start g-3">
            <div class="col-lg-3 col-md-6">
                <div class="proc-card">
                    <div class="proc-num">01</div>
                    <div class="proc-ico"><i class="bi bi-calendar3"></i></div>
                    <h5>Chọn dịp & ngày</h5>
                    <p>Chọn loại tiệc và ngày bạn muốn tổ chức</p>
                </div>
            </div>
            <div class="col-auto d-none d-lg-flex align-items-center proc-arrow">
                <i class="bi bi-arrow-right"></i>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="proc-card">
                    <div class="proc-num">02</div>
                    <div class="proc-ico"><i class="bi bi-grid-1x2"></i></div>
                    <h5>Chọn bàn / phòng</h5>
                    <p>Xem bàn còn trống và chọn vị trí phù hợp</p>
                </div>
            </div>
            <div class="col-auto d-none d-lg-flex align-items-center proc-arrow">
                <i class="bi bi-arrow-right"></i>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="proc-card">
                    <div class="proc-num">03</div>
                    <div class="proc-ico"><i class="bi bi-journals"></i></div>
                    <h5>Chọn set / món</h5>
                    <p>Chọn set tiệc sẵn hoặc tự gọi món theo ý</p>
                </div>
            </div>
            <div class="col-auto d-none d-lg-flex align-items-center proc-arrow">
                <i class="bi bi-arrow-right"></i>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="proc-card">
                    <div class="proc-num">04</div>
                    <div class="proc-ico"><i class="bi bi-check2-circle"></i></div>
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
        <div class="sec-eyebrow justify-content-center">Liên Hệ Ngay</div>
        <h2>Sẵn sàng cho<br><em>bữa tiệc tiếp theo?</em></h2>
        <p>Đặt bàn ngay hôm nay để nhận tư vấn miễn phí và ưu đãi dành cho khách mới.</p>
        <div>
            <a href="<?= BASE_URL ?>?action=dat-ban" class="btn-cta">
                <i class="bi bi-calendar2-check me-1"></i> Đặt Bàn Ngay
            </a>
            <a href="<?= BASE_URL ?>?action=thuc-don" class="btn-cta-out">Xem Thực Đơn</a>
        </div>
        <a href="tel:0123456789" class="hotline">0123 456 789</a>
    </div>
</section>
