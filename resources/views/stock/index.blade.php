<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mouvements de stock</h2>
            @can('stock.move')
                <a href="{{ route('stock.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Nouveau mouvement
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
                <select name="product_id" class="border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="">Tous les produits</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}" @selected(request('product_id') == $p->id)>{{ $p->reference }} — {{ $p->name }}</option>
                    @endforeach
                </select>
                <select name="type" class="border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="">Tous les types</option>
                    @foreach (['entree' => 'Entrée', 'sortie' => 'Sortie', 'ajustement' => 'Ajustement', 'retour' => 'Retour'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <x-primary-button>Filtrer</x-primary-button>
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Produit</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3 text-right">Variation</th>
                            <th class="px-4 py-3">Motif</th>
                            <th class="px-4 py-3">Utilisateur</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($movements as $m)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3">{{ $m->product->name }} <span class="font-mono text-gray-400">({{ $m->product->reference }})</span></td>
                                <td class="px-4 py-3 capitalize">{{ $m->type }}</td>
                                <td class="px-4 py-3 text-right font-semibold {{ $m->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}
                                </td>
                                <td class="px-4 py-3">{{ $m->reason ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $m->user->name }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun mouvement.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $movements->links() }}</div>
        </div>
    </div>
</x-app-layout>
