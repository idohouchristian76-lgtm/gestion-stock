<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class StockService
{
    /**
     * Enregistre un mouvement et met à jour le stock de façon atomique.
     * entree / retour : quantité positive ajoutée
     * sortie          : quantité positive retirée
     * ajustement      : quantité signée (+ ou -)
     * La colonne stock_movements.quantity stocke toujours la variation signée.
     */
    public function move(int $productId, string $type, int $quantity, int $userId, ?string $reason = null): StockMovement
    {
        return DB::transaction(function () use ($productId, $type, $quantity, $userId, $reason) {
            $product = Product::lockForUpdate()->findOrFail($productId);

            $delta = match ($type) {
                'entree', 'retour' => abs($quantity),
                'sortie' => -abs($quantity),
                'ajustement' => $quantity,
                default => throw new InvalidArgumentException("Type de mouvement inconnu : {$type}"),
            };

            if ($delta === 0) {
                throw new InvalidArgumentException('La quantité ne peut pas être nulle.');
            }

            if ($product->quantity + $delta < 0) {
                throw new InvalidArgumentException(
                    "Stock insuffisant pour « {$product->name} » : {$product->quantity} disponible(s)."
                );
            }

            $product->quantity += $delta;
            $product->save();

            return StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $userId,
                'type' => $type,
                'quantity' => $delta,
                'reason' => $reason,
            ]);
        });
    }
}
