@extends('layouts.app')

@section('content')
@php
@endphp
<div class="container">
    <h1>Pelaku Fraud List</h1>
    <a href="{{ route('pelaku-fraud.create') }}" class="btn btn-primary">Create Pelaku Fraud</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kasus</th>
                <th>Kategori</th>
                <th>Nama</th>
                <th>Jenis Identitas</th>
                <th>Nomor Identitas</th>
                <th>Jenis Kelamin</th>
                <th>Status Pelaku</th>
                <th>Jabatan Kejadian</th>
                <th>Jabatan Diketahui</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pelakuFrauds as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->kasus ? $p->kasus->kode_komponen : '' }}</td>
                <td>{{ $p->kategori }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->jenisIdentitas ? $p->jenisIdentitas->kode . ' (' . $p->jenisIdentitas->nama . ')' : '' }}</td>
                <td>{{ $p->nomor_identitas }}</td>
                <td>{{ $p->jenis_kelamin_label }}</td>
                <td>{{ $p->statusPelaku ? $p->statusPelaku->kode . ' (' . $p->statusPelaku->nama . ')' : '' }}</td>
                <td>{{ $p->jabatanSaatKejadian ? $p->jabatanSaatKejadian->kode . ' (' . $p->jabatanSaatKejadian->nama . ')' : '' }}</td>
                <td>{{ $p->jabatanSaatDiketahui ? $p->jabatanSaatDiketahui->kode . ' (' . $p->jabatanSaatDiketahui->nama . ')' : '' }}</td>
                <td>
                    <a href="{{ route('pelaku-fraud.edit', $p) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('pelaku-fraud.destroy', $p) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection