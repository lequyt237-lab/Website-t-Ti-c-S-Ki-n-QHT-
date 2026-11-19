<?php

class SetTiecController
{
    private $model;

    public function __construct()
    {
        $this->model = new SetTiecModel();
    }

    // Trang danh sách tất cả set tiệc (người dùng)
    public function index()
    {
        $sets = $this->model->getAll();

        // Lấy danh sách món cho từng set
        $setsWithMon = [];
        foreach ($sets as $s) {
            $s['mon_an'] = $this->model->getMonBySetId($s['id']);
            $setsWithMon[] = $s;
        }

        $title = 'Set Tiệc – Thực Đơn Trọn Gói | QHT Banquet';
        $view  = 'set_tiec';
        require_once PATH_VIEW . 'main.php';
    }

    // Trang chi tiết 1 set tiệc (người dùng)
    public function detail()
    {
        $id = intval($_GET['id'] ?? 0);
        $set = $this->model->getById($id);

        if (!$set) {
            header('Location: ' . BASE_URL . '?action=set-tiec');
            exit;
        }

        $monTrongSet = $this->model->getMonBySetId($id);

        // Lấy các set khác để gợi ý
        $allSets = $this->model->getAll();
        $otherSets = array_filter($allSets, fn($s) => $s['id'] != $id);

        $title = htmlspecialchars($set['ten']) . ' – QHT Banquet';
        $view  = 'set_tiec_detail_user';
        require_once PATH_VIEW . 'main.php';
    }
}
