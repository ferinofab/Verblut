<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:3|max:1000',
            'product_id' => 'required|exists:products,id',
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Выберите количество звезд',
            'comment.required' => 'Напишите текст отзыва',
            'comment.min' => 'Отзыв должен содержать минимум 3 символа',
        ];
    }
}
