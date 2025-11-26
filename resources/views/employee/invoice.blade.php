<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-box {
            width: 45%;
        }
        .info-box h3 {
            margin-top: 0;
            font-size: 16px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        @media print {
            body {
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>HÓA ĐƠN BÁN HÀNG</h1>
        <p>Mã đơn hàng: #{{ $order->_id }}</p>
        <p>Ngày tạo: {{ $order->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <div class="info-box">
            <h3>Thông tin cửa hàng</h3>
            <p><strong>ShopFashion</strong></p>
            <p>Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM</p>
            <p>Điện thoại: (028) 1234-5678</p>
            <p>Email: info@shopfashion.com</p>
        </div>
        <div class="info-box">
            <h3>Thông tin khách hàng</h3>
            <p><strong>Nhân viên bán hàng:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
            <p><strong>Phương thức thanh toán:</strong>
                @if($order->payment_method === 'transfer')
                    Chuyển khoản
                @else
                    Tiền mặt
                @endif
            </p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Size</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                <td>{{ $item->size ?? 'N/A' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VND</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold;">Tổng cộng:</td>
                <td style="font-weight: bold;">{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
            </tr>
        </tfoot>
    </table>

    <div class="total">
        Tổng tiền thanh toán: {{ number_format($order->total_amount, 0, ',', '.') }} VND
    </div>

    <div class="footer">
        <p>Cảm ơn quý khách đã mua hàng tại ShopFashion!</p>
        <p>Hẹn gặp lại quý khách lần sau.</p>
        <p class="no-print">In hóa đơn này bằng cách nhấn Ctrl+P hoặc nút In trên trình duyệt.</p>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>
</html>
