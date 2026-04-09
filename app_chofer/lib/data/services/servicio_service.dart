import 'package:dio/dio.dart';
import '../../core/api_client.dart';
import '../models/servicio.dart';

class ServicioService {

  Future<List<Servicio>> obtenerServiciosActivos() async {
    try {
      final response = await ApiClient().dio.get('/mis-servicios');

      print("STATUS: ${response.statusCode}");
      print("DATA: ${response.data}");

      final data = response.data['activos'] as List;

      return data.map((json) => Servicio.fromJson(json)).toList();

    } catch (e) {
      print("ERROR SERVICIOS: $e");
      rethrow;
    }
  }

  Future<void> iniciarServicio(int id) async {
    try {
      final response =
          await ApiClient().dio.patch('/servicios/$id/iniciar');

      print("INICIAR STATUS: ${response.statusCode}");
      print("INICIAR DATA: ${response.data}");

    } catch (e) {
      print("ERROR INICIAR: $e");
      rethrow;
    }
  }

  Future<void> registrarEvento(
  int servicioId,
  String tipoEvento,
  double lat,
  double lng,
) async {
  await ApiClient().dio.post(
    '/servicios/$servicioId/evento',
    data: {
      "tipo_evento": tipoEvento,
      "latitud": lat,
      "longitud": lng,
    },
  );
}

Future<void> finalizarServicio(int id) async {
  await ApiClient().dio.patch('/servicios/$id/finalizar');
}

Future<void> marcarEnOrigen(int id) async {
  await ApiClient().dio.patch('/servicios/$id/en-origen');
}
}