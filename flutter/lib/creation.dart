// ignore: avoid_web_libraries_in_flutter
import 'dart:js';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'dart:io';
import 'package:file_picker/file_picker.dart';
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
                              decoration: const InputDecoration(
                                  hintText: 'Nom de la recette'),
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
                              decoration: const InputDecoration(
                                  hintText: 'Liste des ustensiles'),
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
                              decoration: const InputDecoration(
                                  hintText: 'Liste des ingrédients'),
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
                              decoration: const InputDecoration(
                                  hintText: 'Etapes de la recette'),
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
                                child: TextButton(
                                  onPressed: () {
                                    _openFileExplorer(); // Fonction pour ouvrir l'explorateur de fichiers
                                  },
                                  child: const Text('Ajouter des photos'),
                                )),
                            const Text(
                              'Conseil de l\'auteur',
                              style: TextStyle(fontWeight: FontWeight.bold),
                            ),
                            TextFormField(
                              decoration: const InputDecoration(
                                  hintText: 'Conseil de l\'auteur'),
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
                        decoration: const InputDecoration(
                            hintText: 'Espace commentaire'),
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
                              const SnackBar(
                                  content: Text('Formulaire validé !')),
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

void _openFileExplorer() async {
  try {
    FilePickerResult? result = await FilePicker.platform.pickFiles(
      type: FileType.image, // Limite la sélection aux images
      allowMultiple:
          true, // Permet à l'utilisateur de sélectionner plusieurs images
    );

    if (result != null) {
      result.files.map((file) => File(file.path!)).toList();
      // Traitez les fichiers sélectionnés ici, par exemple, affichez-les ou téléchargez-les
      // Vous pouvez également stocker les fichiers dans une liste pour les utiliser plus tard
      // Par exemple : setState(() { _selectedImages = files; });

      // Affiche un message de confirmation
      ScaffoldMessenger.of(context as BuildContext).showSnackBar(
        const SnackBar(content: Text('Photos sélectionnées avec succès')),
      );
    } else {
      // L'utilisateur a annulé la sélection
      ScaffoldMessenger.of(context as BuildContext).showSnackBar(
        const SnackBar(content: Text('Aucune photo sélectionnée')),
      );
    }
  } catch (e) {
    if (kDebugMode) {
      print('Erreur lors de la sélection des photos : $e');
    }
    // Affichez un message d'erreur en cas de problème lors de la sélection des photos
    ScaffoldMessenger.of(context as BuildContext).showSnackBar(
      const SnackBar(content: Text('Erreur lors de la sélection des photos')),
    );
  }
}


class CreateRecipePage extends StatelessWidget {
  const CreateRecipePage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Créer une recette'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            HeaderWidget(), // Affichez le header ici

            const SizedBox(height: 20.0),
            const Text(
              'Ajouter des photos',
              style: TextStyle(fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10.0),
            TextButton(
              onPressed: () {
                _openFileExplorer(); // Ouvrir l'explorateur de fichiers pour sélectionner des photos
              },
              child: const Text('Sélectionner des photos'),
            ),

            // Autres champs de formulaire pour la création de recette...
          ],
        ),
      ),
    );
  }

  Future<void> _openFileExplorer() async {
    try {
      FilePickerResult? result = await FilePicker.platform.pickFiles(
        type: FileType.image,
        allowMultiple: true,
      );

      if (result != null) {
        result.files.map((file) => File(file.path!)).toList();
        // Traitez les fichiers sélectionnés ici
        // Par exemple : setState(() { _selectedImages = files; });
        ScaffoldMessenger.of(context as BuildContext).showSnackBar(
          const SnackBar(content: Text('Photos sélectionnées avec succès')),
        );
      } else {
        ScaffoldMessenger.of(context as BuildContext).showSnackBar(
          const SnackBar(content: Text('Aucune photo sélectionnée')),
        );
      }
    } catch (e) {
      if (kDebugMode) {
        print('Erreur lors de la sélection des photos : $e');
      }
      ScaffoldMessenger.of(context as BuildContext).showSnackBar(
        const SnackBar(content: Text('Erreur lors de la sélection des photos')),
      );
    }
  }
}
