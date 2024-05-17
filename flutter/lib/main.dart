import 'package:flutter/material.dart';
import 'inscription.dart';
import 'header.dart';
import 'creation.dart';
import 'profil.dart';
import 'contact.dart';
import 'connexion.dart';
import 'affichageRecette.dart';
import 'inscription.dart';
import 'package:carousel_slider/carousel_slider.dart';

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
        '/': (context) => HomePage(), // Page d'accueil
        '/creation-recette': (context) =>
            const RecipePage(), // Page de recette //Marche pas
        '/affichage-recette': (context) =>  RecipeListPage(), // Page de creation de recette
        '/profil': (context) => const ProfilePage(), // Page de profil //Marche
        '/contact': (context) =>
            const ContactPage(), // Page de contact //Marche
        '/connexion': (context) =>
            const Connexion2(), // Page de connexion //Marche pas
        '/inscription': (context) => Inscription(), // Page d'inscription
      },
    );
  }
}

class HomePage extends StatelessWidget {
  HomePage({super.key});

  final List<String> imgList = [
    'assets/images/Recette1.jpg',
    'assets/images/Recette2.jpg',
    'assets/images/Recette3.jpg',
  ];

  final CarouselController _controller = CarouselController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          const HeaderWidget(), // Insérer le widget de l'en-tête
          const SizedBox(height: 20),
          const Text(
            'Recettes du moment',
            style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
          ),
          Stack(
            alignment: Alignment.center,
            children: [
              CarouselSlider(
                carouselController: _controller,
                options: CarouselOptions(
                  autoPlay: true,
                  height: 300.0,
                  enlargeCenterPage: true,
                ),
                items: imgList
                    .map((item) => Container(
                          child: Center(
                              child: Image.network(
                            item,
                            fit: BoxFit.cover,
                            width: 400,
                          )),
                        ))
                    .toList(),
              ),
              Positioned(
                left: 10,
                child: IconButton(
                  icon: const Icon(Icons.arrow_back, color: Colors.black),
                  onPressed: () {
                    _controller.previousPage();
                  },
                ),
              ),
              Positioned(
                right: 10,
                child: IconButton(
                  icon: const Icon(Icons.arrow_forward, color: Colors.black),
                  onPressed: () {
                    _controller.nextPage();
                  },
                ),
              ),
            ],
          ),
          //Carrousel d'image pour la page d'acceuil
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
