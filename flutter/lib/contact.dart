import 'package:flutter/material.dart';
import 'header.dart';

void main() {
  runApp(const MaterialApp(
    home: ContactPage(),
  ));
}

class ContactPage extends StatelessWidget {
  const ContactPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const HeaderWidget(), 

            Padding(
              padding: const EdgeInsets.all(20.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Formulaire de Contact',
                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 20.0),
                  ),
                  const SizedBox(height: 20.0),
                  TextFormField(
                    decoration: const InputDecoration(
                      labelText: 'Nom',
                      hintText: 'Entrez votre nom',
                    ),
                    validator: (value) {
                      if (value!.isEmpty) {
                        return 'Veuillez entrer votre nom';
                      }
                      return null;
                    },
                  ),
                  const SizedBox(height: 16.0),
                  TextFormField(
                    decoration: const InputDecoration(
                      labelText: 'Email',
                      hintText: 'Entrez votre email',
                    ),
                    validator: (value) {
                      if (value!.isEmpty) {
                        return 'Veuillez entrer votre email';
                      }
                      // Validation d'email simplifiée (vérifie si le champ contient '@')
                      if (!value.contains('@')) {
                        return 'Veuillez entrer une adresse email valide';
                      }
                      return null;
                    },
                  ),
                  const SizedBox(height: 16.0),
                  TextFormField(
                    decoration: const InputDecoration(
                      labelText: 'Message',
                      hintText: 'Entrez votre message',
                    ),
                    maxLines: 5,
                    validator: (value) {
                      if (value!.isEmpty) {
                        return 'Veuillez entrer votre message';
                      }
                      return null;
                    },
                  ),
                  const SizedBox(height: 20.0),
                  ElevatedButton(
                    onPressed: () {
                      // Ici, vous pouvez ajouter la logique pour traiter le formulaire de contact
                      if (Form.of(context).validate()) {
                        // Si le formulaire est valide, affichez un message de confirmation
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(content: Text('Formulaire de contact envoyé !')),
                        );
                      }
                    },
                    child: const Text('Envoyer'),
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
