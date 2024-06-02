import 'package:flutter/material.dart';
import 'modeleRecette.dart';
import 'detailRecette.dart';
import 'header.dart';

class RecipeListPage extends StatelessWidget {
  final List<Recipe> recipes = [
    Recipe(
      name: 'Tarte aux pommes',
      utensils: ['Moule à tarte', 'Fourchette', 'Couteau'],
      ingredients: ['Pommes', 'Pâte feuilletée', 'Sucre', 'Cannelle'],
      steps: ['Éplucher les pommes', 'Étaler la pâte', 'Ajouter les pommes', 'Cuire au four'],
      photos: ['assets/images/tarte_aux_pommes.jpg'],
      comments: ['Délicieux!', 'Facile à faire!'],
      description: 'Une délicieuse tarte aux pommes avec une pâte feuilletée croustillante.',
    ),
    Recipe(
      name: 'Poulet rôti',
      utensils: ['Four', 'Plat à rôtir'],
      ingredients: ['Poulet', 'Beurre', 'Herbes de Provence', 'Ail', 'Sel', 'Poivre'],
      steps: ['Préchauffer le four', 'Assaisonner le poulet', 'Cuire au four'],
      photos: ['assets/images/poulet_roti.jpg'],
      comments: ['Très bon!', 'Moelleux et savoureux!'],
      description: 'Un poulet rôti savoureux et tendre, parfait pour les repas en famille.',
    ),
    // Ajouter d'autres recettes ici...
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        children: [
          const HeaderWidget(),
          Expanded(
            child: ListView.builder(
              itemCount: recipes.length,
              itemBuilder: (context, index) {
                return Card(
                  margin: EdgeInsets.symmetric(vertical: 10.0, horizontal: 15.0),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(15.0),
                  ),
                  elevation: 5.0,
                  child: InkWell(
                    borderRadius: BorderRadius.circular(15.0),
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (context) => RecipeDetailsPage(recipe: recipes[index])),
                      );
                    },
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.vertical(top: Radius.circular(15.0)),
                          child: Image.asset(
                            recipes[index].photos[0],
                            width: double.infinity,
                            height: 200.0,
                            fit: BoxFit.cover,
                          ),
                        ),
                        Padding(
                          padding: const EdgeInsets.all(15.0),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                recipes[index].name,
                                style: TextStyle(
                                  fontSize: 22.0,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              SizedBox(height: 10.0),
                              Text(
                                recipes[index].description,
                                style: TextStyle(
                                  fontSize: 16.0,
                                  color: Colors.grey[600],
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}

