<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:1', 'max:500000'],
            'has_offer' => ['nullable', 'boolean'],
            'offer_price' => ['nullable', 'numeric', 'min:1', 'max:500000', 'required_if:has_offer,1', 'lt:price'],
            'category_slug' => ['nullable', 'in:electronics,fashion,home,perfumes,gifts'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }
}
