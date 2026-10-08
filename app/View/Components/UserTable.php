@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h2 class="fw-bold">Daftar Pengguna</h2>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="{{ route('user.create') }}" class="btn btn-primary">+ Tambah Pengguna Baru</a>
    </div>
</div>

<x-user-table :users="$users" />

@endsections