<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Cliente;
use App\Models\EstadoLog;
use App\Http\Requests\StoreOrdenRequest;
use App\Http\Requests\UpdateOrdenRequest;
use Illuminate\Http\Request;

class OrdenController extends Controller
{
    // Lista todas las órdenes con filtros opcionales
    public function index(Request $request)
    {
        $query = Orden::with('cliente')->latest();

        // Filtro por estado si se envía desde el formulario
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Búsqueda por número de orden o nombre de cliente
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where('numero_orden', 'like', "%{$buscar}%")
                ->orWhereHas(
                    'cliente',
                    fn($q) =>
                    $q->where('nombre_completo', 'like', "%{$buscar}%")
                );
        }

        $ordenes = $query->paginate(15);

        return view('ordenes.index', compact('ordenes'));
    }

    // Muestra el formulario para crear una nueva orden
    public function create()
    {
        return view('ordenes.create');
    }

    // Guarda la nueva orden en la base de datos
    public function store(Request $request)
    {
        // Validamos manualmente sin FormRequest
        $request->validate([
            'nombre_completo'        => 'required|string|max:150',
            'dni'                    => 'required|string|size:8',
            'telefono'               => 'required|string|max:15',
            'correo'                 => 'nullable|email|max:100',
            'marca'                  => 'required|string|max:50',
            'modelo'                 => 'required|string|max:100',
            'color'                  => 'required|string|max:50',
            'imei'                   => 'nullable|string|max:20',
            'contrasena_equipo'      => 'nullable|string|max:50',
            'accesorios'             => 'nullable|string',
            'falla_cliente'          => 'required|string',
            'diagnostico_tecnico'    => 'nullable|string',
            'tecnico_asignado'       => 'required|string|max:100',
            'mano_obra'           => 'required|numeric|min:0',
            'fecha_entrega_estimada' => 'nullable|date',
        ]);

        // Buscamos o creamos el cliente por su DNI
        $cliente = Cliente::updateOrCreate(
            ['dni' => $request->dni],
            [
                'nombre_completo' => $request->nombre_completo,
                'telefono'        => $request->telefono,
                'correo'          => $request->correo,
            ]
        );

        // Creamos la orden con número y token QR generados automáticamente
        $orden = Orden::create([
            'numero_orden'           => Orden::generarNumeroOrden(),
            'token_qr'               => Orden::generarTokenQr(),
            'cliente_id'             => $cliente->id,
            'tecnico_asignado'       => $request->tecnico_asignado,
            'marca'                  => $request->marca,
            'modelo'                 => $request->modelo,
            'color'                  => $request->color,
            'imei'                   => $request->imei,
            'contrasena_equipo'      => $request->contrasena_equipo,
            'accesorios'             => $request->accesorios,
            'falla_cliente'          => $request->falla_cliente,
            'diagnostico_tecnico'    => $request->diagnostico_tecnico,
            'mano_obra'             => $request->mano_obra,
            'precio_total'           => $request->mano_obra,
            'fecha_entrega_estimada' => $request->fecha_entrega_estimada,
            'estado'                 => 'recibido',
        ]);

        // Registramos el primer log de estado
        EstadoLog::create([
            'orden_id'        => $orden->id,
            'estado_anterior' => null,
            'estado_nuevo'    => 'recibido',
        ]);

        return redirect()->route('ordenes.show', $orden)
            ->with('success', "Orden {$orden->numero_orden} creada correctamente.");
    }

    // Muestra el detalle completo de una orden
    public function show(Orden $orden)
    {
        // Cargamos relaciones para evitar consultas N+1
        $orden->load('cliente', 'pagos', 'repuestos', 'estadoLogs');

        return view('ordenes.show', compact('orden'));
    }

    // Muestra el formulario de edición
    public function edit(Orden $orden)
    {
        $orden->load('cliente');
        return view('ordenes.edit', compact('orden'));
    }

    // Actualiza los datos de la orden
    public function update(UpdateOrdenRequest $request, Orden $orden)
    {
        $orden->update($request->validated());

        // Recalculamos el total por si cambió la mano de obra
        $orden->recalcularTotal();

        return redirect()->route('ordenes.show', $orden)
            ->with('success', 'Orden actualizada correctamente.');
    }

    // Cambia el estado de la orden con validaciones
    public function cambiarEstado(Request $request, Orden $orden)
    {
        $nuevoEstado = $request->estado;

        // No se puede entregar si hay saldo pendiente
        if ($nuevoEstado === 'entregado' && $orden->saldo_pendiente > 0) {
            return back()->with(
                'error',
                "No se puede entregar. Saldo pendiente: S/ {$orden->saldo_pendiente}"
            );
        }

        $estadoAnterior = $orden->estado;

        // Si pasa a "listo" guardamos la fecha exacta
        if ($nuevoEstado === 'listo') {
            $orden->fecha_listo = now();
        }

        // Si pasa a "entregado" guardamos la fecha de entrega
        if ($nuevoEstado === 'entregado') {
            $orden->fecha_entregado = now();
        }

        $orden->estado = $nuevoEstado;
        $orden->save();

        // Registramos el cambio en el historial
        EstadoLog::create([
            'orden_id'        => $orden->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => $nuevoEstado,
        ]);

        return back()->with('success', "Estado actualizado a: {$nuevoEstado}");
    }

    // Asocia un repuesto del inventario a esta orden y descuenta stock
    public function agregarRepuesto(Request $request, Orden $orden)
    {
        $request->validate([
            'repuesto_id' => 'required|exists:repuestos,id',
            'cantidad'    => 'required|integer|min:1',
        ]);

        $repuesto = \App\Models\Repuesto::find($request->repuesto_id);

        // Verificamos que haya stock suficiente
        if ($repuesto->stock_actual < $request->cantidad) {
            return back()->with(
                'error',
                "Stock insuficiente de {$repuesto->nombre}. Disponible: {$repuesto->stock_actual}"
            );
        }

        // Asociamos el repuesto a la orden con el precio actual
        $orden->repuestos()->attach($repuesto->id, [
            'cantidad'        => $request->cantidad,
            'precio_unitario' => $repuesto->precio_venta,
        ]);

        // Descontamos del inventario
        $repuesto->decrement('stock_actual', $request->cantidad);

        // Recalculamos el total de la orden
        $orden->recalcularTotal();

        return back()->with('success', "Repuesto agregado: {$repuesto->nombre}");
    }

    // Quita un repuesto de la orden y devuelve el stock
    public function quitarRepuesto(Orden $orden, \App\Models\Repuesto $repuesto)
    {
        // Recuperamos cuánto se había usado para devolver el stock
        $pivot = $orden->repuestos()->where('repuesto_id', $repuesto->id)->first()->pivot;

        $repuesto->increment('stock_actual', $pivot->cantidad);

        $orden->repuestos()->detach($repuesto->id);
        $orden->recalcularTotal();

        return back()->with('success', 'Repuesto removido de la orden.');
    }

    // Elimina una orden
    public function destroy(Orden $orden)
    {
        $orden->delete();
        return redirect()->route('ordenes.index')
            ->with('success', 'Orden eliminada.');
    }
}
