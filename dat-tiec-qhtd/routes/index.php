<?php

$action = $_GET['action'] ?? '/';

match ($action) {
    '/'                 => (new HomeController)->index(),

    // Xác thực tài khoản
    'dang-nhap'         => (new AuthController)->showLogin(),
    'xu-ly-dang-nhap'   => (new AuthController)->login(),
    'dang-ky'           => (new AuthController)->showRegister(),
    'xu-ly-dang-ky'     => (new AuthController)->register(),
    'dang-xuat'         => (new AuthController)->logout(),

    // Quản trị viên (Admin)
    'admin'             => (new AdminController)->index(),

    // Lịch sử đặt bàn & Khách hàng
    'lich-su-dat-ban'   => (new BookingController)->history(),
    'chi-tiet-dat-ban'  => (new BookingController)->detail(),
    'huy-dat-ban'       => (new BookingController)->cancel(),

    default             => (new HomeController)->index(),
};
