<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tableau de bord</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-500">Produits référencés</p>
                <p class="text-3xl font-semibold text-gray-800">{{ $productCount }}</p>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Stock faible</h3>
                @forelse ($lowStock as $p)
                    <div class="flex items-center justify-between border-b border-gray-100 py-2 text-sm">
                        <span>{{ $p->name }} <span class="font-mono text-gray-400">({{ $p->reference }})</span></span>
                        <span class="text-red-600 font-semibold">{{ $p->quantity }} / min. {{ $p->min_stock }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Aucun produit sous le seuil d'alerte.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
