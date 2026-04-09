import 'package:flutter/material.dart';
import '../../data/models/servicio.dart';
import '../../data/services/servicio_service.dart';
import 'package:geolocator/geolocator.dart';


class DetalleServicioScreen extends StatefulWidget {
  final Servicio servicio;

  const DetalleServicioScreen({super.key, required this.servicio});

  @override
  State<DetalleServicioScreen> createState() =>
      _DetalleServicioScreenState();
}

class _DetalleServicioScreenState
    extends State<DetalleServicioScreen> {

    late String estadoActual;

    @override
    void initState() {
        super.initState();
        estadoActual = widget.servicio.estado;
        }

  final ServicioService _service = ServicioService();
  bool isLoading = false;

  Future<void> marcarLlegadaOrigen() async {
  setState(() => isLoading = true);

  try {
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text("Obteniendo ubicación..."),
        duration: Duration(seconds: 2),
      ),
    );

    final posicion = await obtenerUbicacion();

    // 1️⃣ Registrar evento GPS
    await _service.registrarEvento(
      widget.servicio.id,
      "origen_llegada",
      posicion.latitude,
      posicion.longitude,
    );

    // 2️⃣ Cambiar estado a en_origen
    await _service.marcarEnOrigen(widget.servicio.id);

    if (mounted) {
      setState(() {
        estadoActual = "en_origen";
        });
    }

  } catch (e) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text("Error: $e")),
    );
  }

  if (mounted) {
    setState(() => isLoading = false);
  }
}

    Future<void> marcarLlegadaDestino() async {
  setState(() => isLoading = true);

  try {
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text("Obteniendo ubicación..."),
        duration: Duration(seconds: 2),
      ),
    );

    final posicion = await obtenerUbicacion();

    await _service.registrarEvento(
      widget.servicio.id,
      "destino_llegada",
      posicion.latitude,
      posicion.longitude,
    );

    await _service.finalizarServicio(widget.servicio.id);

    setState(() {
        estadoActual = "finalizado";
        });

    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Destino registrado")),
      );

      Navigator.pop(context, true);
    }

  } catch (e) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text("Error: $e")),
    );
  }

  if (mounted) {
      setState(() => isLoading = false);
    }
}
    
    Future<Position> obtenerUbicacion() async {

    bool servicioHabilitado = await Geolocator.isLocationServiceEnabled();
    if (!servicioHabilitado) {
      throw Exception("GPS desactivado");
    }

    LocationPermission permiso = await Geolocator.checkPermission();

    if (permiso == LocationPermission.denied) {
      permiso = await Geolocator.requestPermission();

      if (permiso == LocationPermission.denied) {
        throw Exception("Permiso de ubicación denegado");
      }
    }

    if (permiso == LocationPermission.deniedForever) {
      throw Exception("Permiso GPS denegado permanentemente");
    }

    // ⬇️ ahora sí obtiene la ubicación
    return await Geolocator.getCurrentPosition(
      desiredAccuracy: LocationAccuracy.high,
    );
  }

  Future<void> iniciarServicio() async {
    setState(() => isLoading = true);

    try {
      await _service.iniciarServicio(widget.servicio.id);

      setState(() {
        estadoActual = "en_proceso";
        });

      if (mounted) {
        //Navigator.pop(context, true); // regresar y refrescar
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Error al iniciar servicio')),
      );
    }

    if (mounted) {
      setState(() => isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final s = widget.servicio;
    print("isLoading: $isLoading");

    return Scaffold(
      appBar: AppBar(title: const Text("Detalle Servicio")),
      body: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text("Cliente: ${s.cliente}",
                style: const TextStyle(fontSize: 18)),
            const SizedBox(height: 10),
            Text("Fecha: ${s.fechaServicio}"),
            Text("Origen: ${s.origen}"),
            Text("Destino: ${s.destino}"),
            Text("Estado: $estadoActual"),
            const SizedBox(height: 30),

            Column(
                children: [

                    // 1️⃣ Cuando está asignado
                    if (estadoActual.trim().toLowerCase() == "asignado")
                    ElevatedButton(
                          onPressed: isLoading ? null : marcarLlegadaOrigen,
                          child: isLoading
                              ? const SizedBox(
                                  width: 20,
                                  height: 20,
                                  child: CircularProgressIndicator(
                                    strokeWidth: 2,
                                    color: Colors.white,
                                  ),
                                )
                              : const Text("Llegué al Origen"),
                        ),

                    // 2️⃣ Cuando ya llegó al origen
                    if (estadoActual.trim().toLowerCase() == "en_origen")
                     ElevatedButton(
                          onPressed: isLoading ? null : iniciarServicio,
                          child: isLoading
                              ? const SizedBox(
                                  width: 20,
                                  height: 20,
                                  child: CircularProgressIndicator(
                                    strokeWidth: 2,
                                    color: Colors.white,
                                  ),
                                )
                              : const Text("Iniciar Viaje"),
                        ),

                    // 3️⃣ Cuando ya inició el viaje
                    if (estadoActual.trim().toLowerCase() == "en_proceso")
                    ElevatedButton(
                      onPressed: isLoading ? null : marcarLlegadaDestino,
                      child: isLoading
                          ? const SizedBox(
                              width: 20,
                              height: 20,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                color: Colors.white,
                              ),
                            )
                          : const Text("Llegué al Destino"),
                    ),

                ],
                ),
           ],
        ),
      ),
    );
  }
}