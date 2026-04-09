<?php

namespace App\Http\Controllers;

use App\Models\Tarifa;
use Illuminate\Http\Request;

class TarifaController extends Controller
{
    public function index()
    {
        $tarifas = Tarifa::orderBy('created_at', 'desc')->get();
        return view('tarifas.index', compact('tarifas'));
    }

    public function create()
    {
        return view('tarifas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'clave' => 'required|unique:tarifas',
            'tarifa_cliente' => 'required|numeric',
            'pago_chofer' => 'required|numeric'
        ]);

        Tarifa::create($request->all());

        return redirect()->route('tarifas.index')
            ->with('success', 'Tarifa creada correctamente');
    }

    public function edit(Tarifa $tarifa)
    {
        return view('tarifas.edit', compact('tarifa'));
    }

    public function update(Request $request, Tarifa $tarifa)
    {
        $request->validate([
            'clave' => 'required|unique:tarifas,clave,' . $tarifa->id,
            'tarifa_cliente' => 'required|numeric',
            'pago_chofer' => 'required|numeric'
        ]);

        $tarifa->update($request->all());

        return redirect()->route('tarifas.index')
            ->with('success', 'Tarifa actualizada correctamente');
    }

    public function destroy(Tarifa $tarifa)
    {
        $tarifa->delete();

        return redirect()->route('tarifas.index')
            ->with('success', 'Tarifa eliminada correctamente');
    }
}
