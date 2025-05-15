<div class="container">
    <h2>Edit departemen</h2>
    <form action="{{ route('departemens.update', $departemen->id) }}" method="POST">
        @csrf
        @method("PATCH")
        <div class="mb-3">
            <label for="nama_departemen" class="form-label">nama_departemen</label>
            <input type="text" class="form-control" name="nama_departemen" value="{{old("nama_departemen", $departemen["nama_departemen"])}}">
            @error("nama_departemen")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="kode_departemen" class="form-label">kode_departemen</label>
            <input type="text" class="form-control" name="kode_departemen" value="{{old("kode_departemen", $departemen["kode_departemen"])}}">
            @error("kode_departemen")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="deskripsi" class="form-label">deskripsi</label>
            <input type="text" class="form-control" name="deskripsi" value="{{old("deskripsi", $departemen["deskripsi"])}}">
            @error("deskripsi")
                <p>{{$message}}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>