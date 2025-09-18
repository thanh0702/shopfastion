<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Wishlist - Shop Thời Trang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
</head>
<body>
    @include('partials.header')

    <div class="container my-5">
        <h1 class="text-center mb-4">Wishlist của bạn</h1>
        @if($wishlists->count() > 0)
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach($wishlists as $wishlist)
            <div class="col">
                <div class="card h-100 position-relative">
                    <button class="btn position-absolute top-0 end-0 m-2 p-0 wishlist-btn" data-product-id="{{ $wishlist->product_id }}" style="background: none; border: none; z-index: 10;">
                        <i class="bi bi-heart-fill text-danger" style="font-size: 1.5rem;"></i>
                    </button>
                    <a href="{{ route('product.show', $wishlist->product->slug) }}" class="text-decoration-none">
                        @if ($wishlist->product->image_url)
                        <img src="{{ $wishlist->product->image_url }}" class="card-img-top" alt="{{ $wishlist->product->name }}" />
                        @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            No Image
                        </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title text-dark">{{ $wishlist->product->name }}</h5>
                            <p class="card-text text-danger fw-bold">{{ number_format($wishlist->product->price, 0, ',', '.') }} VND</p>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center">
            <p>Wishlist của bạn đang trống.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateWishlistBadge(delta) {
            const countSpan = document.querySelector('.wishlist-count');
            if (countSpan) {
                const currentCount = parseInt(countSpan.textContent);
                const newCount = currentCount + delta;
                if (newCount > 0) {
                    countSpan.textContent = newCount;
                } else {
                    const badge = countSpan.closest('.badge');
                    if (badge) badge.remove();
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.wishlist-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.getAttribute('data-product-id');
                    const card = this.closest('.col');
                    fetch('/wishlist/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ product_id: productId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && !data.added) {
                            card.remove();
                            updateWishlistBadge(-1);
                            if (document.querySelectorAll('.col').length === 0) {
                                location.reload();
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
     @include('partials.footer')
</body>
</html>
