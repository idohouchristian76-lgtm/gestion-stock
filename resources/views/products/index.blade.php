<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Produits</h2>
            @can('products.manage')
                <a href="{{ route('products.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Nouveau produit
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif

            <form method="GET" class="mb-4 flex flex-wrap items-center gap-4">
                <x-text-input name="q" placeholder="Rechercher (nom ou référence)" :value="request('q')" class="w-72" />
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="low" value="1" @checked(request()->boolean('low')) class="rounded border-gray-300">
                    Stock faible uniquement
                </label>
                <x-primary-button>Filtrer</x-primary-button>
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Référence</th>
                            <th class="px-4 py-3">Nom</th>
                            <th class="px-4 py-3">Catégorie</th>
                            <th class="px-4 py-3 text-right">Stock</th>
                            <th class="px-4 py-3 text-right">Prix de vente</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($products as $product)
                            <tr>
                                <td class="px-4 py-3 font-mono">{{ $product->reference }}</td>
                                <td class="px-4 py-3">{{ $product->name }}</td>
                                <td class="px-4 py-3">{{ $product->category?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="{{ $product->isLowStock() ? 'text-red-600 font-semibold' : '' }}">
                                        {{ $product->quantity }} {{ $product->unit }}
                                    </span>
                                    @if ($product->isLowStock())
                                        <span class="ml-1 rounded bg-red-100 px-2 py-0.5 text-xs text-red-700">Faible</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">{{ number_format($product->sale_price, 2, ',', ' ') }}</td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    @can('products.manage')
                                        <a href="{{ route('products.edit', $product) }}" class="text-indigo-600 hover:underline">Modifier</a>
                                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline"
                                              onsubmit="return confirm('Supprimer ce produit ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="ml-3 text-red-600 hover:underline">Supprimer</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun produit.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $products->links() }}</div>
        </div>
    </div>
</x-app-layout>
