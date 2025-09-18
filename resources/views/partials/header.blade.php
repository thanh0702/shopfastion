<header>
    <style>
        body {
            padding-top: 100px;
        }
        .navbar-nav .nav-link {
            position: relative;
            text-decoration: none;
        }
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: black;
            transition: width 0.3s ease;
        }
        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }
        .sticky-header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            background-color: black;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            color: white;
        }
        .sticky-header .nav-link {
            color: white !important;
        }
        .sticky-header .nav-link:hover::after {
            background-color: white;
        }
        .sticky-header .navbar-brand img {
            filter: brightness(0) invert(1);
        }
    </style>
    <nav class="navbar navbar-expand-lg navbar-dark border-bottom py-3 sticky-header">
        <div class="container">
<a class="navbar-brand d-flex align-items-center" href="/">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" height="70" width="200" />
</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="/">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="/shop">SHOP</a></li>
                    <li class="nav-item"><a class="nav-link" href="/cart">CART</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">ABOUT</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">CONTACT</a></li>
                    @if(Auth::check() && Auth::user()->is_admin)
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">ADMIN</a></li>
                    @endif
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0 d-flex align-items-center">
                    <li class="nav-item me-3">
                        <form method="GET" action="{{ route('search') }}" class="d-flex">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm kiếm sản phẩm..." style="width: 250px; border-radius: 20px 0 0 20px;" required>
                                <button type="submit" class="btn btn-outline-light btn-sm" style="border-radius: 0 20px 20px 0;" title="Search">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </li>
                    <li class="nav-item me-3">
                        @if(Auth::check())
                        <a class="nav-link" href="/account" title="Account">
                            <i class="bi bi-person" style="font-size: 1.2rem;"></i>
                        </a>
                        @else
                        <a class="nav-link" href="{{ route('login') }}" title="Account">
                            <i class="bi bi-person" style="font-size: 1.2rem;"></i>
                        </a>
                        @endif
                    </li>
                    <li class="nav-item me-3 position-relative">
                        <a class="nav-link" href="{{ route('wishlist.show') }}" title="Wishlist">
                            <i class="bi bi-heart" style="font-size: 1.2rem;"></i>
                            @if($wishlistCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white">
                                <span class="wishlist-count">{{ $wishlistCount }}</span>
                                <span class="visually-hidden">items in wishlist</span>
                            </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="/cart" title="Cart">
                            <i class="bi bi-bag" style="font-size: 1.2rem;"></i>
                            @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                                {{ $cartCount }}
                                <span class="visually-hidden">items in cart</span>
                            </span>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

   <!-- Scroll to Top Button -->
<button id="scrollToTopBtn" title="Go to top">
    <span class="arrow-up"></span>
</button>

<style>
#scrollToTopBtn {
    position: fixed;
    bottom: 40px;
    right: 40px;
    z-index: 1100;
    background-color: #b22222; /* màu đỏ */
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    cursor: pointer;
    display: none;
    align-items: center;
    justify-content: center;
}

/* Tạo mũi tên trắng bằng border */
.arrow-up {
    width: 12px;
    height: 12px;
    border-left: 3px solid white;
    border-bottom: 3px solid white;
    transform: rotate(135deg); /* xoay thành mũi tên lên */
    margin-bottom: 0px; /* căn chỉnh giữa nút */
}
</style>

<script>
// Lấy button
const scrollToTopBtn = document.getElementById("scrollToTopBtn");

// Hiện nút khi scroll xuống 100px
window.onscroll = function() {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        scrollToTopBtn.style.display = "flex";
    } else {
        scrollToTopBtn.style.display = "none";
    }
};

// Khi bấm thì cuộn lên đầu trang
scrollToTopBtn.addEventListener("click", function() {
    window.scrollTo({top: 0, behavior: 'smooth'});
});
</script>

</header>
