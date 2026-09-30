@extends('layouts.app')
@section('content')
<div>
    <h1>Buat Pengguna Baru</h1>
    <form action="{{ route('user.store') }}" method="POST">
        @csrf
        <label for="nama">Nama: </label><br>
        <input type="text" id="nama" name="nama"><br><br>

        <label for="npm">NPM:</label><br>
        <input type="text" id="npm" name="npm"><br><br>

        <label for="kelas">Kelas: </label><br>
        <select name="kelas_id" id="kelas_id">
            <option value="" selected disabled>Pilih kelas</option>
            <option value="1">ILKOMP 2024 A</option>
            <option value="2">ILKOMP 2024 B</option>
        </select><br><br>

        <button type="submit">Submit</button>
    </form>
</div>
@endsection
