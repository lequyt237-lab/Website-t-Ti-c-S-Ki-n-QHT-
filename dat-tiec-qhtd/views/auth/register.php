<?php 
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
?>

<section style="padding: 130px 0 90px; background: var(--cream);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #fff; border: 1px solid var(--stone) !important;">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <span style="font-size: 0.72rem; letter-spacing: 3px; text-transform: uppercase; color: var(--terra); font-weight: 600;">Gia nhập QHT Banquet</span>
                            <h2 class="mt-2" style="font-family: 'DM Serif Display', serif; color: var(--forest); font-size: 2rem;">Tạo Tài Khoản Mới</h2>
                            <p class="text-muted small">Đăng ký để dễ dàng đặt bàn tiệc và theo dõi đơn tiệc của gia đình</p>
                        </div>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger py-2 small" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= BASE_URL ?>?action=xu-ly-dang-ky" method="POST">
                            <div class="mb-3">
                                <label for="ho_ten" class="form-label small fw-semibold text-secondary">Họ và tên</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="ho_ten" name="ho_ten" 
                                           value="<?= htmlspecialchars($old['ho_ten'] ?? '') ?>" placeholder="Nguyễn Văn A" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label small fw-semibold text-secondary">Địa chỉ Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" 
                                               value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="name@example.com" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="so_dien_thoai" class="form-label small fw-semibold text-secondary">Số điện thoại</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0" id="so_dien_thoai" name="so_dien_thoai" 
                                               value="<?= htmlspecialchars($old['so_dien_thoai'] ?? '') ?>" placeholder="0912345678" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="mat_khau" class="form-label small fw-semibold text-secondary">Mật khẩu</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control border-start-0 ps-0" id="mat_khau" name="mat_khau" placeholder="Tối thiểu 6 ký tự" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nhap_lai_mat_khau" class="form-label small fw-semibold text-secondary">Nhập lại mật khẩu</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-check"></i></span>
                                        <input type="password" class="form-control border-start-0 ps-0" id="nhap_lai_mat_khau" name="nhap_lai_mat_khau" placeholder="Nhập lại mật khẩu" required>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn w-100 py-2 mt-3" style="background: var(--forest); color: #fff; font-weight: 600; letter-spacing: 1px; border-radius: 6px;">
                                <i class="bi bi-person-plus me-1"></i> Hoàn Tất Đăng Ký
                            </button>
                        </form>

                        <div class="text-center mt-4 pt-3 border-top" style="border-color: var(--stone) !important;">
                            <p class="small text-muted mb-0">Đã có tài khoản? 
                                <a href="<?= BASE_URL ?>?action=dang-nhap" class="fw-semibold text-decoration-none" style="color: var(--terra);">Đăng nhập tại đây</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

