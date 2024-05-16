import 'package:flutter/material.dart';

class HeaderWidget extends StatelessWidget {
  const HeaderWidget({super.key});
  

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
            children: [
              _buildMenuItem(context, 'Accueil', '/'),
              _buildMenuItem(context, 'Recettes', '/recipe'),
              _buildMenuItem(context, 'Créer une recette', '/creation-recette'),
              _buildMenuItem(context, 'Mon compte', '/profil'),
              _buildMenuItem(context, 'Contact', '/contact'),
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
      child: InkWell(
        onTap: () {
          Navigator.pushNamed(context, route);
        },
        child: Text(
          title,
          style: const TextStyle(
            color: Colors.white,
            fontSize: 16.0,
          ),
        ),
      ),
    );
  }
}