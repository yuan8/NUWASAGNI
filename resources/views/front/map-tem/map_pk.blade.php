<div class="box-warning box">
	<div class="box-header">
		<h5 class="text-center"><b>{{$title}}</b></h5>
	</div>
	<div class="box-body">
		<table class="table table-bordered" id="table-pro-ind">
			<thead>
				<tr>
					<th>Aaction</th>
					<th>Program</th>
					<th>Jumlah Indikator</th>
					<th>Jumlah Kegiatan</th>
					<th>Jumlah Anggaran</th>

				</tr>

			</thead>
			<tbody>
				@foreach($data['data'] as $d)
				<tr>	
						<td><a href="{{route('pr.program.det',['id'=>$d->id])}}" target="_blank" class="btn btn-primary btn-xs">Detail</a></td>
						<td>{{$d->nama}}</td>
						<td>{{$d->jumlah_ind}}</td>
						<td>{{$d->jumlah_kegiatan}}</td>
						<td>{{$d->jumlah_anggaran}}</td>

				</tr>

				@endforeach
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#table-pro-ind").offset().top
    }, 2000);
	$('#table-pro-ind').DataTable();

</script>