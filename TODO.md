# TODO - Trang thanh toán cho nhân viên

## ✅ Hoàn thành

### 1. Cập nhật trang giỏ hàng nhân viên (employee/cart.blade.php)
- [x] Thêm form chọn phương thức thanh toán (chuyển khoản/tiền mặt)
- [x] Thêm nút "Thanh toán đơn hàng"
- [x] Ẩn form thanh toán khi giỏ hàng trống

### 2. Thêm xử lý thanh toán trong EmployeeController
- [x] Thêm method `processEmployeePayment()` để xử lý thanh toán
- [x] Validate phương thức thanh toán
- [x] Tạo đơn hàng từ giỏ hàng
- [x] Tạo các order items
- [x] Xóa giỏ hàng sau khi thanh toán thành công
- [x] Chuyển hướng đến trang chi tiết đơn hàng

### 3. Tạo trang chi tiết đơn hàng nhân viên (employee/order_details.blade.php)
- [x] Hiển thị thông tin đơn hàng
- [x] Hiển thị danh sách sản phẩm
- [x] Hiển thị mã QR VietQR động khi chọn chuyển khoản
- [x] Ẩn QR code khi chọn tiền mặt
- [x] Thêm nút quay lại trang bán hàng

### 4. Thêm method xem chi tiết đơn hàng trong EmployeeController
- [x] Thêm method `orderDetails()` để hiển thị chi tiết đơn hàng
- [x] Kiểm tra quyền truy cập (chỉ nhân viên tạo đơn mới xem được)

### 5. Cập nhật routes (web.php)
- [x] Thêm route POST `/employee/payment` cho xử lý thanh toán
- [x] Thêm route GET `/employee/orders/{order}` cho chi tiết đơn hàng

## 📝 Ghi chú kỹ thuật

### VietQR Dynamic
- Sử dụng API VietQR: `https://img.vietqr.io/image/{bankCode}-{accountNumber}-print.png`
- Ngân hàng: BIDV
- Số tài khoản: 2601663447
- Nội dung chuyển khoản: DH{order_id}
- Số tiền được truyền động từ tổng đơn hàng

### Luồng thanh toán
1. Nhân viên thêm sản phẩm vào giỏ hàng
2. Vào trang giỏ hàng, chọn phương thức thanh toán
3. Nhấn "Thanh toán đơn hàng"
4. Hệ thống tạo đơn hàng và chuyển đến trang chi tiết
5. Nếu chọn chuyển khoản → hiển thị QR code
6. Nếu chọn tiền mặt → không hiển thị QR code

## 🎯 Các tính năng đã triển khai

- ✅ Form thanh toán tích hợp trong giỏ hàng
- ✅ Xử lý thanh toán cho cả tiền mặt và chuyển khoản
- ✅ Tạo đơn hàng tự động
- ✅ Hiển thị QR code động cho chuyển khoản
- ✅ Trang chi tiết đơn hàng với đầy đủ thông tin
- ✅ Bảo mật: chỉ nhân viên tạo đơn mới xem được
