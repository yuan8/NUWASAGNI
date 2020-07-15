<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Http\Request;
use HP;
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
                    "text"=>'DAERAH NUWSP',
                    'url'=>route('d.daerah.index'),
                     'can'=>'role.admin',

                ]);
                 $event->menu->add([
                    "text"=>'IKFD PEMDA',
                    'url'=>route('d.daerah.index'),
                     'can'=>'role.admin',

                ]);
                  $event->menu->add([
                    "text"=>'PAD',
                    'url'=>route('d.daerah.index'),
                     'can'=>'role.admin',

                ]);

                $event->menu->add('DATA RKPD');
                 $event->menu->add([
                    'text' => 'MASTER '.HP::fokus_tahun(),
                    'can'=>'role.admin',
                    'url'=>route('d.master.prokeg.index')

                    
                ]);

                $event->menu->add([
                    'text' => 'HASIL PEMETAAN '.HP::fokus_tahun(),
                    'can'=>'role.admin',
                    'url'=>route('d.prokeg.index')


                    
                ]);
                $event->menu->add([
                    "text"=>'UPLOAD DATA PEMATAAN RKPD',
                    'url'=>route('d.master.prokeg.upload_form'),
                    'can'=>'role.admin',

                ]);



                $event->menu->add('DATA PDAM');

                $event->menu->add([
                    "text"=>'SAT',
                    'url'=>route('d.daerah.index'),

                ]);

                  $event->menu->add([
                    "text"=>'BPPSPAM',
                    'url'=>route('d.daerah.index'),

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
                            'text'=>'ARTIKEL',
                            'url'=>route('d.post.kegiatan.index')


                        ],
                         [
                            'text'=>'TAMBAH ARTIKEL',
                            'url'=>route('d.post.kegiatan.create')

                            

                        ]

                     ]
                   
                ]);

                $event->menu->add([
                    'text' => 'KORDINASI',
                    'icon'=>'fa fa-users',
                    'url'=>'',
                    'can'=>'role.admin'
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
                            'text'=>'RPJMN',
                            'url'=>route('rpjmn.index')
                        ],
                        [
                          'text'=>'NOMENKLATUR (PERMENDAGRI 90) '.HP::fokus_tahun()
                        ],
                         [
                            'text'=>'PROGRAM KEGIATAN '.HP::fokus_tahun().'',
                            'url'=> route('d.index')
                        ],
                        [
                            'text'=>'PAD - TABLE',
                            'url'=> route('f.pad.index')

                        ],
                      
                    ]
                    
                ]);


                 $event->menu->add([
                    'text' => 'KINERJA AIR MINUM',
                    'icon'=>'fa fa-thumbs-up ',
                    'submenu'=>[
                       
                        [
                          'text'=>'INDIKATOR PROGRAM KEGIATAN '.HP::fokus_tahun(),
                          'url'=>route('kinerja-rkpd.index')
                        ],
                        [
                          'text'=>'RAKORTEK '.HP::fokus_tahun()
                        ],
                       
                    ]
                    
                ]);

                 $event->menu->add([
                    'text' => 'KELEMBAGAAN',
                    'icon'=>'fa fa-university',
                    'submenu'=>[
                          [
                            'text'=>'PROFIL PEMDA',
                             'url'=>route('kl.index')
                          ],
                          [
                            'text'=>'TIPOLOGI PEMDA',
                             'url'=>route('ty.index')
                          ],
                         [
                            'text'=>'IKFD '.(HP::fokus_tahun()-1),
                            'url'=> route('ikfd.index',['tahun'=>HP::fokus_tahun()-1])
                        ]


                    ]
                   
                    
                ]);

                  $event->menu->add([
                    'text' => 'CAPAIAN SPM',
                    'icon'=>'fa fa-tint',
                    'url'=>''
                    
                ]);


                  $kor=DB::table('public.kordinasi_kategory')->get();
                  $korsub=[
                     [
                      'text'=>'JADWAL KEGIATAN TEAM NUWSP '.HP::fokus_tahun(),
                      
                    ],
                    [
                      'text'=>'HASIL KEGIATAN TEAM NUWSP '.HP::fokus_tahun(),
                    ]
                  ];
                  foreach ($kor as $key => $k) {

                    $korsub[]=[
                      'text'=>$k->title,
                      'url'=>''

                    ];
                      
                    # code...
                  }


                 $event->menu->add([
                  'text' => 'KEGIATAN',
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
                    'icon'=>'fa fa-door-open',
                    'submenu'=>[
                      [
                        'text'=>'SAT',
                        'url'=>route('p.pdam'),

                      ],
                      [
                        'text'=>'BPPSPAM',
                        'url'=>route('bppspam.index'),

                      ]
                    ]

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
