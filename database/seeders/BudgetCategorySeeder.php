<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BudgetCategory;

class BudgetCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Labor', 'color_hex' => '#4D8EFF', 'description' => 'Wages, salaries, and labor costs'],
            ['category_name' => 'Materials', 'color_hex' => '#4DB6AC', 'description' => 'Construction materials and supplies'],
            ['category_name' => 'Equipment', 'color_hex' => '#FFB74D', 'description' => 'Equipment rental and purchase'],
            ['category_name' => 'Transport', 'color_hex' => '#EF9A9A', 'description' => 'Transportation and logistics'],
            ['category_name' => 'Subcontractors', 'color_hex' => '#CE93D8', 'description' => 'Subcontractor services and payments'],
            ['category_name' => 'Permits & Licenses', 'color_hex' => '#80DEEA', 'description' => 'Permits, fees, and licenses'],
            ['category_name' => 'Safety & Insurance', 'color_hex' => '#A5D6A7', 'description' => 'Safety equipment and insurance costs'],
            ['category_name' => 'Utilities', 'color_hex' => '#64B5F6', 'description' => 'Electricity, water, and other utilities'],
            ['category_name' => 'Contingency', 'color_hex' => '#9575CD', 'description' => 'Emergency and contingency funds'],
            ['category_name' => 'Miscellaneous', 'color_hex' => '#90A4AE', 'description' => 'Other expenses and miscellaneous costs'],
        ];

        foreach ($categories as $cat) {
            BudgetCategory::firstOrCreate(
                ['category_name' => $cat['category_name']],
                $cat
            );
        }

        echo "✓ Budget categories seeded!\n";
    }
}
