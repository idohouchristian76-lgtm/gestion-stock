<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->q . '%';
                $query->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('reference', 'like', $term));
            })
            ->when($request->boolean('low'), fn ($query) => $query->lowStock())
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create', [
            'product' => new Product(),
            'categories' => Category::orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    public function store(ProductRequest $request, StockService $stock)
    {
        $data = $request->validated();
        $initial = (int) ($data['initial_quantity'] ?? 0);
        unset($data['initial_quantity']);

        DB::transaction(function () use ($data, $initial, $stock) {
            $product = Product::create($data);

            if ($initial > 0) {
                $stock->move($product->id, 'entree', $initial, auth()->id(), 'Stock initial');
            }
        });

        return redirect()->route('products.index')->with('status', 'Produit créé.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        unset($data['initial_quantity']);

        $product->update($data);

        return redirect()->route('products.index')->with('status', 'Produit modifié.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('status', 'Produit supprimé.');
    }
}
