<h1>Nueva Tarifa</h1>

<form method="POST" action="{{ route('tarifas.store') }}">
    @csrf

    Clave: <input type="text" name="clave"><br><br>
    Descripción: <input type="text" name="descripcion"><br><br>
    Tarifa Cliente: <input type="number" step="0.01" name="tarifa_cliente"><br><br>
    Pago Chofer: <input type="number" step="0.01" name="pago_chofer"><br><br>

    <button type="submit">Guardar</button>
</form>
