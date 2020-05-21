<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Http\Request;
use Hp;
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

                  $event->menu->add([
                    'text' => 'ONLINE MEET',
                    'url'=>route('d.meet.index')
                ]);

              
                $event->menu->add([
                    'text' => 'KEBIJAKAN',
                    'submenu'=>[
                        [
                            'text'=>'RPJMN',
                            'submenu'=>[
                                [
                                    'text'=>'RPJMN '.Hp::fokus_tahun(),
                                    'url'=>route('d.kb.rpjmn.index')
                                ],
                                // [
                                //     'text'=>'PROKEG PENDUKUNG RPJMN '.Hp::fokus_tahun(),
                                //     'url'=>route('d.kb.rpjmn.pemetaan')
                                // ],

                            ]
                        ]

                    ]
                ]);

                  $event->menu->add([
                    'text' => 'PROKEG',
                    'url'=>route('d.prokeg.index')
                ]);



             });


        }else{
             $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
                $event->menu->add('MAIN NAVIGATION');

                $event->menu->add([
                    'text' => 'PROGRAM KEGIATAN',
                    'icon'=>'fa fa-file',
                    'submenu'=>[
                        [
                            'text'=>'PER DAERAH (AIR MINUM) - CHART',
                            'url'=>route('p.prokeg')
                        ],
                         [
                            'text'=>'PER DAERAH (AIR MINUM) - TABLE',
                            'url'=>route('pr.table')
                        ]
                    ]
                ]);
                
                $event->menu->add([
                    'text' => 'PROFILE PDAM',
                    'url'=>route('p.pdam'),
                    'icon'=>'fa fa-door-open'

                ]);

                $event->menu->add([
                    'text' => 'PROFILE KEBIJAKAN',
                    'url'=>null,
                    'icon'=>'fa fa-university'

                ]);

                $event->menu->add([
                    'text' => 'PROFILE DAERAH',
                    'url'=>null,
                    'icon'=>'fa fa-map'
                ]);
                 $event->menu->add([
                    'text' => 'NUWAS PROJECT ',
                    'url'=>route('p.nuwas.index'),
                    'icon'=>'fa fa-tint'

                ]);
                $event->menu->add([
                    'text' => 'PINDAH TAHUN ',
                    'url'=>route('pilih_tahun'),
                    'icon'=>'fa fa-calendar'
                ]);
            });
        }




       
          
    }
}
