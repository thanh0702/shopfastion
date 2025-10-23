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

        /* Chatbot Styles */
        .chatbot-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #4a0b0b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .chatbot-icon:hover {
            transform: scale(1.1);
        }
        .chatbot-icon i {
            color: white;
            font-size: 24px;
        }
        .chatbot-window {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 350px;
            height: 500px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            display: none;
            flex-direction: column;
            z-index: 1000;
        }
        .chatbot-header {
            background-color: #4a0b0b;
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .chatbot-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background-color: #f8f9fa;
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
            max-width: 80%;
        }
        .message.user {
            background-color: #4a0b0b;
            color: white;
            align-self: flex-end;
            margin-left: auto;
        }
        .message.bot {
            background-color: #e9ecef;
            color: black;
        }
        .chatbot-input {
            padding: 15px;
            border-top: 1px solid #dee2e6;
            display: flex;
        }
        .chatbot-input input {
            flex: 1;
            border: 1px solid #dee2e6;
            border-radius: 20px;
            padding: 10px 15px;
            margin-right: 10px;
        }
        .chatbot-input button {
            background-color: #4a0b0b;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .chatbot-input button:hover {
            background-color: #7a1a1a;
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
        <h2 class="text-center mb-4"><a href="{{ route('category.show', $category->slug) }}" class="text-decoration-none fw-bold text-dark">{{ $category->name }}</a></h2>
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
        <style>
            .btn-view-all {
                background-color: #4a0b0b;
                color: white !important;
                font-weight: bold;
                padding: 10px 20px;
                border-radius: 4px;
                text-decoration: none;
                display: inline-block;
                transition: background-color 0.3s ease;
            }
            .btn-view-all:hover {
                background-color: #7a1a1a;
                color: white !important;
                text-decoration: none;
                transform: scale(1.05);
                box-shadow: 0 0 8px rgba(122, 26, 26, 0.7);
                transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            }
        </style>
        <div class="text-center mt-3">
            <a href="{{ route('category.show', $category->slug) }}" class="btn-view-all">Xem tất cả</a>
        </div>
    </div>
    @endforeach

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
                // If no badge, create one
                const wishlistLink = document.querySelector('a[href*="wishlist"]');
                if (wishlistLink) {
                    const badge = document.createElement('span');
                    badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white';
                    badge.innerHTML = '<span class="wishlist-count">1</span><span class="visually-hidden">items in wishlist</span>';
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
                    .then(response => response.json())
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
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
    @endif

    <!-- Chatbot HTML -->
    <div class="chatbot-icon" id="chatbotIcon">
        <i class="bi bi-chat-dots"></i>
    </div>
    <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">
            <span>Chatbot Hỗ Trợ</span>
            <button class="btn-close btn-close-white" id="closeChatbot"></button>
        </div>
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="message bot">Chào bạn! Mình là chatbot hỗ trợ của Shop Thời Trang. Bạn cần giúp gì hôm nay? 😊</div>
        </div>
        <div class="chatbot-input">
            <input type="text" id="chatbotInput" placeholder="Nhập tin nhắn...">
            <button id="sendMessage"><i class="bi bi-send"></i></button>
        </div>
    </div>

    @include('partials.footer')

    <!-- Chatbot JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatbotIcon = document.getElementById('chatbotIcon');
            const chatbotWindow = document.getElementById('chatbotWindow');
            const closeChatbot = document.getElementById('closeChatbot');
            const chatbotInput = document.getElementById('chatbotInput');
            const sendMessage = document.getElementById('sendMessage');
            const chatbotMessages = document.getElementById('chatbotMessages');

            const replies = {
                "xin chào": "Chào bạn! Mình là chatbot hỗ trợ của Shop Thời Trang 😊",
                "chào": "Xin chào bạn! Bạn cần giúp gì hôm nay?",
                "bạn là ai": "Mình là chatbot tư vấn sản phẩm và hỗ trợ khách hàng!",
                "giờ làm việc": "Cửa hàng hoạt động từ 8h00 đến 21h00 mỗi ngày.",
                "tư vấn": "Bạn muốn mình tư vấn sản phẩm nào ạ? 👗👕👞",
                "giá": "Giá sản phẩm sẽ hiển thị ở phần chi tiết, bạn nói tên sản phẩm nhé!",
                "tạm biệt": "Cảm ơn bạn đã trò chuyện! Hẹn gặp lại ❤️"
            };

            function addMessage(content, type) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${type}`;
                messageDiv.textContent = content;
                chatbotMessages.appendChild(messageDiv);
                chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            }

            function getBotResponse(userMessage) {
                const lowerMessage = userMessage.toLowerCase();
                for (const keyword in replies) {
                    if (lowerMessage.includes(keyword)) {
                        return replies[keyword];
                    }
                }
                return "Xin lỗi, mình chưa hiểu ý bạn 😅";
            }

            function sendUserMessage() {
                const message = chatbotInput.value.trim();
                if (message) {
                    addMessage(message, 'user');
                    const response = getBotResponse(message);
                    setTimeout(() => addMessage(response, 'bot'), 500);
                    chatbotInput.value = '';
                }
            }

            chatbotIcon.addEventListener('click', () => {
                chatbotWindow.style.display = 'flex';
                chatbotIcon.style.display = 'none';
            });

            closeChatbot.addEventListener('click', () => {
                chatbotWindow.style.display = 'none';
                chatbotIcon.style.display = 'flex';
            });

            sendMessage.addEventListener('click', sendUserMessage);

            chatbotInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    sendUserMessage();
                }
            });
        });
    </script>
</body>

</html>
