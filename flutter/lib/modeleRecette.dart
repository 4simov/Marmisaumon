class Recipe {
  final String name;
  final List<String> utensils;
  final List<String> ingredients;
  final List<String> steps;
  final List<String> photos;
  final List<String> comments;
  final String description;

  Recipe({
    required this.name,
    required this.utensils,
    required this.ingredients,
    required this.steps,
    required this.photos,
    required this.comments,
    required this.description,
  });
}
