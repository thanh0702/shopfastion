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
}
