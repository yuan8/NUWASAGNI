

// http://nuwsp.labsgue.com/penilaian/login

// const querystring = require('querystring');
// var request = require('request');
const puppeteer = require('puppeteer');
var host='http://localhost/nuwascore/';

const { readFileSync, writeFileSync } = require('fs');
const fs = require('fs');
const page={};
var id=-1;

var nampak=false;

var link_data=[];

async function login() {
  try{
    console.log("\x1b[37m",'--- mencoba untuk atemp login ----');

  	const browser =  await puppeteer.launch(
  		{headless: !nampak}
  		);
    const page = await browser.newPage();
    await page.setDefaultNavigationTimeout(9900000);
    await page.goto('http://nuwsp.labsgue.com/penilaian/login',{ waitUntil: 'networkidle0' });
    // await page.goto('http://airminum.ciptakarya.pu.go.id/sinkronisasi/login.php?appid=spam',{ waitUntil: 'networkidle0' });
    
    await page.type('#username','guest');
    await page.type('#password','guest');
    await Promise.all([
          page.click('button[type="submit"]'),
        
    ]);
     
    console.log("\x1b[37m",'--- success login ----');

     scaningData(page);
  	
  	}
  	catch(e){
    throw e;
    browser.close();
    // await page.click('button[type="submit"]');
  }
}

function delay(time) {
   return new Promise(function(resolve) { 
       setTimeout(resolve, time)
   });
}

async function scaningData(page){
	 await page.goto('http://nuwsp.labsgue.com/penilaian/survey',{ waitUntil: 'networkidle0' });
   await delay(4000);
    const result = await page.evaluate(()=>{
              
          		var table=$('#DataTables_Table_0').DataTable();
          		 table.destroy();

          		 var pdam=[];


          		 $('#DataTables_Table_0 tbody tr').each(function(key,dom){
          		 	data={

          		 		id:$(dom).find('td:nth-child(10) a.btn.btn-warning.btn-xs.btn-icon').attr('href').replace('http://nuwsp.labsgue.com/penilaian/survey/view/',''),
          		 		nama_pdam:$(dom).find('td:nth-child(2)').text(),
          		 		kode_daerah:'',
          		 		kota:$(dom).find('td:nth-child(3)').html().split('<br>')[0],
          		 		provinsi:$(dom).find('td:nth-child(3)').html().split('<br>')[1],
          		 		date_input:$(dom).find('td:nth-child(4)').text(),
          		 		range_bulan_berlaku:$(dom).find('td:nth-child(5)').html().split('<br>')[0].replace(/ /g,'').split('-'),
          		 		tahun_laporan:$(dom).find('td:nth-child(5)').html().split('<br>')[1].replace(/ /g,''),
          		 		keterangan:$(dom).find('td:nth-child(6)').html(),
          		 		verifikasi_provinsi:$(dom).find('td:nth-child(7)').html().toLowerCase(),
          		 		verifikasi_regional:$(dom).find('td:nth-child(8)').html().toLowerCase(),
          		 		verifikasi_satker:$(dom).find('td:nth-child(9)').html().toLowerCase(),
          		 		url:$(dom).find('td:nth-child(10) a.btn.btn-warning.btn-xs.btn-icon').attr('href'),
          		 		data_detail:[],
          		 	}
          		 	pdam.push(data);

          		 });

          		 return Promise.resolve(pdam);
          		

          		 // return pdam;

    });

     console.log('pengambilan semua path selesai -- mulai mengambil detail data --');


      link_data=result;
      fs.writeFileSync('./storage/file/link.json',JSON.stringify(result,undefined,4));
     if(link_data[id+1]!=undefined){
         setTimeout(function(){
             goDetail(page,(id+1));
         },1000);
     }else{

         console.log("\x1b[37m",'data selesai ter update');
         // notif();
         process.exit(0);
         // await browser.close();
     }




}



async function goDetail(page,id){
var data=link_data[id];
 await page.goto(data.url,{ waitUntil: 'networkidle0' });

 	const fun = await page.evaluate(()=>{
 		


          return Promise.resolve(1);

 	});

   const result = await page.evaluate(()=>{

   			function getValue(text,satuan){
   				text=text.replace(/,/g,'').trim().replace(/ +$/, "").replace(/\r?\n|\r/g,'');
   				if((text=='')||(text==null)){
   					return null;
   				}

   				if(isNaN(Number(text))==false){
   					return parseFloat(text);
   				}else{
   					text=text.toUpperCase().trim().replace(/ +$/, "");
   					var val=text;
   					switch(text){
   						case 'YA':
   						val= 1;
   						break;
   						case 'SEBAGIAN':
   						val= 0.5;
   						break;
   						case 'TIDAK':
   						val= 0;
   						break;
   						case 'SAKIT':
   						val= 1;
   						break;
   						case 'KURANG SEHAT':
   						val= 2;
   						break;
              case 'POTENSI UNTUK SEHAT':
              val= 3;
               break;
              case 'POTENSIAL UNTUK SEHAT':
              val= 3;
               break;
   						case 'SEHAT':
   						val= 4;
   						break;
   						case 'SEHAT BERKELANJUTAN':
   						val= 5;
   						break;
              case 'SANGAT RENDAH':
               val= 1;
   						case 'RENDAH':
   						val= 2;
   						break;
   						case 'SEDANG':
   						val= 3;
   						break;
   						case 'TINGGI':
   						val= 4;
   						break;
              case 'SANGAT TINGGI':
             val= 5;
             break;
   					
   					}
   					return val;
   				}


   			}



   			function getKey(text){
				text=text.trim();
				text=text.replace(/-/g,'').replace(/[?]/g,'').replace(/[/]/g,'').replace(/['-','(',')']/g,'').replace(/ /g,'_').toLowerCase();
				text=text.replace(/__/g,'_');

        text=text.replace(/fiskal/g,'fkl');
        text=text.replace(/pelayanan/g,'pel');
        text=text.replace(/jaringan/g,'jar');
        text=text.replace(/dengan/g,'dg');
        text=text.replace(/dalam/g,'dlm');
        text=text.replace(/kumulatif/g,'kum');
        text=text.replace(/laporan/g,'lap');
        text=text.replace(/periode/g,'per');
        text=text.replace(/didistribusikan/g,'dds');
        text=text.replace(/sambungan/g,'sam');
        text=text.replace(/perpipaan/g,'ppp');
        text=text.replace(/bukan/g,'bkn');
        text=text.replace(/populasi/g,'pop');
        text=text.replace(/yang/g,'yg');
        text=text.replace(/target/g,'tg');
        text=text.replace(/penjualan/g,'pjl');
        text=text.replace(/penyusutan/g,'pensut');
        text=text.replace(/listrik/g,'lstrk');
        text=text.replace(/terlayani/g,'trl');
        text=text.replace(/penyediaan/g,'pny');
        text=text.replace(/untuk/g,'u');
        text=text.replace(/alokasi/g,'alk');
        text=text.replace(/dari/g,'dr');
        text=text.replace(/selama/g,'slm');
        text=text.replace(/dioperasikan/g,'diop');
        text=text.replace(/penduduk/g,'pd');
        text=text.replace(/sebelumnya/g,'sbl');
        text=text.replace(/period/g,'prd');
        text=text.replace(/total/g,'ttl');
        text=text.replace(/harga/g,'hg');
        text=text.replace(/pendapatan/g,'pdpt');
        text=text.replace(/biaya/g,'by');
        text=text.replace(/lainlain/g,'ln');
        text=text.replace(/rekening/g,'rek');


				text=text.split('._');

				if(text.length>1){
					text=text[1];
				}else{
					text=text[0];
				}
			
				var d=text.split('.');

				if(d.length>2){
					text=d[2];
				}

				return text;
			}

   		var html=$('table.table-feedback tbody').html();
      
      $('table.table-feedback tbody th').each(function(k,d){
         var dh=$(d).html();
         $(d).replaceWith('<td>'+dh+'</td>');
       });

   		html=html.replace(/<th/g,'<td').replace(/th>/g,'td>');
   		$('table.table-feedback tbody').html(html);

   		var	data_detail={};

   		$('table.table-feedback tbody tr').each(function(key,dom){
   			if(key<=4){
   				key_dom=('sat_'+getKey($(dom).find('td:nth-child(1)').text()) );
   				data_detail[key_dom]=$(dom).find('td:nth-child(2)').text();
   			}else{
   				if($(dom).find('td:nth-child(4)').text()!=''){
             console.log('----');
             console.log($(dom).find('td:nth-child(1)').text());
             console.log(getKey($(dom).find('td:nth-child(1)').text()));


   					key_dom=('sat_'+getKey($(dom).find('td:nth-child(1)').text()));
   					data_detail[key_dom+'_tahun']=parseInt($(dom).find('td:nth-child(2)').text()||0);

   					switch($(dom).find('td:nth-child(4)').text().toLowerCase()){
   						case 'kategori':
   					}

   					data_detail[key_dom+'_nilai']=getValue($(dom).find('td:nth-child(3)').text(),'s');
   					data_detail[key_dom+'_satuan']=($(dom).find('td:nth-child(4)').text());
   					data_detail[key_dom+'_ket']=($(dom).find('td:nth-child(5)').text());

   				}

   			}
   		});



         return Promise.resolve(data_detail);



   });

   var ss= await page.evaluate(()=>{
          setTimeout(function () {
             return Promise.resolve(1);
            }, 100000000000);
     });
 

   result['id']=data.id;
   result['nama_pdam']='PDAM '+(result['sat_nama_pdam'].split('/')[0].trim()) ;
   result['kode_daerah']=null;
   result['kota']=data.kota.trim();
   result['provinsi']=data.provinsi.trim();
   result['date_input']=data.date_input.trim();
   result['bulan_berlaku_awal']=getBulan(data.range_bulan_berlaku[0].trim());
   result['bulan_berlaku_ahir']=getBulan(data.range_bulan_berlaku[1].trim());
   result['tahun_laporan']=data.tahun_laporan.trim();
   result['keterangan']=data.keterangan.trim();
   result['verifikasi_provinsi']=data.verifikasi_provinsi.trim();
   result['verifikasi_regional']=data.verifikasi_regional.trim();
   result['verifikasi_satker']=data.verifikasi_satker.trim();
   result['url_sumber']=data.url.trim();
   result['kordinat']=null;
   result['open_hours']='[]';
   result['no_telpon']=null;
   result['website']=null;
   result['url_image']=null;
   result['url_direct']=null;

   await page.goto('http://nuwsp.labsgue.com/penilaian/survey/output/'+data.id,{ waitUntil: 'networkidle0' });
   const output = await page.evaluate(()=>{

     var dom=$('table.table.table-bordered.table-feedback tbody tr')[6];
     var kat_pdam=$(dom).find('td:nth-child(3)').text();

       function getValue(text,satuan){
           text=text.replace(/,/g,'').trim().replace(/ +$/, "").replace(/\r?\n|\r/g,'');
           if((text=='')||(text==null)){
             return null;
           }

           if((text=='-')){
             return null;
           }

           if(isNaN(Number(text))==false){
             return parseFloat(text);
           }else{
             text=text.toUpperCase().trim().replace(/ +$/, "");
             var val=text;
             switch(text){
               case 'YA':
               val= 1;
               break;
               case 'SEBAGIAN':
               val= 0.5;
               break;
               case 'TIDAK':
               val= 0;
               break;
               case 'SAKIT':
               val= 1;
               break;
               case 'KURANG SEHAT':
               val= 2;
               break;
              case 'POTENSI UNTUK SEHAT':
              val= 3;
               break;
              case 'POTENSIAL UNTUK SEHAT':
              val= 3;
               break;
               case 'SEHAT':
               val= 4;
               break;
               case 'SEHAT BERKELANJUTAN':
               val= 5;
               break;
               case 'RENDAH':
               val= 1;
               break;
               case 'SEDANG':
               val= 2;
               break;
               case 'TINGGI':
               val= 3;
               break;
             
             }
             return val;
           }


         }

         kat_pdam=getValue(kat_pdam,'s');

         return Promise.resolve(kat_pdam);


   });

   result['kategori_pdam']=output;

   fs.writeFileSync('./storage/file/sat_data/'+data.id+'.json',JSON.stringify(result,undefined,4));
   console.log('\x1b[35m',result['sat_nama_pdam']+' success scrap'+' ('+ (((id+1)*100)/(link_data.length)).toFixed(2)+'%)');
   console.log("\x1b[37m",'------');

if(link_data[id+1]!=undefined){
         setTimeout(function(){
             goDetail(page,(id+1));
         },1000);
 }else{
 	 await page.goto(host+'bot/sat/croning',{ waitUntil: 'networkidle0' });


	 console.log("\x1b[37m",'data selesai ter update');
	 // notif();
	 process.exit(0);
         // await browser.close();
 }


}




	function getBulan(text){
				text=text.trim().toUpperCase().replace(/ +$/, "");
   				var bulan=0;
   				switch(text){

   					case 'JANUARI':
   					bulan=1;
   					break;
   					case 'FEBRUARI':
   					bulan=2;
   					break;
   					case 'MARET':
   					bulan=3;
   					break;
   					case 'APRIL':
   					bulan=4;
   					break;
   					case 'MEI':
   					bulan=5;
   					break;
   					case 'JUNI':
   					bulan=6;
   					break;
   					case 'JULI':
   					bulan=7;
   					break;
   					case 'AGUSTUS':
   					bulan=8;
   					break;
   					case 'SEPTEMBER':
   					bulan=9;
   					break;
   					case 'OKTOBER':
   					bulan=10;
   					break;
   					case 'NOVEMBER':
   					bulan=11;
   					break;
   					case 'DESEMBER':
   					bulan=12;
   					break;
   				}

   				return bulan;
   			}

setTimeout(function(){
 const browser=login();
},2000);




