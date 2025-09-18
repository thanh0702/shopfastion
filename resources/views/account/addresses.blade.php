<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Addresses - Shop Thời Trang</title>
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
                <a href="{{ route('account.addresses') }}" class="mb-2 active">ADDRESSES</a>
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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">My Addresses</h3>
                <a href="{{ route('account.addresses.create') }}" class="btn btn-success btn-sm">Add</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($addresses->count() > 0)
                <div class="row">
                    @foreach($addresses as $address)
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $address->full_name }}</h5>
                                    <p class="card-text">
                                        <strong>Phone:</strong> {{ $address->phone }}<br>
                                        <strong>Address:</strong> {{ $address->number }}, {{ $address->address }}
                                    </p>
                                    <a href="{{ route('account.addresses.edit', $address) }}" class="btn btn-primary">Edit</a>
                                    <form action="{{ route('account.addresses.delete', $address) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this address?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>You have no saved addresses.</p>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
