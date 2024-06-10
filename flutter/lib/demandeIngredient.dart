import 'package:flutter/material.dart';
import 'header.dart';

// Modèle de demande d'ingrédient
class IngredientRequest {
  final String ingredientName;
  final String requesterName;
  bool isApproved;

  IngredientRequest({
    required this.ingredientName,
    required this.requesterName,
    this.isApproved = false,
  });
}

// Page d'administration pour gérer les demandes d'ingrédients
class AdminPage extends StatefulWidget {
  const AdminPage({Key? key}) : super(key: key);

  @override
  _AdminPageState createState() => _AdminPageState();
}

class _AdminPageState extends State<AdminPage> {
  List<IngredientRequest> ingredientRequests = [
    IngredientRequest(ingredientName: 'Nouvel ingrédient 1', requesterName: 'Utilisateur 1'),
    IngredientRequest(ingredientName: 'Nouvel ingrédient 2', requesterName: 'Utilisateur 2'),
    IngredientRequest(ingredientName: 'Nouvel ingrédient 3', requesterName: 'Utilisateur 3'),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        children: [
          HeaderWidget(), // Ajout du Header
          Expanded(
            child: ListView.builder(
              itemCount: ingredientRequests.length,
              itemBuilder: (context, index) {
                return ListTile(
                  title: Text(ingredientRequests[index].ingredientName),
                  subtitle: Text('Demandé par: ${ingredientRequests[index].requesterName}'),
                  trailing: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      IconButton(
                        icon: Icon(Icons.check),
                        onPressed: () {
                          // Accepter la demande
                          setState(() {
                            ingredientRequests[index].isApproved = true;
                            // Supprimer l'ingrédient de la liste
                            ingredientRequests.removeAt(index);
                          });
                          _showMessage('Demande approuvée');
                        },
                      ),
                      IconButton(
                        icon: Icon(Icons.close),
                        onPressed: () {
                          // Rejeter la demande
                          setState(() {
                            ingredientRequests.removeAt(index);
                          });
                          _showMessage('Demande rejetée');
                        },
                      ),
                    ],
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  // Afficher un message temporaire en bas de l'écran
  void _showMessage(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(message)),
    );
  }
}