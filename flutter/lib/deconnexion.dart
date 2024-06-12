import 'package:flutter/material.dart';
import 'Utils/cookieManager.dart';

class Deconnexion extends StatelessWidget {
  final CookieManager _cookieManager = CookieManager();

  Future<void> _logout(BuildContext context) async {
    await _cookieManager.deleteCookie('token');
    Navigator.pushNamedAndRemoveUntil(context, '/connexion', (Route<dynamic> route) => false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: ElevatedButton(
          onPressed: () => _logout(context),
          child: const Text('Se déconnecter'),
        ),
      ),
    );
  }
}
