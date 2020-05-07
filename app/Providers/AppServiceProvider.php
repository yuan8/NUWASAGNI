<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events, Request $request)
    {
        //

        $classter=substr($request->path(), 0,10);

        if($classter=='dash-admin'){
             $events->listen(BuildingMenu::class, function (BuildingMenu $event) {

                $event->menu->add('MAIN NAVIGATION');
                  $event->menu->add([
                    'text' => 'OUTPUT',
                    'submenu'=>[
                        [
                            'text'=>'MAP',
                            'url'=>route('d.out.map.index')
                        ],
                        [
                            'text'=>'POST',
                            'url'=>route('p.prokeg.urusan')
                        ]
                    ]
                ]);


             });


        }else{
             $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
                $event->menu->add('MAIN NAVIGATION');

                $event->menu->add([
                    'text' => 'PROGRAM KEGIATAN',
                    'submenu'=>[
                        [
                            'text'=>'PER DAERAH (AIR MINUM)',
                            'url'=>route('p.prokeg')
                        ],
                         [
                            'text'=>'PER URUSAN (AIR MINUM)',
                            'url'=>route('p.prokeg.urusan')
                        ]
                    ]
                ]);
                
                $event->menu->add([
                    'text' => 'PROFILE PDAM',
                    'url'=>route('p.pdam')
                ]);

                $event->menu->add([
                    'text' => 'PROFILE KEBIJAKAN',
                    'url'=>null
                ]);

                $event->menu->add([
                    'text' => 'PROFILE DAERAH',
                    'url'=>null
                ]);
                 $event->menu->add([
                    'text' => 'NUWAS PROJECT ',
                    'url'=>route('p.nuwas.index')
                ]);
            });
        }




       
          
    }
}
