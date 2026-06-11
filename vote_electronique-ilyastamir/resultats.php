<?php
// ============================================
// Fichier : resultats.php
// Affichage des résultats du scrutin
// ============================================
require_once 'config/database.php';

// Calculer le nombre de votes par candidat
$stmt = $pdo->query("
    SELECT
        c.id,
        c.nom,
        c.photo,
        COUNT(v.id) AS nb_votes
    FROM candidats c
    LEFT JOIN votes v ON v.id_candidat = c.id
    GROUP BY c.id, c.nom, c.photo
    ORDER BY nb_votes DESC, c.nom ASC
");
$resultats = $stmt->fetchAll();

// Calculer le total des votes
$totalVotes = 0;
foreach ($resultats as $r) {
    $totalVotes += $r['nb_votes'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats - Système de Vote Électronique</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Résultats du Scrutin</h1>
            <p>Élection du Délégué de Promotion 2025/2026</p>
        </div>
    </header>

    <main class="container">
        <!-- Total des votes -->
        <div class="total-votes">
            Nombre total de votes enregistrés : <strong><?php echo $totalVotes; ?></strong>
        </div>

        <!-- Tableau des résultats -->
        <?php if (count($resultats) > 0): ?>
            <table class="tableau-resultats">
                <thead>
                    <tr>
                        <th class="col-rang">Rang</th>
                        <th class="col-photo">Photo</th>
                        <th>Candidat</th>
                        <th class="col-votes">Votes</th>
                        <th class="col-pourcentage">Pourcentage</th>
                        <th>Barre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rang = 1; ?>
                    <?php foreach ($resultats as $resultat): ?>
                        <?php
                            $pourcentage = $totalVotes > 0
                                ? round(($resultat['nb_votes'] / $totalVotes) * 100, 1)
                                : 0;
                        ?>
                        <tr>
                            <td class="col-rang">
                                <?php if ($rang === 1 && $resultat['nb_votes'] > 0): ?>
                                    🏆
                                <?php else: ?>
                                    <?php echo $rang; ?>
                                <?php endif; ?>
                            </td>
                            <td class="col-photo">
                                <img src="<?php echo htmlspecialchars($resultat['photo']); ?>"
                                     alt="<?php echo htmlspecialchars($resultat['nom']); ?>"
                                     class="photo-mini">
                            </td>
                            <td><strong><?php echo htmlspecialchars($resultat['nom']); ?></strong></td>
                            <td class="col-votes"><?php echo $resultat['nb_votes']; ?></td>
                            <td class="col-pourcentage"><?php echo $pourcentage; ?>%</td>
                            <td>
                                <div class="barre-vote">
                                    <div class="fill" style="width: <?php echo $pourcentage; ?>%;"></div>
                                </div>
                            </td>
                        </tr>
                        <?php $rang++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: #888; padding: 40px 0; font-size: 1.1rem;">
                Aucun résultat disponible. Aucun vote n'a été enregistré pour le moment.
            </p>
        <?php endif; ?>

        <!-- Lien de retour -->
        <div style="text-align: center;">
            <a href="index.php" class="lien-retour">&larr; Retour à la page de vote</a>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025/2026 - Système de Vote Électronique - MIAGE Casa</p>
        </div>
    </footer>
</body>
</html>