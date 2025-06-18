<?php

namespace App\Http\Controllers\CRUD;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CrudResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    protected function spartaValidation($request, $id = "")
    {
        $required = "";
        if ($id == "") {
            $required = "required";
        }
        $rules = [
            'nm_kategori' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:kategori,slug,' . $id,
            'deskripsi' => 'nullable|string',
            'jenis' => 'required|in:musik,acara,dokumentasi',
            'aktif' => 'nullable|boolean'
        ];

        $messages = [
            'nm_kategori.required' => 'Nama kategori harus diisi.',
            'slug.required' => 'Slug harus diisi.',
            'slug.unique' => 'Slug sudah digunakan.',
            'jenis.required' => 'Jenis kategori harus dipilih.',
            'jenis.in' => 'Jenis kategori tidak valid.',
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
        $jenis = $request->jenis;
        $aktif = $request->aktif;
        $sortby = $request->sortby;
        $order = $request->order;

        $data = Kategori::where(function ($query) use ($search) {
            $query->where('nm_kategori', 'like', "%$search%")
                ->orWhere('slug', 'like', "%$search%")
                ->orWhere('deskripsi', 'like', "%$search%");
        })
            ->when($jenis, function ($query) use ($jenis) {
                $query->where('jenis', $jenis);
            })
            ->when($aktif !== null, function ($query) use ($aktif) {
                $query->where('aktif', $aktif);
            })
            ->when($sortby, function ($query) use ($sortby, $order) {
                $query->orderBy($sortby, $order ?? 'asc');
            }, function ($query) {
                $query->orderBy('nm_kategori');
            })
            ->paginate(10);

        return new CrudResource('success', 'Data Kategori', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data_req = $request->all();

        // Generate slug if not provided
        if (empty($data_req['slug'])) {
            $data_req['slug'] = Str::slug($data_req['nm_kategori']);
        }

        // Set default aktif
        if (!isset($data_req['aktif'])) {
            $data_req['aktif'] = true;
        }

        $validate = $this->spartaValidation($data_req);
        if ($validate) {
            return $validate;
        }

        $kategori = Kategori::create($data_req);

        return new CrudResource('success', 'Data Berhasil Disimpan', $kategori);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Kategori::with(['karyaMusik', 'jadwalKegiatan'])->findOrFail($id);
        return new CrudResource('success', 'Detail Kategori', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data_req = $request->all();

        // Generate slug if not provided
        if (empty($data_req['slug'])) {
            $data_req['slug'] = Str::slug($data_req['nm_kategori']);
        }

        $validate = $this->spartaValidation($data_req, $id);
        if ($validate) {
            return $validate;
        }

        $kategori = Kategori::findOrFail($id);
        $kategori->update($data_req);

        return new CrudResource('success', 'Data Berhasil Diubah', $kategori);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Kategori::findOrFail($id);

        // Check if kategori is being used
        // $usedInKaryaMusik = $data->karyaMusik()->count();
        // $usedInJadwal = $data->jadwalKegiatan()->count();

        // if ($usedInKaryaMusik > 0 || $usedInJadwal > 0) {
        //     return response()->json([
        //         'judul' => 'Gagal',
        //         'type' => 'error',
        //         'message' => 'Kategori tidak dapat dihapus karena masih digunakan.',
        //     ], 400);
        // }

        $data->delete();

        return new CrudResource('success', 'Data Berhasil Dihapus', $data);
    }

    /**
     * Get kategori by jenis (for dropdown/select)
     */
    public function getByJenis($jenis)
    {
        $data = Kategori::where('jenis', $jenis)
            ->where('aktif', true)
            ->orderBy('nm_kategori')
            ->get(['id', 'nm_kategori', 'slug']);

        return new CrudResource('success', 'Data Kategori', $data);
    }

    /**
     * Toggle status aktif
     */
    public function toggleAktif($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->update(['aktif' => !$kategori->aktif]);

        $status = $kategori->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return new CrudResource('success', "Kategori berhasil $status", $kategori);
    }

    /**
     * Generate slug from nama kategori
     */
    public function generateSlug(Request $request)
    {
        $rules = ['nm_kategori' => 'required|string'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'judul' => 'Gagal',
                'type' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $slug = Str::slug($request->nm_kategori);

        // Check if slug exists, add number if needed
        $originalSlug = $slug;
        $counter = 1;
        while (Kategori::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return response()->json([
            'judul' => 'Berhasil',
            'type' => 'success',
            'data' => ['slug' => $slug]
        ]);
    }
}
