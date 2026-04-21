<?php

namespace App\Http\Controllers;

use App\Support\Catalog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $maxPrice = $this->normalizeMaxPrice((int) $request->query('max_price', 5000));
        $categories = Catalog::categories();
        $products = Catalog::filteredProducts(null, $maxPrice);

        return view('categories.index', [
            'categories' => $categories,
            'products' => $products,
            'selectedCategory' => null,
            'maxPrice' => $maxPrice,
        ]);
    }

    public function show(Request $request, string $slug): View
    {
        $category = Catalog::findCategory($slug);

        abort_unless($category !== null, 404);

        $maxPrice = $this->normalizeMaxPrice((int) $request->query('max_price', 5000));
        $categories = Catalog::categories();
        $products = Catalog::filteredProducts($slug, $maxPrice);

        return view('categories.show', [
            'categories' => $categories,
            'products' => $products,
            'selectedCategory' => $category,
            'maxPrice' => $maxPrice,
        ]);
    }

    private function normalizeMaxPrice(int $maxPrice): int
    {
        return max(0, min(5000, $maxPrice));
    }
}
