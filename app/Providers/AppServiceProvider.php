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
                     'can'=>'role.admin',

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
                     'can'=>'role.admin',

                    'url'=>route('d.meet.index')
                ]);

              
               
                $event->menu->add([
                    'text' => 'PROGRAM KEG '.HP::fokus_tahun(),
                     'can'=>'role.admin',
                     'submenu'=>[
                        [
                            'text'=>'RPJMN ',
                            'url'=>route('d.kb.rpjmn.index')
                        ],
                        [
                            'text'=>'RKPD ',
                            'url'=>route('d.prokeg.index')
                        ],

                     ]

                    
                ]);

                 $event->menu->add([
                    'text' => 'BOT',
                     'can'=>'role.superadmin',
                    'submenu'=>[
                        [
                            'text'=>'SAT',
                            'url'=>url('')

                        ],
                        [
                            'text'=>'RISPAM',
                            'url'=>url('')
                        ]
                    ]
                ]);

                 $event->menu->add([
                    "text"=>'UPLOAD FILE LAIN LAIN',
                    'url'=>route('d.kb.f.index',['jenis'=>'LAIN_LAIN'])
                 ]);

                $event->menu->add([
                    'text' => 'FILE KEBIJAKAN',
                    'icon'=>'fa fa-file',
                    'can'=>'role.daerah',
                    'submenu'=>[
                       
                        [
                            "text"=>'JAKSTRA',
                            'url'=>route('d.kb.f.index',['jenis'=>'JAKSTRA'])
                        ],
                        
                        [
                            "text"=>'RENSTRA',
                            'url'=>route('d.kb.f.index',['jenis'=>'RENSTRA'])
                        ],
                        [
                            "text"=>'RISPAM',
                            'url'=>route('d.kb.f.index',['jenis'=>'RISPAM'])
                        ],
                        [
                            "text"=>'RPAM',
                            'url'=>route('d.kb.f.index',['jenis'=>'RPAM'])
                        ],
                        [
                            "text"=>'RKA',
                            'url'=>route('d.kb.f.index',['jenis'=>'RKA'])
                        ],
                        [
                            "text"=>'RKPD FINAL',
                            'url'=>route('d.kb.f.index',['jenis'=>'RKPD'])
                        ]

                    ]
                ]);

                $event->menu->add([
                    'text' => 'USER',
                     // 'can'=>'role.superadmin',

                    'url'=>route('d.user.index')
                 ]);

             });


            

        }else{
             $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
                $event->menu->add('MAIN NAVIGATION');

                $event->menu->add([
                    'text' => 'BERANDA',
                    'icon'=>'fa fa-home',
                    'url'=>''
                    
                ]);

                 $event->menu->add([
                    'text' => 'PETA DUKUNGAN',
                    'icon'=>'fa fa-map',
                    'url'=>''
                    
                ]);


                 $event->menu->add([
                    'text' => 'KINERJA AIR MINUM',
                    'icon'=>'fa fa-thumbs-up ',
                    'url'=>''
                    
                ]);

                 $event->menu->add([
                    'text' => 'KELEMBAGAAN',
                    'icon'=>'fa fa-users',
                    'url'=>''
                    
                ]);

                   $event->menu->add([
                    'text' => 'CAPAIAN SPM',
                    'icon'=>'fa fa-tint',
                    'url'=>''
                    
                ]);

                // $event->menu->add([
                //     'text' => 'PROGRAM KEGIATAN',
                //     'icon'=>'fa fa-file',
                //     'submenu'=>[
                //         [
                //             'text'=>'PER DAERAH (AIR MINUM) - CHART',
                //             'url'=>route('p.prokeg')
                //         ],
                //          [
                //             'text'=>'PER DAERAH (AIR MINUM) - TABLE',
                //             'url'=>route('pr.table')
                //         ]
                //     ]
                // ]);
                
                $event->menu->add([
                    'text' => 'PROFILE PDAM',
                    'url'=>route('p.pdam'),
                    'icon'=>'fa fa-door-open'

                ]);

                $event->menu->add([
                    'text' => 'PARTISIPASI DALAM CB-TA ',
                    'url'=>route('p.pdam'),
                    'icon'=>'fa fa-check'

                ]);

                // $event->menu->add([
                //     'text' => 'PROFILE KEBIJAKAN',
                //     'url'=>null,
                //     'icon'=>'fa fa-university'

                // ]);

                // $event->menu->add([
                //     'text' => 'PROFILE DAERAH',
                //     'url'=>null,
                //     'icon'=>'fa fa-map'
                // ]);
                //  $event->menu->add([
                //     'text' => 'NUWAS PROJECT ',
                //     'url'=>route('p.nuwas.index'),
                //     'icon'=>'fa fa-tint'

                // ]);
                // $event->menu->add([
                //     'text' => 'PINDAH TAHUN ',
                //     'url'=>route('pilih_tahun'),
                //     'icon'=>'fa fa-calendar'
                // ]);
            });
        }




       
          
    }
}
