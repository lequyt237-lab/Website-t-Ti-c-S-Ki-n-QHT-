USE qht_banquet;

SET NAMES 'utf8mb4';
SET CHARACTER SET utf8mb4;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE chitiet_mon;
TRUNCATE TABLE danh_gia;
TRUNCATE TABLE dat_ban;
TRUNCATE TABLE set_mon;
TRUNCATE TABLE mon_an;
TRUNCATE TABLE set_tiec;
TRUNCATE TABLE ban_phong;
TRUNCATE TABLE loai_dip;
TRUNCATE TABLE nguoidung;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Người dùng
INSERT INTO nguoidung (id, ho_ten, email, mat_khau, so_dien_thoai, vai_tro, ngay_tao) VALUES
(1, 'Quản Trị Viên QHT', 'admin@gmail.com', '123456', '0988888888', 'admin', '2026-11-12 15:31:10'),
(2, 'Nguyễn Văn Khách', 'khachhang@gmail.com', '123456', '0912345678', 'khach', '2026-11-12 15:31:10'),
(3, 'Lê Thị Hương Giang', 'huonggiang@gmail.com', '123456', '0987654321', 'khach', '2026-11-13 09:20:00');

-- 2. Bàn & Phòng
INSERT INTO ban_phong (id, ten, loai, suc_chua, phu_phi, mo_ta, anh) VALUES
(1, 'Phòng VIP Hoàng Gia', 'phong_rieng', 12, 200000, 'Không gian riêng tư sang trọng với đèn chùm pha lê, hệ thống âm thanh riêng biệt.', 'vip_hoanggia.jpg'),
(2, 'Phòng VIP Ngọc Bích', 'phong_rieng', 16, 300000, 'Phòng đại tiệc phong cách cung đình, thích hợp tiệc công ty và gia tộc.', 'vip_ngocbich.jpg'),
(3, 'Bàn Sảnh A1 - Sum Vầy', 'ban', 6, 0, 'Bàn tròn ấm cúng nhìn ra hồ nước cảnh quan tại sảnh chính.', 'ban_a1.jpg'),
(4, 'Bàn Sảnh A2 - Hạnh Phúc', 'ban', 8, 0, 'Bàn lớn tại trung tâm sảnh tiệc, trang trí hoa tươi nhẹ nhàng.', 'ban_a2.jpg'),
(5, 'Bàn Sảnh B1 - Tiệc Lớn', 'ban', 14, 0, 'Bàn dài kết hợp phù hợp liên hoan đông người.', 'ban_b1.jpg'),
(6, 'Bàn Sân Vườn Eden', 'ban', 10, 100000, 'Khu vườn ngoài trời thoáng đãng, lãng mạn dưới ánh đèn ấm áp.', 'ban_eden.jpg');

-- 3. Loại dịp tiệc
INSERT INTO loai_dip (id, ten, mo_ta) VALUES
(1, 'Tiệc Sinh Nhật', 'Trang trí nến, hoa và hỗ trợ quà chúc mừng sinh nhật ấm áp'),
(2, 'Tiệc Mừng Thọ', 'Bố trí khánh chúc thọ trang trọng, nghi lễ mừng tuổi ông bà cha mẹ'),
(3, 'Họp Mặt Gia Đình', 'Sum họp gia đình đầm ấm, thực đơn phong phú đủ vị cho cả trẻ nhỏ và người lớn tuổi'),
(4, 'Tiệc Công Ty & Đối Tác', 'Không gian chuyên nghiệp, âm thanh ánh sáng hoàn hảo cho liên hoan và ký kết'),
(5, 'Kỷ Niệm Ngày Cưới', 'Bàn tiệc lãng mạn, rượu vang và hoa hồng cho những khoảnh khắc gắn kết');

-- 4. Set tiệc
INSERT INTO set_tiec (id, ten, gia, so_nguoi_phu_hop, mo_ta) VALUES
(1, 'Set Phú Quý Đoàn Viên', 2850000, 6, 'Gồm 6 món tinh hoa: Súp vi cá, Gỏi củ hủ dừa tôm thịt, Gà đồi hấp lá chanh, Cá chẽm Hồng Kông, Lẩu nấm hải sản, Chè tuyết nhĩ hạt sen.'),
(2, 'Set Hoàng Kim Đại Thọ', 4500000, 10, 'Gồm 8 món thượng hạng: Súp bào ngư hải sâm, Heo sữa quay da giòn, Tôm sú hoàng kim, Bò Úc sốt tiêu đen, Lẩu cua đồng hải sản, Tráng miệng yến chưng đường phèn.'),
(3, 'Set Duyên Nồng Uyên Ương', 1950000, 4, 'Gồm 5 món thanh tao: Salad ức ngỗng xông khói, Bồ câu quay mật ong, Tôm hấp nước dừa, Lẩu thái tomyum, Bánh tuyết mochi.');

-- 5. Món ăn
INSERT INTO mon_an (id, ten, nhom_mon, gia, mo_ta, anh) VALUES
(1, 'Súp Bào Ngư Hải Sâm Thượng Hạng', 'Khai vị', 180000, 'Nước dùng ninh từ xương ống và gà già trong 8 tiếng, bào ngư tươi béo ngậy.', 'sup_baongu.jpg'),
(2, 'Gỏi Củ Hủ Dừa Tôm Thịt Bến Tre', 'Khai vị', 160000, 'Củ hủ dừa giòn ngọt tự nhiên kết hợp tôm sú tươi rói và nước mắm chua ngọt đặc trưng.', 'goi_cuhudua.jpg'),
(3, 'Heo Sữa Quay Da Giòn Ăn Kèm Bánh Bao', 'Món chính', 550000, 'Heo sữa quay vàng ươm, lớp da giòn rụm tan trong miệng kèm xốt chấm gia truyền.', 'heo_quay.jpg'),
(4, 'Gà Đông Tảo Hấp Lá Chanh Quê', 'Món chính', 380000, 'Thịt gà chắc ngọt, da giòn sần sật thơm lừng hương lá chanh bánh tẻ.', 'ga_dongtao.jpg'),
(5, 'Cá Chẽm Hấp Xì Dầu Hồng Kông', 'Món chính', 350000, 'Cá chẽm tươi sống hấp cùng xì dầu thượng hạng, gừng thái chỉ và hành hoa tươi.', 'ca_chem.jpg'),
(6, 'Lẩu Nấm Hải Sản Tươi Sống', 'Món chính / Lẩu', 480000, 'Nước lẩu thanh ngọt tự nhiên cùng 8 loại nấm quý và tôm mực tươi ngon.', 'lau_nam.jpg'),
(7, 'Chè Yến Hạt Sen Táo Đỏ', 'Tráng miệng', 65000, 'Món tráng miệng thanh mát, bổ dưỡng dưỡng nhan cho phái đẹp và người cao tuổi.', 'che_yen.jpg');

-- 6. Đơn đặt bàn
INSERT INTO dat_ban (id, nguoidung_id, banphong_id, loaidip_id, set_id, ngay_dat, ca, so_nguoi, gia_set_luc_dat, tong_tien, trang_thai, ghi_chu, ngay_tao) VALUES
(1, 2, 1, 1, 1, '2026-11-20', 'toi', 6, 2850000, 3600000, 'cho_duyet', 'Mừng sinh nhật mẹ tròn 60 tuổi. Xin chuẩn bị giúp mình bình hoa sen và 1 giá để bánh kem tại bàn.', '2026-11-14 10:15:00'),
(2, 2, 3, 3, NULL, '2026-11-18', 'trua', 4, 0, 1180000, 'da_duyet', 'Bàn gần cửa kính thoáng mát. Gia đình có trẻ nhỏ, xin chuẩn bị thêm 1 ghế trẻ em.', '2026-11-13 14:20:00'),
(3, 2, 2, 4, 2, '2026-11-05', 'toi', 12, 4500000, 4800000, 'da_duyet', 'Tiệc tiếp đoàn đối tác nước ngoài. Bố trí màn hình chiếu nhỏ nếu có.', '2026-10-30 09:00:00'),
(4, 2, 6, 5, NULL, '2026-11-02', 'toi', 2, 0, 850000, 'da_huy', 'Kỷ niệm ngày cưới nhưng gia đình có lịch công tác đột xuất nên xin phép hủy.', '2026-10-28 16:30:00'),
(5, 3, 4, 2, 2, '2026-11-25', 'trua', 10, 4500000, 4500000, 'cho_duyet', 'Tiệc thượng thọ ông nội, gia đình mong muốn phục vụ các món mềm, thanh đạm.', '2026-11-13 18:00:00');

-- 7. Chi tiết món ăn của đơn đặt bàn
INSERT INTO chitiet_mon (id, datban_id, mon_id, so_luong, don_gia) VALUES
(1, 1, 3, 1, 550000),
(2, 2, 1, 2, 180000),
(3, 2, 2, 1, 160000),
(4, 2, 6, 1, 480000),
(5, 2, 7, 4, 65000),
(6, 4, 2, 1, 160000),
(7, 4, 4, 1, 380000);
