<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nouveau mouvement de stock</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('stock.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="product_id" value="Produit" />
                        <select id="product_id" name="product_id" required
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            <option value="">— Choisir —</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}" @selected(old('product_id', $selected) == $p->id)>
                                    {{ $p->reference }} — {{ $p->name }} (stock : {{ $p->quantity }} {{ $p->unit }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="type" value="Type de mouvement" />
                        <select id="type" name="type" required
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            @foreach (['entree' => 'Entrée (réception)', 'sortie' => 'Sortie (vente, consommation)', 'retour' => 'Retour', 'ajustement' => 'Ajustement (inventaire, + ou −)'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="quantity" value="Quantité" />
                        <x-text-input id="quantity" name="quantity" type="number" class="block mt-1 w-full" :value="old('quantity')" required />
                        <p class="mt-1 text-xs text-gray-500">Entrée, sortie, retour : quantité positive. Ajustement : positive pour ajouter, négative pour retirer.</p>
                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="reason" value="Motif (facultatif)" />
                        <x-text-input id="reason" name="reason" class="block mt-1 w-full" :value="old('reason')" />
                        <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('stock.index') }}" class="text-sm text-gray-600 underline">Annuler</a>
                        <x-primary-button>Enregistrer</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
