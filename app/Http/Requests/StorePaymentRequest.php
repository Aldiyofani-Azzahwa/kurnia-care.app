<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'user';
    }

    public function rules(): array
    {
        return [
            'proof_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'proof_image.required' => 'Bukti pembayaran wajib diupload.',
            'proof_image.image' => 'File harus berupa gambar.',
            'proof_image.mimes' => 'Bukti pembayaran hanya boleh JPG, JPEG, atau PNG.',
            'proof_image.max' => 'Ukuran bukti pembayaran maksimal 5MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'proof_image' => 'bukti pembayaran',
        ];
    }
}