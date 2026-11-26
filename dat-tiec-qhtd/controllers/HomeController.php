<?php

class HomeController
{
    private $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
    }

    public function index() 
    {

        $bookingModel   = new BookingModel();
        $roomsAndTables = $bookingModel->getAllRoomsAndTables();
        $occasions      = $bookingModel->getAllOccasions();

        $userBookings = [];
        $bookingStats = [
            'total'     => 0,
            'cho_duyet' => 0,
            'da_duyet'  => 0,
            'tu_choi'   => 0,
            'da_huy'    => 0,
        ];

        if (isset($_SESSION['user'])) {
            $userId       = $_SESSION['user']['id'];
            $userBookings = $this->bookingModel->getRecentBookingsByUserId($userId, 4);
            $bookingStats = $this->bookingModel->getBookingStatsByUserId($userId);
        }

        $reviewModel = new ReviewModel();
        $homeReviews = $reviewModel->getPublicReviews(3);
        $reviewStats = $reviewModel->getStats();

        $title = 'Trang chủ - QHT Banquet';
        $view  = 'home';
        require_once PATH_VIEW . 'main.php';
    }
}
