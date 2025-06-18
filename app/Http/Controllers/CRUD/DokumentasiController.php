<?php

namespace App\Http\Controllers\CRUD;

use App\Models\Dokumentasi;
use App\Models\KaryaMusik;
use App\Models\JadwalKegiatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CrudResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    protected function spartaValidation($request, $id = "")
    {
        $required = "";
        if ($id == "") {
            $required = "required";
        }
        $rules = [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis' => 'required|in:foto,video',
            'file_dokumentasi' => $id ? 'nullable' : 'required|string',
            'url_embed' => 'nullable|url',
            'thumbnail' => 'nullable|string',
            'tgl_dokumentasi' => 'nullable|date',
            'lokasi' => 'nullable|string|max:255',
            'terdokumentasi_type' => 'nullable|in:App\\Models\\KaryaMusik,App\\Models\\JadwalKegiatan',
            'terdokumentasi_id' => 'nullable|integer',
        ];

        // Validate terdokumentasi relationship
        if ($request['terdokumentasi_type'] && $request['terdokumentasi_id']) {
            if ($request['terdokumentasi_type'] === 'App\\Models\\KaryaMusik') {
                $rules['terdokumentasi_id'] .= '|exists:karya_musik,id';
            } elseif ($request['terdokumentasi_type'] === 'App\\Models\\JadwalKegiatan') {
                $rules['terdokumentasi_id'] .= '|exists:jadwal_kegiatan,id';
            }
        }

        $messages = [
            'judul.required' => 'Judul dokumentasi harus diisi.',
            'jenis.required' => 'Jenis dokumentasi harus dipilih.',
            'jenis.in' => 'Jenis dokumentasi tidak valid.',
            'file_dokumentasi.required' => 'File dokumentasi harus diisi.',
            'url_embed.url' => 'Format URL embed tidak valid.',
            'tgl_dokumentasi.date' => 'Format tanggal dokumentasi tidak valid.',
            'terdokumentasi_type.in' => 'Tipe relasi tidak valid.',
            'terdokumentasi_id.exists' => 'Data relasi tidak ditemukan.',
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
        $terdokumentasi_type = $request->terdokumentasi_type;
        $sortby = $request->sortby;
        $order = $request->order;

        $data = Dokumentasi::with('terdokumentasi')
            ->where(function ($query) use ($search) {
                $query->where('judul', 'like', "%$search%")
                    ->orWhere('lokasi', 'like', "%$search%")
                    ->orWhere('deskripsi', 'like', "%$search%");
            })
            ->when($jenis, function ($query) use ($jenis) {
                $query->where('jenis', $jenis);
            })
            ->when($terdokumentasi_type, function ($query) use ($terdokumentasi_type) {
                $query->where('terdokumentasi_type', $terdokumentasi_type);
            })
            ->when($sortby, function ($query) use ($sortby, $order) {
                $query->orderBy($sortby, $order ?? 'asc');
            }, function ($query) {
                $query->latest();
            })
            ->paginate(10);

        return new CrudResource('success', 'Data Dokumentasi', $data);
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

        $dokumentasi = Dokumentasi::create($data_req);
        $data = Dokumentasi::with('terdokumentasi')->find($dokumentasi->id);

        return new CrudResource('success', 'Data Berhasil Disimpan', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Dokumentasi::with('terdokumentasi')->findOrFail($id);
        return new CrudResource('success', 'Detail Dokumentasi', $data);
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

        $dokumentasi = Dokumentasi::findOrFail($id);
        $dokumentasi->update($data_req);
        $data = Dokumentasi::with('terdokumentasi')->find($id);

        return new CrudResource('success', 'Data Berhasil Diubah', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Dokumentasi::findOrFail($id);

        // Delete file if exists
        if ($data->file_dokumentasi && Storage::exists($data->file_dokumentasi)) {
            Storage::delete($data->file_dokumentasi);
        }
        if ($data->thumbnail && Storage::exists($data->thumbnail)) {
            Storage::delete($data->thumbnail);
        }

        $data->delete();

        return new CrudResource('success', 'Data Berhasil Dihapus', $data);
    }

    /**
     * Upload file dokumentasi
     */
    public function uploadFile(Request $request)
    {
        $rules = [
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,avi,mov,wmv|max:50000', // 50MB
            'jenis' => 'required|in:foto,video'
        ];

        $validator = Validator::make($request->all(), $rules, [
            'file.required' => 'File harus dipilih.',
            'file.mimes' => 'Format file tidak didukung.',
            'file.max' => 'Ukuran file maksimal 50MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'judul' => 'Gagal',
                'type' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $file = $request->file('file');
        $jenis = $request->jenis;

        // Store file
        $path = $file->store("dokumentasi/$jenis", 'public');

        return response()->json([
            'judul' => 'Berhasil',
            'type' => 'success',
            'message' => 'File berhasil diupload.',
            'data' => [
                'path' => $path,
                'url' => Storage::url($path),
                'filename' => $file->getClientOriginalName()
            ]
        ]);
    }

    /**
     * Get dokumentasi by related model
     */
    public function getByRelated(Request $request)
    {
        $type = $request->type; // 'karya_musik' or 'jadwal_kegiatan'
        $id = $request->id;

        $model_type = $type === 'karya_musik' ? 'App\\Models\\KaryaMusik' : 'App\\Models\\JadwalKegiatan';

        $data = Dokumentasi::where('terdokumentasi_type', $model_type)
            ->where('terdokumentasi_id', $id)
            ->orderBy('tgl_dokumentasi', 'desc')
            ->get();

        return new CrudResource('success', 'Data Dokumentasi', $data);
    }

    /**
     * Get gallery (foto only)
     */
    public function getGallery(Request $request)
    {
        $search = $request->search;

        $data = Dokumentasi::with('terdokumentasi')
            ->where('jenis', 'foto')
            ->when($search, function ($query) use ($search) {
                $query->where('judul', 'like', "%$search%");
            })
            ->orderBy('tgl_dokumentasi', 'desc')
            ->paginate(20);

        return new CrudResource('success', 'Galeri Foto', $data);
    }
}
