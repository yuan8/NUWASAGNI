<h5 class="text-center"><b>DAERAH TARGET NUWAS {{HP::fokus_tahun()}}</b></h5>
<table class="table-bordered table">
	<thead>
		<tr>
			<th>KODE</th>
			<th>NAMA DAERAH</th>
			<th>TIPE BANTUAN</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $d)
		<tr>
			<td>{{$d->kode_daerah}}</td>
			<td>{{$d->nama_daerah}}</td>
			<td>{{str_replace('@','',$d->jenis_bantuan)}}</td>

		</tr>
		@endforeach	
	</tbody>
</table>