<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isPatch = $this->isMethod('PATCH');
        $mediaId = $this->route('medium')?->id ?? $this->route('media')?->id;

        return [
            'title' => [
                $isPatch ? 'sometimes' : 'required',
                'string',
                'max:255',
                Rule::unique('media', 'title')->ignore($mediaId),
            ],
            'type' => [
                $isPatch ? 'sometimes' : 'required',
                'in:anime,manga',
            ],
            'genre' => [
                $isPatch ? 'sometimes' : 'required',
                'string',
                'max:100',
            ],
            'creator' => [
                'nullable',
                'string',
                'max:255',
            ],
            'release_year' => [
                'nullable',
                'integer',
                'min:1950',
                'max:2099',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul media wajib diisi.',
            'title.unique'   => 'Judul media sudah terdaftar dalam sistem FlipPlay.',
            'type.required'  => 'Jenis media (type) wajib diisi.',
            'type.in'        => 'Jenis media harus berupa anime atau manga.',
            'genre.required' => 'Genre media wajib diisi.',
            'release_year.integer' => 'Tahun rilis harus berupa angka tahun yang valid.',
        ];
    }
}
