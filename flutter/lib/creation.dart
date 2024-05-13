import 'package:flutter/material.dart';
import 'header.dart'; // Importez votre widget Header ici

void main() {
  runApp(const RecipePage());
}

class RecipePage extends StatelessWidget {
  const RecipePage({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      home: Scaffold(
        body: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              const HeaderWidget(), // Affichez le header ici

              Padding(
                padding: const EdgeInsets.all(20.0),
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 100.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Container(
                        padding: const EdgeInsets.only(right: 20.0),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text(
                              'Nom de la recette',
                              style: TextStyle(fontWeight: FontWeight.bold),
                            ),
                            TextFormField(
                              decoration: const InputDecoration(hintText: 'Nom de la recette'),
                              validator: (value) {
                                if (value!.isEmpty) {
                                  return 'Veuillez entrer le nom de la recette';
                                }
                                return null;
                              },
                            ),
                            const SizedBox(height: 16.0),
                            const Text(
                              'Liste des ustensiles',
                              style: TextStyle(fontWeight: FontWeight.bold),
                            ),
                            TextFormField(
                              decoration: const InputDecoration(hintText: 'Liste des ustensiles'),
                              validator: (value) {
                                if (value!.isEmpty) {
                                  return 'Veuillez entrer la liste des ustensiles';
                                }
                                return null;
                              },
                            ),
                            const SizedBox(height: 16.0),
                            const Text(
                              'Liste des ingrédients',
                              style: TextStyle(fontWeight: FontWeight.bold),
                            ),
                            TextFormField(
                              decoration: const InputDecoration(hintText: 'Liste des ingrédients'),
                              validator: (value) {
                                if (value!.isEmpty) {
                                  return 'Veuillez entrer la liste des ingrédients';
                                }
                                return null;
                              },
                            ),
                            const SizedBox(height: 16.0),
                            const Text(
                              'Etapes de la recette',
                              style: TextStyle(fontWeight: FontWeight.bold),
                            ),
                            TextFormField(
                              decoration: const InputDecoration(hintText: 'Etapes de la recette'),
                              validator: (value) {
                                if (value!.isEmpty) {
                                  return 'Veuillez entrer les étapes de la recette';
                                }
                                return null;
                              },
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(height: 16.0),
                      Container(
                        padding: const EdgeInsets.only(left: 20.0),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Container(
                              height: 200.0,
                              color: Colors.grey[300],
                              margin: const EdgeInsets.only(bottom: 16.0),
                              child: const Center(
                                child: Text(
                                  'Photos',
                                  style: TextStyle(fontWeight: FontWeight.bold),
                                ),
                              ),
                            ),
                            const Text(
                              'Conseil de l\'auteur',
                              style: TextStyle(fontWeight: FontWeight.bold),
                            ),
                            TextFormField(
                              decoration: const InputDecoration(hintText: 'Conseil de l\'auteur'),
                              validator: (value) {
                                if (value!.isEmpty) {
                                  return 'Veuillez entrer le conseil de l\'auteur';
                                }
                                return null;
                              },
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(height: 16.0),
                      const Text(
                        'Espace commentaire',
                        style: TextStyle(fontWeight: FontWeight.bold),
                      ),
                      TextFormField(
                        decoration: const InputDecoration(hintText: 'Espace commentaire'),
                        validator: (value) {
                          if (value!.isEmpty) {
                            return 'Veuillez entrer votre commentaire';
                          }
                          return null;
                        },
                      ),
                      const SizedBox(height: 24.0),

                      // Bouton de validation du formulaire
                      ElevatedButton(
                        onPressed: () {
                          // Ajoutez ici la logique de validation ou d'envoi du formulaire
                          if (Form.of(context).validate()) {
                            // Si le formulaire est valide, vous pouvez faire quelque chose ici
                            // Par exemple, enregistrer les données dans la base de données
                            // ou afficher un message de succès
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(content: Text('Formulaire validé !')),
                            );
                          }
                        },
                        child: const Text('Valider'),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
