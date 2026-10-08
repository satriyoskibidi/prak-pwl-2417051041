@props(['users'])

<div class="table-responsive shadow-sm rounded">
    <table class="table table-hover table-bordered mb-0">
        <thead class="table-dark">
            <tr>
                <th class="text-center">ID</th>
                <th>Nama Pengguna</th>
                <th>NPM</th>
                <th>Kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td class="text-center">{{ $user->id }}</td>
                <td>{{ $user->nama }}</td>
                <td>{{ $user->nim }}</td>
                <td><span class="badge bg-info text-dark">{{ $user->nama_kelas }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
