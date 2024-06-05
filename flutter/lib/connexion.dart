import 'package:flutter/material.dart';
import 'header.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'Utils/cookieManager.dart';

class Connexion2 extends StatefulWidget {
  @override
  _MyFormState createState() => _MyFormState();
}

class _MyFormState extends State<Connexion2> {
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  final CookieManager _cookieManager = CookieManager();

  TextEditingController emailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();
  Future<void> _submitForm() async {
    if (_formKey.currentState!.validate()) {
      _formKey.currentState!.save();

      var token = await _cookieManager.getCookieToken();


      var payload = {
        'mail': emailController.text,
        'password': passwordController.text,
      };

      try {
        print('Sending request to API...');
        var response = await http.post(
          Uri.http('localhost:8080','utilisateur/login'), // URL correcte
          body: json.encode(payload),
          headers: {
            'Content-Type': 'application/json',
            'Accept': '*/*',
            'Authorization': token ?? ''
          }
        );

        print('Response received. Status code: ${response.statusCode}');
        if (response.statusCode == 200) {
          var token = json.decode(response.body)['token'];
          await _cookieManager.saveCookieToken(token);
          print(await _cookieManager.getCookieToken());
        } else {
          print('Erreur de connexion: ${response.statusCode}');
        }
      } catch (e) {
        print('Exception: $e');
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          const HeaderWidget(),
          // Partie pour la connexion
          Card(
            child: Padding(
              padding: const EdgeInsets.all(20.0),
              child: Column(
                children: [
                  const Text(
                    'Connexion',
                    style: TextStyle(
                      fontSize: 20.0,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 20.0),
                  Form(
                    key: _formKey,
                    child: Column(
                      children: [
                        TextFormField(
                          decoration: const InputDecoration(
                            labelText: 'Pseudo',
                          ),
                          validator: (value) {
                            if (value!.isEmpty) {
                              return 'Veuillez entrer votre pseudo';
                            }
                            return null;
                          },
                          controller:emailController
                        ),
                        const SizedBox(height: 20.0),
                        TextFormField(
                          decoration: const InputDecoration(
                            labelText: 'Mot de passe',
                          ),
                          obscureText: true,
                          validator: (value) {
                            if (value!.isEmpty) {
                              return 'Veuillez entrer votre mot de passe';
                            }
                            return null;
                          },
                          controller: passwordController,
                        ),
                        const SizedBox(height: 20.0),
                        ElevatedButton(
                          onPressed: _submitForm,
                          child: const Text('Se connecter'),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
          // Partie pour l'inscription
          Card(
            child: Padding(
              padding: const EdgeInsets.all(20.0),
              child: Column(
                children: [
                  const Text(
                    'Inscription',
                    style: TextStyle(
                      fontSize: 20.0,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 20.0),
                  const Text(
                    'Vous n\'avez pas de compte sur notre site ?',
                    style: TextStyle(
                      fontSize: 16.0,
                    ),
                  ),
                  const SizedBox(height: 20.0),
                  ElevatedButton(
                    onPressed: () {
                      // Navigation vers la page d'inscription
                      Navigator.pushNamed(context, '/inscription');
                    },
                    child: const Text('Créer un compte'),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
