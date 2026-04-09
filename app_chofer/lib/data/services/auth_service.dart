import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class AuthService {
  final Dio _dio = Dio(
    BaseOptions(
      baseUrl: "http://172.19.139.115:8000/api", // IMPORTANTE para emulador Android
      connectTimeout: const Duration(seconds: 10),
      receiveTimeout: const Duration(seconds: 10),
    ),
  );

  final FlutterSecureStorage _storage = const FlutterSecureStorage();

  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await _dio.post(
        "/login",
        data: {
          "email": email,
          "password": password,
        },
      );

      final token = response.data["token"];
      final user = response.data["user"];

      await _storage.write(key: "auth_token", value: token);

      return user;
    } catch (e) {
      throw Exception("Error de autenticación");
    }
  }
}