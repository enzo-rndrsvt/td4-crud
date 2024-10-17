<?php

function getUsers() {
    $jsonFile = 'users.json';

    if (!file_exists($jsonFile)) {
        return [];
    }

    $jsonData = file_get_contents($jsonFile);
    return json_decode($jsonData, true);
}

function saveUser($prenom, $email, $motdepasse) {
    $jsonFile = 'users.json';

    $users = getUsers();

    $nouvelUtilisateur = [
        'prenom' => $prenom,
        'email' => $email,
        'motdepasse' => password_hash($motdepasse, PASSWORD_DEFAULT), 
    ];

    $users[] = $nouvelUtilisateur;

    file_put_contents($jsonFile, json_encode($users, JSON_PRETTY_PRINT));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];

    saveUser($prenom, $email, $motdepasse);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


$utilisateurs = getUsers();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h1>Formulaire d'inscription</h1>


<form method="POST" action="">
    <label for="prenom">Prénom :</label><br>
    <input type="text" id="prenom" name="prenom" required><br><br>

    <label for="email">Email :</label><br>
    <input type="email" id="email" name="email" required><br><br>

    <label for="motdepasse">Mot de passe :</label><br>
    <input type="password" id="motdepasse" name="motdepasse" required><br><br>

    <button type="submit">S'inscrire</button>
</form>

<h2>Liste des utilisateurs</h2>


<table>
    <thead>
        <tr>
            <th>Prénom</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($utilisateurs) > 0): ?>
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                    <td><?php echo htmlspecialchars($utilisateur['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($utilisateur['email']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">Aucun utilisateur enregistré.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
