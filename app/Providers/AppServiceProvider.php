<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Http\Request;
use Hp;
use DB;
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

              $event->menu->add('MASTERING PEMDA');
                $event->menu->add([
                    "text"=>'TARGET PEMDA',
                    'url'=>route('d.daerah.index')
                ]);

                  $event->menu->add([
                    "text"=>'PROFIL TARGET PEMDA',
                    'url'=>route('d.daerah.index')
                ]);

                $event->menu->add('DATA RKPD');
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



                $event->menu->add('DATA SAT');

                $event->menu->add([
                    "text"=>'SAT',
                    'url'=>route('d.daerah.index')
                ]);



                $event->menu->add('OLAH DATA');

                $event->menu->add([
                    "text"=>'INPUT DATA OLAH RKPD',
                    'url'=>route('d.daerah.index')
                ]);



                $event->menu->add('PENDUKUNG');





                $event->menu->add([
                    'text' => 'FILE KEBIJAKAN',
                    'icon'=>'fa fa-file',
                    'can'=>'role.daerah',
                    'submenu'=>[
                       
                        [ 'text'=>'RPJMD', 'url'=>route('d.kb.f.index',['jenis'=>'RPJMD'])]
                      ,[ 'text'=>'RKPD', 'url'=>route('d.kb.f.index',['jenis'=>'RKPD'])]
                      ,[ 'text'=>'RKPD RANWAL', 'url'=>route('d.kb.f.index',['jenis'=>'RKPD RANWAL'])]
                      ,[ 'text'=>'RENJA', 'url'=>route('d.kb.f.index',['jenis'=>'RENJA'])]
                      ,[ 'text'=>'RENSTRA', 'url'=>route('d.kb.f.index',['jenis'=>'RENSTRA'])]
                      ,[ 'text'=>'DPA DINAS', 'url'=>route('d.kb.f.index',['jenis'=>'DPA DINAS'])]
                      ,[ 'text'=>'PERKADA APBD', 'url'=>route('d.kb.f.index',['jenis'=>'PERKADA APBD'])]
                      ,[ 'text'=>'PERDA TARIF', 'url'=>route('d.kb.f.index',['jenis'=>'PERDA TARIF'])]
                      ,[ 'text'=>'BUSSINESS PLAN', 'url'=>route('d.kb.f.index',['jenis'=>'BUSSINESS PLAN'])]
                      ,[ 'text'=>'RISPAM', 'url'=>route('d.kb.f.index',['jenis'=>'RISPAM'])]
                      ,[ 'text'=>'RAD MPL', 'url'=>route('d.kb.f.index',['jenis'=>'RAD MPL'])]
                      ,[ 'text'=>'LKPJ', 'url'=>route('d.kb.f.index',['jenis'=>'LKPJ'])]
                      ,[ 'text'=>'DDUB', 'url'=>route('d.kb.f.index',['jenis'=>'DDUB'])]
                      ,[ 'text'=>'FORMAT 6 DAN 7', 'url'=>route('d.kb.f.index',['jenis'=>'FORMAT 6 DAN 7'])]
                      ,[ 'text'=>'PROFIL PDAM', 'url'=>route('d.kb.f.index',['jenis'=>'PROFIL PDAM'])]
                      ,[ 'text'=>'JAKSTRADA', 'url'=>route('d.kb.f.index',['jenis'=>'JAKSTRADA'])]
                      ,[ 'text'=>'PERDA PENYERTA MODAL', 'url'=>route('d.kb.f.index',['jenis'=>'PERDA PENYERTA MODAL'])]
                      ,[ 'text'=>'RTRW', 'url'=>route('d.kb.f.index',['jenis'=>'RTRW'])]
                      ,[ 'text'=>'PROPOSAL', 'url'=>route('d.kb.f.index',['jenis'=>'PROPOSAL'])]
                      ,[ 'text'=>'DATA AKSES AIR MINUM', 'url'=>route('d.kb.f.index',['jenis'=>'DATA AKSES AIR MINUM'])]
                      ,[ 'text'=>'RPIJM', 'url'=>route('d.kb.f.index',['jenis'=>'RPIJM'])]
                      ,[ 'text'=>'LAP. KEUANGAN', 'url'=>route('d.kb.f.index',['jenis'=>'LAP. KEUANGAN'])]
                      ,[ 'text'=>'LAP EVKIN BPKP', 'url'=>route('d.kb.f.index',['jenis'=>'LAP EVKIN BPKP'])]
                      ,[ 'text'=>'PETA KELEMBAGAAN', 'url'=>route('d.kb.f.index',['jenis'=>'PETA KELEMBAGAAN'])]


                    ]
                ]);

                $event->menu->add([
                    "text"=>'UPLOAD FILE LAIN LAIN',
                    'url'=>route('d.kb.f.index',['jenis'=>'LAIN_LAIN'])
                 ]);


                $event->menu->add('PELAKSANAAN');
                  $event->menu->add([
                    'text' => 'OUTPUT',
                     'can'=>'role.admin',

                    'submenu'=>[
                        [
                            'text'=>'MAP',
                            'url'=>route('d.out.map.index')
                        ],
                        [
                            'text'=>'ARTIKEL / DOKUMEN',
                            'url'=>route('d.out.post.index',['post'=>'text'])
                        ]
                    ]
                ]);


                $event->menu->add([
                    'text' => 'KEGIATAN',
                     'can'=>'role.admin',
                     'submenu'=>[
                        [
                            'text'=>'Post',
                            'url'=>route('d.post.kegiatan.index')


                        ],
                         [
                            'text'=>'Tambah Post',
                            'url'=>route('d.post.kegiatan.create')

                            

                        ]

                     ]
                   
                ]);

                $event->menu->add([
                    'text' => 'KORDINASI',
                    'icon'=>'fa fa-users',
                    'url'=>''
                    
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
                    'text' => 'USER',
                     'can'=>'role.superadmin',

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
                   
                    'submenu'=>[
                        [
                            'text'=>'PER DAERAH (AIR MINUM) - CHART',
                            'url'=>route('p.prokeg')
                        ],
                         [
                            'text'=>'PER DAERAH (AIR MINUM) - TABLE',
                            'url'=> route('d.index')
                        ]
                    ]
                    
                ]);


                 $event->menu->add([
                    'text' => 'KINERJA AIR MINUM',
                    'icon'=>'fa fa-thumbs-up ',
                    'url'=>''
                    
                ]);

                 $event->menu->add([
                    'text' => 'KELEMBAGAAN',
                    'icon'=>'fa fa-university',
                    'url'=>route('ty.index')
                    
                ]);

                  $event->menu->add([
                    'text' => 'CAPAIAN SPM',
                    'icon'=>'fa fa-tint',
                    'url'=>''
                    
                ]);


                  $kor=DB::table('public.kordinasi_kategory')->get();
                  $korsub=[];
                  foreach ($kor as $key => $k) {
                    
                    $korsub[]=[
                      'text'=>$k->title,
                      'url'=>''

                    ];
                      
                    # code...
                  }


                 $event->menu->add([
                  'text' => 'KORDINASI',
                  'icon'=>'fa fa-users',

                  'submenu'=>$korsub
                  
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
