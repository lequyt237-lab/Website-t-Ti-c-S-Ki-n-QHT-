<style>
    .auth-section {
        min-height: 90vh;
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
            <div class="col-12 col-md-8 col-lg-5">

                <?php 
                    $successFlash = get_flash('success');
                    $errorFlash = get_flash('error');
                    $loginFailed = form_error('login_failed');
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

                <?php if ($loginFailed): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-octagon-fill me-2"></i> <?= $loginFailed ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="auth-card">
                    <div class="auth-header">
                        <span class="tag">Thành Viên QHT Banquet</span>
                        <h2>Đăng Nhập</h2>
                        <p>Đăng nhập tài khoản để quản lý đặt tiệc và ưu đãi</p>
                    </div>

                    <form action="<?= BASE_URL ?>?action=dang-nhap" method="POST" novalidate>
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">ĐỊA CHỈ EMAIL</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       class="form-control <?= has_error('email') ? 'is-invalid' : '' ?>" 
                                       id="email" 
                                       name="email" 
                                       placeholder="ví dụ: khachhang@gmail.com" 
                                       value="<?= old('email') ?>" 
                                       required 
                                       autofocus>
                            </div>
                            <?php if ($errEmail = form_error('email')): ?>
                                <div class="text-danger mt-1 small"><i class="bi bi-exclamation-circle me-1"></i><?= $errEmail ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Mật khẩu -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="mat_khau" class="form-label mb-0">MẬT KHẨU</label>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       class="form-control <?= has_error('mat_khau') ? 'is-invalid' : '' ?>" 
                                       id="mat_khau" 
                                       name="mat_khau" 
                                       placeholder="Nhập mật khẩu..." 
                                       required>
                                <span class="toggle-password" onclick="togglePasswordVisibility('mat_khau', this)">
                                    <i class="bi bi-eye"></i>
                                </span>
                            </div>
                            <?php if ($errPass = form_error('mat_khau')): ?>
                                <div class="text-danger mt-1 small"><i class="bi bi-exclamation-circle me-1"></i><?= $errPass ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Ghi nhớ đăng nhập -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember" checked>
                                <label class="form-check-label text-muted small" for="remember">
                                    Ghi nhớ phiên đăng nhập
                                </label>
                            </div>
                        </div>

                        <!-- Nút đăng nhập -->
                        <button type="submit" class="btn-auth-submit">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Đăng Nhập Ngay
                        </button>
                    </form>

                    <div class="auth-footer">
                        Bạn chưa có tài khoản? 
                        <a href="<?= BASE_URL ?>?action=dang-ky">Đăng ký thành viên mới</a>
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
