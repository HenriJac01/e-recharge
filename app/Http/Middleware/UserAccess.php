<?php
 
 namespace App\Http\Middleware;

 use Closure;
 use Illuminate\Http\Request;
 use Symfony\Component\HttpFoundation\Response;
 
 class UserAccess
 {
     /**
      * Handle an incoming request.
      */
     public function handle(Request $request, Closure $next, $userType): Response
     {
        $response = $next($request);
        
         if (auth()->check() && auth()->user()->type == $userType) {
             return $next($request);
         }
    
          // Add the script to prevent back navigation
        $response->setContent(
            str_replace('</body>', '<script>history.pushState(null, null, location.href); window.onpopstate = function () { history.pushState(null, null, location.href); };</script></body>', $response->getContent())
        );
         // Rediriger ou renvoyer une réponse d'erreur
         return response()->json(['message' => 'You do not have permission to access this page.'], 403);
     }
 }
 