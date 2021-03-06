<style type="text/css">
  .no-gutter .small-box{
    margin-bottom: 0px;
  }
</style>
<div class="row no-gutter" style="padding-left: 10px; padding-right: 10px;">
        <div class="col-lg-3 col-xs-6" >
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{number_format($daerah_nuwas,0)}}</h3>

              <p>DAERAH NUWSP</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-checkmark-circle"></i>
            </div>
            <a href="{{route('kl.index')}}" class="small-box-footer">DETAIL <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              	<h3>{{number_format($daerah_prioritas,0)}}</h3>
            	<p>NUWSP PRIORITAS {{$tahun}}</p>
            </div>
            <div class="icon">
              <i class="ion ion-ios-plus-outline"></i>
            </div>
            <a href="{{route('kl.index',['prioritas'=>HP::fokus_tahun()])}}" class="small-box-footer">DETAIL <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{number_format($jumlah_sl,0)}}</h3>

              <p>Capaian SL BPPSPAM {{$tahun}}</p>
            </div>
            <div class="icon">
              <i class="ion ion-fork-repo"></i>
            </div>
            <a href="{{route('bppspam.index')}}" class="small-box-footer">DETAIL <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <p style="padding-bottom: 7px;"><b>{{number_format($anggaran_total,3)}}</b></p>
              <p>ANGGARAN RKPD AIR MINUM {{$tahun}}</p>
              <i >Jumlah RKPD : {{number_format($total_pemda,0)}} Daerah</i>
            </div>
            <div class="icon">
              <i class="ion ion-cash"></i>
            </div>
            <a href="{{route('d.index')}}" class="small-box-footer">DETAIL <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>