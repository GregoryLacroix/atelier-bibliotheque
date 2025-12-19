<?php
require_once('include/_init.php');

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];

        // On selectionne tout dans la table emprunt en fonction de l'id de l'abonne dans l'url, celui que l'on souhaite supprimé
        $data = $connect_db->prepare("SELECT * FROM emprunt WHERE livre_id = :livre_id");
        $data->bindValue(':livre_id', $id, PDO::PARAM_INT);
        $data->execute();

        // Si le requete de sélection ne retourne aucun résultat, cela veut dire que l'abonné n'a aucun emprunt, alors on peut le supprimé
        if (!$data->rowCount()) {
            $data = $connect_db->prepare("DELETE FROM livgre WHERE id_livre = :id_livre");
            $data->bindValue(':id_livre', $id, PDO::PARAM_INT);
            $data->execute();

            $_SESSION['messageValidation'] = "Le livre a été supprimé";
        } else {
            // Sinon l'abonné a des emprunts dans la bibliothèque, on affiche un message d'erreur
            $_SESSION['errorDeleteMessage'] = "Impossible de supprimer le livre. Des emprunts y sont associés";
        }

        header('location: livre.php');
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'update') {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];
        $data = $connect_db->prepare("SELECT * FROM livre WHERE id_livre = :id_livre");
        $data->bindValue(':id_livre', $id, PDO::PARAM_INT);
        $data->execute();

        // echo "Nombre de résultat : " . $data->rowCount();
        if (!$data->rowCount()) {
            header('location: livre.php');
            exit;
        }

        $arrayUpdateLivre = $data->fetch(PDO::FETCH_ASSOC);
        // echo '<pre>';
        // print_r($arrayUpdateAbonne);
        // echo '</pre>';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auteur = $_POST['auteur'];
            $titre = $_POST['titre'];
            $pdoStatement = $connect_db->prepare("UPDATE livre SET auteur = :auteur, titre = :titre WHERE id_livre = :id_livre");
            $pdoStatement->bindValue(':auteur', $auteur, PDO::PARAM_STR);
            $pdoStatement->bindValue(':titre', $titre, PDO::PARAM_STR);
            $pdoStatement->bindValue(':id_livre', $id, PDO::PARAM_INT);
            $pdoStatement->execute();

            $_SESSION['messageValidation'] = "Le livre a été modifié";

            header('location: livre.php');
            exit;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $borderDanger = 'border border-danger';

    $auteur = $_POST['auteur'];
    $titre = $_POST['titre'];
    if (empty($auteur)) {
        $error = true;
        $msgErrorAuteur = "Merci de saisir un auteur";
    }

    if (empty($titre)) {
        $error = true;
        $msgErrorTitre = "Merci de saisir un titre";
    }

    if (!isset($error)) {
        $pdoStatement = $connect_db->prepare("INSERT INTO livre (auteur, titre) VALUE (:auteur, :titre)");
        $pdoStatement->bindValue(':auteur', $auteur, PDO::PARAM_STR);
        $pdoStatement->bindValue(':titre', $titre, PDO::PARAM_STR);
        $pdoStatement->execute();

        $_SESSION['messageValidation'] = "Le livre a été enregistré";

        header('location: livre.php');
        exit;
    }
}

$pdoStatement = $connect_db->query("SELECT * FROM livre");
$data = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

require_once('include/_header.php');
?>
<h1 class="text-center my-4">Bibliothèque | Livres</h1>

<p class="text-center text-success fw-bold">
    <?php
    if (isset($_SESSION['messageValidation'])) echo $_SESSION['messageValidation'];
    unset($_SESSION['messageValidation']); ?>
</p>

<p class="text-center text-danger fw-bold">
    <?php
    if (isset($_SESSION['errorDeleteMessage'])) echo $_SESSION['errorDeleteMessage'];
    unset($_SESSION['errorDeleteMessage']); ?>
</p>

<div class="col-md-8 mx-auto">
    <table class="table table-bordered mb-5 align-middle" id="table-bibliotheque">
        <thead>
            <tr>
                <th>Auteur</th>
                <th>Titre</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $array): ?>
            <tr>
                <td><?= $array['auteur'] ?></td>
                <td><?= $array['titre'] ?></td>
                <td class="text-end">
                    <a href="?action=update&id=<?= $array['id_livre'] ?>" class="btn btn-primary">Modifier</a>
                    <a href="?action=delete&id=<?= $array['id_livre'] ?>" class="btn btn-danger">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <form action="" method="post" class="mt-5">
        <div class="mb-3">
            <label for="auteur" class="form-label">Auteur</label>
            <input type="text" class="form-control <?php if (isset($msgErrorAuteur)) echo $borderDanger; ?>" id="auteur" name="auteur" placeholder="Saisir un auteur" value="<?php if (isset($arrayUpdateLivre['auteur'])) echo $arrayUpdateLivre['auteur']; ?>">
        </div>
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" class="form-control <?php if (isset($msgErrorTitre)) echo $borderDanger; ?>" id="titre" name="titre" placeholder="Saisir un titre" value="<?php if (isset($arrayUpdateLivre['titre'])) echo $arrayUpdateLivre['titre']; ?>">
        </div>
        <input type="submit" value="Enregistrer" class="btn btn-dark">
    </form>
</div>

<?php
require_once('include/_footer.php');
?>