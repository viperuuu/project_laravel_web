<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMediaToCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'media_id' => 'required|exists:media,id',
            'status'   => 'nullable|string|in:plan_to_consume,watching,reading,completed,on_hold,dropped',
        ];
    }

    public function messages(): array
    {
        return [
            'media_id.required' => 'ID media wajib disertakan.',
            'media_id.exists'   => 'Media yang dipilih tidak ditemukan dalam katalog FlipPlay.',
            'status.in'         => 'Status harus salah satu dari: plan_to_consume, watching, reading, completed, on_hold, dropped.',
        ];
    }
}
