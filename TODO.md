# TODO: Add Supplier Management for Employees

## Tasks:
1. Add supplier CRUD methods to EmployeeController (indexSuppliers, createSupplier, storeSupplier, editSupplier, updateSupplier, deleteSupplier)
2. Add routes for employee suppliers in routes/web.php under employee middleware
3. Create employee supplier views: index, create, edit (copy from admin suppliers, adjust routes)
4. Add "Quản lý nhà cung cấp" button in employee/sales.blade.php sidebar
5. Test the functionality

## Completed:
- [x] Read relevant files (EmployeeController, AdminSupplierController, Supplier model, views, routes)
- [x] Understand structure: Admin has full CRUD, employees need similar but limited access
