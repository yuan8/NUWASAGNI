@extends('adminlte::dash')


@section('content_header')
    <h1>MEETING ONLINE</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-maroon">
            <div class="inner">
              <h3>TACT LG</h3>
              <p>ROOM</p>
            </div>
            <div class="icon">
              <i class="ion ion-profile"></i>
            </div>
            <a href="{{route('d.meet.v').'/TACT_LG'}}" class="small-box-footer">GO TO ROOM  <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>DSS TEAM</h3>
              <p>ROOM</p>
            </div>
            <div class="icon">
              <i class="ion ion-profile"></i>
            </div>
            <a href="{{route('d.meet.v').'/DSS_TEAM'}}" class="small-box-footer">GO TO ROOM  <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

     <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-navy">
            <div class="inner">
              <h3>CAMPURAN</h3>
              <p>ROOM</p>
            </div>
            <div class="icon">
              <i class="ion ion-profile"></i>
            </div>
            <a href="{{route('d.meet.v').'/CAMPURAN'}}" class="small-box-footer">GO TO ROOM <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>


        <!-- ./col -->
      </div>
@stop