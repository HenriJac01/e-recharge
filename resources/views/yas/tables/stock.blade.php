@extends('layouts/yas')
@section('style')
    <!-- stock CSS -->
    <link rel="stylesheet" href="{{ asset('css/client.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

@endsection


@section('content')
<h2 class="title">Stock</h2>
<ul class="breadcrumbs">
  <li><a href="">Accueil</a></li>
  <li><a href="" class="divider">/</a></li>
  <li><a href="" class="active">Tables</a></li>
  <li><a href="" class="divider">/</a></li>
  <li><a href="" class="active">Stock</a></li>
</ul>

<!-- stocks table -->
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
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Id</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantite</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seuil minimal</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Heure de creation</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> Date & Heure de modification</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
        </tr>
      </thead>
      <tbody id="stocksTableBody">
      @if ($stocks->count() > 0)
      @foreach($stocks as $stock)
        <tr>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stock->id }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($stock['quantity']) }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($stock['minimum_threshold']) }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stock->created_at }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stock->updated_at}}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <a class="action-btn" href="{{ route('yas/formes.editStock', $stock->id) }}"> 
              <i class="bx bx-edit icon2"></i>
            </a>
            <a href="javascript:void(0);" class="action-btn delete"  data-id="{{$stock['id']}}">
             <i class="bx bx-trash icon3"></i>
           </a>
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
                    Affichage {{ $stocks->firstItem() }} à {{ $stocks->lastItem() }} de {{ $stocks->total() }} stocks
                </div>
                <div class="pagination">
                    @if ($stocks->onFirstPage())
                        <button id="prevPage" disabled>
                            <i class="bx bx-chevron-left"></i>
                        </button>
                    @else
                        <button id="prevPage" onclick="window.location='{{ $stocks->previousPageUrl() }}'">
                            <i class="bx bx-chevron-left"></i>
                        </button>
                    @endif

                    <div id="paginationNumbers">
                        @for ($i = 1; $i <= $stocks->lastPage(); $i++)
                            @if ($i == $stocks->currentPage())
                                <span class="current-page">{{ $i }}</span>
                            @else
                                <a href="{{ $stocks->url($i) }}">{{ $i }}</a>
                            @endif
                        @endfor
                    </div>

                    @if ($stocks->hasMorePages())
                        <button id="nextPage" onclick="window.location='{{ $stocks->nextPageUrl() }}'">
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

<!-- stocks table -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script>
  //Suppression
   $('.delete').click(function(){
    var stock = $(this).attr('data-id');
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        html: "<b style='color: #f44336;'>Vous ne pourrez pas annuler cette action !</b><br>stock ID: <strong>" + stock + "</strong>",
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
            window.location = "/yas/stock/" + stock;
        }
    });
});

//Rechercher
$(document).ready(function () {
    $("#searchInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        var visibleRows = 0;

        $("#stocksTableBody tr").filter(function () {
            var match = $(this).text().toLowerCase().indexOf(value) > -1;
            $(this).toggle(match);

            if (match) {
                visibleRows++;
            }
        });

        // Affiche un message s'il n'y a aucune correspondance
        if (visibleRows === 0) {
            if ($("#noDataRow").length === 0) {
                $("#stocksTableBody").append(
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