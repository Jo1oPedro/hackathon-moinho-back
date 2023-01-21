<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UsersFormRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:2',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'description' => 'sometimes|string',
            'user_type' => 'required|numeric|between:0,2',
            'cnpj' => 'sometimes|string|unique:institutions,cnpj',
        ];
    }

    public function messages()
    {
        return [
            '*.min' => "O campo :attribute precisa ter pelo menos :value caracteres",
            '*.required' => "O campo :attribute é obrigatorio",
            'email.email' => "O campo :attribute precisa ser um email valido",
            '*.unique' => "O :attribute já foi utilizado",
            '*.required' => "O campo senha é obrigatorio",
            '*.min' => "O campo senha precisa ter pelo menos 6 caracteres",
            '*.between' => "O campo :attribute precisa estar entre :min e :max",
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nome',
            'password' => 'senha',
            'description' => 'descrição',
            'user_type' => 'tipo de usuario',
        ];
    }
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors()->all(), 422));
    }
}
