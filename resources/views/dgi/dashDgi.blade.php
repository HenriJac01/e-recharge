<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Document</title>
</head>

<body>
    <p>bievenue à dgi </p>

    <div class="title">
        <div class="container d-flex justify-content-between align-items-center">
            <h5 class="m-0">@yield('title')</h5>
            <a href="{{ url('/logout') }}">Déconnexion</a>
        </div>

        <img src="{{ asset('images/' . Auth::user()->image) }}" width="80" height="80"
            alt="Image de l'utilisateur">
    </div>


</body>

</html>
