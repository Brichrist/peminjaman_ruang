<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreIuranTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'dari' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'nominal' => 'required|numeric|min:0',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.required' => 'Field tanggal wajib diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'jam.required' => 'Field jam wajib diisi',
            'jam.date_format' => 'Format jam harus HH:MM',
            'dari.required' => 'Field dari wajib diisi',
            'dari.max' => 'Field dari maksimal 255 karakter',
            'nominal.required' => 'Field nominal wajib diisi',
            'nominal.numeric' => 'Nominal harus berupa angka',
            'nominal.min' => 'Nominal tidak boleh negatif',
            'bukti_foto.image' => 'File harus berupa gambar',
            'bukti_foto.mimes' => 'Format gambar harus jpg, jpeg, atau png',
            'bukti_foto.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors(),
        ], 422));
    }
}
