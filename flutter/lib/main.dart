import 'package:flutter/material.dart';
import 'inscription.dart';
import 'header.dart';
import 'creation.dart';
import 'profil.dart';
import 'contact.dart';
import 'connexion.dart';
import 'inscription.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Mon Application',
      initialRoute: '/',
      routes: {
        '/': (context) => const HomePage(), // Page d'accueil
        '/creation-recette': (context) => const RecipePage(), // Page de recette //Marche pas
        // '/affichage-recette': (context) => const RecipePage(), // Page de creation de recette
        '/profil': (context) => const ProfilePage(), // Page de profil //Marche
        '/contact': (context) => const ContactPage(), // Page de contact //Marche
        '/connexion': (context) => const Connexion2(), // Page de connexion //Marche pas
        '/inscription': (context) => Inscription(), // Page d'inscription
        
      },
    );
  }
}


class HomePage extends StatelessWidget {
  const HomePage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          const HeaderWidget(), // Insérer le widget de l'en-tête
          const SizedBox(height: 20),
          ElevatedButton(
            onPressed: () {
              Navigator.pushNamed(context, '/creation-recette');
            },
            child: const Text('Créer une recette'),
          ),
          const SizedBox(height: 20),
          const Text(
            'Recettes populaires',
            style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
          ),
          // Ajouter ici la liste de recettes populaires ou catégories
          Expanded(
            child: ListView(
              children: [
                ListTile(
                  title: const Text('Poulet rôti'),
                  onTap: () {
                    // Naviguer vers la page de détail de la recette
                    Navigator.pushNamed(context, '/affichage-recette');
                  },
                ),
                ListTile(
                  title: const Text('Tarte aux pommes'),
                  onTap: () {
                    // Naviguer vers la page de détail de la recette
                    Navigator.pushNamed(context, '/affichage-recette');
                  },
                ),
                // Ajouter plus de recettes populaires ici...
              ],
            ),
          ),
        ],
      ),
    );
  }
}

