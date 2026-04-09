<x-app-layout>
<div class="flex">

    <!-- MENÚ LATERAL -->
    <div class="w-1/4 bg-gray-100 p-4">
        <h3 class="font-bold mb-4">Panel Chofer</h3>

        <ul class="space-y-2">
            <li>
                <a href="#activos" class="text-blue-600">Servicios Activos</a>
            </li>
            <li>
                <a href="#realizados" class="text-green-600">Servicios Realizados</a>
            </li>
        </ul>
    </div>

    <!-- CONTENIDO -->
    <div class="w-3/4 p-6">

        <!-- ACTIVOS -->
        <section id="activos">
            <h2 class="text-xl font-bold mb-4">Servicios Activos</h2>

            @forelse($activos as $servicio)
                <div class="border p-3 mb-2 rounded">
                    <p><strong>Fecha:</strong> {{ $servicio->fecha_servicio }}</p>
                    <p><strong>Cliente:</strong> {{ $servicio->cliente }}</p>
                    <p><strong>Pago:</strong> ${{ number_format($servicio->pago_chofer_snapshot, 2) }}</p>

                    <!-- BOTÓN FINALIZAR -->
                    <form method="POST"
                        action="{{ route('servicios.finalizar', $servicio->id) }}"
                        class="mt-2">
                        @csrf
                        @method('PATCH')

                        <button class="bg-green-600 text-white px-3 py-1 rounded">
                            Finalizar Servicio
                        </button>
                    </form>

                </div>
            @empty
                <p>No tienes servicios activos.</p>
            @endforelse

        </section>

        <hr class="my-8">

        <!-- REALIZADOS -->
        <section id="realizados">
            <h2 class="text-xl font-bold mb-4">Servicios Realizados</h2>

            <!-- Filtro por semana -->
                <form method="GET" class="mb-4">
                <select name="inicio" onchange="this.form.submit()" class="border rounded p-2">
                    <option value="">Selecciona semana</option>

                    @foreach($semanas as $semana)
                        <option value="{{ $semana['inicio'] }}"
                            {{ request('inicio') == $semana['inicio'] ? 'selected' : '' }}>
                            {{ $semana['label'] }}
                        </option>
                    @endforeach
                </select>

                <input type="hidden" name="fin" 
                    value="{{ request('fin') }}">
            </form>

            <div class="mb-4 font-bold text-lg">
                Total Semana: ${{ number_format($totalSemana, 2) }}
            </div>

            @forelse($finalizados as $servicio)
                <div class="border p-3 mb-2 rounded bg-green-50">
                    <p><strong>Fecha:</strong> {{ $servicio->fecha_servicio }}</p>
                    <p><strong>Cliente:</strong> {{ $servicio->cliente }}</p>
                    <p><strong>Pago:</strong> ${{ number_format($servicio->pago_chofer_snapshot, 2) }}</p>
                </div>
            @empty
                <p>No hay servicios realizados para esta semana.</p>
            @endforelse



            <script>
            document.querySelector('select[name="inicio"]')
                .addEventListener('change', function() {
                    const semanas = @json($semanas);
                    const seleccionada = semanas.find(s => s.inicio === this.value);
                    if (seleccionada) {
                        const inputFin = document.createElement('input');
                        inputFin.type = 'hidden';
                        inputFin.name = 'fin';
                        inputFin.value = seleccionada.fin;
                        this.form.appendChild(inputFin);
                    }
                });
            </script>



        </section>

    </div>
</div>
</x-app-layout>
