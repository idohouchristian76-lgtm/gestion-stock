<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Commande {{ $order->number }}</h2>
            <span class="rounded px-3 py-1 text-sm {{ $order->statusClass() }}">{{ $order->statusLabel() }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif
            @if ($errors->has('order'))
                <div class="rounded-md bg-red-50 p-4 text-sm text-red-700">{{ $errors->first('order') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-500">Fournisseur :</span> {{ $order->supplier->name }}</div>
                <div><span class="text-gray-500">Créée par :</span> {{ $order->user->name }} le {{ $order->created_at->format('d/m/Y') }}</div>
                <div><span class="text-gray-500">Envoyée le :</span> {{ $order->ordered_at?->format('d/m/Y') ?? '—' }}</div>
                <div><span class="text-gray-500">Reçue le :</span> {{ $order->received_at?->format('d/m/Y') ?? '—' }}</div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Produit</th>
                            <th class="px-4 py-3 text-right">Quantité</th>
                            <th class="px-4 py-3 text-right">Prix unitaire</th>
                            <th class="px-4 py-3 text-right">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item->product->name }} <span class="font-mono text-gray-400">({{ $item->product->reference }})</span></td>
                                <td class="px-4 py-3 text-right">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($item->unit_price, 2, ',', ' ') }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($item->quantity * $item->unit_price, 2, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 font-semibold">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right">Total</td>
                            <td class="px-4 py-3 text-right">{{ number_format($order->total(), 2, ',', ' ') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('purchase-orders.index') }}" class="text-sm text-gray-600 underline">← Retour à la liste</a>

                @if ($order->canBeReceived())
                    <div class="flex gap-3">
                        @if ($order->status === 'brouillon')
                            <form method="POST" action="{{ route('purchase-orders.send', $order) }}">
                                @csrf
                                <x-secondary-button type="submit">Marquer comme envoyée</x-secondary-button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('purchase-orders.receive', $order) }}"
                              onsubmit="return confirm('Réceptionner la commande ? Le stock sera mis à jour.')">
                            @csrf
                            <x-primary-button>Réceptionner</x-primary-button>
                        </form>

                        <form method="POST" action="{{ route('purchase-orders.cancel', $order) }}"
                              onsubmit="return confirm('Annuler cette commande ?')">
                            @csrf
                            <x-danger-button type="submit">Annuler</x-danger-button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
