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

    // Thanh toán & Hóa đơn (Phía khách hàng)
    'thanh-toan'            => (new PaymentController)->checkout(),
    'xu-ly-thanh-toan'      => (new PaymentController)->process(),
    'vnpay-gateway'         => (new PaymentController)->vnpayGateway(),
    'vnpay-callback'        => (new PaymentController)->vnpayCallback(),
    'thanh-toan-thanh-cong' => (new PaymentController)->success(),
    'hoa-don'               => (new PaymentController)->invoice(),

    // Quản trị viên (Admin)
    'admin'                   => (new AdminController)->index(),
    'admin-dat-ban'           => (new AdminController)->bookings(),
    'admin-dat-ban-chi-tiet'  => (new AdminController)->bookingDetail(),
    'admin-dat-ban-cap-nhat'  => (new AdminController)->updateBookingStatus(),
    // Quản lý Bàn & Phòng
    'admin-ban-phong'         => (new AdminController)->banPhong(),
    'admin-ban-phong-store'   => (new AdminController)->banPhongStore(),
    'admin-ban-phong-delete'  => (new AdminController)->banPhongDelete(),

    // Quản lý Món Ăn
    'admin-mon-an'            => (new AdminController)->monAn(),
    'admin-mon-an-store'      => (new AdminController)->monAnStore(),
    'admin-mon-an-edit'       => (new AdminController)->monAnEdit(),
    'admin-mon-an-update'     => (new AdminController)->monAnUpdate(),
    'admin-mon-an-quick-price'=> (new AdminController)->monAnQuickPrice(),
    'admin-mon-an-delete'     => (new AdminController)->monAnDelete(),

    'admin-set-tiec'          => (new AdminController)->setTiec(),
    'admin-set-tiec-create'   => (new AdminController)->setTiecCreate(),
    'admin-set-tiec-store'    => (new AdminController)->setTiecStore(),
    'admin-set-tiec-edit'     => (new AdminController)->setTiecEdit(),
    'admin-set-tiec-update'   => (new AdminController)->setTiecUpdate(),
    'admin-set-tiec-delete'   => (new AdminController)->setTiecDelete(),
    'admin-set-tiec-detail'   => (new AdminController)->setTiecDetail(),

    // Quản trị Thanh toán & Thu tiền (Admin)
    'admin-thanh-toan'          => (new AdminController)->payments(),
    'admin-thanh-toan-cap-nhat' => (new AdminController)->updatePaymentStatus(),
    'admin-thu-tien'            => (new AdminController)->adminRecordPayment(),

    // Lịch sử đặt bàn & Khách hàng
    'lich-su-dat-ban'     => (new BookingController)->history(),
    'chi-tiet-dat-ban'    => (new BookingController)->detail(),
    'huy-dat-ban'         => (new BookingController)->cancel(),

    // Trang cá nhân khách hàng
    'tai-khoan'           => (new ProfileController)->index(),
    'cap-nhat-thong-tin'  => (new ProfileController)->updateInfo(),
    'doi-mat-khau'        => (new ProfileController)->updatePassword(),

    // Đặt món cho bàn tiệc
    'dat-mon'           => (new BookingController)->orderFood(),
    'xu-ly-dat-mon'     => (new BookingController)->saveFoodOrder(),

    default             => (new HomeController)->index(),
};
