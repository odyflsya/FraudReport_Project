@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Pelaku Fraud</h1>
    <form action="{{ route('pelaku-fraud.update', $pelakuFraud) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Kasus</label>
            <select name="kasus_id" class="form-control" required>
                <option value="">Select Kasus</option>
                @foreach($kasus as $k)
                <option value="{{ $k->id }}" {{ $pelakuFraud->kasus_id == $k->id ? 'selected' : '' }}>{{ $k->kode_komponen }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="internal" {{ $pelakuFraud->kategori == 'internal' ? 'selected' : '' }}>Internal</option>
                <option value="eksternal" {{ $pelakuFraud->kategori == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
            </select>
        </div>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ $pelakuFraud->nama }}" required>
        </div>
        <div class="form-group">
            <label>Jenis Identitas</label>
            <select name="jenis_identitas_id" class="form-control" required>
                <option value="">Select Jenis Identitas</option>
                @foreach($jenisIdentitas as $j)
                <option value="{{ $j->id }}" {{ $pelakuFraud->jenis_identitas_id == $j->id ? 'selected' : '' }}>{{ $j->kode }} ({{ $j->nama }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Nomor Identitas</label>
            <input type="text" name="nomor_identitas" class="form-control" value="{{ $pelakuFraud->nomor_identitas }}" required>
        </div>
        <div class="form-group">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-control" required>
                <option value="L" {{ $pelakuFraud->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ $pelakuFraud->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
        <div class="form-group">
            <label>Alamat Identitas</label>
            <textarea name="alamat_identitas" class="form-control" required>{{ $pelakuFraud->alamat_identitas }}</textarea>
        </div>
        <div class="form-group">
            <label>Alamat Domisili</label>
            <textarea name="alamat_domisili" class="form-control" required>{{ $pelakuFraud->alamat_domisili }}</textarea>
        </div>
        <div class="form-group">
            <label>Tempat Lahir</label>
            <input type="text" name="tempat_lahir" class="form-control" value="{{ $pelakuFraud->tempat_lahir }}" required>
        </div>
        <div class="form-group">
            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" value="{{ $pelakuFraud->tanggal_lahir }}" required>
        </div>
        <div class="form-group">
            <label>Status Pelaku</label>
            <select name="status_pelaku_id" class="form-control" required>
                <option value="">Select Status Pelaku</option>
                @foreach($statusPelaku as $s)
                <option value="{{ $s->id }}" {{ $pelakuFraud->status_pelaku_id == $s->id ? 'selected' : '' }}>{{ $s->kode }} ({{ $s->nama }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Jabatan Saat Kejadian</label>
            <select name="jabatan_saat_kejadian_id" class="form-control" required>
                <option value="">Select Jabatan</option>
                @foreach($jabatan as $j)
                <option value="{{ $j->id }}" {{ $pelakuFraud->jabatan_saat_kejadian_id == $j->id ? 'selected' : '' }}>{{ $j->kode }} ({{ $j->nama }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Keterangan Jabatan Kejadian</label>
            <textarea name="ket_jabatan_kejadian" class="form-control">{{ $pelakuFraud->ket_jabatan_kejadian }}</textarea>
        </div>
        <div class="form-group">
            <label>Jabatan Saat Diketahui</label>
            <select name="jabatan_saat_diketahui_id" class="form-control" required>
                <option value="">Select Jabatan</option>
                @foreach($jabatan as $j)
                <option value="{{ $j->id }}" {{ $pelakuFraud->jabatan_saat_diketahui_id == $j->id ? 'selected' : '' }}>{{ $j->kode }} ({{ $j->nama }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Keterangan Jabatan Diketahui</label>
            <textarea name="ket_jabatan_diketahui" class="form-control">{{ $pelakuFraud->ket_jabatan_diketahui }}</textarea>
        </div>
        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control" required>{{ $pelakuFraud->keterangan }}</textarea>
        </div>
        <div class="form-group">
            <label>Sanksi</label>
            <textarea name="sanksi" class="form-control" required>{{ $pelakuFraud->sanksi }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection