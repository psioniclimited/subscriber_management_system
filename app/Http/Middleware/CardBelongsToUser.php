<?php

namespace App\Http\Middleware;

use Closure;

class CardBelongsToUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //1- Get authenticated user
        //2- Get card model
        //3- check if card belong to user
        $user = Entrust::user();
        $card = Card::find($request->input('card_id'));
        //if user admin let him pass
        //if distributor or subdistributor check #3
        if(Entrust::hasRole('admin'))
            return $next($request);
        else if(Entrust::hasRole('distributor'))
            if $card->users_id === $user->id || $card->user->manager->id === $user->id ? return $next($request) : return back();
        else if(Entrust::hasRole('subdistributor'))
            if $card->users_id === $user->id ? return $next($request) : return back();
        
        // return $next($request);
    }
}
