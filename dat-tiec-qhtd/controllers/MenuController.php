<?php

class MenuController
{
    private $menuModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
    }

    public function index()
    {
        $dishes = $this->menuModel->getAllDishes();
        $categories = $this->menuModel->getCategories();

        $title = 'Thực đơn món ăn & Bánh tiệc - QHT Banquet';
        $view  = 'menu';
        require_once PATH_VIEW . 'main.php';
    }
}

