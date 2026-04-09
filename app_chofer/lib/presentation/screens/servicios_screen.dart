import 'package:flutter/material.dart';
import '../../data/models/servicio.dart';
import '../../data/services/servicio_service.dart';
import 'detalle_servicio_screen.dart';

class ServiciosScreen extends StatefulWidget {
  const ServiciosScreen({super.key});

  @override
  State<ServiciosScreen> createState() => _ServiciosScreenState();
}

class _ServiciosScreenState extends State<ServiciosScreen> {
  final ServicioService _servicioService = ServicioService();
  List<Servicio> servicios = [];
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    cargarServicios();
  }

  Future<void> cargarServicios() async {
    try {
      final data = await _servicioService.obtenerServiciosActivos();
      setState(() {
        servicios = data;
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Error al cargar servicios')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Servicios Asignados'),
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : servicios.isEmpty
              ? const Center(child: Text('No hay servicios activos'))
              : ListView.builder(
                  itemCount: servicios.length,
                  itemBuilder: (context, index) {
                    final servicio = servicios[index];

                    return Card(
                      margin: const EdgeInsets.symmetric(
                          horizontal: 12, vertical: 6),
                      child: ListTile(
                        title: Text(servicio.cliente),
                        subtitle: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text("Fecha: ${servicio.fechaServicio}"),
                            Text("Origen: ${servicio.origen}"),
                            Text("Destino: ${servicio.destino}"),
                          ],
                        ),
                        trailing: Text(
                          servicio.estado,
                          style: const TextStyle(
                              fontWeight: FontWeight.bold),
                        ),
                        onTap: () async {
                        final actualizado = await Navigator.push(
                            context,
                            MaterialPageRoute(
                            builder: (context) =>
                                DetalleServicioScreen(servicio: servicio),
                            ),
                        );

                        if (actualizado == true) {
                            cargarServicios();
                        }
                        },
                      ),
                    );
                  },
                ),
    );
  }
}