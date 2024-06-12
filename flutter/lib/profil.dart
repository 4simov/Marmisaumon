import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:marmisaumon/Utils/constants.dart';
import 'package:http/http.dart' as http;
import 'package:marmisaumon/Utils/cookieManager.dart';
import 'header.dart';
import 'package:shared_preferences/shared_preferences.dart';

void main() {
  runApp(const MaterialApp(
    home: ProfilePage(),
  ));
}

class ProfilePage extends StatefulWidget {
  const ProfilePage({super.key});

  @override
  _ProfilePageState createState() => _ProfilePageState();
}

class _ProfilePageState extends State<ProfilePage> {
  //Permet de savoir quand que on est en mode editable ou pas
  bool _isEditing = false;
  //Récupere les informations de l'utilisateur depuis la base de données !!!!!!
  // final TextEditingController _nameController =
  //     TextEditingController(text: 'John Doe');
  // final TextEditingController _emailController =
  //     TextEditingController(text: 'johndoe@example.com');
  // final TextEditingController _cityController =
  //     TextEditingController(text: 'New York');
  TextEditingController _nameController = TextEditingController();
  TextEditingController _emailController = TextEditingController();

  void initState() {
    super.initState();
    _loadUserData();
  }

  Future<void> _loadUserData() async {
    final CookieManager cookieManager = CookieManager();
    SharedPreferences prefs = await SharedPreferences.getInstance();
    var token = await cookieManager.getCookieToken() ?? '';

    try {
      print('Sending request to API...');
      var response = await http
          .get(Uri.http(API_URL, '/utilisateurs/infos'), // URL correcte
              headers: {
            "Access-Control-Allow-Origin": "*",
            'Content-Type': 'application/json',
            'Accept': '*/*',
            'Authorization': token
          });
      print('Response received. Status code: ${response.statusCode}');
      print('Response body: ${response.body}');
      print(json.decode(response.body)['Pseudo']);
      if (response.statusCode == 200) {
        setState(() {
          _nameController.text = json.decode(response.body)['Pseudo'] ?? '';
          _emailController.text = json.decode(response.body)["Mail"] ?? '';
        });
      } else {
        print('Erreur de récupération info raté');
      }
    } catch (e) {
      print('Exception: $e');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const HeaderWidget(), // Affichez le header ici
            Padding(
              padding: const EdgeInsets.all(20.0),
              child: Card(
                elevation: 5,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(15.0),
                ),
                child: Padding(
                  padding: const EdgeInsets.all(20.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const SizedBox(height: 20.0),
                      //Condition pour savoir si on est en mode editable pour afficher le nom ou pas
                      _isEditing
                          ? TextField(
                              controller: _nameController,
                              decoration: const InputDecoration(
                                labelText: 'Nom',
                              ),
                            )
                          : Text(
                              'Nom: ${_nameController.text}',
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                                fontSize: 18.0,
                              ),
                            ),
                      const SizedBox(height: 8.0),
                      //Condition pour savoir si on est en mode editable pour afficher l'email ou pas
                      _isEditing
                          ? TextField(
                              controller: _emailController,
                              decoration: const InputDecoration(
                                labelText: 'Email',
                              ),
                            )
                          : Text(
                              'Email: ${_emailController.text}',
                              style: const TextStyle(fontSize: 16.0),
                            ),
                      const SizedBox(height: 8.0),
                      //Condition pour savoir si on est en mode editable pour afficher la ville ou pas
                      const SizedBox(height: 20.0),
                      /*Center(
                        child: ElevatedButton(
                          onPressed: () {
                            setState(() {
                              _isEditing = !_isEditing;
                            });
                          },
                          child: Text(_isEditing
                              ? 'Enregistrer'
                              : 'Modifier le profil'),
                        ),
                      ),*/
                    ],
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
