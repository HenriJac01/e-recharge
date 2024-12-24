<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    //register
    public function register()
    {
        return view('auth/register');
    }

    // Méthode pour gérer l'inscription de l'utilisateur
    public function registerSave(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:255',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
          ]);
  
        // Gestion de l'image (si elle est fournie)
        $imageName = null;
        if (isset($validatedData['image']) && $validatedData['image']->isValid()) {
            $imageName = time() . '_' . uniqid() . '.' . $validatedData['image']->getClientOriginalExtension();
            $validatedData['image']->move(public_path('images'), $imageName);
        }
  
        // Création de l'utilisateur
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'password' => Hash::make($validatedData['password']),
            'image' => $imageName,
          ]);

        // Rediriger vers la page de connexion ou le tableau de bord
        return redirect()->route('login');
    }


   //login
    public function login()
    {
        return view('auth/login');
    }
    public function loginAction(Request $request)
{
    // Validation des données
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur de validation',
            'errors' => $validator->errors()
        ], 422);
    }

    // Tentative de connexion
    if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        return response()->json([
            'success' => false,
            'message' => trans('auth.failed') // Message d'erreur
        ], 401);
    }

    // Régénération de la session
    $request->session()->regenerate();

    // Redirection basée sur le type d'utilisateur
    $user = auth()->user();
    if ($user->type === 'dgi') {
        return response()->json([
            'success' => true,
            'message' => 'Bienvenue, DGI !',
            'redirect' => route('dgi/dashDgi') // URL pour le tableau de bord DGI
        ]);
    } elseif ($user->type === 'yas') {
        return response()->json([
            'success' => true,
            'message' => 'Bienvenue, YAS !',
            'redirect' => route('yas/dashYas') // URL pour le tableau de bord YAS
        ]);
    } else {
        // Cas où le type d'utilisateur est inconnu ou invalide
        Auth::logout(); // Déconnecter l'utilisateur
        return response()->json([
            'success' => false,
            'message' => 'Type d\'utilisateur non reconnu.'
        ], 403);
    }
}


    //logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
 
        $request->session()->invalidate();
        $request->session()->regenerateToken();
 
        return redirect('/login');
    }
 

    //Mot de oubier
    public function forgotPassword()
    {
        return view('auth/forgotPassword');
    }
 
}
