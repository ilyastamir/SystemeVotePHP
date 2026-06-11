<?php
// ============================================
// Fichier : index.php
// Page principale - Affichage des candidats et formulaire de vote
// ============================================
require_once 'config/database.php';

// Récupérer tous les candidats depuis la base de données
$stmt = $pdo->query("SELECT * FROM candidats ORDER BY id ASC");
$candidats = $stmt->fetchAll();

// Gestion des messages
$message = '';
$typeMessage = '';

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'succes') {
        $message = 'Votre vote a été enregistré avec succès !';
        $typeMessage = 'succes';
    } elseif ($_GET['msg'] === 'deja_vote') {
        $message = 'Vous avez déjà voté. Chaque étudiant ne peut voter qu\'une seule fois.';
        $typeMessage = 'erreur';
    } elseif ($_GET['msg'] === 'erreur') {
        $message = 'Une erreur est survenue lors du traitement de votre vote.';
        $typeMessage = 'erreur';
    } elseif ($_GET['msg'] === 'champs_vides') {
        $message = 'Veuillez remplir tous les champs obligatoires.';
        $typeMessage = 'erreur';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Vote Électronique</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Système de Vote Électronique</h1>
            <p>Élection du Délégué de Promotion 2025/2026</p>
        </div>
    </header>

    <main class="container">
        <!-- Message utilisateur -->
        <?php if ($message): ?>
            <div class="message <?php echo $typeMessage; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Section : Liste des candidats -->
        <section class="section-candidats">
            <h2>Candidats</h2>
            <div class="candidats-grid">
                <?php if (count($candidats) > 0): ?>
                    <?php foreach ($candidats as $candidat): ?>
                        <div class="carte-candidat">
                            <div class="candidat-photo">
                                <img src="<?php echo htmlspecialchars($candidat['photo']); ?>"
                                     alt="Photo de <?php echo htmlspecialchars($candidat['nom']); ?>">
                            </div>
                            <div class="candidat-info">
                                <h3><?php echo htmlspecialchars($candidat['nom']); ?></h3>
                                <p class="programme"><?php echo nl2br(htmlspecialchars($candidat['programme'])); ?></p>
                            </div>
                            <form method="POST" action="vote.php" class="form-vote-rapide">
                                <input type="hidden" name="id_candidat" value="<?php echo $candidat['id']; ?>">
                                <input type="text" name="id_etudiant" placeholder="Votre identifiant étudiant" required>
                                <button type="submit" class="btn-voter">Voter</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="aucun-candidat">Aucun candidat n'est disponible pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Section : Formulaire de vote -->
        <section class="section-vote">
            <h2>Formulaire de Vote</h2>
            <form method="POST" action="vote.php" class="formulaire-vote" id="formVote">
                <div class="form-groupe">
                    <label for="id_etudiant">Identifiant Étudiant <span class="obligatoire">*</span></label>
                    <input type="text" id="id_etudiant" name="id_etudiant" placeholder="Ex : ETU2025001" required>
                </div>
                <div class="form-groupe">
                    <label for="id_candidat">Choisir un Candidat <span class="obligatoire">*</span></label>
                    <select id="id_candidat" name="id_candidat" required>
                        <option value="">-- Sélectionnez un candidat --</option>
                        <?php foreach ($candidats as $candidat): ?>
                            <option value="<?php echo $candidat['id']; ?>">
                                <?php echo htmlspecialchars($candidat['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Soumettre mon vote</button>
            </form>
        </section>

        <!-- Lien vers les résultats -->
        <div class="lien-resultats">
            <a href="resultats.php" class="btn-resultats">Voir les résultats du scrutin</a>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025/2026 - Système de Vote Électronique - MIAGE Casa</p>
        </div>
    </footer>

    <script>
        // Confirmation avant soumission du vote
        document.getElementById('formVote').addEventListener('submit', function(e) {
            var idEtudiant = document.getElementById('id_etudiant').value.trim();
            var idCandidat = document.getElementById('id_candidat').value;

            if (!idEtudiant || !idCandidat) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
                return;
            }

            if (!confirm('Confirmez-vous votre vote ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });

        // Confirmation pour les formulaires rapides sur les cartes
        document.querySelectorAll('.form-vote-rapide').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                var idEtudiant = form.querySelector('input[name="id_etudiant"]').value.trim();
                if (!idEtudiant) {
                    e.preventDefault();
                    alert('Veuillez saisir votre identifiant étudiant.');
                    return;
                }
                if (!confirm('Confirmez-vous votre vote ? Cette action est irréversible.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>