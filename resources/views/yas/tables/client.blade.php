@extends('layouts/yas')
@section('style')
    <!-- client CSS -->
    <link rel="stylesheet" href="{{ asset('css/client.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

@endsection


@section('content')
<h2 class="title">Client</h2>
<ul class="breadcrumbs">
  <li><a href="">Accueil</a></li>
  <li><a href="" class="divider">/</a></li>
  <li><a href="" class="active">Tables</a></li>
  <li><a href="" class="divider">/</a></li>
  <li><a href="" class="active">Client</a></li>
</ul>

<!-- clients table -->
<div class="container">
  <div class="filters-section">
    <div class="filter-group">
      <div class="search-container">
        <div class="search-wrapper">
          <input
            type="text"
            id="searchInput"
            placeholder="Rechercher par nom ou téléphone..."
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

      <a href="{{ url('yas/formes/client') }}" class="btn-add">
        <i class="bx bx-plus-circle icon"></i> Ajouter
      </a>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead >
        <tr>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cin</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telephone</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code secret</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solde</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
        </tr>
      </thead>
      <tbody id="clientsTableBody">
      @if ($clients->count() > 0)
      @foreach($clients as $client)
        <tr>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $client->cin }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $client->name }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $client->phone_number }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $client->adress }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $client->secret_code}}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" class="amount"> {{ number_format($client['balance']) }} Ar</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <a class="action-btn" href="{{ route('yas/formes.editClient', $client->id) }}"> 
              <i class="bx bx-edit icon2"></i>
            </a>
            <a href="javascript:void(0);" class="action-btn delete"  data-id="{{$client['id']}}">
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
                    Affichage {{ $clients->firstItem() }} à {{ $clients->lastItem() }} de {{ $clients->total() }} clients
                </div>
                <div class="pagination">
                    @if ($clients->onFirstPage())
                        <button id="prevPage" disabled>
                            <i class="bx bx-chevron-left"></i>
                        </button>
                    @else
                        <button id="prevPage" onclick="window.location='{{ $clients->previousPageUrl() }}'">
                            <i class="bx bx-chevron-left"></i>
                        </button>
                    @endif

                    <div id="paginationNumbers">
                        @for ($i = 1; $i <= $clients->lastPage(); $i++)
                            @if ($i == $clients->currentPage())
                                <span class="current-page">{{ $i }}</span>
                            @else
                                <a href="{{ $clients->url($i) }}">{{ $i }}</a>
                            @endif
                        @endfor
                    </div>

                    @if ($clients->hasMorePages())
                        <button id="nextPage" onclick="window.location='{{ $clients->nextPageUrl() }}'">
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

<!-- clients table -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script>
  //Suppression
   $('.delete').click(function(){
    var client = $(this).attr('data-id');
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        html: "<b style='color: #f44336;'>Vous ne pourrez pas annuler cette action !</b><br>Client ID: <strong>" + client + "</strong>",
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
            window.location = "/yas/client/" + client;
        }
    });
});

//Rechercher
$(document).ready(function () {
    $("#searchInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        var visibleRows = 0;

        $("#clientsTableBody tr").filter(function () {
            var match = $(this).text().toLowerCase().indexOf(value) > -1;
            $(this).toggle(match);

            if (match) {
                visibleRows++;
            }
        });

        // Affiche un message s'il n'y a aucune correspondance
        if (visibleRows === 0) {
            if ($("#noDataRow").length === 0) {
                $("#clientsTableBody").append(
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