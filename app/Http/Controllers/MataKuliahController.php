<?php

namespace App\Http\Controllers;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index()
    {
        $search = request('search');
        $data = [
            'title' => 'List Mata Kuliah',
            'search' => $search,
            'mks' => MataKuliah::query()
                ->when($search, function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('id', 'like', "%{$search}%")
                            ->orWhere('nama_mk', 'like', "%{$search}%")
                            ->orWhere('sks', 'like', "%{$search}%");
                    });
                })
                ->orderBy('nama_mk')
                ->paginate(9)
                ->withQueryString(),
        ];
        return view('list_mk', $data);
    }

    public function create()
    {
        return redirect()->route('matakuliah.index', ['add' => 1]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mk' => ['required', 'string', 'max:255'],
            'sks' => ['required', 'integer', 'min:1'],
        ]);

        MataKuliah::create($validated);

        return redirect()->route('matakuliah.index')->with('success', 'Subject added successfully.');
    }

    public function update(Request $request, MataKuliah $mk)
    {
        $validated = $request->validate([
            'nama_mk' => ['required', 'string', 'max:255'],
            'sks' => ['required', 'integer', 'min:1'],
        ]);

        $mk->update($validated);

        return redirect()->route('matakuliah.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(MataKuliah $mk)
    {
        $mk->delete();

        return redirect()->route('matakuliah.index')->with('success', 'Subject deleted successfully.');
    }
}
