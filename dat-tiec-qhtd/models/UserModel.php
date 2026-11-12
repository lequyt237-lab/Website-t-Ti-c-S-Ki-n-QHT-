<?php

class UserModel extends BaseModel
{
    protected $table = 'nguoidung';

    /**
     * Tìm người dùng theo email
     */
    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Tìm người dùng theo ID
     */
    public function findById(int $id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Kiểm tra xem email đã tồn tại hay chưa
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
        $params = [':email' => $email];

        if ($excludeId !== null) {
            $sql .= " AND id != :excludeId";
            $params[':excludeId'] = $excludeId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Thêm người dùng mới (đăng ký)
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} (ho_ten, email, mat_khau, so_dien_thoai, vai_tro, ngay_tao)
                VALUES (:ho_ten, :email, :mat_khau, :so_dien_thoai, :vai_tro, NOW())";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ho_ten'        => $data['ho_ten'],
            ':email'         => $data['email'],
            ':mat_khau'      => $data['mat_khau'],
            ':so_dien_thoai' => $data['so_dien_thoai'] ?? null,
            ':vai_tro'       => $data['vai_tro'] ?? 'khach',
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Cập nhật mật khẩu cho người dùng
     */
    public function updatePassword(int $id, string $hashedPassword): bool
    {
        $sql = "UPDATE {$this->table} SET mat_khau = :mat_khau WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':mat_khau' => $hashedPassword,
            ':id'       => $id,
        ]);
    }

    /**
     * Cập nhật thông tin người dùng
     */
    public function updateProfile(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} SET ho_ten = :ho_ten, so_dien_thoai = :so_dien_thoai WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':ho_ten'        => $data['ho_ten'],
            ':so_dien_thoai' => $data['so_dien_thoai'] ?? null,
            ':id'            => $id,
        ]);
    }

    /**
     * Lấy tất cả người dùng
     */
    public function all(): array
    {
        $sql = "SELECT id, ho_ten, email, so_dien_thoai, vai_tro, ngay_tao FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}

// Cho phép gọi cả 'User' lẫn 'UserModel'
if (!class_exists('User')) {
    class_alias('UserModel', 'User');
}
