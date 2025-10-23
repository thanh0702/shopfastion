<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trang chủ - Shop Thời Trang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <style>
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .product-image-container {
            position: relative;
            overflow: hidden;
        }
        .product-image {
            transition: opacity 0.3s ease;
        }
        .product-image.second {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
        }
        .product-card:hover .product-image.first {
            opacity: 0;
        }
        .product-card:hover .product-image.second {
            opacity: 1;
        }

        /* === CHATBOT STYLE === */
        #chatbot-icon {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            z-index: 999;
        }

        #chatbot {
            position: fixed;
            bottom: 100px;
            right: 25px;
            width: 320px;
            height: 420px;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            display: none;
            flex-direction: column;
            overflow: hidden;
            font-size: 14px;
            z-index: 999;
        }

        #chat-header {
            background: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }

        #chat-log {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
        }

        #chat-input {
            display: flex;
            border-top: 1px solid #ccc;
        }

        #chat-input input {
            flex: 1;
            border: none;
            padding: 10px;
            font-size: 14px;
        }

        #chat-input button {
            border: none;
            background: #007bff;
            color: white;
            padding: 10px 15px;
            cursor: pointer;
        }

        .user-msg {
            text-align: right;
            margin-bottom: 10px;
        }

        .bot-msg {
            text-align: left;
            margin-bottom: 10px;
        }

        .msg-bubble {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 10px;
            max-width: 80%;
        }

        .user-msg .msg-bubble {
            background: #dcf8c6;
        }

        .bot-msg .msg-bubble {
            background: #f1f0f0;
        }
    </style>
</head>
<body>
    @include('partials.header')

    @if($banners->count() > 0)
    <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($banners as $index => $banner)
                <div class="carousel-item @if($index == 0) active @endif">
                    @if($banner->link)
                        <a href="{{ $banner->link }}">
                            <img src="{{ $banner->image }}" class="d-block w-100" alt="Banner {{ $index + 1 }}">
                        </a>
                    @else
                        <img src="{{ $banner->image }}" class="d-block w-100" alt="Banner {{ $index + 1 }}">
                    @endif
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="container py-4" style="background-color: #4a0b0b; color: white; height: 200px; margin: 50px auto 0 auto; max-width: 1140px;">
        <div class="row text-center h-100 align-items-center">
            <div class="col-md-3 mb-3">
                <i class="bi bi-truck" style="font-size: 2rem;"></i>
                <h5 class="fw-bold mt-2">GIAO HÀNG TOÀN QUỐC</h5>
                <p class="mb-0">Miễn phí vận chuyển với các đơn hàng trị giá trên 1.000.000Đ</p>
            </div>
            <div class="col-md-3 mb-3">
                <i class="bi bi-telephone" style="font-size: 2rem;"></i>
                <h5 class="fw-bold mt-2">HỖ TRỢ ONLINE</h5>
                <p class="mb-0">Đội ngũ hỗ trợ hoạt động tất cả các ngày trong tuần, từ 9am->9pm</p>
            </div>
            <div class="col-md-3 mb-3">
                <i class="bi bi-arrow-repeat" style="font-size: 2rem;"></i>
                <h5 class="fw-bold mt-2">ĐỔI HÀNG DỄ DÀNG</h5>
                <p class="mb-0">Đổi hàng online đơn giản, trực tiếp</p>
            </div>
            <div class="col-md-3 mb-3">
                <i class="bi bi-gift" style="font-size: 2rem;"></i>
                <h5 class="fw-bold mt-2">QUÀ TẶNG HẤP DẪN</h5>
                <p class="mb-0">Chương trình khuyến mãi cực lớn và hấp dẫn hàng tháng</p>
            </div>
        </div>
    </div>
    @endif

    @foreach ($categories as $category)
    <div class="container my-5">
        <h2 class="text-center mb-4">
            <a href="{{ route('category.show', $category->slug) }}" class="text-decoration-none fw-bold text-dark">{{ $category->name }}</a>
        </h2>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach ($category->products->take(8) as $product)
            <div class="col">
                <div class="card h-100 position-relative product-card">
                    @if(Auth::check())
                    <button class="btn position-absolute top-0 end-0 m-2 p-0 wishlist-btn" data-product-id="{{ $product->id }}" style="background: none; border: none; z-index: 10;">
                        <i class="bi bi-heart{{ in_array($product->id, $wishlists) ? '-fill text-danger' : '' }}" style="font-size: 1.5rem;"></i>
                    </button>
                    @endif
                    <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                        <div class="product-image-container">
                            @if ($product->images && is_array($product->images) && count($product->images) > 1)
                            <img src="{{ $product->images[0] }}" class="card-img-top product-image first" alt="{{ $product->name }}" />
                            <img src="{{ $product->images[1] }}" class="card-img-top product-image second" alt="{{ $product->name }}" />
                            @elseif ($product->images && is_array($product->images) && count($product->images) > 0)
                            <img src="{{ $product->images[0] }}" class="card-img-top product-image first" alt="{{ $product->name }}" />
                            @elseif ($product->image_url)
                            <img src="{{ $product->image_url }}" class="card-img-top product-image first" alt="{{ $product->name }}" />
                            @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                No Image
                            </div>
                            @endif
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title text-dark">{{ $product->name }}</h5>
                            <p class="card-text text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }} VND</p>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('category.show', $category->slug) }}" class="btn-view-all" style="background-color: #4a0b0b; color: white; font-weight: bold; padding: 10px 20px; border-radius: 4px; text-decoration: none;">Xem tất cả</a>
        </div>
    </div>
    @endforeach

    <div id="chatbot-icon"><i class="bi bi-chat-dots"></i></div>

    <div id="chatbot">
        <div id="chat-header">Chatbot Hỗ Trợ</div>
        <div id="chat-log"></div>
        <div id="chat-input">
            <input type="text" id="message" placeholder="Nhập tin nhắn...">
            <button id="send">Gửi</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @if(Auth::check())
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
            } else if (delta > 0) {
                const wishlistLink = document.querySelector('a[href*="wishlist"]');
                if (wishlistLink) {
                    const badge = document.createElement('span');
                    badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white';
                    badge.innerHTML = '<span class="wishlist-count">1</span>';
                    wishlistLink.appendChild(badge);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.wishlist-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.getAttribute('data-product-id');
                    const icon = this.querySelector('i');
                    fetch('/wishlist/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ product_id: productId })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (data.added) {
                                icon.className = 'bi bi-heart-fill text-danger';
                                updateWishlistBadge(1);
                            } else {
                                icon.className = 'bi bi-heart';
                                updateWishlistBadge(-1);
                            }
                        }
                    });
                });
            });
        });
    </script>
    @endif

    <script>
        const chatbot = document.getElementById('chatbot');
        const chatbotIcon = document.getElementById('chatbot-icon');
        const log = document.getElementById('chat-log');
        const input = document.getElementById('message');
        const send = document.getElementById('send');

        chatbotIcon.onclick = () => {
            chatbot.style.display = chatbot.style.display === 'flex' ? 'none' : 'flex';
        };

        const replies = {
            "xin chào": "Chào bạn! Mình là chatbot hỗ trợ của Shop Thời Trang 😊",
            "chào": "Xin chào bạn! Bạn cần giúp gì hôm nay?",
            "bạn là ai": "Mình là chatbot tư vấn sản phẩm và hỗ trợ khách hàng!",
            "giờ làm việc": "Cửa hàng hoạt động từ 8h00 đến 21h00 mỗi ngày.",
            "tư vấn": "Bạn muốn mình tư vấn sản phẩm nào ạ? 👗👕👞",
            "giá": "Giá sản phẩm sẽ hiển thị ở phần chi tiết, bạn nói tên sản phẩm nhé!",
            "tạm biệt": "Cảm ơn bạn đã trò chuyện! Hẹn gặp lại ❤️"
        };

        function addMessage(text, sender) {
            const div = document.createElement('div');
            div.className = sender === 'user' ? 'user-msg' : 'bot-msg';
            div.innerHTML = `<div class="msg-bubble">${text}</div>`;
            log.appendChild(div);
            log.scrollTop = log.scrollHeight;
        }

        send.onclick = () => {
            const msg = input.value.trim().toLowerCase();
            if (!msg) return;
            addMessage(input.value, 'user');
            input.value = '';

            let reply = "Xin lỗi, mình chưa hiểu ý bạn 😅";
            for (let key in replies) {
                if (msg.includes(key)) {
                    reply = replies[key];
                    break;
                }
            }
            setTimeout(() => addMessage(reply, 'bot'), 500);
        };

        input.addEventListener("keypress", function(e) {
            if (e.key === "Enter") send.click();
        });
    </script>

    @include('partials.footer')
</body>
</html>
