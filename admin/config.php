<?php
/**
 * Configuration du back-office.
 *
 * SÉCURITÉ — à faire avant la mise en ligne :
 *   1. Changez le mot de passe. Générez un nouveau hash :
 *        php -r 'echo password_hash("VOTRE_MOT_DE_PASSE", PASSWORD_DEFAULT), "\n";'
 *      puis collez le résultat dans ADMIN_PASSWORD_HASH ci-dessous.
 *   2. Servez le site en HTTPS (les cookies de session passent alors en "secure").
 *
 * Mot de passe par défaut (À CHANGER) : DIF-admin-2026
 */

// Identifiant d'affichage (non secret).
const ADMIN_USER = 'admin';

// Hash du mot de passe (jamais le mot de passe en clair).
const ADMIN_PASSWORD_HASH = '$2y$12$Gz/CdV6syLJjXkVFv.zrMe5fOlLiSe82x2FPdCjnGuUyqpD/dJIOa';

// Durée d'inactivité avant déconnexion automatique (secondes).
const ADMIN_IDLE_TIMEOUT = 3600;
