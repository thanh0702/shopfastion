# TODO: Add Product Management for Employees

## Information Gathered
- Employee sales page: `resources/views/employee/sales.blade.php` extends `admin.layout` and has a sidebar with buttons for cart, orders, etc.
- Admin product management exists in `resources/views/admin/products/` with index, create, edit views.
- AdminController has product CRUD methods.
- EmployeeController has sales, cart, orders methods but no product management.
- Routes for employees are under EmployeeMiddleware in web.php.

## Plan
- Add employee product management routes in `routes/web.php` under employee middleware.
- Add product CRUD methods to `EmployeeController.php` (similar to AdminController but for employees).
- Create employee product views in `resources/views/employee/products/` by copying and adapting admin views.
- Add a "Quản lý sản phẩm" button in the sidebar of `resources/views/employee/sales.blade.php`.

## Dependent Files to be edited
- `routes/web.php`: Add employee product routes.
- `app/Http/Controllers/EmployeeController.php`: Add product management methods.
- `resources/views/employee/sales.blade.php`: Add button to sidebar.
- New files: `resources/views/employee/products/index.blade.php`, `create.blade.php`, `edit.blade.php`.

## Followup steps
- Test the new routes and views.
- Ensure employees can access product management without admin privileges.
- Verify button appears and links correctly on sales page.
