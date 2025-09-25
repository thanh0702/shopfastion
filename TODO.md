# Fix Product Detail Page Checkout Button

## Problem
The "Thanh toán" (Payment) button on the product detail page has href="#" and does not redirect to the checkout page.

## Solution
Modify the button to submit a form that adds the product to cart and redirects to checkout.

## Steps
- [ ] Edit resources/views/product.blade.php to add a form for the "Thanh toán" button with hidden 'action' input set to 'buy'
- [ ] Edit app/Http/Controllers/HomeController.php in addToCart method to check for $request->action == 'buy' and redirect to checkout route
- [ ] Test the functionality by running the app and clicking the button
