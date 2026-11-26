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

          <!-- Tổng kết tiền & thanh toán -->
          <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #FFF9F3, #FFF0E5); border: 1.5px dashed var(--terra);">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="text-muted small">Thời gian gửi yêu cầu:</span>
              <span class="small fw-semibold text-secondary" id="modalCreatedAt"></span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="text-muted small">Tình trạng thanh toán:</span>
              <span id="modalPaymentStatusBadge" class="badge"></span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="text-muted small">Đã thanh toán / cọc:</span>
              <span class="fw-semibold text-success" id="modalPaidAmount">0 đ</span>
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
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold text-danger">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>Gửi yêu cầu hủy đơn
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= BASE_URL ?>?action=huy-dat-ban" method="POST" id="formCancelBooking">
        <div class="modal-body p-4 pt-3">
          <p class="text-muted small mb-3">
            Bạn đang gửi yêu cầu hủy đơn đặt bàn <strong id="cancelModalBookingCode" class="text-dark">#...</strong>. Yêu cầu sẽ được gửi tới Quản trị viên để kiểm tra và xác nhận đồng ý hủy.
          </p>
          <input type="hidden" name="id" id="cancelBookingInputId" value="">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Lý do hủy đơn <span class="text-danger">*</span></label>
            <textarea name="ly_do_huy" class="form-control" rows="3" placeholder="Ví dụ: Gia đình bận việc đột xuất, muốn dời lịch hẹn sang tuần sau..." required></textarea>
          </div>
          <div class="alert alert-light border small text-muted mb-0 py-2">
            <i class="bi bi-info-circle me-1 text-primary"></i> Miễn phí hủy bàn trước 4 tiếng. Mọi thắc mắc xin gọi hotline 0123 456 789.
          </div>
        </div>
        <div class="modal-footer border-top-0 pt-0">
          <button type="button" class="btn btn-light px-3 fw-semibold border" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-danger px-4 fw-semibold">
            <i class="bi bi-send-fill me-1"></i>Gửi yêu cầu hủy
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Đánh Giá & Nhận Xét Bữa Tiệc -->
<div class="modal fade" id="reviewBookingModal" tabindex="-1" aria-labelledby="reviewBookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
      
      <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--forest-dk), var(--forest)); padding: 18px 24px;">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-warning text-dark fw-bold px-2 py-1" id="reviewModalBookingCode">#DB000</span>
          </div>
          <h5 class="modal-title fw-bold fs-5 mb-0" id="reviewBookingModalLabel">
            <i class="bi bi-star-fill text-warning me-2"></i>Đánh Giá &amp; Nhận Xét Bữa Tiệc
          </h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="<?= BASE_URL ?>?action=gui-danh-gia" method="POST" id="formSubmitBookingReview">
        <input type="hidden" name="datban_id" id="reviewModalBookingId" value="">

        <div class="modal-body p-4 bg-white">
          
          <!-- Chấm sao -->
          <div class="text-center py-3 mb-3 bg-light rounded-3 border">
            <div class="small fw-semibold text-secondary mb-2">Chấm điểm mức độ hài lòng của bạn:</div>
            
            <div class="d-inline-flex flex-row-reverse gap-2 mb-2" id="starRatingWidget" style="cursor:pointer;">
              <input type="radio" id="bstar5" name="so_sao" value="5" class="d-none" checked onclick="updateBookingStarDesc(5)">
              <label for="bstar5" class="fs-2 text-warning cursor-pointer" style="cursor:pointer;" title="5 sao"><i class="bi bi-star-fill"></i></label>
              
              <input type="radio" id="bstar4" name="so_sao" value="4" class="d-none" onclick="updateBookingStarDesc(4)">
              <label for="bstar4" class="fs-2 text-warning cursor-pointer" style="cursor:pointer;" title="4 sao"><i class="bi bi-star-fill"></i></label>
              
              <input type="radio" id="bstar3" name="so_sao" value="3" class="d-none" onclick="updateBookingStarDesc(3)">
              <label for="bstar3" class="fs-2 text-warning cursor-pointer" style="cursor:pointer;" title="3 sao"><i class="bi bi-star-fill"></i></label>
              
              <input type="radio" id="bstar2" name="so_sao" value="2" class="d-none" onclick="updateBookingStarDesc(2)">
              <label for="bstar2" class="fs-2 text-warning cursor-pointer" style="cursor:pointer;" title="2 sao"><i class="bi bi-star-fill"></i></label>
              
              <input type="radio" id="bstar1" name="so_sao" value="1" class="d-none" onclick="updateBookingStarDesc(1)">
              <label for="bstar1" class="fs-2 text-warning cursor-pointer" style="cursor:pointer;" title="1 sao"><i class="bi bi-star-fill"></i></label>
            </div>

            <div id="bookingStarDescText" class="fw-bold text-warning small">
              ⭐⭐⭐⭐⭐ Tuyệt vời! Rất hài lòng về món ăn &amp; dịch vụ
            </div>
          </div>

          <!-- Lời nhận xét -->
          <div class="mb-3">
            <label class="form-label fw-bold small text-secondary text-uppercase" style="letter-spacing: 0.5px;">
              <i class="bi bi-chat-quote-fill me-1 text-success"></i>Lời Nhận Xét &amp; Cảm Nhận:
            </label>
            <textarea name="binh_luan" id="bookingReviewCommentInput" class="form-control" rows="4" placeholder="Chia sẻ cảm nhận của bạn về hương vị món ăn, sự bày trí, không gian ấm cúng và cung cách phục vụ của nhân viên..."></textarea>
            <div class="form-text small text-muted">
              Đánh giá của bạn sẽ được hiển thị công khai trên trang Đánh giá của nhà hàng.
            </div>
          </div>

        </div>

        <div class="modal-footer bg-light py-3 px-4 d-flex justify-content-between">
          <div id="reviewDeleteBtnWrapper"></div>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary px-3 fw-semibold btn-sm" data-bs-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-warning px-4 fw-bold text-dark shadow-sm btn-sm" id="bookingReviewSubmitBtn">
              <i class="bi bi-send-fill me-1"></i> Gửi Đánh Giá
            </button>
          </div>
        </div>
      </form>

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
                'cho_duyet':   { text: 'Chờ duyệt', cls: 'bg-warning text-dark', icon: 'bi-hourglass-split' },
                'da_duyet':    { text: 'Đã duyệt', cls: 'bg-success text-white', icon: 'bi-check-circle-fill' },
                'yeu_cau_huy': { text: 'Chờ duyệt hủy', cls: 'bg-danger text-white', icon: 'bi-exclamation-octagon-fill' },
                'tu_choi':     { text: 'Từ chối', cls: 'bg-danger text-white', icon: 'bi-x-circle-fill' },
                'da_huy':      { text: 'Đã hủy', cls: 'bg-secondary text-white', icon: 'bi-slash-circle' },
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
            const timeStr = b.gio_den ? b.gio_den.substring(0, 5) + ' - ' : '';
            if (b.ca === 'trua') {
                shiftBadge.innerHTML = '<i class="bi bi-clock text-danger me-1"></i>' + timeStr + 'Ca Trưa';
            } else {
                shiftBadge.innerHTML = '<i class="bi bi-clock text-danger me-1"></i>' + timeStr + 'Ca Tối';
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
                    let orderLink = '';
                    if (b.trang_thai !== 'da_huy' && b.trang_thai !== 'tu_choi') {
                        orderLink = `<br><a href="<?= BASE_URL ?>?action=dat-mon&booking_id=${b.id}" class="btn btn-sm btn-warning text-dark fw-bold mt-2 shadow-sm"><i class="bi bi-cart-plus me-1"></i>Đặt món ăn trước ngay</a>`;
                    }
                    tbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-3 fst-italic">Khách chưa chọn món trước hoặc gọi món trực tiếp khi đến nhà hàng.${orderLink}</td></tr>`;
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

            // Nút tác vụ (Hủy đơn & Đặt món)
            const cancelBtnWrapper = document.getElementById('modalCancelButtonWrapper');
            let actionButtonsHtml = '';
            if (b.trang_thai === 'yeu_cau_huy') {
                actionButtonsHtml += `
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 me-2">
                        <i class="bi bi-hourglass-split me-1"></i>Đang chờ duyệt hủy
                    </span>
                `;
            } else if (b.trang_thai === 'cho_duyet' || b.trang_thai === 'da_duyet') {
                actionButtonsHtml += `
                    <button type="button" class="btn btn-outline-danger btn-sm fw-semibold me-2" onclick="promptCancelBooking(${b.id})">
                        <i class="bi bi-x-circle me-1"></i>Yêu cầu hủy đơn
                    </button>
                `;
            }
            if (b.trang_thai !== 'da_huy' && b.trang_thai !== 'tu_choi' && b.trang_thai !== 'yeu_cau_huy') {
                const isReviewed = b.danh_gia_sao ? true : false;
                actionButtonsHtml += `
                    <button type="button" class="btn btn-outline-warning text-dark btn-sm fw-bold me-2 shadow-sm" onclick="openReviewModalFromHistory(${b.id}, ${b.danh_gia_sao || 5}, '${(b.danh_gia_binh_luan || '').replace(/'/g, "\\'")}')">
                        <i class="bi bi-star-fill text-warning me-1"></i>${isReviewed ? b.danh_gia_sao + '★ Sửa nhận xét' : 'Đánh giá tiệc'}
                    </button>
                    <a href="<?= BASE_URL ?>?action=dat-mon&booking_id=${b.id}" class="btn btn-warning text-dark btn-sm fw-bold shadow-sm">
                        <i class="bi bi-cart-plus-fill me-1"></i>${dishes.length > 0 || b.ten_set ? 'Cập nhật món ăn' : 'Đặt món cho bàn này'}
                    </a>
                `;
            }
            cancelBtnWrapper.innerHTML = actionButtonsHtml;

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

const BOOKING_STAR_TEXTS = {
    5: '⭐⭐⭐⭐⭐ Tuyệt vời! Rất hài lòng về món ăn & dịch vụ',
    4: '⭐⭐⭐⭐☆ Hài lòng! Bữa tiệc diễn ra chu đáo, tốt đẹp',
    3: '⭐⭐⭐☆☆ Tạm ổn! Trải nghiệm ở mức bình thường',
    2: '⭐⭐☆☆☆ Chưa hài lòng! Cần cải thiện thêm chất lượng',
    1: '⭐☆☆☆☆ Rất thất vọng! Không đạt mong đợi'
};

function updateBookingStarDesc(star) {
    const descEl = document.getElementById('bookingStarDescText');
    if (descEl) {
        descEl.textContent = BOOKING_STAR_TEXTS[star] || '';
    }
}

function openReviewModalFromHistory(bookingId, currentStars, currentComment) {
    // Đóng modal chi tiết nếu đang mở
    const detailModalEl = document.getElementById('bookingDetailModal');
    if (detailModalEl) {
        const detailModal = bootstrap.Modal.getInstance(detailModalEl);
        if (detailModal) detailModal.hide();
    }

    const modalEl = document.getElementById('reviewBookingModal');
    if (!modalEl) return;

    document.getElementById('reviewModalBookingId').value = bookingId;
    document.getElementById('reviewModalBookingCode').textContent = '#DB' + String(bookingId).padStart(4, '0');

    currentStars = parseInt(currentStars) || 5;
    const starRadio = document.getElementById('bstar' + currentStars);
    if (starRadio) starRadio.checked = true;
    updateBookingStarDesc(currentStars);

    document.getElementById('bookingReviewCommentInput').value = currentComment || '';

    const delWrapper = document.getElementById('reviewDeleteBtnWrapper');
    const submitBtn = document.getElementById('bookingReviewSubmitBtn');
    if (currentComment || currentStars < 5) {
        submitBtn.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Cập Nhật Đánh Giá';
        delWrapper.innerHTML = `
            <a href="<?= BASE_URL ?>?action=xoa-danh-gia&booking_id=${bookingId}" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa nhận xét của đơn này?');">
                <i class="bi bi-trash3 me-1"></i>Xóa đánh giá
            </a>
        `;
    } else {
        submitBtn.innerHTML = '<i class="bi bi-send-fill me-1"></i> Gửi Đánh Giá';
        delWrapper.innerHTML = '';
    }

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
}
</script>
