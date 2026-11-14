<?php

class MenuModel extends BaseModel
{
    protected $table = 'mon_an';

    public function getAllDishes()
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function getCategories()
    {
        $stmt = $this->pdo->query("SELECT DISTINCT nhom_mon FROM {$this->table} ORDER BY nhom_mon ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getDishesByCategory($category)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE nhom_mon = :category ORDER BY id ASC");
        $stmt->execute([':category' => $category]);
        return $stmt->fetchAll();
    }
}

