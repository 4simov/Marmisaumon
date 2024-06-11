import 'package:flutter/material.dart';
// ignore: unnecessary_import
import 'package:flutter/rendering.dart';
import 'package:marmisaumon/Enums/roleEnum.dart';

class HeaderWidget extends StatefulWidget {
  const HeaderWidget({super.key});

  @override
  // ignore: library_private_types_in_public_api
  _HeaderWidgetState createState() => _HeaderWidgetState();
}

class _HeaderWidgetState extends State<HeaderWidget> {
  String _hoveredItem = '';
  RoleEnum role = RoleEnum.UTILISATEUR;
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
            children: <Widget> [
              _buildMenuItem(context, 'Accueil', '/'),
              _buildMenuItem(context, 'Recettes', '/affichage-recette'),
              if(role.value >= RoleEnum.UTILISATEUR.value )
                _buildMenuItem(context, 'Créer une recette', '/creation-recette'),//montrer que pour invité
              if(role.value >= RoleEnum.UTILISATEUR.value )
                _buildMenuItem(context, 'Mon compte', '/profil'),//montrer que pour utilisateur pour utilisateur 
              _buildMenuItem(context, 'Contact', '/contact'),
              if(role.value >= RoleEnum.UTILISATEUR.value )
                _buildMenuItem(context, 'Nouvel ingrédient', 'ajoutIngredient'),//montrer que pour Utilisateur ou plus
              if(role.value >= RoleEnum.ADMIN.value )
                _buildMenuItem(context, 'Admin', '/admin'),//montrer que pour l'admin
              if(role.value < RoleEnum.UTILISATEUR.value)
                _buildMenuItem(context, 'Inscription - Connexion', '/connexion'),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildMenuItem(BuildContext context, String title, String route) {
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
            Navigator.pushNamed(context, route);
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
