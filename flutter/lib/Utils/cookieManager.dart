// lib/cookie_manager.dart
import 'package:shared_preferences/shared_preferences.dart';

class CookieManager {
  // Sauvegarder un cookie
  Future<void> saveCookie(String cookieName, String cookie) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(cookieName, cookie);
  }

  // Récupérer un cookie
  Future<String?> getCookie(String cookieName) async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(cookieName);
  }

  // Supprimer un cookie
  Future<void> deleteCookie(String cookieName) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(cookieName);
  }

  // Sauvegarder un cookie
  Future<void> saveCookieToken(String cookie) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('token', cookie);
  }

  // Récupérer un cookie
  Future<String?> getCookieToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('token');
  }

  // Supprimer un cookie
  Future<void> deleteCookieToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('token');
  }
}