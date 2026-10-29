<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('purchase_orders.index', [
            'orders' => PurchaseOrder::with(['supplier', 'items'])->latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('purchase_orders.create', [
            'suppliers' => Supplier::orderBy('name')->get(['id', 'name']),
            'products' => Product::orderBy('name')->get(['id', 'name', 'reference', 'purchase_price']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'distinct', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ], [], [
            'supplier_id' => 'fournisseur',
            'items' => 'lignes',
            'items.*.product_id' => 'produit',
            'items.*.quantity' => 'quantité',
            'items.*.unit_price' => 'prix unitaire',
        ]);

        $order = DB::transaction(function () use ($data, $request) {
            $order = PurchaseOrder::create([
                'number' => 'TMP-' . Str::uuid(),
                'supplier_id' => $data['supplier_id'],
                'user_id' => $request->user()->id,
                'status' => 'brouillon',
            ]);

            $order->update(['number' => sprintf('CMD-%s-%05d', now()->format('Y'), $order->id)]);
            $order->items()->createMany($data['items']);

            return $order;
        });

        return redirect()->route('purchase-orders.show', $order)->with('status', 'Commande créée.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'user', 'items.product']);

        return view('purchase_orders.show', ['order' => $purchaseOrder]);
    }

    public function send(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'brouillon') {
            return back()->withErrors(['order' => 'Seule une commande en brouillon peut être envoyée.']);
        }

        $purchaseOrder->update(['status' => 'envoyee', 'ordered_at' => now()]);

        return back()->with('status', 'Commande marquée comme envoyée.');
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder, StockService $stock)
    {
        try {
            DB::transaction(function () use ($purchaseOrder, $request, $stock) {
                $order = PurchaseOrder::with('items')->lockForUpdate()->findOrFail($purchaseOrder->id);

                if (! $order->canBeReceived()) {
                    throw new InvalidArgumentException('Cette commande ne peut plus être réceptionnée.');
                }

                foreach ($order->items as $item) {
                    $stock->move(
                        $item->product_id,
                        'entree',
                        $item->quantity,
                        $request->user()->id,
                        "Réception commande {$order->number}"
                    );
                }

                $order->update(['status' => 'recue', 'received_at' => now()]);
            });
        } catch (InvalidArgumentException $e) {
            return back()->withErrors(['order' => $e->getMessage()]);
        }

        return back()->with('status', 'Commande réceptionnée : le stock a été mis à jour.');
    }

    public function cancel(PurchaseOrder $purchaseOrder)
    {
        if (! $purchaseOrder->canBeReceived()) {
            return back()->withErrors(['order' => 'Cette commande ne peut plus être annulée.']);
        }

        $purchaseOrder->update(['status' => 'annulee']);

        return back()->with('status', 'Commande annulée.');
    }
}
