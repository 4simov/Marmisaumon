import 'dart:convert';
import 'dart:js';
import 'package:flutter/material.dart';
import 'package:flutter/rendering.dart';
import 'package:http/http.dart' as http;
import 'package:marmisaumon/Enums/roleEnum.dart';
import 'package:marmisaumon/Utils/cookieManager.dart';

import 'Utils/constants.dart';
import 'package:marmisaumon/Enums/roleEnum.dart';
import 'Utils/cookieManager.dart';

class HeaderWidget extends StatefulWidget {
  const HeaderWidget({super.key});

  @override
  _HeaderWidgetState createState() => _HeaderWidgetState();
}

class _HeaderWidgetState extends State<HeaderWidget> {
  String _hoveredItem = '';
  int role = 1;

  @protected
  @mustCallSuper
  void initState() {
    getListHeaderRole().then((value) => {
          setState(() {
            role = value;
          })
    });
  
  }

  final CookieManager _cookieManager = CookieManager();

  Future<void> _showLogoutConfirmation(BuildContext context) async {
    return showDialog<void>(
      context: context,
      barrierDismissible: false, // user must tap button for close dialog!
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Confirmation'),
          content: const Text('Voulez-vous vraiment vous déconnecter ?'),
          actions: <Widget>[
            TextButton(
              child: const Text('Annuler'),
              onPressed: () {
                Navigator.of(context).pop();
              },
            ),
            TextButton(
              child: const Text('Déconnexion'),
              onPressed: () async {
                await _cookieManager.deleteCookie('token');
                Navigator.of(context).pop();
                Navigator.pushNamedAndRemoveUntil(context, '/connexion', (Route<dynamic> route) => false);
              },
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      color: const Color(0xFF001B2E),
      padding: const EdgeInsets.all(20.0),
      child: Column(
        children: [
          const Text(
            'Marmisaumon',
            style: TextStyle(
              color: Colors.white,
              fontSize: 24.0,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 20.0),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: <Widget>[
              _buildMenuItem(context, 'Accueil', '/'),
              _buildMenuItem(context, 'Recettes', '/affichage-recette'),
              if (role >= RoleEnum.UTILISATEUR.value)
                _buildMenuItem(context, 'Créer une recette',
                    '/creation-recette'), //montrer que pour invité
              if (role >= RoleEnum.UTILISATEUR.value)
                _buildMenuItem(context, 'Mon compte',
                    '/profil'), //montrer que pour utilisateur pour utilisateur
              _buildMenuItem(context, 'Contact', '/contact'),
              if (role >= RoleEnum.UTILISATEUR.value)
                _buildMenuItem(context, 'Nouvel ingrédient',
                    'ajoutIngredient'), //montrer que pour Utilisateur ou plus
              if (role >= RoleEnum.ADMIN.value)
                _buildMenuItem(
                    context, 'Admin', '/admin'), //montrer que pour l'admin
              if (role < RoleEnum.UTILISATEUR.value)
                _buildMenuItem(
                    context, 'Inscription - Connexion', '/connexion'),
              if (role >= RoleEnum.UTILISATEUR.value)
                _buildMenuItem(context, 'Déconnexion', '', isLogout: true),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildMenuItem(BuildContext context, String title, String route, {bool isLogout = false}) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 8.0),
      child: MouseRegion(
        onEnter: (_) {
          setState(() {
            _hoveredItem = title;
          });
        },
        onExit: (_) {
          setState(() {
            _hoveredItem = '';
          });
        },
        child: InkWell(
          onTap: () {
            if (isLogout) {
              _showLogoutConfirmation(context);
            } else {
              Navigator.pushNamed(context, route);
            }
          },
          child: Text(
            title,
            style: TextStyle(
              color: _hoveredItem == title ? Colors.blueAccent : Colors.white,
              fontSize: 16.0,
            ),
          ),
        ),
      ),
    );
  }

  Future<int> getListHeaderRole() async {
    final CookieManager cookieManager = CookieManager();
    var token = await cookieManager.getCookieToken() ?? '';
    var role = 1;
    try {
      print('Sending request to API...');
      var response = await http
          .get(Uri.http(API_URL, '/utilisateurs/role'), // URL correcte
              headers: {
            "Access-Control-Allow-Origin": "*",
            'Content-Type': 'application/json',
            'Accept': '*/*',
            'Authorization': token
          });
      var js = json.decode(response.body);
      print('Response received. Status code: ${js}');
      if (response.statusCode == 200) {
        role = json.decode(response.body)['IdRole'] ?? 1;
      } else {
        role = 1;
      }
    } catch (e) {
      print('Exception: $e');
      role = 1;
    }
    return role;
  }
}
