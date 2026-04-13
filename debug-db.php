<?php
require 'php.php';

// Show all users (no passwords)
$users = $pdo->query('SELECT id, nom, prenom, mail, role, must_change_password FROM Utilisateur ORDER BY role, nom')->fetchAll();

// Show hash comparison
$testHash = hash('sha256', 'changeme');
$testHashAdmin = hash('sha256', 'admin123');

echo "<h2>Hash PHP pour 'changeme' : " . $testHash . "</h2>";
echo "<h2>Hash PHP pour 'admin123' : " . $testHashAdmin . "</h2>";
echo "<h2>Utilisateurs en base (" . count($users) . ") :</h2>";
echo "<table border='1' cellpadding='4'>";
echo "<tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Rôle</th><th>MustChange</th><th>Hash = 'changeme' ?</th><th>Hash = 'admin123' ?</th></tr>";
foreach ($users as $u) {
    $stmtH = $pdo->prepare('SELECT password_hash FROM Utilisateur WHERE id = ?');
    $stmtH->execute([$u['id']]);
    $hash = $stmtH->fetchColumn();
    $isChangeme = ($hash === $testHash) ? '✅' : '❌';
    $isAdmin = ($hash === $testHashAdmin) ? '✅' : '❌';
    echo "<tr>";
    echo "<td>{$u['id']}</td><td>" . htmlspecialchars($u['nom']) . "</td><td>" . htmlspecialchars($u['prenom']) . "</td>";
    echo "<td>" . htmlspecialchars($u['mail']) . "</td><td>{$u['role']}</td><td>{$u['must_change_password']}</td>";
    echo "<td>$isChangeme</td><td>$isAdmin</td>";
    echo "</tr>";
}
echo "</table>";
