<?php
// Connexion à la base de données
$connect_db = new PDO('mysql:host=localhost;dbname=bibliotheque', 'user_entreprise', 'Entreprise28!', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
]);

// session
session_start();

// Failles XSS
foreach($_POST as $key => $value){
    // $_POST['prenom'] = htmlspecialchars('<script>alert()</script>', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
    $_POST[$key] = htmlspecialchars($_POST[$key], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

foreach($_GET as $key => $value){
    $_GET[$key] = htmlspecialchars($_GET[$key], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

