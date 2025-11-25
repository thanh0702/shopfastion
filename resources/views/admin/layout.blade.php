<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - Shop Thời Trang</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

    <style>
        body {
            background-color: #f8fafc;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 220px;
            background-color: #f0f4ff;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
            border-right: 1px solid #ddd;
        }
        .sidebar .sidebar-header {
            font-weight: 700;
            font-size: 14px;
            color: #a0aec0;
            padding-left: 20px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .sidebar ul {
            list-style: none;
            padding-left: 0;
        }
        .sidebar ul li {
            margin-bottom: 5px;
        }
        .sidebar ul li a {
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: #1a202c;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
        }
        .sidebar ul li a:hover,
        .sidebar ul li.active > a {
            background-color: #2563eb;
            color: white;
        }
        .sidebar ul li .submenu {
            margin-left: 30px;
            margin-top: 5px;
            list-style: none;
            padding-left: 0;
        }
        .sidebar ul li .submenu li a {
            font-weight: 400;
            font-size: 14px;
            color: #718096;
            padding: 6px 0;
            display: block;
        }
        .sidebar ul li .submenu li a:hover,
        .sidebar ul li .submenu li.active > a {
            color: #2563eb;
            font-weight: 600;
        }
        .content {
            margin-left: 240px;
            padding: 30px 40px;
        }
        .breadcrumb {
            font-size: 14px;
            color: #718096;
            margin-bottom: 20px;
        }
        .breadcrumb a {
            color: #718096;
            text-decoration: none;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    @include('partials.header')

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">MAIN HOME</div>
        <ul>
            <!-- Dashboard -->
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <span><i class="bi bi-grid-3x3-gap"></i> Dashboard</span>
                </a>
            </li>

            <!-- Category -->
            <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a href="#" onclick="event.preventDefault(); toggleSubmenu('category-submenu', this)">
                    <span><i class="bi bi-stack"></i> Category</span>
                    <i class="bi bi-chevron-down arrow"></i>
                </a>
                <ul id="category-submenu" class="submenu d-none">
                    <li class="{{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.categories.create') }}">New Category</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.categories.index') }}">Category</a>
                    </li>
                </ul>
            </li>

            <!-- Product -->
            <li class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <a href="#" onclick="event.preventDefault(); toggleSubmenu('product-submenu', this)">
                    <span><i class="bi bi-box-seam"></i> Product</span>
                    <i class="bi bi-chevron-down arrow"></i>
                </a>
                <ul id="product-submenu" class="submenu d-none">
                    <li class="{{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.products.create') }}">New Product</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.products.index') }}">Products</a>
                    </li>
                </ul>
            </li>

            <!-- Banner -->
            <li class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <a href="#" onclick="event.preventDefault(); toggleSubmenu('banner-submenu', this)">
                    <span><i class="bi bi-image"></i> Banner</span>
                    <i class="bi bi-chevron-down arrow"></i>
                </a>
                <ul id="banner-submenu" class="submenu d-none">
                    <li class="{{ request()->routeIs('admin.banners.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.banners.index') }}">Banners</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.banners.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.banners.create') }}">New Banner</a>
                    </li>
                </ul>
            </li>

            <!-- QR Code -->
            <li class="{{ request()->routeIs('admin.qr_codes.*') ? 'active' : '' }}">
                <a href="#" onclick="event.preventDefault(); toggleSubmenu('qr-code-submenu', this)">
                    <span><i class="bi bi-qr-code"></i> QR Code</span>
                    <i class="bi bi-chevron-down arrow"></i>
                </a>
                <ul id="qr-code-submenu" class="submenu d-none">
                    <li class="{{ request()->routeIs('admin.qr_codes.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.qr_codes.index') }}">QR Codes</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.qr_codes.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.qr_codes.create') }}">New QR Code</a>
                    </li>
                </ul>
            </li>

            <!-- Orders -->
            <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.index') }}">
                    <span><i class="bi bi-receipt"></i> Orders</span>
                </a>
            </li>

            <!-- Chat Messages -->
            <li class="{{ request()->routeIs('admin.chat_messages.*') ? 'active' : '' }}">
                <a href="{{ route('admin.chat_messages.index') }}">
                    <span><i class="bi bi-chat-dots"></i> Chat Messages</span>
                </a>
            </li>

            <!-- Admin Management -->
            <li class="{{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                <a href="#" onclick="event.preventDefault(); toggleSubmenu('admin-submenu', this)">
                    <span><i class="bi bi-person-badge"></i> Admins</span>
                    <i class="bi bi-chevron-down arrow"></i>
                </a>
                <ul id="admin-submenu" class="submenu d-none">
                    <li class="{{ request()->routeIs('admin.admins.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.admins.create') }}">Add Admin</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.admins.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.admins.index') }}">Admin List</a>
                    </li>
                </ul>
            </li>

            <!-- Employee Management -->
            <li class="{{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                <a href="#" onclick="event.preventDefault(); toggleSubmenu('employee-submenu', this)">
                    <span><i class="bi bi-people"></i> Employees</span>
                    <i class="bi bi-chevron-down arrow"></i>
                </a>
                <ul id="employee-submenu" class="submenu d-none">
                    <li class="{{ request()->routeIs('admin.employees.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.employees.create') }}">Add Employee</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.employees.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.employees.index') }}">Employee List</a>
                    </li>
                </ul>
            </li>

            <!-- Logout -->
            <li>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   <span><i class="bi bi-gear"></i> Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        @yield('breadcrumb')
        @yield('content')
    </div>

    <script>
        function toggleSubmenu(id, element) {
            let submenu = document.getElementById(id);
            submenu.classList.toggle('d-none');

            // Đổi icon ▲▼
            let arrow = element.querySelector('.arrow');
            if (submenu.classList.contains('d-none')) {
                arrow.classList.remove('bi-chevron-up');
                arrow.classList.add('bi-chevron-down');
            } else {
                arrow.classList.remove('bi-chevron-down');
                arrow.classList.add('bi-chevron-up');
            }
        }
    </script>

    @yield('scripts')
</body>
</html>
