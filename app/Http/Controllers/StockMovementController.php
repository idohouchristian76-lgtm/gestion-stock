<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $movements = StockMovement::with(['product', 'user'])
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->product_id))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('stock.index', [
            'movements' => $movements,
            'products' => Product::orderBy('name')->get(['id', 'name', 'reference']),
        ]);
    }

    public function create(Request $request)
    {
        return view('stock.create', [
            'products' => Product::orderBy('name')->get(['id', 'name', 'reference', 'quantity', 'unit']),
            'selected' => $request->integer('product_id'),
        ]);
    }

    public function store(StockMovementRequest $request, StockService $stock)
    {
        try {
            $stock->move(
                (int) $request->product_id,
                $request->type,
                (int) $request->quantity,
                $request->user()->id,
                $request->reason
            );
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['quantity' => $e->getMessage()]);
        }

        return redirect()->route('stock.index')->with('status', 'Mouvement enregistré.');
    }
}
