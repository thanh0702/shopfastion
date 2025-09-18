<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Address - Shop Thời Trang</title>
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
    </style>
</head>
<body>
    @include('partials.header')

    <div class="container my-4 d-flex">
        <nav class="sidebar d-flex flex-column">
            <h2>MY ACCOUNT</h2>
            <div class="d-flex flex-column mb-3">
                <a href="{{ route('account') }}" class="mb-2">DASHBOARD</a>
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
            <h3>Edit Address</h3>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('account.addresses.update', $address) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $address->full_name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $address->phone) }}" required>
                </div>
                <div class="mb-3">
                    <label for="number" class="form-label">Number</label>
                    <input type="text" class="form-control" id="number" name="number" value="{{ old('number', $address->number) }}" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $address->address) }}</textarea>
                </div>
                <button type="submit" class="btn btn-success">Update Address</button>
                <a href="{{ route('account.addresses') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
