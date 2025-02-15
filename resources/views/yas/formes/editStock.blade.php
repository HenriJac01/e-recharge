@extends('layouts/yas')

@section('style')
<link rel="stylesheet" href="{{ asset('css/transmission.css') }}">
@endsection

@section('content')
<h2 class="title">Stock</h2>
<ul class="breadcrumbs">
  <li><a href="">Accueil</a></li>
  <li><a href="" class="divider">/</a></li>
  <li><a href="" class="active">Formes</a></li>
  <li><a href="" class="divider">/</a></li>
  <li><a href="" class="active">Modifier</a></li>
</ul>
 <!--fromulaire stock-->
<div class="form-container">
      <div class="form-card">
        <div class="form-header">
          <i class="fas fa-box"></i>
          <h2>Modifier Stock</h2>
        </div>

        <form id="customerForm" action="{{ route('stock.update', $stock->id) }}" method="POST">
            @csrf
            @method('PUT')
          <div class="form-group">
            <label for="quantity"> Quantité </label>
            <input
              type="number"
              id="quantity"
              name="quantity"
              value="{{ $stock->quantity }}"
              required
              min="0"
              step="0.01"
              value="0"
              placeholder="0.00"
            />
          </div>

          <div class="form-group">
            <label for="minimum_threshold"> Seuil minimal </label>
            <input
              type="number"
              id="minimum_threshold"
              name="minimum_threshold"
              value="{{ $stock->minimum_threshold }}"
              required
              min="0"
              step="0.01"
              value="0"
              placeholder="0.00"
            />
          </div>  
          
          <div class="form-group">
              <label for="operator">
                Opérateur
              </label>
              <select id="operator_id" name="operator_id">
                <option value="">Choisir</option>
                @foreach ($operators as $operator)
                    <option value="{{ $operator->id }}" 
                        {{ $stock->operator_id == $operator->id ? 'selected' : '' }}>
                        {{ $operator->name }}
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
     <!--fromulaire stock-->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{ asset('js/transmission.js') }}"></script>
@endsection

