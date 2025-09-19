<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateToMongo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-to-mongo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration from MySQL to MongoDB...');

        // Migrate tables in order to handle foreign keys
        $this->idMap = [];

        // Migrate users
        $this->migrateTable('users', \App\Models\User::class);

        // Migrate categories
        $this->migrateTable('categories', \App\Models\Category::class);

        // Migrate products (has category_id)
        $this->migrateTableWithForeignKey('products', \App\Models\Product::class, ['category_id' => 'categories']);

        // Migrate banners
        $this->migrateTable('banners', \App\Models\Banner::class);

        // Migrate qr_codes
        $this->migrateTable('qr_codes', \App\Models\QrCode::class);

        // Migrate receipt_qrs
        $this->migrateTable('receipt_qrs', \App\Models\ReceiptQr::class);

        // Migrate wishlists (has user_id, product_id)
        $this->migrateTableWithForeignKey('wishlists', \App\Models\Wishlist::class, ['user_id' => 'users', 'product_id' => 'products']);

        // Migrate carts (has user_id)
        $this->migrateTableWithForeignKey('carts', \App\Models\Cart::class, ['user_id' => 'users']);

        // Migrate cart_items (has cart_id, product_id)
        $this->migrateTableWithForeignKey('cart_items', \App\Models\CartItem::class, ['cart_id' => 'carts', 'product_id' => 'products']);

        // Migrate orders (has user_id)
        $this->migrateTableWithForeignKey('orders', \App\Models\Order::class, ['user_id' => 'users']);

        // Migrate order_items (has order_id, product_id)
        $this->migrateTableWithForeignKey('order_items', \App\Models\OrderItem::class, ['order_id' => 'orders', 'product_id' => 'products']);

        // Migrate addresses (has user_id)
        $this->migrateTableWithForeignKey('addresses', \App\Models\Address::class, ['user_id' => 'users']);

        $this->info('Migration completed.');
    }

    private function migrateTable($table, $modelClass)
    {
        $records = \DB::connection('mysql')->table($table)->get();
        $this->idMap[$table] = [];

        foreach ($records as $record) {
            $data = (array) $record;
            $oldId = $data['id'];
            unset($data['id']); // Remove id to let MongoDB generate _id
            $model = $modelClass::on('mongodb')->create($data);
            $this->idMap[$table][$oldId] = $model->_id;
        }

        $this->info("Migrated {$table}: " . $records->count() . " records");
    }

    private function migrateTableWithForeignKey($table, $modelClass, $foreignKeys)
    {
        $records = \DB::connection('mysql')->table($table)->get();
        $this->idMap[$table] = [];

        foreach ($records as $record) {
            $data = (array) $record;
            $oldId = $data['id'];
            unset($data['id']);

            // Update foreign keys
            foreach ($foreignKeys as $fk => $refTable) {
                if (isset($data[$fk]) && $data[$fk]) {
                    $data[$fk] = $this->idMap[$refTable][$data[$fk]] ?? null;
                }
            }

            $model = $modelClass::on('mongodb')->create($data);
            $this->idMap[$table][$oldId] = $model->_id;
        }

        $this->info("Migrated {$table}: " . $records->count() . " records");
    }
}
