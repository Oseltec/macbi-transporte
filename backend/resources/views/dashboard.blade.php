<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-xl font-bold mb-4">Panel Administrativo</h2>

                    <div class="space-y-3">
                        <a href="{{ route('tarifas.index') }}" 
                        class="block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Gestionar Tarifas
                        </a>

                        <a href="#" 
                        class="block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Gestionar Servicios (próximo)
                        </a>

                        <a href="#" 
                        class="block bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">
                            Gestionar Choferes (próximo)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
