<?php

$action = $_GET['action'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

match ($action) {
    '/'          => (new HomeController)->index(),
    
    // Đăng nhập
    'dang-nhap'  => $method === 'POST'
                     ? (new AuthController)->login()
                     : (new AuthController)->showLoginForm(),

    // Đăng ký
    'dang-ky'    => $method === 'POST'
                     ? (new AuthController)->register()
                     : (new AuthController)->showRegisterForm(),

    // Đăng xuất
    'dang-xuat'  => (new AuthController)->logout(),

    // Mặc định về trang chủ nếu action không khớp
    default      => (new HomeController)->index(),
};
