<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Address;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Wishlist;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\QrCode;
use App\Models\ReceiptQr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        $categories = Category::with(['products' => function($query) {
            $query->limit(8);
        }])->get();
        $wishlists = auth()->check() ? Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray() : [];
        return view('home', compact('banners', 'categories', 'wishlists'));
    }

    public function showCategory(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $sort = $request->get('sort', 'default');
        $query = $category->products();

        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('id', 'asc');
                break;
        }

        $products = $query->get();

        return view('category', compact('category', 'products', 'sort'));
    }

    public function showProduct($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('product', compact('product'));
    }

    public function shop(Request $request)
    {
        $sort = $request->get('sort', 'default');
        $categoryId = $request->get('category', null);

        $query = Product::query();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('id', 'asc');
                break;
        }

        $products = $query->get();
        $categories = Category::all();

        return view('shop', compact('products', 'categories', 'sort', 'categoryId'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $sort = $request->get('sort', 'default');

        $productQuery = Product::query();

        if ($query) {
            $productQuery->where('name', 'like', '%' . $query . '%');
        }

        switch ($sort) {
            case 'name_asc':
                $productQuery->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $productQuery->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $productQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $productQuery->orderBy('price', 'desc');
                break;
            default:
                $productQuery->orderBy('id', 'asc');
                break;
        }

        $products = $productQuery->get();

        return view('search', compact('products', 'query', 'sort'));
    }

    public function account()
    {
        $user = auth()->user();
        $orders = $user->orders()->latest()->take(5)->get(); // Get latest 5 orders
        return view('account', compact('user', 'orders'));
    }

    public function orders()
    {
        $user = auth()->user();
        $orders = $user->orders()->with('orderItems.product')->latest()->get();
        return view('account.orders', compact('orders'));
    }

    public function orderDetails(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('orderItems.product');
        return view('account.order_details', compact('order'));
    }

    public function cancelOrder(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Only allow cancellation if order is pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Order cannot be cancelled.');
        }

        $order->status = 'cancelled';
        $order->save();

        return redirect()->route('account.order.details', $order)->with('success', 'Order has been cancelled.');
    }

    public function addresses()
    {
        $user = auth()->user();
        $addresses = Address::where('user_id', $user->id)->get();
        return view('account.addresses', compact('addresses'));
    }

    public function createAddress()
    {
        return view('account.address_create');
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'number' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        Address::create([
            'user_id' => auth()->id(),
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'number' => $request->number,
            'address' => $request->address,
        ]);

        return redirect()->route('account.addresses')->with('success', 'Address added successfully.');
    }

    public function editAddress(Address $address)
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        return view('account.address_edit', compact('address'));
    }

    public function updateAddress(Request $request, Address $address)
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'number' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $address->update([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'number' => $request->number,
            'address' => $request->address,
        ]);

        return redirect()->route('account.addresses')->with('success', 'Address updated successfully.');
    }

    public function deleteAddress(Address $address)
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $address->delete();

        return redirect()->route('account.addresses')->with('success', 'Address deleted successfully.');
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function editAccount()
    {
        $user = auth()->user();
        return view('account.details', compact('user'));
    }

    public function updateAccount(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('account.details')->with('success', 'Account updated successfully.');
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $request->product_id)
                            ->where('size', $request->size)
                            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'size' => $request->size,
            ]);
        }

        if ($request->action == 'buy') {
            return redirect()->route('checkout');
        } else {
            return back()->with('success', 'Product added to cart.');
        }
    }

    public function toggleWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = auth()->user();
        $product = Product::find($request->product_id);

        $wishlist = Wishlist::where('user_id', $user->id)
                            ->where('product_id', $request->product_id)
                            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['success' => true, 'added' => false]);
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'product_name' => $product->name,
                'product_price' => $product->current_price,
            ]);
            return response()->json(['success' => true, 'added' => true]);
        }
    }

    public function showWishlist()
    {
        $user = auth()->user();
        $wishlists = Wishlist::where('user_id', $user->id)->with('product')->get();
        return view('wishlist', compact('wishlists'));
    }

    public function showCart()
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();
        $cartItems = $cart ? $cart->items : collect();
        return view('cart', compact('cartItems'));
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->first();
        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], 404);
        }

        $cartItem = CartItem::where('id', $request->cart_item_id)
                            ->where('cart_id', $cart->id)
                            ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // Calculate updated totals
        $itemTotal = $cartItem->product->price * $cartItem->quantity;
        $cartTotal = $cart->items->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'item_total' => $itemTotal,
            'cart_total' => $cartTotal
        ]);
    }

    public function deleteCartItem(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);

        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->first();
        if (!$cart) {
            return back()->with('error', 'Cart not found.');
        }

        $cartItem = CartItem::where('id', $request->cart_item_id)
                            ->where('cart_id', $cart->id)
                            ->firstOrFail();

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    public function checkout()
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();
        $cartItems = $cart ? $cart->items : collect();
        $addresses = Address::where('user_id', $user->id)->get();
        $qrCode = QrCode::first(); // Assume first QR code for transfer

        return view('checkout', compact('cartItems', 'addresses', 'qrCode'));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cash,transfer',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // For transfer
        ]);

        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Giỏ hàng trống.');
        }

        $address = Address::where('user_id', $user->id)->findOrFail($request->address_id);
        $total = $cart->items->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $total,
            'status' => 'pending',
            'shipping_address' => $address->full_name . ', ' . $address->phone . ', ' . $address->number . ', ' . $address->address,
            'payment_method' => $request->payment_method,
        ]);

        // Create order items
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'size' => $item->size,
            ]);
        }

        // Handle receipt upload for transfer
        if ($request->payment_method === 'transfer' && $request->hasFile('receipt_image')) {
            try {
                Configuration::instance()->cloud->cloudName = config('services.cloudinary.cloud_name');
                Configuration::instance()->cloud->apiKey = config('services.cloudinary.api_key');
                Configuration::instance()->cloud->apiSecret = config('services.cloudinary.api_secret');

                $uploadApi = new UploadApi();
                $uploadResult = $uploadApi->upload($request->file('receipt_image')->getRealPath(), [
                    'folder' => 'receipts'
                ]);
                $imageUrl = $uploadResult['secure_url'];

                ReceiptQr::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'image' => $imageUrl,
                ]);
            } catch (\Exception $e) {
                \Log::error('Receipt image upload failed: ' . $e->getMessage());
                // Continue without receipt image, order is still created
            }
        }

        // Clear cart
        $cart->items()->delete();
        $cart->delete();

        return redirect()->route('account')->with('success', 'Đơn hàng đã được đặt thành công.');
    }
}
