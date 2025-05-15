<div class="container">
    <h2>Edit pegawai</h2>
    <form action="{{ route('pegawais.update', $pegawai->id) }}" method="POST">
        @csrf
        @method("PATCH")
        <div class="mb-3">
            <label for="nama" class="form-label">nama</label>
            <input type="text" class="form-control" name="nama" value="{{old("nama", $pegawai["nama"])}}">
            @error("nama")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="nip" class="form-label">nip</label>
            <input type="text" class="form-control" name="nip" value="{{old("nip", $pegawai["nip"])}}">
            @error("nip")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="email" class="form-label">email</label>
            <input type="text" class="form-control" name="email" value="{{old("email", $pegawai["email"])}}">
            @error("email")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="no_telepon" class="form-label">no_telepon</label>
            <input type="text" class="form-control" name="no_telepon" value="{{old("no_telepon", $pegawai["no_telepon"])}}">
            @error("no_telepon")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="alamat" class="form-label">alamat</label>
            <input type="text" class="form-control" name="alamat" value="{{old("alamat", $pegawai["alamat"])}}">
            @error("alamat")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="tanggal_lahir" class="form-label">tanggal_lahir</label>
            <input type="text" class="form-control" name="tanggal_lahir" value="{{old("tanggal_lahir", $pegawai["tanggal_lahir"])}}">
            @error("tanggal_lahir")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="jenis_kelamin" class="form-label">jenis_kelamin</label>
            <input type="text" class="form-control" name="jenis_kelamin" value="{{old("jenis_kelamin", $pegawai["jenis_kelamin"])}}">
            @error("jenis_kelamin")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="departemen_id" class="form-label">departemen_id</label>
            <input type="text" class="form-control" name="departemen_id" value="{{old("departemen_id", $pegawai["departemen_id"])}}">
            @error("departemen_id")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="jabatan" class="form-label">jabatan</label>
            <input type="text" class="form-control" name="jabatan" value="{{old("jabatan", $pegawai["jabatan"])}}">
            @error("jabatan")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="tanggal_bergabung" class="form-label">tanggal_bergabung</label>
            <input type="text" class="form-control" name="tanggal_bergabung" value="{{old("tanggal_bergabung", $pegawai["tanggal_bergabung"])}}">
            @error("tanggal_bergabung")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="status_kepegawaian" class="form-label">status_kepegawaian</label>
            <input type="text" class="form-control" name="status_kepegawaian" value="{{old("status_kepegawaian", $pegawai["status_kepegawaian"])}}">
            @error("status_kepegawaian")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="gaji" class="form-label">gaji</label>
            <input type="text" class="form-control" name="gaji" value="{{old("gaji", $pegawai["gaji"])}}">
            @error("gaji")
                <p>{{$message}}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>