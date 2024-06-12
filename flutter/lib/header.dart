import 'package:flutter/material.dart';
import 'package:marmisaumon/Enums/roleEnum.dart';
import 'Utils/cookieManager.dart';

class HeaderWidget extends StatefulWidget {
  const HeaderWidget({super.key});

  @override
  _HeaderWidgetState createState() => _HeaderWidgetState();
}

class _HeaderWidgetState extends State<HeaderWidget> {
  String _hoveredItem = '';
  RoleEnum role = RoleEnum.UTILISATEUR;
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
              if (role.value >= RoleEnum.UTILISATEUR.value)
                _buildMenuItem(context, 'Créer une recette', '/creation-recette'),
              if (role.value >= RoleEnum.UTILISATEUR.value)
                _buildMenuItem(context, 'Mon compte', '/profil'),
              _buildMenuItem(context, 'Contact', '/contact'),
              if (role.value >= RoleEnum.UTILISATEUR.value)
                _buildMenuItem(context, 'Nouvel ingrédient', 'ajoutIngredient'),
              if (role.value >= RoleEnum.ADMIN.value)
                _buildMenuItem(context, 'Admin', '/admin'),
              if (role.value < RoleEnum.UTILISATEUR.value)
                _buildMenuItem(context, 'Inscription - Connexion', '/connexion'),
              if (role.value >= RoleEnum.UTILISATEUR.value)
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
}
