@extends('layouts/yas')
@section('style')
    <!-- transaction CSS -->
    <link rel="stylesheet" href="{{ asset('css/transaction.css') }}" />
@endsection
@section('content')
<h2 class="title">Transactions</h2>
<ul class="breadcrumbs">
  <li><a href="">Accueil</a></li>
  <li><a href="" class="divider">/</a></li>
  <li><a href="" class="active">Transactions</a></li>
</ul>
<div class="info-data">
  <div class="card">
    <div class="head">
      <div class="head-section">
        <i class="bx bx-shopping-bag"></i>
        <span class="curent-aa"></span>
      </div>
      <p>Nombre total</p>
      <div class="count">
        <h2>{{ number_format($stats['total_achat']) }}</h2>
        <p class="title_montb">Achat</p>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="head">
      <div class="head-section">
        <i class="bx bx-send"></i>
        <span class="curent-b"></span>
      </div>
      <p>Nombre total</p>
      <div class="count">
        <h2>{{ number_format($stats['total_transfert']) }}</</h2>
        <p class="title_monta">Transfert</p>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="head">
      <div class="head-section">
        <i class="bx bx-wallet"></i>
        <span class="curent-aa"></span>
      </div>
      <p>Montant total</p>
      <div class="count">
        <h2>{{ number_format($stats['total_amount_achat']) }} Ar</h2>
        <p class="title_montb">Achat</p>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="head">
      <div class="head-section">
        <i class="bx bx-credit-card"></i>
        <span class="curent-b"></span>
      </div>
      <p>Montant total</p>
      <div class="count">
        <h2>{{ number_format($stats['total_amount_transfert']) }} Ar</h2>
        <p class="title_monta">Transfert</p>
      </div>
    </div>
  </div>
</div>

<!-- Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Historique des transactions</h2>
            </div>

            <!-- Filters -->
            <!-- Section des filtres -->
<div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6 mb-6">
    <form action="{{ route('yas/transaction') }}" method="GET" class="space-y-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <!-- Période -->
            <div>
                <label for="date_range" class="block text-sm font-medium text-gray-700">Période</label>
                <select id="date_range" name="date_range" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Toutes les périodes</option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>Ce mois</option>
                </select>
            </div>

            <!-- Type -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <select id="type" name="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Tous les types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Statut -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Tous les statuts</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Montant -->
            <div>
                <label for="amount_range" class="block text-sm font-medium text-gray-700">Montant</label>
                <select id="amount_range" name="amount_range" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Tous les montants</option>
                    <option value="small" {{ request('amount_range') == 'small' ? 'selected' : '' }}>&lt; 1,000 Ar</option>
                    <option value="medium" {{ request('amount_range') == 'medium' ? 'selected' : '' }}>1,000 - 5,000 Ar</option>
                    <option value="large" {{ request('amount_range') == 'large' ? 'selected' : '' }}>&gt; 5,000 Ar</option>
                </select>
            </div>
        </div>

        <!-- Recherche -->
        <div class="flex gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ request('search') }}"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="Rechercher par référence, nom ou numéro..."
                           autocomplete="off">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filtrer
                </button>
                @if(request()->hasAny(['date_range', 'type', 'status', 'amount_range', 'search']))
                    <a href="{{ route('yas/transaction') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Réinitialiser
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

   <!-- Transactions Table -->
   <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Heure
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reference
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nom
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Montant
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transactions as $transaction)
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="showTransactionDetails('{{ $transaction->id }}')">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $transaction->reference }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ ucfirst($transaction->type) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ optional($transaction->sender)->name ?? 'N/A' }}</div>
                               </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($transaction->status)
                                    @case('completed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Complété
                                        </span>
                                        @break
                                    @case('pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            En attente
                                        </span>
                                        @break
                                    @case('error')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Erreur
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($transaction->amount, 0, ',', ' ') }} Ar
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button class="text-teal-600 hover:text-teal-900">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Transaction Details Panel -->
                        <tr class="hidden" id="details-{{ $transaction->id }}">
                            <td colspan="8" class="px-6 py-4 bg-gray-50">
                                <div class="grid grid-cols-4 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Méthode de Transaction</h4>
                                        <div class="text-sm text-gray-500">
                                            <p>Type: {{ ucfirst($transaction->type) }}</p>
                                            <p>Référence: {{ $transaction->reference }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Expéditeur</h4>
                                        <div class="text-sm text-gray-500">
                                            <p>Nom: {{ optional($transaction->sender)->name ?? 'N/A' }}</p>
                                            <p>Téléphone: {{ optional($transaction->sender)->phone_number ?? 'N/A' }}</p>
                                            <p>Solde: {{ number_format(optional($transaction->sender)->balance ?? 0, 0, ',', ' ') }} Ar</p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Destinataire</h4>
                                        <div class="text-sm text-gray-500">
                                            <p>Nom: {{ optional($transaction->transfer)->receiver->name ?? 'N/A' }}</p>
                                            <p>Téléphone: {{ optional($transaction->transfer)->receiver->phone_number ?? 'N/A' }}</p>
                                            <p>Solde: {{ number_format(optional(optional($transaction->transfer)->receiver)->balance ?? 0, 0, ',', ' ') }} Ar</p>
                                        </div>
                                    </div>
                                  
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-container px-6 py-4 border-t border-gray-200">
                <div class="table-info">
                    Affichage {{ $transactions->firstItem() }} à {{ $transactions->lastItem() }} de {{ $transactions->total() }} transactions
                </div>
                <div class="pagination">
                    @if ($transactions->onFirstPage())
                        <button id="prevPage" disabled>
                            <i class="bx bx-chevron-left"></i>
                        </button>
                    @else
                        <button id="prevPage" onclick="window.location='{{ $transactions->previousPageUrl() }}'">
                            <i class="bx bx-chevron-left"></i>
                        </button>
                    @endif

                    <div id="paginationNumbers">
                        @for ($i = 1; $i <= $transactions->lastPage(); $i++)
                            @if ($i == $transactions->currentPage())
                                <span class="current-page">{{ $i }}</span>
                            @else
                                <a href="{{ $transactions->url($i) }}">{{ $i }}</a>
                            @endif
                        @endfor
                    </div>

                    @if ($transactions->hasMorePages())
                        <button id="nextPage" onclick="window.location='{{ $transactions->nextPageUrl() }}'">
                            <i class="bx bx-chevron-right"></i>
                        </button>
                    @else
                        <button id="nextPage" disabled>
                            <i class="bx bx-chevron-right"></i>
                        </button>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        function showTransactionDetails(id) {
            const detailsRow = document.getElementById(`details-${id}`);
            const allDetails = document.querySelectorAll('[id^="details-"]');
            
            // Fermer tous les autres panneaux
            allDetails.forEach(row => {
                if (row.id !== `details-${id}`) {
                    row.classList.add('hidden');
                }
            });

            // Basculer le panneau actuel
            if (detailsRow.classList.contains('hidden')) {
                detailsRow.classList.remove('hidden');
            } else {
                detailsRow.classList.add('hidden');
            }
        }
    </script>
@endsection