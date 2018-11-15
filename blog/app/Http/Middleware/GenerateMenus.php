<?php

namespace App\Http\Middleware;

use Closure;

class GenerateMenus
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
        \Menu::make('MyNavBar', function ($menu) {
            $menu->add('Home');



            /*$menu->add('About', 'posts/about');
                $menu->about->add('Info', 'info');
                    $menu->info->add('Ciao', 'ciao');*/

            //$menu->add('About', ['action' => ['PostController@postdata', 'slug' => 'about-us']]);
            $menu->add('About', ['action' => ['PostController@show', 'id' => 8]]);
                $menu->about->add('Terms of use', ['action' => ['PostController@show', 'id' => 19]]);
            //$menu->add('About',    ['route'  => ['posts', 'id' => 1]]);

            $menu->add('Get Involved', ['action' => ['PostController@show', 'id' => 16]]);
            $menu->add('How to', ['action' => ['PostController@show', 'id' => 20]]);

            /*$menu->add('Admin', 'admin');
                $menu->admin->add('Posts', 'posts');
                $menu->admin->add('Categories', 'categories');*/

        });

        return $next($request);
    }
}
