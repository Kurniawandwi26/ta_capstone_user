<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Doctor; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Import untuk PDF

class AntrianController extends Controller
{
    /**
     * Dashboard antrian user
     */
    public function index(Request $request)
    {
        // Antrian terbaru user - DENGAN RELATIONSHIP
        $antrianTerbaru = Antrian::with(['user', 'doctor'])
                                ->where('user_id', Auth::id())
                                ->latest()
                                ->first();

        return view('antrian.index', compact('antrianTerbaru'));
    }

    /**
     * Form buat antrian baru
     */
    public function create()
    {
        // Cek apakah user sudah punya antrian aktif
        $existingAntrian = Antrian::where('user_id', Auth::id())
                                 ->whereIn('status', ['menunggu', 'dipanggil'])
                                 ->first();

        if ($existingAntrian) {
            return redirect()->route('antrian.index')->withErrors([
                'error' => 'Anda masih memiliki antrian aktif. Harap batalkan atau selesaikan antrian tersebut terlebih dahulu.'
            ]);
        }

        $doctors = Doctor::all();
        $poli = DB::table('poli')->get();
        
        return view('antrian.ambil', compact('doctors', 'poli'));
    }

    /**
     * Simpan antrian baru
     */
    public function store(Request $request)
    {
        // Validasi input - SEDERHANA, tanpa keluhan
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'poli' => 'required|string',
            'doctor_id' => 'required|exists:doctors,doctor_id',
            'tanggal' => 'required|date|after_or_equal:today',
        ], [
            'name.required' => 'Nama harus diisi',
            'phone.required' => 'Nomor telepon harus diisi',
            'gender.required' => 'Jenis kelamin harus dipilih',
            'poli.required' => 'Poli harus dipilih',
            'doctor_id.required' => 'Dokter harus dipilih',
            'tanggal.required' => 'Tanggal harus diisi',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh kurang dari hari ini',
        ]);

        try {
            // Cek apakah user sudah punya antrian di tanggal yang sama
            $existingAntrian = Antrian::where('user_id', Auth::id())
                                     ->where('tanggal', $request->tanggal)
                                     ->where('status', 'menunggu')
                                     ->first();

            if ($existingAntrian) {
                return back()->withErrors([
                    'tanggal' => 'Anda sudah memiliki antrian pada tanggal tersebut.'
                ])->withInput();
            }

            // Generate nomor antrian dan urutan
            $noAntrian = Antrian::generateNoAntrian($request->poli, $request->tanggal);
            $urutan = Antrian::generateUrutan($request->poli, $request->tanggal);

            // Buat antrian baru - SEDERHANA, hanya field yang diperlukan
            Antrian::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'no_antrian' => $noAntrian,
                'urutan' => $urutan,
                'poli' => $request->poli,
                'doctor_id' => $request->doctor_id,
                'tanggal' => $request->tanggal,
                'status' => 'menunggu',
            ]);

            return redirect()->route('antrian.index')->with('success', 
                'Antrian berhasil dibuat! Nomor antrian Anda: ' . $noAntrian
            );

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat membuat antrian. Silakan coba lagi.'
            ])->withInput();
        }
    }

    /**
     * Print tiket antrian (HTML View)
     */
    public function print($id)
    {
        $antrian = Antrian::with(['user', 'doctor'])->findOrFail($id);
        
        // Pastikan user hanya bisa print antrian mereka sendiri
        if (Auth::id() !== $antrian->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('antrian.print', compact('antrian'));
    }

    /**
     * Download tiket antrian sebagai PDF
     */
    public function downloadPdf($id)
    {
        $antrian = Antrian::with(['user', 'doctor'])->findOrFail($id);
        
        // Pastikan user hanya bisa download antrian mereka sendiri
        if (Auth::id() !== $antrian->user_id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Generate PDF dengan template khusus
            $pdf = Pdf::loadView('antrian.pdf', compact('antrian'))
                      ->setPaper([0, 0, 283.46, 566.93], 'portrait') // Ukuran setengah A4
                      ->setOptions([
                          'dpi' => 150,
                          'defaultFont' => 'sans-serif',
                          'isHtml5ParserEnabled' => true,
                          'isRemoteEnabled' => false, // Keamanan
                      ]);

            $filename = 'tiket-antrian-' . $antrian->no_antrian . '-' . date('Ymd-His') . '.pdf';
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal membuat PDF. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Lihat detail antrian
     */
    public function show($id)
    {
        $antrian = Antrian::with(['user', 'doctor'])->findOrFail($id);
        
        // Pastikan user hanya bisa lihat antrian mereka sendiri
        if (Auth::id() !== $antrian->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('antrian.show', compact('antrian'));
    }

    /**
     * Form edit antrian
     */
    public function edit($id)
    {
        $antrian = Antrian::with(['user', 'doctor'])->findOrFail($id); // Load relationship
        
        // Pastikan user hanya bisa edit antrian mereka sendiri
        if (Auth::id() !== $antrian->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Check apakah masih bisa diedit
        if (!$antrian->canEdit()) {
            return redirect()->route('antrian.index')
                           ->withErrors(['error' => 'Antrian tidak dapat diedit karena sudah melewati batas waktu atau status tidak memungkinkan.']);
        }

        $doctors = Doctor::all();
        $poli = DB::table('poli')->get();
        
        return view('antrian.edit', compact('antrian', 'doctors', 'poli'));
    }

    /**
     * Update antrian
     */
    public function update(Request $request, $id)
    {
        $antrian = Antrian::findOrFail($id);
        
        // Pastikan user hanya bisa update antrian mereka sendiri
        if (Auth::id() !== $antrian->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Check apakah masih bisa diedit
        if (!$antrian->canEdit()) {
            return redirect()->route('antrian.index')
                           ->withErrors(['error' => 'Antrian tidak dapat diedit karena sudah melewati batas waktu atau status tidak memungkinkan.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'poli' => 'required|string',
            'doctor_id' => 'required|exists:doctors,doctor_id',
            'tanggal' => 'required|date|after_or_equal:today',
        ], [
            'name.required' => 'Nama harus diisi',
            'phone.required' => 'Nomor telepon harus diisi',
            'gender.required' => 'Jenis kelamin harus dipilih',
            'poli.required' => 'Poli harus dipilih',
            'doctor_id.required' => 'Dokter harus dipilih',
            'tanggal.required' => 'Tanggal harus diisi',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh kurang dari hari ini',
        ]);

        try {
            // Cek duplikat tanggal jika user mengubah tanggal
            if ($antrian->tanggal->format('Y-m-d') !== $request->tanggal) {
                $existingAntrian = Antrian::where('user_id', Auth::id())
                                         ->where('tanggal', $request->tanggal)
                                         ->where('status', 'menunggu')
                                         ->where('id', '!=', $id) // Exclude current antrian
                                         ->first();

                if ($existingAntrian) {
                    return back()->withErrors([
                        'tanggal' => 'Anda sudah memiliki antrian lain pada tanggal tersebut.'
                    ])->withInput();
                }
            }

            $updateData = [
                'name' => $request->name,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'poli' => $request->poli,
                'doctor_id' => $request->doctor_id,
                'tanggal' => $request->tanggal,
            ];

            // Jika poli atau tanggal berubah, generate ulang nomor antrian
            if ($antrian->poli !== $request->poli || $antrian->tanggal->format('Y-m-d') !== $request->tanggal) {
                $updateData['no_antrian'] = Antrian::generateNoAntrian($request->poli, $request->tanggal);
                $updateData['urutan'] = Antrian::generateUrutan($request->poli, $request->tanggal);
            }

            $antrian->update($updateData);

            return redirect()->route('antrian.index')->with('success', 'Antrian berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat memperbarui antrian: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Batalkan antrian
     */
    public function destroy($id)
    {
        $antrian = Antrian::findOrFail($id);
        
        // Pastikan user hanya bisa hapus antrian mereka sendiri
        if (Auth::id() !== $antrian->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Check apakah masih bisa dibatalkan
        if (!$antrian->canCancel()) {
            return redirect()->route('antrian.index')
                           ->withErrors(['error' => 'Antrian tidak dapat dibatalkan karena sudah melewati batas waktu atau status tidak memungkinkan.']);
        }

        try {
            // Update status menjadi dibatalkan (soft cancel, tidak delete)
            $antrian->update(['status' => 'dibatalkan']);
            
            return redirect()->route('antrian.index')->with('success', 'Antrian berhasil dibatalkan!');
            
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat membatalkan antrian: ' . $e->getMessage()
            ]);
        }
    }
}