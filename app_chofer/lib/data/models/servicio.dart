class Servicio {
  final int id;
  final String cliente;
  final String fechaServicio;
  final String origen;
  final String destino;
  final String estado;
  final int choferId;

  Servicio({
    required this.id,
    required this.cliente,
    required this.fechaServicio,
    required this.origen,
    required this.destino,
    required this.estado,
    required this.choferId,
  });

  factory Servicio.fromJson(Map<String, dynamic> json) {
    return Servicio(
      id: json['id'],
      cliente: json['cliente'],
      fechaServicio: json['fecha_servicio'],
      origen: json['origen'],
      destino: json['destino'],
      estado: json['estado'],
      choferId: json['chofer_id'],
    );
  }
}