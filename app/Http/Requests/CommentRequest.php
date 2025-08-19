<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'content' => 'required|min:3|max:2000|profanity_filter',
            'parent_id' => 'nullable|exists:comments,id'
        ];
    }

    public function messages(): array
    {
        return [
            'content.profanity_filter' => 'Your comment contains inappropriate language.',
            'content.required' => 'Please enter a comment.',
            'content.min' => 'Comment must be at least 3 characters long.',
            'content.max' => 'Comment cannot exceed 2000 characters.',
            'parent_id.exists' => 'Invalid parent comment.'
        ];
    }
}
