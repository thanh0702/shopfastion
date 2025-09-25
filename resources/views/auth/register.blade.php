<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ShopFashion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #FFFFFF;
            color: #000000;
        }
        .card {
            background-color: #FFFFFF;
            border: 0px solid #CCCCCC;
            color: #000000;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            border-radius: 0;
        }
        .card-header {
            background-color: #FFFFFF;
            border-bottom: 0px solid #CCCCCC;
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
                        <h3 class="text-center">Register</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile</label>
                                <input type="text" class="form-control" id="mobile" name="mobile" required>
                            </div>
                            <div class="mb-3">
                                <label for="is_admin" class="form-label">Role</label>
                                <select class="form-control" id="is_admin" name="is_admin" required>
                                    <option value="0">User</option>
                                    <option value="1">Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}">Already have an account? Login</a>
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
