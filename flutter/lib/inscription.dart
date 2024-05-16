import 'package:flutter/material.dart';
import 'header.dart';

//Création de la classe pour le formulaire
class Inscription extends StatefulWidget {
  @override
  _MyFormState createState() => _MyFormState();
}

class _MyFormState extends State<Inscription> {
  final GlobalKey<FormState> _formKey =
      GlobalKey<FormState>(); // la clé pour gérer le formulaire

  //     //Pour récupérer l'information
  TextEditingController emailController = TextEditingController();
  TextEditingController confirmEmailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();
  TextEditingController confirmPasswordController = TextEditingController();

  void _submitForm() {
    // Vérifie si le formulaire est valide
    if (_formKey.currentState!.validate()) {
      _formKey.currentState!.save(); // Enregistres les données du formulaire
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        // appBar: AppBar(
        //   title: const Text('Inscription'),
        //   leading: IconButton(
        //     onPressed: () {
        //       Navigator.of(context).pop();
        //     },
        //     icon: const BackButtonIcon(),
        //   ),
        // ),
        body: Form(
      //Header

      key: _formKey, // Associe la clé au formulaire

      child: Column(
        children: <Widget>[
          HeaderWidget(),
          TextFormField(
            keyboardType: TextInputType.emailAddress,
            controller: emailController,
            decoration: const InputDecoration(
              labelText: 'Adresse e-mail',
            ),
            validator: (value) {
              bool emailRegex = RegExp(
                      r"^[a-zA-Z0-9.a-zA-Z0-9.!#$%&'*+-/=?^_`{|}~]+@[a-zA-Z0-9]+\.[a-zA-Z]+")
                  .hasMatch(value!);
              if (value.isEmpty) {
                return "Entrez votre adresse e-mail";
              }

              if (!emailRegex) {
                return "Veuillez entrer une adresse e-mail valide";
              }
              return null;
            },
          ),
          const SizedBox(height: 20.0),
          TextFormField(
            controller: confirmEmailController,
            decoration: const InputDecoration(
              labelText: 'Confirmer votre adresse e-mail',
            ),
            keyboardType: TextInputType.emailAddress,
            validator: (value) {
              if (value != emailController.text) {
                return 'Les adresses e-mail ne sont pas identiques';
              }
              return null;
            },
          ),
          const SizedBox(height: 20.0),
          TextFormField(
            decoration: const InputDecoration(
              labelText: 'Pseudo',
            ),
            validator: (value) {
              if (value!.isEmpty) {
                return 'Veuillez entrer votre pseudo';
              }
              return null;
            },
          ),
          const SizedBox(height: 20.0),
          TextFormField(
            controller: passwordController,
            decoration: const InputDecoration(
              labelText: 'Mot de passe',
            ),
            obscureText: true,
            validator: (value) {
              if (value!.isEmpty) {
                return 'Veuillez entrer votre mot de passe';
              }
              return null;
            },
          ),
          const SizedBox(height: 20.0),
          TextFormField(
              controller: confirmPasswordController,
              decoration: const InputDecoration(
                labelText: 'Confirmer votre mot de passe',
              ),
              obscureText: true,
              validator: (value) {
                if (value != passwordController.text) {
                  return 'Les mots de passe ne sont pas identiques';
                }
                return null;
              }),
          const SizedBox(height: 20.0),
          ElevatedButton(
            onPressed: _submitForm,
            child: const Text('S\'inscrire'), // Bouton pour s'inscrire
          ),
        ],
      ),
    ));
  }
}
