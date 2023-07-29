<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id'=>auth()->user()->id
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        
        return [
            'title'=>['required','string',Rule::notIn(['\'','"',';'])],
            'data'=>['required','string'],
            'status'=>['required','string',Rule::in(['draft','published'])],
            'image'=>['file','required'],
            'user_id'=>['required','exists:users,id'],

            //
        ];
    }
}
