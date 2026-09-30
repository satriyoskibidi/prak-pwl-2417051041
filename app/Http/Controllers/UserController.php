<?php

namespace App\Http\Controllers;
use App\Models\Kelas;
use App\Models\UserModel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create()
    {
        return redirect()->route('user.index', ['add' => 1]);
    }

    public function index()
    {
        $search = request('search');
        $data = [
            'title' => 'List User',
            'search' => $search,
            'users' => UserModel::with('kelas')
                ->when($search, function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('id', 'like', "%{$search}%")
                            ->orWhere('nama', 'like', "%{$search}%")
                            ->orWhere('npm', 'like', "%{$search}%")
                            ->orWhereHas('kelas', function ($kelasQuery) use ($search) {
                                $kelasQuery->where('nama_kelas', 'like', "%{$search}%");
                            });
                    });
                })
                ->orderBy('id')
                ->paginate(9)
                ->withQueryString(),
            'kelas' => $this->kelasModel->getKelas(),
        ];
        return view('layouts.list_user', $data);
    }
    public $userModel;
    public $kelasModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->kelasModel = new Kelas();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'npm' => ['required', 'string', 'max:255', 'unique:user,npm'],
            'kelas_id' => ['required', 'exists:kelas,id'],
        ]);

        $this->userModel->create($validated);

        return redirect()->route('user.index')->with('success', 'Student added successfully.');
    }

    public function update(Request $request, UserModel $user)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'npm' => ['required', 'string', 'max:255', 'unique:user,npm,' . $user->id],
            'kelas_id' => ['required', 'exists:kelas,id'],
        ]);

        $user->update($validated);

        return redirect()->route('user.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(UserModel $user)
    {
        $user->delete();

        return redirect()->route('user.index')->with('success', 'Student deleted successfully.');
    }

    public function export()
    {
        $students = UserModel::with('kelas')->orderBy('id')->get();

        return response()->streamDownload(function () use ($students) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Students', 'NPM', 'Class']);

            foreach ($students as $student) {
                fputcsv($output, [$student->id, $student->nama, $student->npm, $student->kelas?->nama_kelas]);
            }

            fclose($output);
        }, 'students.csv', ['Content-Type' => 'text/csv']);
    }
}
