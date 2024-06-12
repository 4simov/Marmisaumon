import 'dart:convert';
import 'dart:typed_data';
import 'package:flutter/foundation.dart';

import 'package:flutter/material.dart';
import 'package:file_picker/file_picker.dart';
import 'header.dart';

void main() {
  runApp(const MaterialApp(
    home: RecipePage(),
  ));
}


class RecipeFormData {
  String? recipeName;
  String? description;
  List<String> ingredients = [];
  List<String> instructions = [];
  List<String> images = [];
  String? comments;

  RecipeFormData({
    this.recipeName,
    this.description,
    required this.ingredients,
    required this.instructions,
    required this.images,
    this.comments,
  });

  Map<String, dynamic> toJson() {
    return {
      'recipeName': recipeName,
      'description': description,
      'ingredients': ingredients,
      'instructions': instructions,
      'images': images,
      'comments': comments,
    };
  }
}

class RecipePage extends StatefulWidget {
  const RecipePage({Key? key}) : super(key: key);

  @override
  _RecipePageState createState() => _RecipePageState();
}

class _RecipePageState extends State<RecipePage> {
  final _formKey = GlobalKey<FormState>();
  List<Uint8List> _selectedImages = [];
  List<String> ingredients = [
    "Tomate", "Fromage", "Basilic", "Sel", "Poivre", "Oignon", "Ail", "Poulet",
    "Bœuf", "Porc", "Agneau", "Poisson", "Crevettes", "Carotte", "Pomme de terre",
    "Courgette", "Aubergine", "Épinards", "Brocoli", "Chou-fleur", "Champignons",
    "Lait", "Crème", "Beurre", "Huile d'olive", "Vinaigre", "Citron", "Lime",
    "Oranges", "Pommes", "Bananes", "Fraises", "Framboises", "Myrtilles", "Mangue",
    "Avocat", "Concombre", "Poivron", "Chili", "Paprika", "Cumin", "Curcuma",
    "Gingembre", "Cannelle", "Clous de girofle", "Noix de muscade", "Anis étoilé",
    "Cardamome", "Safran", "Pâtes", "Riz", "Quinoa", "Lentilles", "Pois chiches"
  ];
  String? selectedIngredient;
  List<String> selectedIngredients = [];
  List<TextEditingController> instructionControllers = [TextEditingController()];
  TextEditingController _recipeNameController = TextEditingController();
  TextEditingController _descriptionController = TextEditingController();
  TextEditingController _commentsController = TextEditingController();
  TextEditingController searchController = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              const HeaderWidget(),

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
                              controller: _recipeNameController,
                              decoration: const InputDecoration(
                                  hintText: 'Nom de la recette'),
                              validator: (value) {
                                if (value!.isEmpty) {
                                  return 'Veuillez entrer le nom de la recette';
                                }
                                return null;
                              },
                            ),
                            const SizedBox(height: 24.0),
                            const Text(
                              'Description',
                              style: TextStyle(fontWeight: FontWeight.bold),
                            ),
                            TextFormField(
                              controller: _descriptionController,
                              maxLines: 4,
                              decoration: const InputDecoration(
                                  hintText: 'Description de la recette'),
                              validator: (value) {
                                if (value!.isEmpty) {
                                  return 'Veuillez entrer la description de la recette';
                                }
                                return null;
                              },
                            ),
                            const SizedBox(height: 24.0),
                            const Text(
                              'Ingrédients',
                              style: TextStyle(fontWeight: FontWeight.bold),
                            ),
                            TextField(
                              decoration: const InputDecoration(
                                hintText: 'Rechercher un ingrédient',
                              ),
                              onChanged: (value) {
                                setState(() {});
                              },
                            ),
                            const SizedBox(height: 8.0),
                            DropdownButtonFormField<String>(
                              decoration: const InputDecoration(
                                  hintText: 'Sélectionnez un ingrédient'),
                              items: ingredients
                                  .where((ingredient) => ingredient
                                      .toLowerCase()
                                      .contains(searchController.text.toLowerCase()))
                                  .map((String ingredient) {
                                return DropdownMenuItem<String>(
                                  value: ingredient,
                                  child: Text(ingredient),
                                );
                              }).toList(),
                              onChanged: (String? newValue) {
                                setState(() {
                                  selectedIngredient = newValue;
                                });
                              },
                              value: selectedIngredient,
                            ),
                            ElevatedButton(
                              onPressed: () {
                                if (selectedIngredient != null &&
                                    !selectedIngredients.contains(selectedIngredient)) {
                                  setState(() {
                                    selectedIngredients.add(selectedIngredient!);
                                  });
                                }
                              },
                              child: const Text('Ajouter Ingrédient'),
                            ),
                            Wrap(
                              spacing: 8.0,
                              children: selectedIngredients
                                  .map((ingredient) => Chip(
                                label: Text(ingredient),
                                onDeleted: () {
                                  setState(() {
                                    selectedIngredients.remove(ingredient);
                                  });
                                },
                              ))
                                  .toList(),
                            ),
                            const SizedBox(height: 24.0),
                            const Text(
                              'Instructions',
                              style: TextStyle(fontWeight: FontWeight.bold),
                            ),
                            Column(
                              children: instructionControllers.map((controller) {
                                int index = instructionControllers.indexOf(controller);
                                return Padding(
                                  padding: const EdgeInsets.symmetric(vertical: 8.0),
                                  child: Row(
                                    children: [
                                      Expanded(
                                        child: TextFormField(
                                          controller: controller,
                                          maxLines: 2,
                                          decoration: InputDecoration(
                                            hintText: 'Étape ${index + 1}',
                                          ),
                                        ),
                                      ),
                                      IconButton(
                                        icon: const Icon(Icons.delete),
                                        onPressed: () {
                                          setState(() {
                                            instructionControllers.removeAt(index);
                                          });
                                        },
                                      ),
                                    ],
                                  ),
                                );
                              }).toList(),
                            ),
                            ElevatedButton(
                              onPressed: () {
                                setState(() {
                                  instructionControllers.add(TextEditingController());
                                });
                              },
                              child: const Text('Ajouter une étape'),
                            ),
                            const SizedBox(height: 24.0),
                            const Text(
                              'Photo',
                              style: TextStyle(fontWeight: FontWeight.bold),
                            ),
                            ElevatedButton(
                              onPressed: () => _openFileExplorer(context),
                              child: const Text('Ajouter des photos'),
                            ),
                            const SizedBox(height: 24.0),
                            const Text(
                              'Commentaires',
                              style: TextStyle(fontWeight: FontWeight.bold),
                            ),
                            TextFormField(
                              controller: _commentsController,
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
                            ElevatedButton(
                              onPressed: () {
                                if (_formKey.currentState!.validate()) {
                                  RecipeFormData formData = collectFormData();
                                  String jsonFormData = jsonEncode(formData.toJson());
                                  print(jsonFormData); // Affiche le JSON dans la console
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

  void _openFileExplorer(BuildContext context) async {
    try {
      FilePickerResult? result = await FilePicker.platform.pickFiles(
        type: FileType.image,
        allowMultiple: true,
      );

      if (result != null) {
        setState(() {
          _selectedImages = result.files.map((file) => file.bytes!).toList();
        });

        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Photos sélectionnées avec succès')),
        );
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

  RecipeFormData collectFormData() {
    List<String> instructionTexts = [];
    instructionControllers.forEach((controller) {
      instructionTexts.add(controller.text);
    });

    return RecipeFormData(
      recipeName: _recipeNameController.text,
      description: _descriptionController.text,
      ingredients: selectedIngredients,
      instructions: instructionTexts,
      images: _selectedImages.map((image) => base64Encode(image)).toList(),
      comments: _commentsController.text,
    );
  }
}

