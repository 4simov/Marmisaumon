import 'package:flutter/material.dart';
import 'header.dart';

class AddIngredientPage extends StatefulWidget {
  const AddIngredientPage({Key? key}) : super(key: key);

  @override
  _AddIngredientPageState createState() => _AddIngredientPageState();
}

class _AddIngredientPageState extends State<AddIngredientPage> {
  TextEditingController ingredientController = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          HeaderWidget(), // Ajout du Header
          Expanded(
            child: Padding(
              padding: const EdgeInsets.all(20.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  TextField(
                    controller: ingredientController,
                    decoration: InputDecoration(
                      labelText: 'Nom de l\'ingrédient',
                    ),
                  ),
                  const SizedBox(height: 20.0),
                  ElevatedButton(
                    onPressed: () {
                      _addIngredient(context);
                    },
                    child: Text('Ajouter'),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  void _addIngredient(BuildContext context) {
    String newIngredient = ingredientController.text.trim();
    if (newIngredient.isNotEmpty) {
      // implémenter la logique pour ajouter l'ingrédient à la base de données
      print('Nouvel ingrédient ajouté : $newIngredient');
      Navigator.pop(context); // Revenir à la page précédente
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Veuillez entrer le nom de l\'ingrédient'),
        ),
      );
    }
  }
}

