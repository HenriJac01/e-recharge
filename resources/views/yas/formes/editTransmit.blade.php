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
  <li><a href="" class="active">Modifier</a></li>
</ul>
 <!--fromulaire Transmission-->
<div class="form-container">
      <div class="form-card">
        <div class="form-header">
        <i class="fas fa-share"></i>
          <h2>Modifier Transmission</h2>
        </div>
        

        <form id="customerForm" action="{{ route('transmission.update', $transmit->id) }}" method="POST">
            @csrf
            @method('PUT')
          <div class="form-group">
            <label for="nif"> NIF </label>
            <input
              type="text"
              id="nif"
              name="nif"
              placeholder="XXX XXX XXX"
               value="{{ $transmit->nif }}"
              required
              pattern="\d{3} \d{3} \d{3}$"
              data-error="Format: XXX XXX XXX"
            />
          </div>

          <div class="form-group">
          <label for="chiffre_daffaire">Chiffre d'affaire (Mois en cours)</label>
            <input 
              type="number" 
              step="0.01" 
              id="chiffre_daffaire" 
              name="chiffre_daffaire"  value="{{ $transmit->chiffre_daffaire }}" 
            />
          </div>  

          <div class="form-group">
            <label for="droit_dacise">Taux (%)</label>
            <input 
              type="number" 
              step="0.08" 
              id="taux" 
              name="taux" 
              value="{{ $transmit->taux }}" 
            />  
          </div> 

          <div class="form-group">
            <label for="droit_dacise">Droit d'accise</label>
            <input 
              type="number" 
              step="0.01" 
              id="droit_daccise" 
              name="droit_daccise" 
              value="{{ $transmit->droit_daccise }}" 
            />  
          </div> 
          
          <div class="form-group">
              <label for="operator">
                Opérateur
              </label>
              <select id="operator_id" name="operator_id" >
                <option value="">Choisir</option>
                @foreach ($operators as $operator)
                    <option value="{{ $operator->id }}" 
                        {{ $transmit->operator_id == $operator->id ? 'selected' : '' }}>
                        {{ $operator->name }}
                    </option>
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
                    <option value="{{ $dgi->id }}" 
                        {{ $transmit->dgi_id == $dgi->id ? 'selected' : '' }}>
                        {{ $dgi->adress }}
                    </option>
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

