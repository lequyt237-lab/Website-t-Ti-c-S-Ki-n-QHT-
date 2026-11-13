<section style="padding: 130px 0 90px; background: var(--cream);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #fff; border: 1px solid var(--stone) !important;">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <span style="font-size: 0.72rem; letter-spacing: 3px; text-transform: uppercase; color: var(--terra); font-weight: 600;">Chào mừng bạn trở lại</span>
                            <h2 class="mt-2" style="font-family: 'DM Serif Display', serif; color: var(--forest); font-size: 2rem;">Đăng Nhập</h2>
                            <p class="text-muted small">Nhập thông tin tài khoản để quản lý hoặc đặt tiệc</p>
                        </div>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger py-2 small" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success py-2 small" role="alert">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= BASE_URL ?>?action=xu-ly-dang-nhap" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-semibold text-secondary">Địa chỉ Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" placeholder="name@example.com" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="mat_khau" class="form-label small fw-semibold text-secondary">Mật khẩu</label>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control border-start-0 ps-0" id="mat_khau" name="mat_khau" placeholder="••••••••" required>
                                </div>
                            </div>

                            <button type="submit" class="btn w-100 py-2 mt-3" style="background: var(--forest); color: #fff; font-weight: 600; letter-spacing: 1px; border-radius: 6px;">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng Nhập
                            </button>
                        </form>

                        <div class="text-center mt-4 pt-3 border-top" style="border-color: var(--stone) !important;">
                            <p class="small text-muted mb-0">Chưa có tài khoản? 
                                <a href="<?= BASE_URL ?>?action=dang-ky" class="fw-semibold text-decoration-none" style="color: var(--terra);">Đăng ký ngay</a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Gợi ý đăng nhập nhanh cho người kiểm tra/chấm bài -->
                <div class="card mt-3 border-0" style="background: rgba(44, 74, 53, 0.05); border-radius: 8px;">
                    <div class="card-body p-3">
                        <div class="small text-muted mb-1"><i class="bi bi-info-circle me-1"></i> <b>Tài khoản mẫu từ Database:</b></div>
                        <div class="small">
                            <div>👑 <b>Admin:</b> <code>admin@qhtbanquet.vn</code> | MK: <code>admin</code> hoặc mật khẩu bạn đặt</div>
                            <div>👤 <b>Khách:</b> <code>nguyenvanan@gmail.com</code> | MK: <code>123456</code> hoặc tạo tài khoản mới</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

