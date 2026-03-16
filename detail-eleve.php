<?php
require 'session.php';
requireRole('prof', 'admin');
require 'php.php';

$eleveId = (int)($_GET['id'] ?? 0);
if ($eleveId <= 0) {
    header('Location: notes-classe.php');
    exit;
}

$stmt = $pdo->prepare("SELECT id, nom, prenom, mail FROM Utilisateur WHERE id = ? AND role = 'etudiant'");
$stmt->execute([$eleveId]);
$eleve = $stmt->fetch();

if (!$eleve) {
    http_response_code(404);
    die('<p>Élève introuvable.</p>');
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $matiere     = trim($_POST['matiere'] ?? '');
        $note        = (float)($_POST['note'] ?? -1);
        $appreciation = trim($_POST['appreciation'] ?? '');
        if ($matiere !== '' && $note >= 0 && $note <= 20) {
            $stmt = $pdo->prepare('INSERT INTO Notes (etudiant_id, matiere, note, appreciation) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE note = VALUES(note), appreciation = VALUES(appreciation)');
            $stmt->execute([$eleveId, $matiere, $note, $appreciation ?: null]);
        }
    } elseif ($action === 'edit') {
        $noteId      = (int)($_POST['note_id'] ?? 0);
        $matiere     = trim($_POST['matiere'] ?? '');
        $note        = (float)($_POST['note'] ?? -1);
        $appreciation = trim($_POST['appreciation'] ?? '');
        if ($noteId > 0 && $matiere !== '' && $note >= 0 && $note <= 20) {
            $stmt = $pdo->prepare('UPDATE Notes SET matiere = ?, note = ?, appreciation = ? WHERE id = ? AND etudiant_id = ?');
            $stmt->execute([$matiere, $note, $appreciation ?: null, $noteId, $eleveId]);
        }
    } elseif ($action === 'delete') {
        $noteId = (int)($_POST['note_id'] ?? 0);
        if ($noteId > 0) {
            $stmt = $pdo->prepare('DELETE FROM Notes WHERE id = ? AND etudiant_id = ?');
            $stmt->execute([$noteId, $eleveId]);
        }
    }

    header('Location: detail-eleve.php?id=' . $eleveId);
    exit;
}

$stmt = $pdo->prepare('SELECT id, matiere, note, appreciation, date_saisie FROM Notes WHERE etudiant_id = ? ORDER BY matiere');
$stmt->execute([$eleveId]);
$notes = $stmt->fetchAll();

$moyenne = null;
if ($notes) {
    $moyenne = array_sum(array_column($notes, 'note')) / count($notes);
}

$editId = (int)($_GET['edit'] ?? 0);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail élève — Portfolio BTS SIO1</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        main { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: middle; }
        th { background: #007bff; color: #fff; }
        tr:nth-child(even) { background: #f9f9f9; }
        .moyenne { margin-top: 16px; font-weight: bold; font-size: 1.1rem; }
        .back-link { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .btn { display: inline-block; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.85rem; text-decoration: none; }
        .btn-edit  { background: #ffc107; color: #212529; }
        .btn-del   { background: #dc3545; color: #fff; }
        .btn-save  { background: #28a745; color: #fff; }
        .btn-cancel{ background: #6c757d; color: #fff; }
        .btn-add   { background: #007bff; color: #fff; margin-top: 16px; padding: 8px 16px; }
        .form-section { background: #f0f4ff; border: 1px solid #c0d0ff; border-radius: 6px; padding: 16px; margin-top: 24px; }
        .form-section h3 { margin-top: 0; }
        .form-row { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
        .form-row label { display: flex; flex-direction: column; font-size: 0.9rem; gap: 4px; }
        .form-row input, .form-row textarea, .form-row select { padding: 6px 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 0.95rem; }
        .inline-input { width: 100%; padding: 4px 6px; border: 1px solid #aaa; border-radius: 4px; box-sizing: border-box; }
        .action-btns { display: flex; gap: 6px; }
    </style>
</head>
<body>
<?php require 'header.php'; ?>
<main>
    <h2>Notes de <?= htmlspecialchars($eleve['prenom'] . ' ' . $eleve['nom']) ?></h2>
    <p><em><?= htmlspecialchars($eleve['mail']) ?></em></p>

    <?php if (empty($notes)): ?>
        <p>Aucune note disponible.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>Note /20</th>
                    <th>Appréciation</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($notes as $n): ?>
                <?php if ($editId === (int)$n['id']): ?>
                <tr style="background:#fffbe6;">
                    <form method="post" action="detail-eleve.php?id=<?= $eleveId ?>">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="note_id" value="<?= (int)$n['id'] ?>">
                        <td><input class="inline-input" type="text" name="matiere" value="<?= htmlspecialchars($n['matiere']) ?>" required></td>
                        <td><input class="inline-input" type="number" name="note" step="0.5" min="0" max="20" value="<?= $n['note'] ?>" required style="width:70px;"></td>
                        <td><input class="inline-input" type="text" name="appreciation" value="<?= htmlspecialchars($n['appreciation'] ?? '') ?>"></td>
                        <td><?= htmlspecialchars($n['date_saisie']) ?></td>
                        <td class="action-btns">
                            <button type="submit" class="btn btn-save">Enregistrer</button>
                            <a href="detail-eleve.php?id=<?= $eleveId ?>" class="btn btn-cancel">Annuler</a>
                        </td>
                    </form>
                </tr>
                <?php else: ?>
                <tr>
                    <td><?= htmlspecialchars($n['matiere']) ?></td>
                    <td><?= number_format($n['note'], 2) ?></td>
                    <td><?= htmlspecialchars($n['appreciation'] ?? '') ?></td>
                    <td><?= htmlspecialchars($n['date_saisie']) ?></td>
                    <td class="action-btns">
                        <a href="detail-eleve.php?id=<?= $eleveId ?>&edit=<?= (int)$n['id'] ?>" class="btn btn-edit">Modifier</a>
                        <form method="post" action="detail-eleve.php?id=<?= $eleveId ?>" style="display:inline;" onsubmit="return confirm('Supprimer cette note ?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="note_id" value="<?= (int)$n['id'] ?>">
                            <button type="submit" class="btn btn-del">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p class="moyenne">Moyenne : <?= number_format($moyenne, 2) ?> / 20</p>
    <?php endif; ?>

    <!-- Formulaire ajout note -->
    <div class="form-section">
        <h3>Ajouter une note</h3>
        <form method="post" action="detail-eleve.php?id=<?= $eleveId ?>">
            <input type="hidden" name="action" value="add">
            <div class="form-row">
                <label>
                    Matière
                    <input type="text" name="matiere" list="matieres-list" placeholder="Ex: Contrôle 1" required>
                    <datalist id="matieres-list">
                        <option value="Contrôle 1">
                        <option value="Contrôle 2">
                        <option value="TP">
                        <option value="Projet">
                    </datalist>
                </label>
                <label>
                    Note /20
                    <input type="number" name="note" step="0.5" min="0" max="20" placeholder="ex: 14.5" required style="width:90px;">
                </label>
                <label style="flex:1;">
                    Appréciation (optionnel)
                    <input type="text" name="appreciation" placeholder="Commentaire...">
                </label>
                <button type="submit" class="btn btn-add">Ajouter</button>
            </div>
        </form>
    </div>

    <a class="back-link" href="notes-classe.php">&larr; Retour à la liste</a>
</main>
</body>
</html>
