<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier le produit</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('products.update', $product) }}">
                    @csrf
                    @method('PUT')
                    @include('products._form')
                    <p class="mt-4 text-sm text-gray-500">
                        Quantité en stock : <strong>{{ $product->quantity }}</strong> (elle se modifie uniquement via les mouvements de stock).
                    </p>
                    <div class="flex items-center justify-end gap-4 mt-6">
                        <a href="{{ route('products.index') }}" class="text-sm text-gray-600 underline">Annuler</a>
                        <x-primary-button>Mettre à jour</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
