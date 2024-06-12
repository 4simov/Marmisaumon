import 'package:flutter/material.dart';
import 'modeleRecette.dart';
import 'header.dart';

class RecipeDetailsPage extends StatelessWidget {
  final Recipe recipe;

  RecipeDetailsPage({required this.recipe});

  @override
  Widget build(BuildContext context) {
    Color blueColor = Color(0xFF001B2E).withOpacity(0.9);
    Color textColor = Colors.white;

    return Scaffold(
      body: Column(
        children: [
          const HeaderWidget(),
          AppBar(
            backgroundColor: Colors.white,
            elevation: 0,
            leading: IconButton(
              icon: Icon(Icons.arrow_back, color: blueColor),
              onPressed: () {
                Navigator.pop(context);
              },
            ),
            title: Text(
              recipe.name,
              style: TextStyle(color: blueColor),
            ),
            centerTitle: true,
          ),
          Expanded(
            child: SingleChildScrollView(
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Center(
                  child: Container(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.stretch,
                      children: [
                        SizedBox(
                          height: 200,
                          child: ListView(
                            scrollDirection: Axis.horizontal,
                            children: recipe.photos.map((photo) => Padding(
                              padding: const EdgeInsets.symmetric(horizontal: 5.0),
                              child: Container(
                                width: 300,
                                decoration: BoxDecoration(
                                  borderRadius: BorderRadius.circular(15.0),
                                  image: DecorationImage(
                                    image: AssetImage(photo),
                                    fit: BoxFit.cover,
                                  ),
                                ),
                              ),
                            )).toList(),
                          ),
                        ),
                        SizedBox(height: 20),
                        section('Description', recipe.description, blueColor, textColor),
                        SizedBox(height: 20),
                        Row(
                          children: [
                            Expanded(
                              child: section('Ustensiles', recipe.utensils.join('\n'), blueColor, textColor),
                            ),
                            SizedBox(width: 20),
                            Expanded(
                              child: section('Ingrédients', recipe.ingredients.join('\n'), blueColor, textColor),
                            ),
                          ],
                        ),
                        SizedBox(height: 20),
                        stepsSection('Étapes', recipe.steps, blueColor, textColor),
                        SizedBox(height: 20),
                        section('Commentaires', recipe.comments.join('\n'), blueColor, textColor),
                        SizedBox(height: 20),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget section(String title, String content, Color color, Color textColor) {
    return Container(
      padding: EdgeInsets.all(16.0),
      margin: EdgeInsets.symmetric(vertical: 10.0),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(15.0),
        color: color,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: TextStyle(
              fontSize: 20.0,
              fontWeight: FontWeight.bold,
              color: textColor,
            ),
          ),
          SizedBox(height: 10),
          Text(
            content,
            style: TextStyle(fontSize: 16.0, color: textColor),
          ),
        ],
      ),
    );
  }

  Widget stepsSection(String title, List<String> steps, Color color, Color textColor) {
    return Container(
      padding: EdgeInsets.all(16.0),
      margin: EdgeInsets.symmetric(vertical: 10.0),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(15.0),
        color: color,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: TextStyle(
              fontSize: 20.0,
              fontWeight: FontWeight.bold,
              color: textColor,
            ),
          ),
          SizedBox(height: 10),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: steps.asMap().entries.map((entry) {
              int index = entry.key;
              String step = entry.value;
              return Padding(
                padding: const EdgeInsets.symmetric(vertical: 8.0),
                child: Text(
                  'Étape ${index + 1}: $step',
                  style: TextStyle(
                    fontSize: 16.0,
                    color: textColor,
                  ),
                ),
              );
            }).toList(),
          ),
        ],
      ),
    );
  }
}







