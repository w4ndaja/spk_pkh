<div class="form-group">
	<label>Kriteria</label>
	<input class="form-control" name="nama_kri" placeholder="Kriteria">
</div>
<div class="form-group">
	<label>Sub Kriteria</label>
	<input type="number" class="form-control" name="sub_kriteria" placeholder="Kriteria">
</div>
<div class="form-group">
	<label>Selects</label>
	<select class="form-control" name="id_ms">
		<option value="{{$data->id_ms}}">{{$data->nama}}</option>
	</select>
</div>