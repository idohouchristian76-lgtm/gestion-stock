<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Commandes d'achat</h2>
            <a href="{{ route('purchase-orders.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Nouvelle commande
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Numéro</th>
                            <th class="px-4 py-3">Fournisseur</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3 text-right">Lignes</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3">Créée le</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            <tr>
                                <td class="px-4 py-3 font-mono">{{ $order->number }}</td>
                                <td class="px-4 py-3">{{ $order->supplier->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded px-2 py-0.5 text-xs {{ $order->statusClass() }}">{{ $order->statusLabel() }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">{{ $order->items->count() }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($order->total(), 2, ',', ' ') }}</td>
                                <td class="px-4 py-3">{{ $order->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('purchase-orders.show', $order) }}" class="text-indigo-600 hover:underline">Voir</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">Aucune commande.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $orders->links() }}</div>
        </div>
    </div>
</x-app-layout>
