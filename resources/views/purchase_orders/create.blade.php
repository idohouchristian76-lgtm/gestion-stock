@php
    $oldLines = array_values(old('items', [['product_id' => '', 'quantity' => 1, 'unit_price' => 0]]));
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nouvelle commande d'achat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('purchase-orders.store') }}" class="space-y-6"
                      x-data="{
                          products: @js($products),
                          lines: @js($oldLines),
                          add() { this.lines.push({ product_id: '', quantity: 1, unit_price: 0 }) },
                          remove(i) { this.lines.splice(i, 1) },
                          setPrice(i) {
                              const p = this.products.find(p => p.id == this.lines[i].product_id);
                              if (p) this.lines[i].unit_price = p.purchase_price;
                          },
                          total() { return this.lines.reduce((s, l) => s + (l.quantity * l.unit_price || 0), 0) }
                      }">
                    @csrf

                    <div>
                        <x-input-label for="supplier_id" value="Fournisseur" />
                        <select id="supplier_id" name="supplier_id" required
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            <option value="">— Choisir —</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Lignes de la commande</h3>
                        <x-input-error :messages="$errors->get('items')" class="mb-2" />

                        <template x-for="(line, i) in lines" :key="i">
                            <div class="grid grid-cols-12 gap-3 items-end mb-3">
                                <div class="col-span-12 md:col-span-6">
                                    <label class="block text-sm text-gray-600">Produit</label>
                                    <select :name="`items[${i}][product_id]`" x-model="line.product_id" @change="setPrice(i)" required
                                            class="border-gray-300 rounded-md shadow-sm block mt-1 w-full text-sm">
                                        <option value="">— Choisir —</option>
                                        <template x-for="p in products" :key="p.id">
                                            <option :value="p.id" x-text="p.reference + ' — ' + p.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="col-span-5 md:col-span-2">
                                    <label class="block text-sm text-gray-600">Quantité</label>
                                    <input type="number" min="1" :name="`items[${i}][quantity]`" x-model.number="line.quantity" required
                                           class="border-gray-300 rounded-md shadow-sm block mt-1 w-full text-sm">
                                </div>
                                <div class="col-span-5 md:col-span-3">
                                    <label class="block text-sm text-gray-600">Prix unitaire</label>
                                    <input type="number" min="0" step="0.01" :name="`items[${i}][unit_price]`" x-model.number="line.unit_price" required
                                           class="border-gray-300 rounded-md shadow-sm block mt-1 w-full text-sm">
                                </div>
                                <div class="col-span-2 md:col-span-1">
                                    <button type="button" @click="remove(i)" x-show="lines.length > 1"
                                            class="text-red-600 hover:underline text-sm">Retirer</button>
                                </div>
                            </div>
                        </template>

                        @foreach ($errors->getBag('default')->keys() as $key)
                            @if (str_starts_with($key, 'items.'))
                                <p class="text-sm text-red-600">{{ $errors->first($key) }}</p>
                            @endif
                        @endforeach

                        <button type="button" @click="add()" class="mt-2 text-sm text-indigo-600 hover:underline">+ Ajouter une ligne</button>
                    </div>

                    <div class="text-right text-lg font-semibold text-gray-800">
                        Total : <span x-text="total().toFixed(2)"></span>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('purchase-orders.index') }}" class="text-sm text-gray-600 underline">Annuler</a>
                        <x-primary-button>Créer la commande</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
