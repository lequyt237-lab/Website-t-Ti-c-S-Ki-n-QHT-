<!-- Modal Xem Chi Tiết Đơn Đặt Bàn -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
      
      <!-- Modal Header -->
      <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--forest-dk), var(--forest)); border: none; padding: 20px 28px;">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-warning text-dark fw-bold px-2 py-1" id="modalBookingCode">#DB000</span>
            <span id="modalBookingStatusBadge" class="badge"></span>
          </div>
          <h5 class="modal-title fw-bold fs-5 mb-0" id="bookingDetailModalLabel">
            <i class="bi bi-receipt me-2 text-warning"></i>Chi Tiết Đơn Đặt Bàn
          </h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4" style="background: #faf8f5;">
        
        <!-- Loading Spinner -->
        <div id="modalLoadingSpinner" class="text-center py-5">
          <div class="spinner-border text-success" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Đang tải thông tin...</span>
          </div>
          <p class="mt-3 text-muted fw-semibold">Đang tải chi tiết đơn đặt bàn...</p>
        </div>

        <!-- Detail Content -->
        <div id="modalDetailContent" style="display: none;">
          
          <!-- Thông tin chung & Thời gian -->
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <div class="p-3 bg-white rounded-3 border h-100 shadow-sm">
                <h6 class="fw-bold text-uppercase text-secondary mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">
                  <i class="bi bi-geo-alt-fill text-danger me-1"></i>Vị Trí &amp; Dịp Tiệc
                </h6>
                <div class="d-flex align-items-start mb-2">
                  <span class="text-muted small" style="width: 100px;">Bàn / Phòng:</span>
                  <span class="fw-bold text-dark" id="modalTableName"></span>
                </div>
                <div class="d-flex align-items-start mb-2">
                  <span class="text-muted small" style="width: 100px;">Loại bàn:</span>
                  <span id="modalTableTypeBadge"></span>
                </div>
                <div class="d-flex align-items-start mb-2">
                  <span class="text-muted small" style="width: 100px;">Sức chứa:</span>
                  <span class="fw-semibold text-dark" id="modalTableCapacity"></span>
                </div>
                <div class="d-flex align-items-start">
                  <span class="text-muted small" style="width: 100px;">Dịp tổ chức:</span>
                  <span class="fw-semibold text-primary" id="modalOccasionName"></span>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="p-3 bg-white rounded-3 border h-100 shadow-sm">
                <h6 class="fw-bold text-uppercase text-secondary mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">
                  <i class="bi bi-calendar-event-fill text-success me-1"></i>Thời Gian &amp; Khách Hàng
                </h6>
                <div class="d-flex align-items-start mb-2">
                  <span class="text-muted small" style="width: 100px;">Ngày tiệc:</span>
                  <span class="fw-bold text-dark" id="modalBookingDate"></span>
                </div>
                <div class="d-flex align-items-start mb-2">
                  <span class="text-muted small" style="width: 100px;">Ca phục vụ:</span>
                  <span class="badge bg-light text-dark border" id="modalBookingShift"></span>
                </div>
                <div class="d-flex align-items-start mb-2">
                  <span class="text-muted small" style="width: 100px;">Số lượng:</span>
                  <span class="fw-bold text-danger" id="modalGuestCount"></span>
                </div>
                <div class="d-flex align-items-start">
                  <span class="text-muted small" style="width: 100px;">Người đặt:</span>
                  <span class="fw-semibold text-dark" id="modalCustomerName"></span>
                </div>
              </div>
            </div>
          </div>

          <!-- Set tiệc (nếu có) -->
          <div id="modalSetSection" class="p-3 bg-white rounded-3 border mb-4 shadow-sm" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="fw-bold text-uppercase text-secondary mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">
                <i class="bi bi-stars text-warning me-1"></i>Set Tiệc Đã Chọn
              </h6>
              <span class="fw-bold text-danger fs-6" id="modalSetPrice"></span>
            </div>
            <div class="fw-bold text-dark fs-6" id="modalSetName"></div>
            <div class="text-muted small mt-1" id="modalSetDesc"></div>
          </div>

          <!-- Món ăn gọi thêm (nếu có) -->
          <div id="modalDishesSection" class="p-3 bg-white rounded-3 border mb-4 shadow-sm">
            <h6 class="fw-bold text-uppercase text-secondary mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">
              <i class="bi bi-egg-fried text-primary me-1"></i>Danh Sách Món Ăn Gọi Kèm
            </h6>
            <div class="table-responsive">
              <table class="table table-sm align-middle mb-0">
                <thead class="table-light small text-secondary">
                  <tr>
                    <th>Món ăn</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-end">Đơn giá</th>
                    <th class="text-end">Thành tiền</th>
                  </tr>
                </thead>
                <tbody id="modalDishesTableBody" class="small">
                  <!-- JS chèn món ăn -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- Ghi chú đặc biệt -->
          <div class="p-3 bg-white rounded-3 border mb-4 shadow-sm" id="modalNoteSection">
            <h6 class="fw-bold text-uppercase text-secondary mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">
              <i class="bi bi-chat-quote-fill text-info me-1"></i>Yêu Cầu &amp; Ghi Chú Đặc Biệt
            </h6>
            <p class="mb-0 small text-dark fst-italic" id="modalCustomerNote">Không có ghi chú đặc biệt.</p>
          </div>

          <!-- Tổng kết tiền thanh toán -->
          <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #FFF9F3, #FFF0E5); border: 1.5px dashed var(--terra);">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="text-muted small">Thời gian gửi yêu cầu:</span>
              <span class="small fw-semibold text-secondary" id="modalCreatedAt"></span>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
              <span class="fw-bold text-dark fs-6">Tổng chi phí ước tính:</span>
              <span class="fw-bold text-danger fs-4" id="modalTotalPrice">0 đ</span>
            </div>
          </div>

        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center">
        <div id="modalCancelButtonWrapper">
          <!-- JS chèn nút hủy bàn nếu trạng thái là cho_duyet -->
        </div>
        <button type="button" class="btn btn-secondary px-4 fw-semibold" data-bs-dismiss="modal">Đóng</button>
      </div>

    </div>
  </div>
</div>

<!-- Modal Xác Nhận Hủy Đặt Bàn -->
<div class="modal fade" id="confirmCancelBookingModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow">
      <div class="modal-body text-center p-4">
        <div class="text-danger mb-3">
          <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem;"></i>
        </div>
        <h5 class="fw-bold mb-2">Xác nhận hủy đặt bàn?</h5>
        <p class="text-muted small mb-4">
          Bạn có chắc chắn muốn hủy đơn đặt bàn <strong id="cancelModalBookingCode">#...</strong> không? Thao tác này không thể hoàn tác.
        </p>
        <form action="<?= BASE_URL ?>?action=huy-dat-ban" method="POST" id="formCancelBooking">
          <input type="hidden" name="id" id="cancelBookingInputId" value="">
          <div class="d-flex gap-2 justify-content-center">
            <button type="button" class="btn btn-light px-3 fw-semibold border" data-bs-dismiss="modal">Không hủy</button>
            <button type="submit" class="btn btn-danger px-3 fw-semibold">Xác nhận hủy</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
/**
 * Xem chi tiết đơn đặt bàn
 */
function viewBookingDetail(bookingId) {
    const modalEl = document.getElementById('bookingDetailModal');
    if (!modalEl) return;
    const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
    bsModal.show();

    const spinner = document.getElementById('modalLoadingSpinner');
    const content = document.getElementById('modalDetailContent');
    spinner.style.display = 'block';
    content.style.display = 'none';

    fetch('<?= BASE_URL ?>?action=chi-tiet-dat-ban&id=' + bookingId)
        .then(res => res.json())
        .then(res => {
            if (!res.success) {
                alert(res.message || 'Không thể tải chi tiết đơn đặt bàn.');
                bsModal.hide();
                return;
            }

            const b = res.data.booking;
            const dishes = res.data.dishes || [];

            // Mã đơn & trạng thái
            document.getElementById('modalBookingCode').textContent = '#DB' + String(b.id).padStart(4, '0');
            
            const statusMap = {
                'cho_duyet': { text: 'Chờ duyệt', cls: 'bg-warning text-dark', icon: 'bi-hourglass-split' },
                'da_duyet':  { text: 'Đã duyệt', cls: 'bg-success text-white', icon: 'bi-check-circle-fill' },
                'tu_choi':   { text: 'Từ chối', cls: 'bg-danger text-white', icon: 'bi-x-circle-fill' },
                'da_huy':    { text: 'Đã hủy', cls: 'bg-secondary text-white', icon: 'bi-slash-circle' },
            };
            const st = statusMap[b.trang_thai] || { text: b.trang_thai, cls: 'bg-secondary text-white', icon: 'bi-info-circle' };
            const statusBadge = document.getElementById('modalBookingStatusBadge');
            statusBadge.className = 'badge px-2 py-1 ' + st.cls;
            statusBadge.innerHTML = `<i class="bi ${st.icon} me-1"></i>${st.text}`;

            // Vị trí bàn & dịp
            document.getElementById('modalTableName').textContent = b.ten_ban || 'Chưa xếp bàn';
            const tableType = document.getElementById('modalTableTypeBadge');
            if (b.loai_ban === 'phong_rieng') {
                tableType.className = 'badge bg-warning-subtle text-warning border border-warning-subtle';
                tableType.textContent = 'Phòng VIP riêng tư';
            } else {
                tableType.className = 'badge bg-light text-secondary border';
                tableType.textContent = 'Bàn sảnh chung';
            }
            document.getElementById('modalTableCapacity').textContent = (b.suc_chua || '-') + ' khách';
            document.getElementById('modalOccasionName').textContent = b.ten_dip || 'Bình thường';

            // Thời gian & Khách
            const dateParts = (b.ngay_dat || '').split('-');
            const formattedDate = dateParts.length === 3 ? `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}` : b.ngay_dat;
            document.getElementById('modalBookingDate').textContent = formattedDate;

            const shiftBadge = document.getElementById('modalBookingShift');
            if (b.ca === 'trua') {
                shiftBadge.innerHTML = '<i class="bi bi-sun-fill text-warning me-1"></i>Ca Trưa (11:00 - 14:00)';
            } else {
                shiftBadge.innerHTML = '<i class="bi bi-moon-stars-fill text-primary me-1"></i>Ca Tối (17:00 - 21:30)';
            }

            document.getElementById('modalGuestCount').textContent = b.so_nguoi + ' người';
            document.getElementById('modalCustomerName').textContent = b.ten_khach || 'Khách hàng';

            // Set tiệc
            const setSection = document.getElementById('modalSetSection');
            if (b.ten_set) {
                setSection.style.display = 'block';
                document.getElementById('modalSetName').textContent = b.ten_set;
                document.getElementById('modalSetPrice').textContent = Number(b.gia_set_luc_dat || b.gia_set || 0).toLocaleString('vi-VN') + ' đ';
                document.getElementById('modalSetDesc').textContent = b.mota_set || '';
            } else {
                setSection.style.display = 'none';
            }

            // Món gọi thêm
            const dishesSection = document.getElementById('modalDishesSection');
            const tbody = document.getElementById('modalDishesTableBody');
            tbody.innerHTML = '';
            if (dishes.length > 0) {
                dishesSection.style.display = 'block';
                dishes.forEach(d => {
                    const lineTotal = Number(d.so_luong) * Number(d.don_gia);
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="fw-semibold text-dark">${d.ten_mon} <span class="text-muted small">(${d.nhom_mon || 'Món lẻ'})</span></td>
                        <td class="text-center fw-bold">${d.so_luong}</td>
                        <td class="text-end">${Number(d.don_gia).toLocaleString('vi-VN')} đ</td>
                        <td class="text-end fw-semibold text-danger">${lineTotal.toLocaleString('vi-VN')} đ</td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                if (!b.ten_set) {
                    dishesSection.style.display = 'block';
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-2 fst-italic">Khách gọi món trực tiếp khi đến nhà hàng.</td></tr>';
                } else {
                    dishesSection.style.display = 'none';
                }
            }

            // Ghi chú
            const noteEl = document.getElementById('modalCustomerNote');
            if (b.ghi_chu && b.ghi_chu.trim() !== '') {
                noteEl.textContent = b.ghi_chu;
                noteEl.classList.remove('fst-italic', 'text-muted');
                noteEl.classList.add('text-dark');
            } else {
                noteEl.textContent = 'Không có ghi chú đặc biệt.';
                noteEl.classList.add('fst-italic', 'text-muted');
            }

            // Ngày tạo & Tổng tiền
            document.getElementById('modalCreatedAt').textContent = b.ngay_tao || '';
            document.getElementById('modalTotalPrice').textContent = Number(b.tong_tien || 0).toLocaleString('vi-VN') + ' đ';

            // Nút hủy bàn (nếu đang chờ duyệt)
            const cancelBtnWrapper = document.getElementById('modalCancelButtonWrapper');
            if (b.trang_thai === 'cho_duyet') {
                cancelBtnWrapper.innerHTML = `
                    <button type="button" class="btn btn-outline-danger btn-sm fw-semibold" onclick="promptCancelBooking(${b.id})">
                        <i class="bi bi-x-circle me-1"></i>Hủy Đặt Bàn Này
                    </button>
                `;
            } else {
                cancelBtnWrapper.innerHTML = '';
            }

            spinner.style.display = 'none';
            content.style.display = 'block';
        })
        .catch(err => {
            console.error(err);
            alert('Lỗi kết nối khi tải chi tiết đơn đặt bàn.');
            bsModal.hide();
        });
}

/**
 * Hiển thị popup xác nhận hủy đơn đặt bàn
 */
function promptCancelBooking(bookingId) {
    // Đóng modal chi tiết nếu đang mở
    const detailModalEl = document.getElementById('bookingDetailModal');
    if (detailModalEl) {
        const detailModal = bootstrap.Modal.getInstance(detailModalEl);
        if (detailModal) detailModal.hide();
    }

    const cancelModalEl = document.getElementById('confirmCancelBookingModal');
    if (!cancelModalEl) return;
    document.getElementById('cancelModalBookingCode').textContent = '#DB' + String(bookingId).padStart(4, '0');
    document.getElementById('cancelBookingInputId').value = bookingId;

    const bsCancelModal = bootstrap.Modal.getOrCreateInstance(cancelModalEl);
    bsCancelModal.show();
}
</script>
