import 'package:flutter/material.dart';
import 'package:marmisaumon/profil.dart';
import 'creation.dart'; 
import 'header.dart';
import 'contact.dart';

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
        '/creation-recette': (context) => const RecipePage(), // Page de recette
        '/affichage-recette': (context) => const RecipePage(), // Page de creation de recette
        '/profil': (context) => const ProfilePage(), // Page de profil
        '/contact': (context) => const ContactPage(), // Page de contact
        '/connexion': (context) => const RecipePage(), // Page de connexion
        
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

