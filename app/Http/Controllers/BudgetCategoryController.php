<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BudgetCategory;

class BudgetCategoryController extends Controller
{
    /**
     * Display a listing of budget categories.
     */
    public function index()
    {
        $categories = BudgetCategory::where('is_active', true)
            ->orderBy('category_name')
            ->paginate(10);

        return view('budget.categories.index', compact('categories'));
    }

    /**
     * Store a newly created budget category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:100|unique:budget_categories',
            'color_hex' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'description' => 'nullable|string|max:255',
        ]);

        BudgetCategory::create([
            'category_name' => $request->category_name,
            'color_hex' => $request->color_hex,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('budget.categories.index')
            ->with('success', 'Budget category "' . $request->category_name . '" created successfully!');
    }

    /**
     * Update the specified budget category.
     */
    public function update(Request $request, BudgetCategory $category)
    {
        $request->validate([
            'category_name' => 'required|string|max:100|unique:budget_categories,category_name,' . $category->id,
            'color_hex' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'description' => 'nullable|string|max:255',
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'color_hex' => $request->color_hex,
            'description' => $request->description,
        ]);

        return redirect()->route('budget.categories.index')
            ->with('success', 'Budget category updated successfully!');
    }

    /**
     * Delete (deactivate) the specified budget category.
     */
    public function destroy(BudgetCategory $category)
    {
        $category->update(['is_active' => false]);

        return redirect()->route('budget.categories.index')
            ->with('success', 'Budget category archived successfully!');
    }

    /**
     * API endpoint to create a budget category (for AJAX).
     */
    public function apiCreate(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:100|unique:budget_categories',
            'color_hex' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $category = BudgetCategory::create([
            'category_name' => $request->category_name,
            'color_hex' => $request->color_hex,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully!',
            'category' => $category,
        ], 201);
    }
}
