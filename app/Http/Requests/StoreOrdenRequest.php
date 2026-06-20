<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdenRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo usuarios autenticados pueden crear órdenes
        return true;
    }

    public function rules(): array
    {
        return [
            // Datos del cliente
            'nombre_completo'        => 'required|string|max:150',
            'dni'                    => 'required|string|size:8',
            'telefono'               => 'required|string|max:15',
            'correo'                 => 'nullable|email|max:100',
            // Datos del equipo
            'marca'                  => 'required|string|max:50',
            'modelo'                 => 'required|string|max:100',
            'color'                  => 'required|string|max:50',
            'imei'                   => 'nullable|string|max:20',
            'contrasena_equipo'      => 'nullable|string|max:50',
            'accesorios'             => 'nullable|string',
            // Datos del servicio
            'falla_cliente'          => 'required|string',
            'diagnostico_tecnico'    => 'nullable|string',
            'tecnico_asignado'       => 'required|string|max:100',
            'precio_total'           => 'required|numeric|min:0',
            'fecha_entrega_estimada' => 'nullable|date|after:today',
        ];
    }

    // Mensajes de error en español
    public function messages(): array
    {
        return [
            'nombre_completo.required' => 'El nombre del cliente es obligatorio.',
            'dni.required'             => 'El DNI es obligatorio.',
            'dni.size'                 => 'El DNI debe tener exactamente 8 dígitos.',
            'marca.required'           => 'La marca del equipo es obligatoria.',
            'modelo.required'          => 'El modelo es obligatorio.',
            'falla_cliente.required'   => 'Debe describir la falla reportada.',
            'tecnico_asignado.required' => 'Debe asignar un técnico.',
            'precio_total.required'    => 'El precio total es obligatorio.',
            'precio_total.min'         => 'El precio no puede ser negativo.',
        ];
    }
}
