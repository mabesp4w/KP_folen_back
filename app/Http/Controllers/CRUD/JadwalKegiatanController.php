<?php

namespace App\Http\Controllers\CRUD;

use App\Models\JadwalKegiatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CrudResource;
use Illuminate\Support\Facades\Validator;

class JadwalKegiatanController extends Controller
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
            'jenis' => 'required|in:acara,sesi_studio,konser,rekaman',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'lokasi' => 'nullable|string|max:255',
            'harga' => 'nullable|numeric|min:0',
            'maksimal_peserta' => 'nullable|integer|min:1',
            'peserta_saat_ini' => 'nullable|integer|min:0',
            'status' => 'nullable|in:terjadwal,berlangsung,selesai,dibatalkan',
            'catatan' => 'nullable|string',
            'kategori_ids' => 'nullable|array',
            'kategori_ids.*' => 'exists:kategori,id'
        ];

        $messages = [
            'judul.required' => 'Judul kegiatan harus diisi.',
            'jenis.required' => 'Jenis kegiatan harus dipilih.',
            'jenis.in' => 'Jenis kegiatan tidak valid.',
            'waktu_mulai.required' => 'Waktu mulai harus diisi.',
            'waktu_selesai.required' => 'Waktu selesai harus diisi.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'maksimal_peserta.integer' => 'Maksimal peserta harus berupa angka.',
            'maksimal_peserta.min' => 'Maksimal peserta minimal 1.',
            'peserta_saat_ini.integer' => 'Peserta saat ini harus berupa angka.',
            'status.in' => 'Status tidak valid.',
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
        $jenis = $request->jenis;
        $status = $request->status;
        $sortby = $request->sortby;
        $order = $request->order;

        $data = JadwalKegiatan::with('kategori')
            ->where(function ($query) use ($search) {
                $query->where('judul', 'like', "%$search%")
                    ->orWhere('lokasi', 'like', "%$search%")
                    ->orWhere('catatan', 'like', "%$search%");
            })
            ->when($jenis, function ($query) use ($jenis) {
                $query->where('jenis', $jenis);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($sortby, function ($query) use ($sortby, $order) {
                $query->orderBy($sortby, $order ?? 'asc');
            }, function ($query) {
                $query->orderBy('waktu_mulai', 'desc');
            })
            ->paginate(10);

        return new CrudResource('success', 'Data Jadwal Kegiatan', $data);
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

        // Set default values
        if (!isset($data_req['peserta_saat_ini'])) {
            $data_req['peserta_saat_ini'] = 0;
        }
        if (!isset($data_req['status'])) {
            $data_req['status'] = 'terjadwal';
        }

        $jadwal = JadwalKegiatan::create($data_req);

        // Sync kategori
        if (!empty($kategori_ids)) {
            $jadwal->kategori()->sync($kategori_ids);
        }

        $data = JadwalKegiatan::with('kategori')->find($jadwal->id);

        return new CrudResource('success', 'Data Berhasil Disimpan', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = JadwalKegiatan::with(['kategori', 'dokumentasi'])->findOrFail($id);
        return new CrudResource('success', 'Detail Jadwal Kegiatan', $data);
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

        $jadwal = JadwalKegiatan::findOrFail($id);
        $jadwal->update($data_req);

        // Sync kategori
        $jadwal->kategori()->sync($kategori_ids);

        $data = JadwalKegiatan::with('kategori')->find($id);

        return new CrudResource('success', 'Data Berhasil Diubah', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = JadwalKegiatan::findOrFail($id);

        // Detach semua kategori
        $data->kategori()->detach();

        // Delete data
        $data->delete();

        return new CrudResource('success', 'Data Berhasil Dihapus', $data);
    }

    /**
     * Get jadwal by date range
     */
    public function getByDateRange(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $data = JadwalKegiatan::with('kategori')
            ->whereBetween('waktu_mulai', [$start_date, $end_date])
            ->orderBy('waktu_mulai')
            ->get();

        return new CrudResource('success', 'Data Jadwal', $data);
    }

    /**
     * Update peserta count
     */
    public function updatePeserta(Request $request, $id)
    {
        $rules = [
            'peserta_saat_ini' => 'required|integer|min:0'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'judul' => 'Gagal',
                'type' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $jadwal = JadwalKegiatan::findOrFail($id);

        // Check maksimal peserta
        if ($jadwal->maksimal_peserta && $request->peserta_saat_ini > $jadwal->maksimal_peserta) {
            return response()->json([
                'judul' => 'Gagal',
                'type' => 'error',
                'message' => 'Jumlah peserta melebihi batas maksimal.',
            ], 400);
        }

        $jadwal->update(['peserta_saat_ini' => $request->peserta_saat_ini]);

        return new CrudResource('success', 'Jumlah peserta berhasil diupdate', $jadwal);
    }
}
