<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

class AdminController extends Controller
{
    public function indexCategories()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    // Order management methods
    public function indexOrders()
    {
        $orders = \App\Models\Order::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(\App\Models\Order $order)
    {
        $order->load('user', 'orderItems.product', 'receiptQrs');
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrder(\Illuminate\Http\Request $request, \App\Models\Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipping,completed,cancelled,refunded',
        ]);

        $order->status = $request->input('status');
        $order->save();

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated successfully.');
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            try {
                Configuration::instance()->cloud->cloudName = config('services.cloudinary.cloud_name');
                Configuration::instance()->cloud->apiKey = config('services.cloudinary.api_key');
                Configuration::instance()->cloud->apiSecret = config('services.cloudinary.api_secret');

                $uploadApi = new UploadApi();
                $uploadResult = $uploadApi->upload($request->file('image')->getRealPath(), [
                    'folder' => 'categories'
                ]);
                $imageUrl = $uploadResult['secure_url'];
            } catch (\Exception $e) {
                // Log the error and continue without image
                \Log::error('Image upload failed: ' . $e->getMessage());
                return redirect()->back()->withErrors(['image' => 'The image failed to upload. Please try again.']);
            }
        }

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'imageurl' => $imageUrl,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Category created successfully!');
    }

    // New methods for product management
    public function indexProducts()
    {
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            try {
                Configuration::instance()->cloud->cloudName = config('services.cloudinary.cloud_name');
                Configuration::instance()->cloud->apiKey = config('services.cloudinary.api_key');
                Configuration::instance()->cloud->apiSecret = config('services.cloudinary.api_secret');

                $uploadApi = new UploadApi();
                $uploadResult = $uploadApi->upload($request->file('image')->getRealPath(), [
                    'folder' => 'products'
                ]);
                $imageUrl = $uploadResult['secure_url'];
            } catch (\Exception $e) {
                \Log::error('Image upload failed: ' . $e->getMessage());
                return redirect()->back()->withErrors(['image' => 'The image failed to upload. Please try again.']);
            }
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image_url' => $imageUrl,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Product created successfully!');
    }

    public function editProduct(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $imageUrl = $product->image_url;
        if ($request->hasFile('image')) {
            try {
                Configuration::instance()->cloud->cloudName = config('services.cloudinary.cloud_name');
                Configuration::instance()->cloud->apiKey = config('services.cloudinary.api_key');
                Configuration::instance()->cloud->apiSecret = config('services.cloudinary.api_secret');

                $uploadApi = new UploadApi();
                $uploadResult = $uploadApi->upload($request->file('image')->getRealPath(), [
                    'folder' => 'products'
                ]);
                $imageUrl = $uploadResult['secure_url'];
            } catch (\Exception $e) {
                \Log::error('Image upload failed: ' . $e->getMessage());
                return redirect()->back()->withErrors(['image' => 'The image failed to upload. Please try again.']);
            }
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image_url' => $imageUrl,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $imageUrl = $category->imageurl;
        if ($request->hasFile('image')) {
            try {
                Configuration::instance()->cloud->cloudName = config('services.cloudinary.cloud_name');
                Configuration::instance()->cloud->apiKey = config('services.cloudinary.api_key');
                Configuration::instance()->cloud->apiSecret = config('services.cloudinary.api_secret');

                $uploadApi = new UploadApi();
                $uploadResult = $uploadApi->upload($request->file('image')->getRealPath(), [
                    'folder' => 'categories'
                ]);
                $imageUrl = $uploadResult['secure_url'];
            } catch (\Exception $e) {
                // Log the error and continue without image
                \Log::error('Image upload failed: ' . $e->getMessage());
                return redirect()->back()->withErrors(['image' => 'The image failed to upload. Please try again.']);
            }
        }

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'imageurl' => $imageUrl,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
    }

    public function createBanner()
    {
        return view('admin.banners.create');
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link' => 'nullable|url',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            try {
                Configuration::instance()->cloud->cloudName = config('services.cloudinary.cloud_name');
                Configuration::instance()->cloud->apiKey = config('services.cloudinary.api_key');
                Configuration::instance()->cloud->apiSecret = config('services.cloudinary.api_secret');

                $uploadApi = new UploadApi();
                $uploadResult = $uploadApi->upload($request->file('image')->getRealPath(), [
                    'folder' => 'banners'
                ]);
                $imageUrl = $uploadResult['secure_url'];
            } catch (\Exception $e) {
                \Log::error('Image upload failed: ' . $e->getMessage());
                return redirect()->back()->withErrors(['image' => 'The image failed to upload. Please try again.']);
            }
        }

        Banner::create([
            'image' => $imageUrl,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Banner created successfully!');
    }

    public function indexBanners()
    {
        $banners = Banner::all();
        return view('admin.banners.index', compact('banners'));
    }

    public function editBanner(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function updateBanner(Request $request, Banner $banner)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link' => 'nullable|url',
        ]);

        $imageUrl = $banner->image;
        if ($request->hasFile('image')) {
            try {
                Configuration::instance()->cloud->cloudName = config('services.cloudinary.cloud_name');
                Configuration::instance()->cloud->apiKey = config('services.cloudinary.api_key');
                Configuration::instance()->cloud->apiSecret = config('services.cloudinary.api_secret');

                $uploadApi = new UploadApi();
                $uploadResult = $uploadApi->upload($request->file('image')->getRealPath(), [
                    'folder' => 'banners'
                ]);
                $imageUrl = $uploadResult['secure_url'];
            } catch (\Exception $e) {
                \Log::error('Image upload failed: ' . $e->getMessage());
                return redirect()->back()->withErrors(['image' => 'The image failed to upload. Please try again.']);
            }
        }

        $banner->update([
            'image' => $imageUrl,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully!');
    }

    public function deleteBanner(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully!');
    }

    // QR Code management methods
    public function indexQrCodes()
    {
        $qrCodes = QrCode::all();
        return view('admin.qr_codes.index', compact('qrCodes'));
    }

    public function createQrCode()
    {
        return view('admin.qr_codes.create');
    }

    public function storeQrCode(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            try {
                Configuration::instance()->cloud->cloudName = config('services.cloudinary.cloud_name');
                Configuration::instance()->cloud->apiKey = config('services.cloudinary.api_key');
                Configuration::instance()->cloud->apiSecret = config('services.cloudinary.api_secret');

                $uploadApi = new UploadApi();
                $uploadResult = $uploadApi->upload($request->file('image')->getRealPath(), [
                    'folder' => 'qr_codes'
                ]);
                $imageUrl = $uploadResult['secure_url'];
            } catch (\Exception $e) {
                \Log::error('Image upload failed: ' . $e->getMessage());
                return redirect()->back()->withErrors(['image' => 'The image failed to upload. Please try again.']);
            }
        }

        QrCode::create([
            'content' => $request->content,
            'title' => $request->title,
            'image' => $imageUrl,
        ]);

        return redirect()->route('admin.qr_codes.index')->with('success', 'QR Code created successfully!');
    }

    public function editQrCode(QrCode $qrCode)
    {
        return view('admin.qr_codes.edit', compact('qrCode'));
    }

    public function updateQrCode(Request $request, QrCode $qrCode)
    {
        $request->validate([
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $imageUrl = $qrCode->image;
        if ($request->hasFile('image')) {
            try {
                Configuration::instance()->cloud->cloudName = config('services.cloudinary.cloud_name');
                Configuration::instance()->cloud->apiKey = config('services.cloudinary.api_key');
                Configuration::instance()->cloud->apiSecret = config('services.cloudinary.api_secret');

                $uploadApi = new UploadApi();
                $uploadResult = $uploadApi->upload($request->file('image')->getRealPath(), [
                    'folder' => 'qr_codes'
                ]);
                $imageUrl = $uploadResult['secure_url'];
            } catch (\Exception $e) {
                \Log::error('Image upload failed: ' . $e->getMessage());
                return redirect()->back()->withErrors(['image' => 'The image failed to upload. Please try again.']);
            }
        }

        $qrCode->update([
            'content' => $request->content,
            'title' => $request->title,
            'image' => $imageUrl,
        ]);

        return redirect()->route('admin.qr_codes.index')->with('success', 'QR Code updated successfully!');
    }

    public function deleteQrCode(QrCode $qrCode)
    {
        $qrCode->delete();
        return redirect()->route('admin.qr_codes.index')->with('success', 'QR Code deleted successfully!');
    }
}
