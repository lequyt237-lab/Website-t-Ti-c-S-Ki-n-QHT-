<?php

class AuthController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Hiển thị trang đăng nhập
     */
    public function showLoginForm()
    {
        if (is_logged_in()) {
            redirect(BASE_URL);
        }

        $title = 'Đăng Nhập - QHT Banquet';
        $view  = 'auth/login';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xử lý gửi form đăng nhập
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . '?action=dang-nhap');
        }

        $email    = trim($_POST['email'] ?? '');
        $matKhau  = $_POST['mat_khau'] ?? '';
        $errors   = [];

        // Validate cơ bản
        if (empty($email)) {
            $errors['email'] = 'Vui lòng nhập địa chỉ email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Địa chỉ email không đúng định dạng.';
        }

        if (empty($matKhau)) {
            $errors['mat_khau'] = 'Vui lòng nhập mật khẩu.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old']    = ['email' => $email];
            redirect(BASE_URL . '?action=dang-nhap');
        }

        // Tìm người dùng trong database
        $user = $this->userModel->findByEmail($email);

        $isPasswordValid = false;
        if ($user) {
            // Kiểm tra mật khẩu băm
            if (password_verify($matKhau, $user['mat_khau'])) {
                $isPasswordValid = true;
            } 
            // Fallback: nếu mật khẩu trong DB là mật khẩu thường (nhập tay trực tiếp)
            elseif ($user['mat_khau'] === $matKhau) {
                $isPasswordValid = true;
                // Nâng cấp tự động lên bcrypt hash để an toàn
                $this->userModel->updatePassword($user['id'], password_hash($matKhau, PASSWORD_DEFAULT));
            }
        }

        if (!$user || !$isPasswordValid) {
            $_SESSION['errors'] = [
                'login_failed' => 'Email hoặc mật khẩu không chính xác. Vui lòng thử lại!'
            ];
            $_SESSION['old'] = ['email' => $email];
            redirect(BASE_URL . '?action=dang-nhap');
        }

        // Đăng nhập thành công - lưu session
        $_SESSION['user'] = [
            'id'            => $user['id'],
            'ho_ten'        => $user['ho_ten'],
            'email'         => $user['email'],
            'so_dien_thoai' => $user['so_dien_thoai'],
            'vai_tro'       => $user['vai_tro'] ?? 'khach',
        ];

        set_flash('success', 'Đăng nhập thành công! Chào mừng ' . htmlspecialchars($user['ho_ten']) . ' quay trở lại.');
        redirect(BASE_URL);
    }

    /**
     * Hiển thị trang đăng ký
     */
    public function showRegisterForm()
    {
        if (is_logged_in()) {
            redirect(BASE_URL);
        }

        $title = 'Đăng Ký Tài Khoản - QHT Banquet';
        $view  = 'auth/register';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xử lý gửi form đăng ký
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(BASE_URL . '?action=dang-ky');
        }

        $hoTen          = trim($_POST['ho_ten'] ?? '');
        $email          = trim($_POST['email'] ?? '');
        $soDienThoai    = trim($_POST['so_dien_thoai'] ?? '');
        $matKhau        = $_POST['mat_khau'] ?? '';
        $xacNhanMatKhau = $_POST['xac_nhan_mat_khau'] ?? '';

        $errors = [];

        // Validate Họ tên
        if (empty($hoTen)) {
            $errors['ho_ten'] = 'Vui lòng nhập họ và tên của bạn.';
        } elseif (mb_strlen($hoTen) < 2) {
            $errors['ho_ten'] = 'Họ và tên phải có ít nhất 2 ký tự.';
        }

        // Validate Email
        if (empty($email)) {
            $errors['email'] = 'Vui lòng nhập địa chỉ email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Địa chỉ email không hợp lệ (ví dụ: name@example.com).';
        } elseif ($this->userModel->emailExists($email)) {
            $errors['email'] = 'Địa chỉ email này đã được sử dụng. Vui lòng chọn email khác!';
        }

        // Validate Số điện thoại (nếu có nhập)
        if (!empty($soDienThoai)) {
            if (!preg_match('/^(0[3|5|7|8|9])[0-9]{8}$/', $soDienThoai)) {
                $errors['so_dien_thoai'] = 'Số điện thoại không hợp lệ (gồm 10 chữ số, bắt đầu bằng 03, 05, 07, 08, 09).';
            }
        }

        // Validate Mật khẩu
        if (empty($matKhau)) {
            $errors['mat_khau'] = 'Vui lòng nhập mật khẩu.';
        } elseif (strlen($matKhau) < 6) {
            $errors['mat_khau'] = 'Mật khẩu phải chứa ít nhất 6 ký tự.';
        }

        // Validate Xác nhận mật khẩu
        if (empty($xacNhanMatKhau)) {
            $errors['xac_nhan_mat_khau'] = 'Vui lòng nhập lại mật khẩu xác nhận.';
        } elseif ($matKhau !== $xacNhanMatKhau) {
            $errors['xac_nhan_mat_khau'] = 'Mật khẩu xác nhận không trùng khớp.';
        }

        // Nếu có lỗi, chuyển về form kèm thông báo
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = [
                'ho_ten'        => $hoTen,
                'email'         => $email,
                'so_dien_thoai' => $soDienThoai,
            ];
            redirect(BASE_URL . '?action=dang-ky');
        }

        // Mã hóa mật khẩu bảo mật bằng bcrypt
        $hashPassword = password_hash($matKhau, PASSWORD_DEFAULT);

        // Lưu tài khoản mới vào cơ sở dữ liệu
        try {
            $userId = $this->userModel->create([
                'ho_ten'        => $hoTen,
                'email'         => $email,
                'mat_khau'      => $hashPassword,
                'so_dien_thoai' => !empty($soDienThoai) ? $soDienThoai : null,
                'vai_tro'       => 'khach',
            ]);

            set_flash('success', 'Đăng ký tài khoản thành công! Mời bạn đăng nhập để tiếp tục.');
            redirect(BASE_URL . '?action=dang-nhap');
        } catch (Exception $e) {
            set_flash('error', 'Có lỗi xảy ra trong quá trình đăng ký: ' . $e->getMessage());
            redirect(BASE_URL . '?action=dang-ky');
        }
    }

    /**
     * Đăng xuất tài khoản
     */
    public function logout()
    {
        unset($_SESSION['user']);
        set_flash('success', 'Bạn đã đăng xuất thành công.');
        redirect(BASE_URL);
    }
}
