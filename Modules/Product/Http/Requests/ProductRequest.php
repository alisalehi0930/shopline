<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Product\Enums\ProductStatusEnum;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'first_media_id'  => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'second_media_id' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|string|min:3|max:255|unique:products,title',
            'price' => 'required|numeric',
            'count' => 'required|numeric',
            'type' => 'required|string|max:255',
            'short_description' => 'required|string|min:3',
            'body' => 'required|string|min:3',
            'status' => ['required', 'string', 'max:255', new Enum(ProductStatusEnum::class)],
            'categories' => 'required|array',
        ];

        if (request()->method === 'PATCH') {
            $rules['first_media_id'] = 'nullable|file|mimes:jpeg,png,jpg|max:2048';
            $rules['second_media_id'] = 'nullable|file|mimes:jpeg,png,jpg|max:2048';
            $rules['title'] = 'required|string|min:3|max:255|unique:products,title,' . request()->id;
        }

        return $rules;
    }
}
