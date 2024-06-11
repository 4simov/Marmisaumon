<?php
namespace MyEnum{

enum RolesEnum : int
{
    case INVITE = 1;
    case UTILISATEUR = 2;
    case MODERATEUR;
    case ADMIN = 4;
}

}