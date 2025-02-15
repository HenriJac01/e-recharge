@extends('layouts/yas')

@section('style')
<link rel="stylesheet" href="{{ asset('css/transmission.css') }}">
@endsection

@section('content')
<h2 class="title">Transmission</h2>
<ul class="breadcrumbs">
  <li><a href="">Accueil</a></li>
  <li><a href="" class="divider">/</a></li>
  <li><a href="" class="active">Formes</a></li>
  <li><a href="" class="divider">/</a></li>
  <li><a href="" class="active">Transmission</a></li>
</ul>
 <!--fromulaire Transmission-->
<div class="form-container">
      <div class="form-card">
        <div class="form-header">
          <i class="fas fa-share"></i>
          <h2>Nouvelle Transmission</h2>
        </div>

        <form id="customerForm" action="{{ route('transmission.store') }}" method="POST">
            @csrf
          <div class="form-group">
            <label for="nif"> NIF </label>
            <input
              type="text"
              id="nif"
              name="nif"
              required
              pattern="[0-9]{10}"
              placeholder="XXXXXXXXXX"
              data-error="Format: XXXXXXXXXX"
            />
          </div>

          <div class="form-group">
          <label for="chiffre_daffaire">Chiffre d'affaire (Mois en cours)</label>
            <input 
              type="number" 
              step="0.01" 
              id="chiffre_daffaire" 
              name="chiffre_daffaire" value="{{ $chiffreDaffaire }}" 
              readonly
            />
          </div>  

          <div class="form-group">
            <label for="droit_dacise">Droit d'accise (%)</label>
            <input 
              type="number" 
              step="0.08" 
              id="taux" 
              name="taux" 
              value="0.08" 
              readonly
            />  
          </div> 
          
          <div class="form-group">
              <label for="operator">
                Opérateur
              </label>
              <select id="operator_id" name="operator_id" required>
                <option value="">Choisir</option>
                @foreach ($operators as $operator)
                    <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="operator">
                DGI
              </label>
              <select id="dgi_id" name="dgi_id" required>
                <option value="">Choisir</option>
                @foreach ($dgis as $dgi)
                    <option value="{{ $dgi->id }}">{{ $dgi->adress }}</option>
                @endforeach
              </select>
            </div>

          <div class="form-actions">
            <button type="submit" class="btn-submit">
              <i class="fas fa-save"></i>
              Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
     <!--fromulaire Transmission-->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{ asset('js/transmission.js') }}"></script>
@endsection

