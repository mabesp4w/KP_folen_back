<?php

namespace App\Http\Controllers\CRUD;

use App\Models\KaryaMusik;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CrudResource;
use Illuminate\Support\Facades\Validator;

class KaryaMusikController extends Controller
{
    protected function spartaValidation($request, $id = "")
    {
        $required = "";
        if ($id == "") {
            $required = "required";
        }
        $rules = [
            'judul' => 'required|string|max:255',
            'nm_artis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'tgl_rilis' => 'nullable|date',
            'url_video' => 'nullable|url',
            'url_audio' => 'nullable|url',
            'thumbnail' => 'nullable|string',
            'kategori_ids' => 'nullable|array',
            'kategori_ids.*' => 'exists:kategori,id'
        ];

        $messages = [
            'judul.required' => 'Judul karya musik harus diisi.',
            'nm_artis.required' => 'Nama artis harus diisi.',
            'url_video.url' => 'Format URL video tidak valid.',
            'url_audio.url' => 'Format URL audio tidak valid.',
            'tgl_rilis.date' => 'Format tanggal rilis tidak valid.',
            'kategori_ids.*.exists' => 'Kategori yang dipilih tidak valid.',
        ];
        $validator = Validator::make($request, $rules, $messages);

        if ($validator->fails()) {
            $message = [
                'judul' => 'Gagal',
                'type' => 'error',
                'message' => $validator->errors()->first(),
            ];
            return response()->json($message, 400);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $genre = $request->genre;
        $sortby = $request->sortby;
        $order = $request->order;

        $data = KaryaMusik::with('kategori')
            ->where(function ($query) use ($search) {
                $query->where('judul', 'like', "%$search%")
                    ->orWhere('nm_artis', 'like', "%$search%")
                    ->orWhere('genre', 'like', "%$search%");
            })
            ->when($genre, function ($query) use ($genre) {
                $query->where('genre', $genre);
            })
            ->when($sortby, function ($query) use ($sortby, $order) {
                $query->orderBy($sortby, $order ?? 'asc');
            }, function ($query) {
                $query->latest();
            })
            ->paginate(10);

        return new CrudResource('success', 'Data Karya Musik', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data_req = $request->all();
        $validate = $this->spartaValidation($data_req);
        if ($validate) {
            return $validate;
        }

        $kategori_ids = $request->kategori_ids ?? [];
        unset($data_req['kategori_ids']);

        $karyaMusik = KaryaMusik::create($data_req);

        // Sync kategori
        if (!empty($kategori_ids)) {
            $karyaMusik->kategori()->sync($kategori_ids);
        }

        $data = KaryaMusik::with('kategori')->find($karyaMusik->id);

        return new CrudResource('success', 'Data Berhasil Disimpan', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = KaryaMusik::with(['kategori', 'dokumentasi'])->findOrFail($id);
        return new CrudResource('success', 'Detail Karya Musik', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data_req = $request->all();
        $validate = $this->spartaValidation($data_req, $id);
        if ($validate) {
            return $validate;
        }

        $kategori_ids = $request->kategori_ids ?? [];
        unset($data_req['kategori_ids']);

        $karyaMusik = KaryaMusik::findOrFail($id);
        $karyaMusik->update($data_req);

        // Sync kategori
        $karyaMusik->kategori()->sync($kategori_ids);

        $data = KaryaMusik::with('kategori')->find($id);

        return new CrudResource('success', 'Data Berhasil Diubah', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = KaryaMusik::findOrFail($id);

        // Detach semua kategori
        $data->kategori()->detach();

        // Delete data
        $data->delete();

        return new CrudResource('success', 'Data Berhasil Dihapus', $data);
    }

    /**
     * Get unique genres for filter
     */
    public function getGenres()
    {
        $genres = KaryaMusik::whereNotNull('genre')
            ->distinct()
            ->pluck('genre')
            ->sort()
            ->values();

        return new CrudResource('success', 'Data Genre', $genres);
    }
}
