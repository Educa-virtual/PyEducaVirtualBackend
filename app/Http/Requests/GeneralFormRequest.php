<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

abstract class GeneralFormRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Devolver mensaje de error formateado
     *
     * @param  Validator  $validator
     * @throws JsonResponse Mensaje de error formateado
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = implode(', ', $validator->errors()->all());
        $response = new JsonResponse(
            [
                'status' => 'Error',
                'data' => '',
                'message' => 'Al menos uno de los campos es inválido: ' . $errors
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
        throw new ValidationException($validator, $response);
    }
}
