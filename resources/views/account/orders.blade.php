<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Orders - Shop Thời Trang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <style>
        .sidebar {
            min-width: 200px;
            /* border-right: 1px solid #ddd; */
            padding-top: 20px;
        }
        .sidebar h2 {
            font-weight: bold;
            padding-left: 15px;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .sidebar a {
            display: block;
            padding: 10px 15px;
            font-weight: bold;
            color: #000;
            text-decoration: none;
            text-transform: uppercase;
            position: relative;
        }
        .sidebar a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: black;
            transition: width 0.3s ease;
        }
        .sidebar a:hover::after {
            width: 100%;
        }
        .content {
            padding: 20px;
        }
        .order-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 15px;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .order-status {
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { color: orange; }
        .status-completed { color: green; }
        .status-cancelled { color: red; }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="container my-4 d-flex">
        <nav class="sidebar d-flex flex-column">
            <h2>MY ACCOUNT</h2>
            <div class="d-flex flex-column mb-3">
                <a href="{{ route('account') }}" class="mb-2">DASHBOARD</a>
                <a href="{{ route('account.orders') }}" class="mb-2 active">ORDERS</a>
                <a href="{{ route('account.details') }}" class="mb-2">ACCOUNT DETAILS</a>
                <a href="{{ route('account.addresses') }}" class="mb-2">ADDRESSES</a>
            </div>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                LOGOUT
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </nav>

        <div class="content flex-grow-1 d-flex flex-column ps-4">
            <h3 class="mb-4">My Orders</h3>

            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Payment Method</th>
                                <th>Shipping Address</th>
                                <th>Items</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($order->status == 'pending') bg-warning text-dark
                                            @elseif($order->status == 'completed') bg-success
                                            @elseif($order->status == 'cancelled') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td>{{ ucfirst($order->payment_method) }}</td>
                                    <td>{{ Str::limit($order->shipping_address, 50) }}</td>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                            @foreach($order->orderItems as $item)
                                                <li>{{ $item->product->name }} (Qty: {{ $item->quantity }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <a href="{{ route('account.order.details', $order) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle"></i> You have no orders yet.
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     @include('partials.footer')
</body>
</html>
