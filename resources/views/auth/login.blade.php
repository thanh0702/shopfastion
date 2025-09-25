<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ShopFashion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #FFFFFF;
            color: #000000;
        }
        .card {
            background-color: #FFFFFF;
            border: 1px solid #000000;
            color: #000000;
        }
        .card-header {
            background-color: #FFFFFF;
            border-bottom: 1px solid #000000;
            color: #000000;
        }
        .card-body {
            background-color: #FFFFFF;
        }
        .form-label {
            color: #000000;
        }
        .form-control {
            border-color: #000000;
            color: #000000;
        }
        .btn-primary {
            background-color: #000000;
            border-color: #000000;
            color: #FFFFFF;
        }
        .btn-primary:hover {
            background-color: #333333;
            border-color: #333333;
        }
        a {
            color: #000000;
        }
        a:hover {
            color: #333333;
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Login</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="{{ route('register') }}">Don't have an account? Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     @include('partials.footer')
</body>
</html>
