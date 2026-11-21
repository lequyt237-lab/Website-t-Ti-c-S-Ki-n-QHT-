<?php

class MenuModel extends BaseModel
{
    protected $table = 'mon_an';

    public function getAllDishes()
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY nhom_mon ASC, ten ASC");
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getCategories()
    {
        $stmt = $this->pdo->query("SELECT DISTINCT nhom_mon FROM {$this->table} ORDER BY nhom_mon ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getDishesByCategory($category)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE nhom_mon = :category ORDER BY ten ASC");
        $stmt->execute([':category' => $category]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (ten, nhom_mon, gia, mo_ta) VALUES (:ten, :nhom_mon, :gia, :mo_ta)");
        $stmt->execute([
            ':ten'      => $data['ten'],
            ':nhom_mon' => $data['nhom_mon'],
            ':gia'      => $data['gia'],
            ':mo_ta'    => $data['mo_ta'] ?? null,
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET ten = :ten, nhom_mon = :nhom_mon, gia = :gia, mo_ta = :mo_ta WHERE id = :id");
        return $stmt->execute([
            ':id'       => $id,
            ':ten'      => $data['ten'],
            ':nhom_mon' => $data['nhom_mon'],
            ':gia'      => $data['gia'],
            ':mo_ta'    => $data['mo_ta'] ?? null,
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Kiểm tra món có đang dùng trong set_mon hoặc chitiet_mon không
    public function isInUse($id)
    {
        $inSet = $this->pdo->prepare("SELECT COUNT(*) FROM set_mon WHERE mon_id = :id");
        $inSet->execute([':id' => $id]);
        if ($inSet->fetchColumn() > 0) return true;

        $inOrder = $this->pdo->prepare("SELECT COUNT(*) FROM chitiet_mon WHERE mon_id = :id");
        $inOrder->execute([':id' => $id]);
        return $inOrder->fetchColumn() > 0;
    }
}

