@extends('layouts/dgi')
@section('style')
    <!-- transmit CSS -->
    <link rel="stylesheet" href="{{ asset('css/client.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">


@endsection
@section('content')
<h2 class="title">Transmission</h2>
<ul class="breadcrumbs">
  <li><a href="">Accueil</a></li>
  <li><a href="" class="divider">/</a></li>
  <li><a href="" class="active">Tables</a></li>
  <li><a href="" class="divider">/</a></li>
  <li><a href="" class="active">Transmission</a></li>
</ul>

<!-- transmits table -->
<div class="container">
  <div class="filters-section">
    <div class="filter-group">
      <div class="search-container">
        <div class="search-wrapper">
          <input
            type="text"
            id="searchInput"
            placeholder="Rechercher"
            aria-label="Rechercher"
          />
          <button
            type="button"
            class="search-button"
            aria-label="Lancer la recherche"
          >
            <i class="bx bx-search icon"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead >
        <tr>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIF</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operateur</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chiffre d'affaire</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux (%)</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Droit d'accise</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
        </tr>
      </thead>
      <tbody id="transmiTableBody">
      @if ($transmits->count() > 0)
      @foreach($transmits as $transmit)
        <tr>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transmit->nif }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transmit->operator->name }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($transmit['chiffre_daffaire']) }} Ar</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transmit->taux }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($transmit['droit_daccise']) }} Ar</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transmit->created_at->format('d/m/Y') }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                @if ($transmit->status)
                    <span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">Imprimé</span>
                @else
                    <span class="px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded-full">N/A</span>
                @endif
           </td>
           <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                @if (!$transmit->status)
                <a href="{{ route('decompte.print', $transmit->id) }}" class="action-btn">
                    <i class="fas fa-download icon4"  style="cursor: pointer;"></i>
                </a>
                @else
                <button class="action-btn" disabled>
                <i class="fas fa-check"></i> 
                </button>
            @endif
           </td> 
        </tr>
        @endforeach
        @else
        <tr>
          <td colspan="100%" >
          <span>Pas de données disponibles pour le moment.</span>
          </td> 
        </tr>        
        @endif 
      </tbody>
    </table>
  </div>
  <!-- Pagination -->
  <div class="pagination-container px-6 py-4 border-t border-gray-200">
                <div class="table-info">
                    Affichage {{ $transmits->firstItem() }} à {{ $transmits->lastItem() }} de {{ $transmits->total() }} transmits
                </div>
                <div class="pagination">
                    @if ($transmits->onFirstPage())
                        <button id="prevPage" disabled>
                            <i class="bx bx-chevron-left"></i>
                        </button>
                    @else
                        <button id="prevPage" onclick="window.location='{{ $transmits->previousPageUrl() }}'">
                            <i class="bx bx-chevron-left"></i>
                        </button>
                    @endif

                    <div id="paginationNumbers">
                        @for ($i = 1; $i <= $transmits->lastPage(); $i++)
                            @if ($i == $transmits->currentPage())
                                <span class="current-page">{{ $i }}</span>
                            @else
                                <a href="{{ $transmits->url($i) }}">{{ $i }}</a>
                            @endif
                        @endfor
                    </div>

                    @if ($transmits->hasMorePages())
                        <button id="nextPage" onclick="window.location='{{ $transmits->nextPageUrl() }}'">
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

<!-- transmits table -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script>
  //Suppression
   $('.delete').click(function(){
    var transmit = $(this).attr('data-id');
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        html: "<b style='color: #f44336;'>Vous ne pourrez pas annuler cette action !</b><br>transmit ID: <strong>" + transmit + "</strong>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '<i class="fas fa-check"></i> Oui',
        cancelButtonText: '<i class="fas fa-times"></i> Annuler',
        customClass: {
            popup: 'custom-popup-class',
            confirmButton: 'custom-confirm-button',
            cancelButton: 'custom-cancel-button'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = "/yas/transmission/" + transmit;
        }
    });
});

//Rechercher
$(document).ready(function () {
    $("#searchInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        var visibleRows = 0;

        $("#transmiTableBody tr").filter(function () {
            var match = $(this).text().toLowerCase().indexOf(value) > -1;
            $(this).toggle(match);

            if (match) {
                visibleRows++;
            }
        });

        // Affiche un message s'il n'y a aucune correspondance
        if (visibleRows === 0) {
            if ($("#noDataRow").length === 0) {
                $("#transmiTableBody").append(
                    "<tr id='noDataRow'><td colspan='100%' style='text-align: center; color: #858ba3;'>Aucune donnée trouvée</td></tr>"
                );
            }
        } else {
            $("#noDataRow").remove();
        }
    });
});


</script>
@endsection