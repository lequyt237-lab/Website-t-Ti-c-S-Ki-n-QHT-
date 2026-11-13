<?php

class UserModel extends BaseModel
{
    protected $table = 'nguoidung';

    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (ho_ten, email, mat_khau, so_dien_thoai, vai_tro) 
                VALUES (:ho_ten, :email, :mat_khau, :so_dien_thoai, :vai_tro)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':ho_ten'        => $data['ho_ten'],
            ':email'         => $data['email'],
            ':mat_khau'      => $data['mat_khau'],
            ':so_dien_thoai' => $data['so_dien_thoai'],
            ':vai_tro'       => $data['vai_tro'] ?? 'khach',
        ]);
    }

    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT id, ho_ten, email, so_dien_thoai, vai_tro, ngay_tao FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function countUsers()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM {$this->table}");
        return $stmt->fetch()['total'] ?? 0;
    }
}

