@extends('layouts/yas')

@section('style')
  <!-- client form CSS -->
    <link rel="stylesheet" href="{{ asset('css/clientForm.css') }}" />
@endsection
@section('content')
<h2 class="title">Client</h2>
<ul class="breadcrumbs">
  <li><a href="">Accueil</a></li>
  <li><a href="" class="divider">/</a></li>
  <li><a href="" class="active">Formes</a></li>
  <li><a href="" class="divider">/</a></li>
  <li><a href="" class="active">Client</a></li>
</ul>
<div class="form-container">
      <div class="form-card">
        <div class="form-header">
          <i class="fas fa-user-circle"></i>
          <h2>Nouveau Client</h2>
          <div class="step-indicator">
            <span class="step active" data-step="1">1</span>
            <span class="step-line"></span>
            <span class="step" data-step="2">2</span>
          </div>
        </div>
    <form id="customerForm" action="{{ route('client.store') }}" method="POST">
        @csrf
          <!-- Étape 1 : Informations personnelles -->
          <div class="form-step" id="step1">
            <div class="form-group">
              <label for="name">
                Nom
              </label>
              <input
                type="text"
                id="name"
                name="name"
                required
                placeholder="Nom complet"
              />
            </div>
            <div class="form-group">
              <label for="address">
                Adresse
              </label>
              <input
                type="text"
                id="adress"
                name="adress"
                required
                placeholder="Adresse complète"
              />
            </div>

            <div class="form-group">
              <label for="cin">
                CIN
              </label>
              <input
                type="text"
                id="cin"
                name="cin"
                value="{{ old('cin') }}"
                required
                pattern="[0-9]{12}"
                placeholder="XXXXXXXXXXXX"
                data-error="12 chiffres requis"
                
              />
            </div>

            <div class="form-group">
              <label for="phone">
                Téléphone
              </label>
              <input
                type="tel"
                id="phone_number"
                name="phone_number"
                required
                value="{{ old('phone_number') }}"
                pattern="[0-9]{10}"
                placeholder="0XXXXXXXXX"
                data-error="Format: 0XXXXXXXXX"
              />
            </div>

            <div class="form-actions">
              <button type="button" class="btn-next">
                Suivant
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>

          <!-- Étape 2 : Adresse et autres -->
          <div class="form-step" id="step2" style="display: none">
          <div class="form-group">
              <label for="secret_code">
                Code secret
              </label>
              <input
                type="password"
                id="secret_code"
                name="secret_code"
                required
                minlength="4"
                maxlength="4"
                pattern="[0-9]{4}"
                placeholder="XXXX"
                data-error="4 chiffres requis"
              />
            </div>
            <div class="form-group">
              <label for="balance">
                Solde
              </label>
              <input
                type="number"
                id="balance"
                name="balance"
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
              <select id="operator_id" name="operator_id" required>
                <option value="">Choisir</option>
                @foreach ($operators as $operator)
                    <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-actions">
              <button type="button" class="btn-prev">
                <i class="fas fa-arrow-left"></i>
                Retour
              </button>
              <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i>
                Enregistrer
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
 <!--fromulaire client-->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{ asset('js/clientForm.js') }}"></script>
@endsection