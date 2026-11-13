<?php

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Hiển thị form đăng nhập
    public function showLogin()
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['vai_tro'] === 'admin') {
                header('Location: ' . BASE_URL . '?action=admin');
            } else {
                header('Location: ' . BASE_URL);
            }
            exit;
        }

        $title = 'Đăng nhập - QHT Banquet';
        $view  = 'auth/login';
        require_once PATH_VIEW . 'main.php';
    }

    // Xử lý đăng nhập
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=dang-nhap');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['mat_khau'] ?? '');

        $errors = [];
        if (empty($email)) {
            $errors[] = 'Vui lòng nhập email.';
        }
        if (empty($password)) {
            $errors[] = 'Vui lòng nhập mật khẩu.';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . '?action=dang-nhap');
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            $_SESSION['error'] = 'Tài khoản email này không tồn tại trong hệ thống.';
            header('Location: ' . BASE_URL . '?action=dang-nhap');
            exit;
        }

        // Hỗ trợ kiểm tra cả mật khẩu băm và mật khẩu thường (để dễ test dữ liệu mẫu)
        $isPasswordValid = password_verify($password, $user['mat_khau']) || ($password === $user['mat_khau']);

        if (!$isPasswordValid) {
            $_SESSION['error'] = 'Mật khẩu không chính xác.';
            header('Location: ' . BASE_URL . '?action=dang-nhap');
            exit;
        }

        // Đăng nhập thành công -> Lưu session
        $_SESSION['user'] = [
            'id'            => $user['id'],
            'ho_ten'        => $user['ho_ten'],
            'email'         => $user['email'],
            'so_dien_thoai' => $user['so_dien_thoai'],
            'vai_tro'       => $user['vai_tro'],
        ];

        $_SESSION['success'] = 'Đăng nhập thành công! Chào mừng ' . htmlspecialchars($user['ho_ten']) . '.';

        // Phân luồng vai trò: Admin -> Trang Admin, Khách -> Trang chủ
        if ($user['vai_tro'] === 'admin') {
            header('Location: ' . BASE_URL . '?action=admin');
        } else {
            header('Location: ' . BASE_URL);
        }
        exit;
    }

    // Hiển thị form đăng ký
    public function showRegister()
    {
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $title = 'Đăng ký tài khoản - QHT Banquet';
        $view  = 'auth/register';
        require_once PATH_VIEW . 'main.php';
    }

    // Xử lý đăng ký
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=dang-ky');
            exit;
        }

        $hoTen         = trim($_POST['ho_ten'] ?? '');
        $email         = trim($_POST['email'] ?? '');
        $soDienThoai   = trim($_POST['so_dien_thoai'] ?? '');
        $password      = trim($_POST['mat_khau'] ?? '');
        $rePassword    = trim($_POST['nhap_lai_mat_khau'] ?? '');

        $errors = [];
        if (empty($hoTen)) {
            $errors[] = 'Vui lòng nhập họ và tên.';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ.';
        }
        if (empty($soDienThoai)) {
            $errors[] = 'Vui lòng nhập số điện thoại.';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
        }
        if ($password !== $rePassword) {
            $errors[] = 'Mật khẩu nhập lại không trùng khớp.';
        }

        // Kiểm tra email đã có chưa
        if (empty($errors)) {
            $existUser = $this->userModel->findByEmail($email);
            if ($existUser) {
                $errors[] = 'Email này đã được đăng ký trước đó.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['old_input'] = [
                'ho_ten'        => $hoTen,
                'email'         => $email,
                'so_dien_thoai' => $soDienThoai
            ];
            header('Location: ' . BASE_URL . '?action=dang-ky');
            exit;
        }

        // Tạo tài khoản khách (mặc định vai_tro = khach)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $result = $this->userModel->create([
            'ho_ten'        => $hoTen,
            'email'         => $email,
            'mat_khau'      => $hashedPassword,
            'so_dien_thoai' => $soDienThoai,
            'vai_tro'       => 'khach',
        ]);

        if ($result) {
            $newUser = $this->userModel->findByEmail($email);
            $_SESSION['user'] = [
                'id'            => $newUser['id'],
                'ho_ten'        => $newUser['ho_ten'],
                'email'         => $newUser['email'],
                'so_dien_thoai' => $newUser['so_dien_thoai'],
                'vai_tro'       => $newUser['vai_tro'],
            ];
            $_SESSION['success'] = 'Đăng ký tài khoản thành công!';
            header('Location: ' . BASE_URL);
            exit;
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra trong quá trình đăng ký, vui lòng thử lại.';
            header('Location: ' . BASE_URL . '?action=dang-ky');
            exit;
        }
    }

    // Đăng xuất
    public function logout()
    {
        unset($_SESSION['user']);
        $_SESSION['success'] = 'Bạn đã đăng xuất thành công.';
        header('Location: ' . BASE_URL . '?action=dang-nhap');
        exit;
    }
}

