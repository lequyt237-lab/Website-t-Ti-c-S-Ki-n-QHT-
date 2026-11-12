<style>
    .auth-section {
        min-height: 95vh;
        background: linear-gradient(135deg, rgba(244, 237, 226, 0.6) 0%, rgba(253, 248, 242, 0.95) 100%);
        padding-top: 130px;
        padding-bottom: 80px;
        display: flex;
        align-items: center;
    }
    .auth-card {
        background: var(--white);
        border: 1px solid var(--stone);
        border-radius: 8px;
        padding: 40px;
        box-shadow: 0 12px 35px rgba(44, 74, 53, 0.08);
        position: relative;
    }
    .auth-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .auth-header .tag {
        display: inline-block;
        font-size: 0.72rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--terra);
        font-weight: 600;
        margin-bottom: 8px;
    }
    .auth-header h2 {
        font-family: 'DM Serif Display', serif;
        font-size: 2.1rem;
        color: var(--forest);
        margin-bottom: 6px;
    }
    .auth-header p {
        font-size: 0.9rem;
        color: var(--muted);
    }
    .form-label {
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        color: var(--charcoal);
        margin-bottom: 6px;
    }
    .input-group-text {
        background: var(--cream);
        border-color: var(--stone);
        color: var(--forest);
    }
    .form-control {
        border-color: var(--stone);
        padding: 11px 14px;
        font-size: 0.92rem;
        transition: all 0.25s ease;
    }
    .form-control:focus {
        border-color: var(--forest);
        box-shadow: 0 0 0 3px rgba(44, 74, 53, 0.12);
    }
    .btn-auth-submit {
        background: var(--forest);
        color: var(--white);
        border: none;
        border-radius: 4px;
        padding: 13px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        width: 100%;
        transition: all 0.3s ease;
        margin-top: 10px;
    }
    .btn-auth-submit:hover {
        background: var(--terra);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(196, 96, 60, 0.3);
    }
    .auth-footer {
        text-align: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid var(--stone);
        font-size: 0.88rem;
        color: var(--muted);
    }
    .auth-footer a {
        color: var(--terra);
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }
    .auth-footer a:hover {
        color: var(--forest);
        text-decoration: underline;
    }
    .toggle-password {
        cursor: pointer;
        background: transparent;
        border: 1px solid var(--stone);
        border-left: none;
        color: var(--muted);
        padding: 0 12px;
        display: flex;
        align-items: center;
    }
    .toggle-password:hover {
        color: var(--forest);
    }
</style>

<div class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9 col-lg-6">

                <?php 
                    $successFlash = get_flash('success');
                    $errorFlash = get_flash('error');
                ?>

                <?php if ($successFlash): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> <?= $successFlash ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($errorFlash): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $errorFlash ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="auth-card">
                    <div class="auth-header">
                        <span class="tag">Gia Nhập Cùng QHT Banquet</span>
                        <h2>Tạo Tài Khoản</h2>
                        <p>Đăng ký thành viên để nhận ưu đãi và trải nghiệm đặt tiệc trọn vẹn</p>
                    </div>

                    <form action="<?= BASE_URL ?>?action=dang-ky" method="POST" novalidate>
                        <!-- Họ và tên -->
                        <div class="mb-3">
                            <label for="ho_ten" class="form-label">HỌ VÀ TÊN <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" 
                                       class="form-control <?= has_error('ho_ten') ? 'is-invalid' : '' ?>" 
                                       id="ho_ten" 
                                       name="ho_ten" 
                                       placeholder="Ví dụ: Nguyễn Văn Hải" 
                                       value="<?= old('ho_ten') ?>" 
                                       required 
                                       autofocus>
                            </div>
                            <?php if ($errName = form_error('ho_ten')): ?>
                                <div class="text-danger mt-1 small"><i class="bi bi-exclamation-circle me-1"></i><?= $errName ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">ĐỊA CHỈ EMAIL <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       class="form-control <?= has_error('email') ? 'is-invalid' : '' ?>" 
                                       id="email" 
                                       name="email" 
                                       placeholder="Ví dụ: khachhang@gmail.com" 
                                       value="<?= old('email') ?>" 
                                       required>
                            </div>
                            <?php if ($errEmail = form_error('email')): ?>
                                <div class="text-danger mt-1 small"><i class="bi bi-exclamation-circle me-1"></i><?= $errEmail ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Số điện thoại -->
                        <div class="mb-3">
                            <label for="so_dien_thoai" class="form-label">SỐ ĐIỆN THOẠI</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="tel" 
                                       class="form-control <?= has_error('so_dien_thoai') ? 'is-invalid' : '' ?>" 
                                       id="so_dien_thoai" 
                                       name="so_dien_thoai" 
                                       placeholder="Ví dụ: 0912345678" 
                                       value="<?= old('so_dien_thoai') ?>">
                            </div>
                            <?php if ($errPhone = form_error('so_dien_thoai')): ?>
                                <div class="text-danger mt-1 small"><i class="bi bi-exclamation-circle me-1"></i><?= $errPhone ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Mật khẩu -->
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label for="mat_khau" class="form-label">MẬT KHẨU <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" 
                                           class="form-control <?= has_error('mat_khau') ? 'is-invalid' : '' ?>" 
                                           id="mat_khau" 
                                           name="mat_khau" 
                                           placeholder="Tối thiểu 6 ký tự" 
                                           required>
                                    <span class="toggle-password" onclick="togglePasswordVisibility('mat_khau', this)">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                                <?php if ($errPass = form_error('mat_khau')): ?>
                                    <div class="text-danger mt-1 small"><i class="bi bi-exclamation-circle me-1"></i><?= $errPass ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Xác nhận mật khẩu -->
                            <div class="col-md-6">
                                <label for="xac_nhan_mat_khau" class="form-label">XÁC NHẬN MẬT KHẨU <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                    <input type="password" 
                                           class="form-control <?= has_error('xac_nhan_mat_khau') ? 'is-invalid' : '' ?>" 
                                           id="xac_nhan_mat_khau" 
                                           name="xac_nhan_mat_khau" 
                                           placeholder="Nhập lại mật khẩu" 
                                           required>
                                    <span class="toggle-password" onclick="togglePasswordVisibility('xac_nhan_mat_khau', this)">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                                <?php if ($errConfirm = form_error('xac_nhan_mat_khau')): ?>
                                    <div class="text-danger mt-1 small"><i class="bi bi-exclamation-circle me-1"></i><?= $errConfirm ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" required checked>
                            <label class="form-check-label text-muted small" for="terms">
                                Tôi đồng ý với các điều khoản dịch vụ và chính sách bảo mật của QHT Banquet
                            </label>
                        </div>

                        <!-- Nút đăng ký -->
                        <button type="submit" class="btn-auth-submit">
                            <i class="bi bi-person-plus me-1"></i> Đăng Ký Tài Khoản
                        </button>
                    </form>

                    <div class="auth-footer">
                        Đã có tài khoản thành viên? 
                        <a href="<?= BASE_URL ?>?action=dang-nhap">Đăng nhập tại đây</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, el) {
        const input = document.getElementById(inputId);
        const icon = el.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>

<?php clear_flash_data(); ?>
