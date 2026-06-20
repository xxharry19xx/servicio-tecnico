<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'monto'       => 'required|numeric|min:0.01',
            'metodo_pago' => 'required|in:efectivo,yape,plin,transferencia',
            'nota'        => 'nullable|string|max:150',
        ];
    }

    public function messages(): array
    {
        return [
            'monto.required'       => 'El monto es obligatorio.',
            'monto.min'            => 'El monto debe ser mayor a cero.',
            'metodo_pago.required' => 'Selecciona un método de pago.',
            'metodo_pago.in'       => 'Método de pago no válido.',
        ];
    }
}
