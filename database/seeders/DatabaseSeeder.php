<?php

    namespace Database\Seeders;

    // use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use App\Models\Category;
    use App\Models\Product;
    use App\Models\User;
    use Illuminate\Database\Seeder;

    class DatabaseSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         */
        public function run(): void
        {
            $this->call([
                UserSeeder::class,
                CategorySeeder::class,
                ProductSeeder::class
            ]);

            $categories = Category::all();
            foreach (Product::all() as $product) {
                $product->categories()->attach(
                    $categories->random(rand(1, 3))->pluck('id')->toArray()
                );
            }
        }
    }
