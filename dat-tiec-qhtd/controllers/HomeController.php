<?php

class HomeController
{
    public function index() 
    {
        $bookingModel   = new BookingModel();
        $roomsAndTables = $bookingModel->getAllRoomsAndTables();
        $occasions      = $bookingModel->getAllOccasions();

        $title = 'Trang chủ - QHT Banquet';
        $view  = 'home';
        require_once PATH_VIEW . 'main.php';
    }
}