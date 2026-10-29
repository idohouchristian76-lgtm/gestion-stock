@php
    $selectClass = 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full';
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="reference" value="Référence" />
        <x-text-input id="reference" name="reference" class="block mt-1 w-full" :value="old('reference', $product->reference)" required />
        <x-input-error :messages="$errors->get('reference')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="name" value="Nom" />
        <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $product->name)" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="category_id" value="Catégorie" />
        <select id="category_id" name="category_id" class="{{ $selectClass }}">
            <option value="">— Aucune —</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="supplier_id" value="Fournisseur" />
        <select id="supplier_id" name="supplier_id" class="{{ $selectClass }}">
            <option value="">— Aucun —</option>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}" @selected(old('supplier_id', $product->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="purchase_price" value="Prix d'achat" />
        <x-text-input id="purchase_price" name="purchase_price" type="number" step="0.01" min="0" class="block mt-1 w-full" :value="old('purchase_price', $product->purchase_price ?? 0)" required />
        <x-input-error :messages="$errors->get('purchase_price')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="sale_price" value="Prix de vente" />
        <x-text-input id="sale_price" name="sale_price" type="number" step="0.01" min="0" class="block mt-1 w-full" :value="old('sale_price', $product->sale_price ?? 0)" required />
        <x-input-error :messages="$errors->get('sale_price')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="min_stock" value="Stock minimum (seuil d'alerte)" />
        <x-text-input id="min_stock" name="min_stock" type="number" min="0" class="block mt-1 w-full" :value="old('min_stock', $product->min_stock ?? 0)" required />
        <x-input-error :messages="$errors->get('min_stock')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="unit" value="Unité" />
        <x-text-input id="unit" name="unit" class="block mt-1 w-full" :value="old('unit', $product->unit ?? 'pièce')" required />
        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
    </div>

    @unless ($product->exists)
        <div>
            <x-input-label for="initial_quantity" value="Stock initial" />
            <x-text-input id="initial_quantity" name="initial_quantity" type="number" min="0" class="block mt-1 w-full" :value="old('initial_quantity', 0)" />
            <x-input-error :messages="$errors->get('initial_quantity')" class="mt-2" />
        </div>
    @endunless

    <div class="md:col-span-2">
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description" rows="3" class="{{ $selectClass }}">{{ old('description', $product->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>
</div>
