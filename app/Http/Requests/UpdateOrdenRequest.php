<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrdenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Al editar solo actualizamos datos del servicio y equipo
            'tecnico_asignado'       => 'required|string|max:100',
            'diagnostico_tecnico'    => 'nullable|string',
            'mano_obra'              => 'required|numeric|min:0',
            'descuento'              => 'nullable|numeric|min:0',
            'motivo_descuento'       => 'nullable|string|max:150',
            'fecha_entrega_estimada' => 'nullable|date',
            'accesorios'             => 'nullable|string',
            'contrasena_equipo'      => 'nullable|string|max:50',
        ];
    }
}
