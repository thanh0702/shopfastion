<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function salesPage(Request $request)
    {
        $categories = Category::all();

        $query = Product::query();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $products = $query->paginate(20);

        // Get current cart count for the employee user
        $cartCount = $this->getEmployeeCart()->items()->sum('quantity');

        return view('employee.sales', compact('categories', 'products', 'cartCount'))
            ->with('search', $request->search)
            ->with('selectedCategory', $request->category);
    }

    // Helper to get or create employee cart
    protected function getEmployeeCart()
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['session_id' => null]
        );
        return $cart;
    }

    // Show employee cart page
    public function cartPage()
    {
        $cart = $this->getEmployeeCart();
        $cart->load('items.product');
        return view('employee.cart', compact('cart'));
    }

    // Add product to employee cart via ajax
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,_id',
                'quantity' => 'sometimes|integer|min:1',
                'size' => 'sometimes|string|nullable',
            ]);

            $cart = $this->getEmployeeCart();

            $quantity = $request->input('quantity', 1);
            $productId = $request->product_id;
            $size = $request->input('size', null);

            // Check if item exists in cart already with same product and size
            $cartItem = CartItem::where('cart_id', $cart->_id)
                ->where('product_id', $productId)
                ->where('size', $size)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                $cartItem = new CartItem([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'size' => $size,
                ]);
                $cart->items()->save($cartItem);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'cartCount' => $this->getEmployeeCart()->items()->sum('quantity')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Exception: '.$e->getMessage(),
            ], 500);
        }
    }

    // Get cart item count for ajax badge update
    public function getCartCount()
    {
        $cart = $this->getEmployeeCart();
        $count = $cart->items()->sum('quantity');
        return response()->json(['count' => $count]);
    }

    // Update cart item quantity
    public function updateCartItem(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $cart = $this->getEmployeeCart();
            $cartItem = CartItem::where('cart_id', $cart->_id)
                ->where('_id', $request->cart_item_id)
                ->first();

            if (!$cartItem) {
                return response()->json(['success' => false, 'message' => 'Cart item not found'], 404);
            }

            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            return response()->json([
                'success' => true,
                'message' => 'Cart item updated',
                'cartCount' => $this->getEmployeeCart()->items()->sum('quantity'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Delete cart item
    public function deleteCartItem(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|string',
        ]);

        try {
            $cart = $this->getEmployeeCart();
            $cartItem = CartItem::where('cart_id', $cart->_id)
                ->where('_id', $request->cart_item_id)
                ->first();

            if (!$cartItem) {
                return response()->json(['success' => false, 'message' => 'Cart item not found'], 404);
            }

            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cart item deleted',
                'cartCount' => $this->getEmployeeCart()->items()->sum('quantity'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Process employee payment
    public function processEmployeePayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,transfer',
        ]);

        $user = Auth::user();
        $cart = $this->getEmployeeCart();
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('employee.cart')->with('error', 'Giỏ hàng đang trống.');
        }

        // Calculate total
        $total = $cart->items->reduce(function ($carry, $item) {
            return $carry + (($item->product->price ?? 0) * $item->quantity);
        }, 0);

        // Create order
        $order = \App\Models\Order::create([
            'user_id' => $user->id,
            'total_amount' => $total,
            'status' => 'pending',
            'shipping_address' => 'Mua tại cửa hàng - Nhân viên: ' . $user->name,
            'payment_method' => $request->payment_method,
        ]);

        // Create order items
        foreach ($cart->items as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price ?? 0,
                'size' => $item->size ?? null,
            ]);
        }

        // Clear cart
        $cart->items()->delete();

        return redirect()->route('employee.order.details', $order->_id)->with('success', 'Đơn hàng đã được tạo thành công.');
    }

    // Show employee order details
    public function orderDetails($orderId)
    {
        $order = \App\Models\Order::with('orderItems.product')->findOrFail($orderId);

        // Ensure the order belongs to the authenticated employee
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        return view('employee.order_details', compact('order'));
    }

    // Complete order and deduct product quantities
    public function completeOrder($orderId)
    {
        $order = \App\Models\Order::with('orderItems.product')->findOrFail($orderId);

        // Ensure the order belongs to the authenticated employee
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        // Only allow completion if order is pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể hoàn thành đơn hàng đang chờ xử lý.');
        }

        // Deduct product quantities
        foreach ($order->orderItems as $item) {
            $product = $item->product;
            if ($product) {
                // Check if enough stock
                if ($product->stock_quantity < $item->quantity) {
                    return redirect()->back()->with('error', 'Không đủ số lượng sản phẩm "' . $product->name . '" trong kho.');
                }

                // Deduct stock
                $product->stock_quantity -= $item->quantity;
                $product->save();
            }
        }

        // Update order status
        $order->status = 'completed';
        $order->save();

        return redirect()->route('employee.order.invoice', $order->_id)->with('success', 'Đơn hàng đã được hoàn thành và số lượng sản phẩm đã được cập nhật.');
    }

    // Cancel order
    public function cancelOrder($orderId)
    {
        $order = \App\Models\Order::findOrFail($orderId);

        // Ensure the order belongs to the authenticated employee
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        // Only allow cancellation if order is pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể hủy đơn hàng đang chờ xử lý.');
        }

        // Update order status
        $order->status = 'cancelled';
        $order->save();

        return redirect()->back()->with('success', 'Đơn hàng đã được hủy.');
    }

    // Show invoice for completed order
    public function showInvoice($orderId)
    {
        $order = \App\Models\Order::with('orderItems.product')->findOrFail($orderId);

        // Ensure the order belongs to the authenticated employee
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        // Only allow invoice for completed orders
        if ($order->status !== 'completed') {
            return redirect()->route('employee.order.details', $order->_id)->with('error', 'Chỉ có thể xuất hóa đơn cho đơn hàng đã hoàn thành.');
        }

        return view('employee.invoice', compact('order'));
    }

    // Show employee orders list
    public function ordersList()
    {
        $user = Auth::user();
        $orders = \App\Models\Order::where('user_id', $user->id)
            ->with('orderItems.product')
            ->latest()
            ->get();

        return view('employee.orders', compact('orders'));
    }

    // Show all orders for employee management
    public function allOrders()
    {
        $orders = \App\Models\Order::orderBy('created_at', 'desc')->get();
        return view('employee.all_orders', compact('orders'));
    }

    // Show order details for any order (employee management)
    public function showOrder($orderId)
    {
        $order = \App\Models\Order::with('orderItems.product', 'receiptQrs')->findOrFail($orderId);
        return view('employee.order_show', compact('order'));
    }

    // Update order status for employee management
    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|string|in:pending,active,shipping,completed,cancelled,refunded',
        ]);

        $order = \App\Models\Order::findOrFail($orderId);
        $newStatus = $request->input('status');
        $oldStatus = $order->status;

        $order->load('orderItems.product');

        // If status is being changed to active, reduce product stock
        if ($newStatus === 'active' && $oldStatus !== 'active') {
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                if ($product) {
                    $product->stock_quantity = max(0, $product->stock_quantity - $item->quantity);
                    $product->save();
                }
            }
        }

        // If status changes to cancelled or refunded and was not cancelled or refunded before, increase stock quantity
        if (
            in_array($newStatus, ['cancelled', 'refunded']) &&
            !in_array($oldStatus, ['cancelled', 'refunded'])
        ) {
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                if ($product) {
                    $product->stock_quantity += $item->quantity;
                    $product->save();
                }
            }
        }

        $order->status = $newStatus;
        $order->save();

        return redirect()->route('employee.orders.show', $order)->with('success', 'Trạng thái đơn hàng đã được cập nhật thành công.');
    }

    // Employee Product Management Methods
    public function indexProducts()
    {
        $products = Product::with('category')->get();
        return view('employee.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('employee.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'size' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $imageUrls = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image) {
                    try {
                        Configuration::instance()->cloud->cloudName = config('services.cloudinary.cloud_name');
                        Configuration::instance()->cloud->apiKey = config('services.cloudinary.api_key');
                        Configuration::instance()->cloud->apiSecret = config('services.cloudinary.api_secret');

                        $uploadApi = new UploadApi();
                        $uploadResult = $uploadApi->upload($image->getRealPath(), [
                            'folder' => 'products'
                        ]);
                        $imageUrls[] = $uploadResult['secure_url'];
                    } catch (\Exception $e) {
                        \Log::error('Image upload failed: ' . $e->getMessage());
                        return redirect()->back()->withErrors(['images' => 'One or more images failed to upload. Please try again.']);
                    }
                }
            }
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'size' => $request->size,
            'images' => $imageUrls,
        ]);

        return redirect()->route('employee.products.index')->with('success', 'Product created successfully!');
    }

    public function editProduct(Product $product)
    {
        $categories = Category::all();
        return view('employee.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'size' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $imageUrls = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                if ($image) {
                    try {
                        Configuration::instance()->cloud->cloudName = config('services.cloudinary.cloud_name');
                        Configuration::instance()->cloud->apiKey = config('services.cloudinary.api_key');
                        Configuration::instance()->cloud->apiSecret = config('services.cloudinary.api_secret');

                        $uploadApi = new UploadApi();
                        $uploadResult = $uploadApi->upload($image->getRealPath(), [
                            'folder' => 'products'
                        ]);
                        $imageUrls[$index] = $uploadResult['secure_url'];
                    } catch (\Exception $e) {
                        \Log::error('Image upload failed: ' . $e->getMessage());
                        return redirect()->back()->withErrors(['images' => 'One or more images failed to upload. Please try again.']);
                    }
                }
            }
        }
        // Remove null values and reindex array
        $imageUrls = array_values(array_filter($imageUrls));

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'size' => $request->size,
            'images' => $imageUrls,
        ]);

        return redirect()->route('employee.products.index')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return redirect()->route('employee.products.index')->with('success', 'Product deleted successfully!');
    }
}
