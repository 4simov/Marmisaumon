enum RoleEnum {
  INVITE(1),
  UTILISATEUR(2),
  MODERATEUR(3),
  ADMIN(4);

  const RoleEnum(this.value);
  final int value;
}