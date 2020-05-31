<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GoodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'icon_id' => 'integer|min:1|max:15',
            'group_id' => 'required|integer|min:1',
            'name' => 'required',
            'price' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Поле обязательно для заполнения.',
            'icon_id.min' => 'Не выбрана иконка.',
            'icon_id.max' => 'Не выбрана иконка.',
            'group_id.min' => 'Не выбрана группа.',
            'group_id.integer' => 'Не выбрана группа.',
            'price.numeric' => 'Цена должна быть числом, например, 23.5.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json($errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
