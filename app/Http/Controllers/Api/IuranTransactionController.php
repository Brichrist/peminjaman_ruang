<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreIuranTransactionRequest;
use App\Models\IuranTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IuranTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = IuranTransaction::query();

        $query->filterByTanggal(
            $request->input('tanggal_dari'),
            $request->input('tanggal_sampai')
        );

        $query->filterByDari($request->input('dari'));

        $query->orderBy('tanggal', 'desc')->orderBy('jam', 'desc');

        $perPage = $request->input('per_page', 15);
        $data = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
            ],
        ]);
    }

    public function store(StoreIuranTransactionRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('bukti_foto')) {
            $path = $request->file('bukti_foto')->store('iuran', 'public');
            $data['bukti_foto'] = '/storage/' . $path;
        }

        $transaction = IuranTransaction::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
            'data' => $transaction,
        ], 201);
    }

    public function show(IuranTransaction $iuran)
    {
        return response()->json([
            'success' => true,
            'data' => $iuran,
        ]);
    }

    public function destroy(IuranTransaction $iuran)
    {
        if ($iuran->bukti_foto) {
            $path = str_replace('/storage/', '', $iuran->bukti_foto);
            Storage::disk('public')->delete($path);
        }

        $iuran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus',
        ]);
    }
}
