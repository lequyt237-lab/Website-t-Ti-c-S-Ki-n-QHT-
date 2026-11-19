-- =============================================
-- DỮ LIỆU MẪU CHO BẢNG set_mon
-- (Gắn món ăn vào từng Set Tiệc)
-- Chạy sau khi đã import file qht_banquet_full_mariadb.sql
-- =============================================

USE `qht_banquet`;

-- Xóa dữ liệu cũ (nếu có) để tránh trùng
DELETE FROM `set_mon`;

-- ─────────────────────────────────
-- Set 1: Set Gia Đình Nhỏ (4-6 người, 6 món)
-- ─────────────────────────────────
INSERT INTO `set_mon` (`set_id`, `mon_id`) VALUES
(1, 1),   -- Gỏi cuốn tôm thịt (Khai vị)
(1, 3),   -- Gà hấp muối hoa tiêu (Món chính)
(1, 13),  -- Thịt ba chỉ kho tàu nước dừa xiêm (Gia đình Việt)
(1, 6),   -- Rau muống xào tỏi (Món phụ)
(1, 7),   -- Cơm chiên dương châu (Món phụ)
(1, 8);   -- Chè trôi nước (Tráng miệng)

-- ─────────────────────────────────
-- Set 2: Set Tiệc Vừa (6-10 người, 8 món)
-- ─────────────────────────────────
INSERT INTO `set_mon` (`set_id`, `mon_id`) VALUES
(2, 45),  -- Gỏi ngó sen tôm thịt chua ngọt (Khai vị)
(2, 2),   -- Súp bào ngư nấm (Khai vị)
(2, 4),   -- Bò lúc lắc sốt tiêu xanh (Món chính)
(2, 3),   -- Gà hấp muối hoa tiêu (Món chính)
(2, 14),  -- Canh chua cá lăng bông điên điển (Gia đình Việt)
(2, 6),   -- Rau muống xào tỏi (Món phụ)
(2, 7),   -- Cơm chiên dương châu (Món phụ)
(2, 55);  -- Panna Cotta dâu tây tươi (Tráng miệng)

-- ─────────────────────────────────
-- Set 3: Set Tiệc Lớn (10+ người, 10 món cao cấp)
-- ─────────────────────────────────
INSERT INTO `set_mon` (`set_id`, `mon_id`) VALUES
(3, 45),  -- Gỏi ngó sen tôm thịt chua ngọt (Khai vị)
(3, 2),   -- Súp bào ngư nấm (Khai vị)
(3, 5),   -- Tôm hùm hấp sả (Món chính)
(3, 4),   -- Bò lúc lắc sốt tiêu xanh (Món chính)
(3, 50),  -- Mực trứng hấp gừng ớt Phú Quốc (Đặc sản)
(3, 47),  -- Chả cá Lã Vọng Hà Thành (Đặc sản)
(3, 53),  -- Lẩu Thái hải sản chua cay đặc biệt (Lẩu)
(3, 6),   -- Rau muống xào tỏi (Món phụ)
(3, 57),  -- Đĩa trái cây thập cẩm tứ quý (Tráng miệng)
(3, 54);  -- Bánh Tiramisu Ý truyền thống (Tráng miệng)

-- ─────────────────────────────────
-- Set 4: Set Mừng Thọ (10 người, truyền thống)
-- ─────────────────────────────────
INSERT INTO `set_mon` (`set_id`, `mon_id`) VALUES
(4, 44),  -- Nem rán truyền thống Hà Nội (Khai vị)
(4, 2),   -- Súp bào ngư nấm (Khai vị)
(4, 16),  -- Gà ta thả vườn nướng mật ong rừng (Gia đình Việt)
(4, 12),  -- Cá lóc đồng nướng trui cuốn bánh tráng (Gia đình Việt)
(4, 13),  -- Thịt ba chỉ kho tàu nước dừa xiêm (Gia đình Việt)
(4, 17),  -- Bánh bao đào tiên mừng thọ (Truyền thống)
(4, 15),  -- Cơm niêu đáy cháy sốt kho quẹt (Gia đình Việt)
(4, 56);  -- Chè hạt sen long nhãn thanh nhiệt (Tráng miệng)
