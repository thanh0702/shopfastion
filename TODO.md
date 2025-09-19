# TODO: Switch to MongoDB

## Information Gathered
- The Laravel project is already configured for MongoDB:
  - Default database connection is set to 'mongodb' in config/database.php
  - Jenssegers MongoDB package is installed in composer.json
  - Models (User, Product, Category, etc.) extend Jenssegers\Mongodb\Eloquent\Model or Auth\User
- No migrations or raw SQL queries found that need updating
- DatabaseSeeder.php uses User factory, compatible with MongoDB

## Plan
1. Install MongoDB on your system
2. Update .env file with MongoDB connection details (use .env.mongodb as reference)
3. Generate application key
4. Run database seeder to populate initial data
5. Test the application

## Dependent Files
- .env (update with MongoDB vars)
- .env.mongodb (created as example)
- All models updated with protected $connection = 'mongodb';

## Followup Steps
- [x] Start MySQL in XAMPP to access existing data.
- [ ] Update MongoDB PHP extension: Your current extension version is 1.16.2, but the package requires ^1.21 or ^2. Download the latest php_mongodb.dll from https://pecl.php.net/package/mongodb (choose the version matching your PHP 8.x), replace the file in D:\xam\php\ext\, and restart Apache. This is critical as the old version causes the migration to fail with "Method Illuminate\Database\MySqlConnection::getCollection does not exist."
- [x] Dependencies are installed (note: switched to mongodb/laravel-mongodb for Laravel 12 compatibility).
- [x] Since you are using MongoDB Atlas, your .env is already updated with the connection string.
- [x] Run `php artisan app:migrate-to-mongo` to migrate data from MySQL to MongoDB (updated to use mongodb connection). Migration completed successfully: migrated users (2), categories (4), products (12), banners (3), qr_codes (1), receipt_qrs (3), wishlists (2), carts (0), cart_items (0), orders (4), order_items (5), addresses (1).
- [ ] Alternatively, run `php artisan db:seed` to populate initial data if no existing data.
- [x] Test the app with `php artisan serve` (server running on http://127.0.0.1:8000)
- [x] Generate application key if not done: `php artisan key:generate`
