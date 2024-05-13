import 'package:flutter/material.dart';
import 'header.dart'; // Importez votre widget Header ici

void main() {
  runApp(const MaterialApp(
    home: ProfilePage(),
  ));
}

class ProfilePage extends StatelessWidget {
  const ProfilePage({super.key});

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
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const CircleAvatar(
                    radius: 60.0,
                    backgroundImage: AssetImage('assets/profile_image.jpg'), // Remplacez par votre image de profil
                  ),
                  const SizedBox(height: 20.0),
                  const Text(
                    'Nom: John Doe',
                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18.0),
                  ),
                  const SizedBox(height: 8.0),
                  const Text(
                    'Email: johndoe@example.com',
                    style: TextStyle(fontSize: 16.0),
                  ),
                  const SizedBox(height: 8.0),
                  const Text(
                    'Ville: New York',
                    style: TextStyle(fontSize: 16.0),
                  ),
                  const SizedBox(height: 20.0),
                  ElevatedButton(
                    onPressed: () {
                      // Action à effectuer lorsque le bouton est pressé
                      // Par exemple, permettre à l'utilisateur de modifier son profil
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Fonctionnalité non implémentée')),
                      );
                    },
                    child: const Text('Modifier le profil'),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
