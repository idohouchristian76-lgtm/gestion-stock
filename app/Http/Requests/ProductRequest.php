<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('products.manage');
    }

    public function rules(): array
    {
        return [
            'reference' => ['required', 'string', 'max:255', Rule::unique('products', 'reference')->ignore($this->route('product'))],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:20'],
            'initial_quantity' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'reference' => 'référence',
            'name' => 'nom',
            'category_id' => 'catégorie',
            'supplier_id' => 'fournisseur',
            'purchase_price' => "prix d'achat",
            'sale_price' => 'prix de vente',
            'min_stock' => 'stock minimum',
            'unit' => 'unité',
            'initial_quantity' => 'stock initial',
        ];
    }
}
