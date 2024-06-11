enum RoleEnum {
  INVITE(0),
  UTILISATEUR(1),
  MODERATEUR(3),
  ADMIN(4);

  const RoleEnum(this.value);
  final int value;
}