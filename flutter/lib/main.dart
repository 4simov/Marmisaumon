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
    return const Scaffold(
      body: Column(
        children: [
          HeaderWidget(), 
          
        ],
      ),
    );  
  }
}

