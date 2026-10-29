<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fournisseurs</h2>
            <a href="{{ route('suppliers.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Nouveau fournisseur
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif
            @if ($errors->has('delete'))
                <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">{{ $errors->first('delete') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Nom</th>
                            <th class="px-4 py-3">E-mail</th>
                            <th class="px-4 py-3">Téléphone</th>
                            <th class="px-4 py-3">Adresse</th>
                            <th class="px-4 py-3 text-right">Produits</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td class="px-4 py-3">{{ $supplier->name }}</td>
                                <td class="px-4 py-3">{{ $supplier->email ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $supplier->phone ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $supplier->address ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">{{ $supplier->products_count }}</td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="text-indigo-600 hover:underline">Modifier</a>
                                    <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" class="inline"
                                          onsubmit="return confirm('Supprimer ce fournisseur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="ml-3 text-red-600 hover:underline">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun fournisseur.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $suppliers->links() }}</div>
        </div>
    </div>
</x-app-layout>
