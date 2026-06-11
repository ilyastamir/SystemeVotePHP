<?php
// ============================================
// Fichier : vote.php
// Traitement du vote - Backend PHP
// ============================================
session_start();
require_once 'config/database.php';

// Vérifier que la requête est en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

// Récupérer et nettoyer les données du formulaire
$idEtudiant   = trim($_POST['id_etudiant'] ?? '');
$idCandidat   = intval($_POST['id_candidat'] ?? 0);

// Vérifier que les champs ne sont pas vides
if (empty($idEtudiant) || empty($idCandidat)) {
    header('Location: index.php?msg=champs_vides');
    exit();
}

try {
    // 1) Vérifier si l'étudiant a déjà voté
    $stmt = $pdo->prepare("SELECT id FROM votes WHERE id_etudiant = :id_etudiant");
    $stmt->execute([':id_etudiant' => $idEtudiant]);

    if ($stmt->fetch()) {
        // L'étudiant a déjà voté
        header('Location: index.php?msg=deja_vote');
        exit();
    }

    // 2) Vérifier que le candidat existe
    $stmt = $pdo->prepare("SELECT id FROM candidats WHERE id = :id_candidat");
    $stmt->execute([':id_candidat' => $idCandidat]);

    if (!$stmt->fetch()) {
        header('Location: index.php?msg=erreur');
        exit();
    }

    // 3) Enregistrer le vote
    $stmt = $pdo->prepare("
        INSERT INTO votes (id_etudiant, id_candidat, date_vote)
        VALUES (:id_etudiant, :id_candidat, NOW())
    ");
    $stmt->execute([
        ':id_etudiant' => $idEtudiant,
        ':id_candidat' => $idCandidat
    ]);

    // Vote enregistré avec succès
    header('Location: index.php?msg=succes');
    exit();

} catch (PDOException $e) {
    // Gestion des erreurs (ex : doublon malgré la vérification)
    if ($e->getCode() == 23000) {
        // Violation de contrainte unique - l'étudiant a déjà voté
        header('Location: index.php?msg=deja_vote');
    } else {
        header('Location: index.php?msg=erreur');
    }
    exit();
}