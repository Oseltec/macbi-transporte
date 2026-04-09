import 'package:app_macbi_chofer/presentation/screens/servicios_screen.dart';
import '../../data/services/auth_service.dart';
import 'package:flutter/material.dart';


class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {

  final TextEditingController emailController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  final AuthService _authService = AuthService();
  bool isLoading = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Text(
              'Macbi Chofer',
              style: TextStyle(
                fontSize: 28,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 40),

            TextField(
              controller: emailController,
              decoration: const InputDecoration(
                labelText: 'Correo',
                border: OutlineInputBorder(),
              ),
            ),

            const SizedBox(height: 20),

            TextField(
              controller: passwordController,
              obscureText: true,
              decoration: const InputDecoration(
                labelText: 'Contraseña',
                border: OutlineInputBorder(),
              ),
            ),

            const SizedBox(height: 30),

            ElevatedButton(
              onPressed: isLoading ? null : () async {
                setState(() {
                  isLoading = true;
                });

                try {
                  final user = await _authService.login(
                    emailController.text,
                    passwordController.text,
                  );

                  if (user["rol"] != "chofer") {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text("Acceso no autorizado")),
                    );
                    return;
                  }

                    Navigator.pushReplacement(
                      context,
                      MaterialPageRoute(
                       builder: (context) => ServiciosScreen(),
                      ),
                    );
                  

                } catch (e) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text("Credenciales incorrectas")),
                  );
                }

                setState(() {
                  isLoading = false;
                });
              },
              child: isLoading
                  ? const CircularProgressIndicator(color: Colors.white)
                  : const Text("Iniciar sesión"),
            ),
          ],
        ),
      ),
    );
  }
}

