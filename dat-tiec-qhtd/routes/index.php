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

    // Thực đơn & Ẩm thực
    'thuc-don'          => (new MenuController)->index(),

    // Set Tiệc (Trang người dùng)
    'set-tiec'          => (new SetTiecController)->index(),
    'chi-tiet-set-tiec' => (new SetTiecController)->detail(),

    // Đặt bàn / Đặt phòng
    'dat-ban'           => header('Location: ' . BASE_URL . '#dat-ban') ?: exit,
    'xu-ly-dat-ban'     => (new BookingController)->submit(),

    // Quản trị viên (Admin)
    'admin'                   => (new AdminController)->index(),
    'admin-set-tiec'          => (new AdminController)->setTiec(),
    'admin-set-tiec-create'   => (new AdminController)->setTiecCreate(),
    'admin-set-tiec-store'    => (new AdminController)->setTiecStore(),
    'admin-set-tiec-edit'     => (new AdminController)->setTiecEdit(),
    'admin-set-tiec-update'   => (new AdminController)->setTiecUpdate(),
    'admin-set-tiec-delete'   => (new AdminController)->setTiecDelete(),
    'admin-set-tiec-detail'   => (new AdminController)->setTiecDetail(),

    // Lịch sử đặt bàn & Khách hàng
    'lich-su-dat-ban'   => (new BookingController)->history(),
    'chi-tiet-dat-ban'  => (new BookingController)->detail(),
    'huy-dat-ban'       => (new BookingController)->cancel(),

    default             => (new HomeController)->index(),
};
