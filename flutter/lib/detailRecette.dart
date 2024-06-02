import 'package:flutter/material.dart';
import 'modeleRecette.dart';
import 'header.dart';

class RecipeDetailsPage extends StatelessWidget {
  final Recipe recipe;

  RecipeDetailsPage({required this.recipe});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        children: [
          const HeaderWidget(),
          Expanded(
            child: Padding(
              padding: EdgeInsets.all(16.0),
              child: SingleChildScrollView(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: <Widget>[
                    Row(
                      children: [
                        IconButton(
                          icon: Icon(Icons.arrow_back, color: Colors.black),
                          onPressed: () {
                            Navigator.pop(context);
                          },
                        ),
                        Text(
                          recipe.name,
                          style: TextStyle(
                            fontSize: 22.0,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ],
                    ),
                    SizedBox(
                      height: 200,
                      child: ListView(
                        scrollDirection: Axis.horizontal,
                        children: recipe.photos.map((photo) => Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 5.0),
                          child: ClipRRect(
                            borderRadius: BorderRadius.circular(15.0),
                            child: Image.asset(photo, fit: BoxFit.cover),
                          ),
                        )).toList(),
                      ),
                    ),
                    SizedBox(height: 20),
                    Text(
                      'Ustensiles',
                      style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                    ),
                    ...recipe.utensils.map((utensil) => Padding(
                      padding: const EdgeInsets.only(top: 5.0),
                      child: Text(utensil, style: TextStyle(fontSize: 16)),
                    )).toList(),
                    SizedBox(height: 20),
                    Text(
                      'Ingrédients',
                      style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                    ),
                    ...recipe.ingredients.map((ingredient) => Padding(
                      padding: const EdgeInsets.only(top: 5.0),
                      child: Text(ingredient, style: TextStyle(fontSize: 16)),
                    )).toList(),
                    SizedBox(height: 20),
                    Text(
                      'Étapes',
                      style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                    ),
                    ...recipe.steps.map((step) => Padding(
                      padding: const EdgeInsets.only(top: 5.0),
                      child: Text(step, style: TextStyle(fontSize: 16)),
                    )).toList(),
                    SizedBox(height: 20),
                    Text(
                      'Commentaires',
                      style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                    ),
                    ...recipe.comments.map((comment) => Padding(
                      padding: const EdgeInsets.only(top: 5.0),
                      child: Text(comment, style: TextStyle(fontSize: 16)),
                    )).toList(),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
