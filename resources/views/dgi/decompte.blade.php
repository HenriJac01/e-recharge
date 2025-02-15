<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('css/decompte.css') }}" />
  </head>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap');

* {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


header {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 0;
}

header .slogan {
    text-align: center;
}

header .slogan .title {
    font-size: 20px;
    font-weight: 700;
}

header .slogan .sous-title {
    font-size: 14px;
}

.hierarchie {
    display: flex;
    margin-top: 20px;
}

.hierarchie .content-hiera {
    text-align: center;
    line-height: 1.2;
    margin-left: 10px
        /* Espacement entre les lignes */
}

.title-contenu {
    text-align: center;
    padding: 25px;
    font-size: 18px;
    font-weight: 700;

}

.content {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-left: 30px;
    padding: 20px;
}

.text-card {
    text-align: left;
    line-height: 1.6;
    padding: 15px;
    /* Espacement entre les lignes */
}

.text-card p {
    margin-bottom: 10px;
}

.calcul {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-left: 15%;
}

.base-droit {
    display: flex;
    justify-content: space-between;
    /* Espace entre la description et le résultat */
    align-items: center;
    /* Alignement vertical */
    width: 80%;
    /* Ajuster la largeur selon vos besoins */
    border-bottom: 1px solid #ddd;
    /* Ligne séparatrice optionnelle */
    padding: 5px 0;
}

.base-title {
    font-size: 14px;
    font-weight: 500;
    text-align: left;
}

.reslt {
    font-size: 16px;
    font-weight: 400;
    text-align: right;
}

.footer {
    display: flex;
    margin-top: 20px;
    text-align: center;
}

.footer-title {
    font-size: 16px;
    text-decoration: underline;
    margin-right: 5px;
}

.letter {
    display: flex;
    font-size: 16px;
}
  </style>
  <body>
    <header>
      <div class="slogan">
        <div class="title">REPOBLIKAN'I MADAKASIKARA</div>
        <div class="sous-title">Fitiavana-Tanindrazana-Fandrosoana</div>
      </div>
    </header>
    <div class="hierarchie">
      <div class="content-hiera">
        <p>MINISTERE DES FINANCES ET DU BUDGET</p>
        <p>------------------</p>
        <p>SECRETARIAT GENERAL</p>
        <p>------------------</p>
        <p>DIRECTION GENERALE DES IMPÔTS</p>
        <p>------------------</p>
        <p>DIRECTION DES GRANDES ENTREPRISES</p>
        <p>------------------</p>
        <p>SERVICE ACCUEIL ET INFORMATION</p>
        <p>------------------</p>
      </div>
    </div>
    <div class="title-contenu">DECOMPTE DROIT D'ACCISES</div>
    <div class="content">
      <div class="card-content">
        <div class="text-card">
          <p><strong>OPERATEUR :</strong> {{ $data['operator'] }}</p>

          <p><strong>NIF :</strong> {{ $data['nif'] }}</p>
          <p><strong>TAUX DROIT D'ACCISES :</strong> {{ $data['taux'] }}</p>
        </div>
      </div>
    </div>
    <div class="calcul">
      <div class="base-droit">
        <div class="base-title">BASE DE DROIT D'ACCISES:</div>
        <div class="reslt">{{ $data['chiffre_daffaire'] }}</div>
      </div>
    </div>
    <div class="calcul">
      <div class="base-droit">
        <div class="base-title">DROIT D'ACCISES:</div>
        <div class="reslt ">{{ $data['droit_daccise'] }}</div>
      </div>
    </div>
    
    <div class="calcul">
      <div class="base-droit">
        <div class="base-title tl">TOTAL A PAYER : AR</div>
        <div class="reslt">{{ $data['total_a_payer'] }}</div>
      </div>
    </div>

    <div class="footer">
      <div class="footer-title">Montant de crédit arreté à la somme de:</div>
      <div class="letter">{{ $data['total_in_words'] }} Ariary {{ $data['total_in_words_decimal'] }} </div>
    </div>
  </body>
</html>
