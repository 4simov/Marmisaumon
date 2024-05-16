import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'dart:typed_data';
import 'package:file_picker/file_picker.dart';
import 'header.dart'; // Importez votre widget Header ici

void main() {
  runApp(const MaterialApp(
    home: RecipePage(),
  ));
}

class RecipePage extends StatefulWidget {
  const RecipePage({Key? key}) : super(key: key);

  @override
  _RecipePageState createState() => _RecipePageState();
}

class _RecipePageState extends State<RecipePage> {
  List<Uint8List> _selectedImages = [];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
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
                            child: _selectedImages.isNotEmpty
                                ? ListView.builder(
                                    scrollDirection: Axis.horizontal,
                                    itemCount: _selectedImages.length,
                                    itemBuilder: (context, index) {
                                      return Stack(
                                        children: [
                                          Container(
                                            width: 200.0,
                                            height: 200.0,
                                            margin: const EdgeInsets.only(
                                                right: 8.0),
                                            child: Image.memory(
                                              _selectedImages[index],
                                              fit: BoxFit.cover,
                                            ),
                                          ),
                                          Positioned(
                                            top: 0,
                                            right: 0,
                                            child: GestureDetector(
                                              onTap: () {
                                                setState(() {
                                                  _selectedImages
                                                      .removeAt(index);
                                                });
                                              },
                                              child: Container(
                                                decoration: BoxDecoration(
                                                  color: Colors.black
                                                      .withOpacity(0.5),
                                                  shape: BoxShape.circle,
                                                ),
                                                padding:
                                                    const EdgeInsets.all(4.0),
                                                child: const Icon(
                                                  Icons.close,
                                                  color: Colors.white,
                                                ),
                                              ),
                                            ),
                                          ),
                                        ],
                                      );
                                    },
                                  )
                                : TextButton(
                                    onPressed: () {
                                      _openFileExplorer(context);
                                    },
                                    child: const Text('Ajouter des photos'),
                                  ),
                          ),
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
                      decoration:
                          const InputDecoration(hintText: 'Espace commentaire'),
                      validator: (value) {
                        if (value!.isEmpty) {
                          return 'Veuillez entrer votre commentaire';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 24.0),
                    ElevatedButton(
                      onPressed: () {
                        if (Form.of(context).validate()) {
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
    );
  }

  void _openFileExplorer(BuildContext context) async {
    try {
      FilePickerResult? result = await FilePicker.platform.pickFiles(
        type: FileType.image,
        allowMultiple: true,
      );

      if (result != null) {
        _selectedImages = result.files.map((file) => file.bytes!).toList();

        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Photos sélectionnées avec succès')),
        );

        setState(() {});
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Aucune photo sélectionnée')),
        );
      }
    } catch (e) {
      if (kDebugMode) {
        print('Erreur lors de la sélection des photos : $e');
      }
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erreur lors de la sélection des photos')),
      );
    }
  }
}
