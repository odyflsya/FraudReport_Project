@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Pelaku Fraud</h1>
    <form action="{{ route('pelaku-fraud.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Kasus</label>
            <select name="kasus_id" class="form-control" required>
                <option value="">Select Kasus</option>
                @foreach($kasus as $k)
                <option value="{{ $k->id }}">{{ $k->kode_komponen }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="internal">Internal</option>
                <option value="eksternal">Eksternal</option>
            </select>
        </div>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Jenis Identitas</label>
            <select name="jenis_identitas_id" class="form-control" required>
                <option value="">Select Jenis Identitas</option>
                @foreach($jenisIdentitas as $j)
                <option value="{{ $j->id }}">{{ $j->kode }} ({{ $j->nama }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Nomor Identitas</label>
            <input type="text" name="nomor_identitas" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-control" required>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
        <div class="form-group">
            <label>Alamat Identitas</label>
            <textarea name="alamat_identitas" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>Alamat Domisili</label>
            <textarea name="alamat_domisili" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>Tempat Lahir</label>
            <input type="text" name="tempat_lahir" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Status Pelaku</label>
            <select name="status_pelaku_id" class="form-control" required>
                <option value="">Select Status Pelaku</option>
                @foreach($statusPelaku as $s)
                <option value="{{ $s->id }}">{{ $s->kode }} ({{ $s->nama }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Jabatan Saat Kejadian</label>
            <select name="jabatan_saat_kejadian_id" class="form-control" required>
                <option value="">Select Jabatan</option>
                @foreach($jabatan as $j)
                <option value="{{ $j->id }}">{{ $j->kode }} ({{ $j->nama }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Keterangan Jabatan Kejadian</label>
            <textarea name="ket_jabatan_kejadian" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label>Jabatan Saat Diketahui</label>
            <select name="jabatan_saat_diketahui_id" class="form-control" required>
                <option value="">Select Jabatan</option>
                @foreach($jabatan as $j)
                <option value="{{ $j->id }}">{{ $j->kode }} ({{ $j->nama }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Keterangan Jabatan Diketahui</label>
            <textarea name="ket_jabatan_diketahui" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>Sanksi</label>
            <textarea name="sanksi" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
@endsection