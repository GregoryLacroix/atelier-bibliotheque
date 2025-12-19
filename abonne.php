<?php
require_once('include/_init.php');

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];

        // On selectionne tout dans la table emprunt en fonction de l'id de l'abonne dans l'url, celui que l'on souhaite supprimé
        $data = $connect_db->prepare("SELECT * FROM emprunt WHERE abonne_id = :abonne_id");
        $data->bindValue(':abonne_id', $id, PDO::PARAM_INT);
        $data->execute();

        // Si le requete de sélection ne retourne aucun résultat, cela veut dire que l'abonné n'a aucun emprunt, alors on peut le supprimé
        if (!$data->rowCount()) {
            $data = $connect_db->prepare("DELETE FROM abonne WHERE id_abonne = :id_abonne");
            $data->bindValue(':id_abonne', $id, PDO::PARAM_INT);
            $data->execute();

            $_SESSION['messageValidation'] = "L'abonné a été supprimé";
        } else {
            // Sinon l'abonné a des emprunts dans la bibliothèque, on affiche un message d'erreur
            $_SESSION['errorDeleteMessage'] = "Impossible de supprimer l'abonné. Des emprunts y sont associés";
        }

        header('location: abonne.php');
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'update') {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];
        $data = $connect_db->prepare("SELECT * FROM abonne WHERE id_abonne = :id_abonne");
        $data->bindValue(':id_abonne', $id, PDO::PARAM_INT);
        $data->execute();

        // echo "Nombre de résultat : " . $data->rowCount();
        if (!$data->rowCount()) {
            header('location: abonne.php');
            exit;
        }

        $arrayUpdateAbonne = $data->fetch(PDO::FETCH_ASSOC);
        // echo '<pre>';
        // print_r($arrayUpdateAbonne);
        // echo '</pre>';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $prenom = $_POST['prenom'];
            $pdoStatement = $connect_db->prepare("UPDATE abonne SET prenom = :prenom WHERE id_abonne = :id_abonne");
            $pdoStatement->bindValue(':prenom', $prenom, PDO::PARAM_STR);
            $pdoStatement->bindValue(':id_abonne', $id, PDO::PARAM_INT);
            $pdoStatement->execute();

            $_SESSION['messageValidation'] = "L'abonné a été modifié";

            header('location: abonne.php');
            exit;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $borderDanger = 'border border-danger';

    $prenom = $_POST['prenom'];
    if (!empty($prenom)) {
        $pdoStatement = $connect_db->prepare("INSERT INTO abonne (prenom) VALUES (:prenom)");
        $pdoStatement->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $pdoStatement->execute();

        $_SESSION['messageValidation'] = "L'abonné a été enregistré";

        header('location: abonne.php');
        exit;
    } else {
        $message = "Merci de saisir un prénom";
    }
}

$pdoStatement = $connect_db->query("SELECT * FROM abonne");
$data = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

require_once('include/_header.php');
?>
<h1 class="text-center my-4">Bibliothèque | Abonnés</h1>

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

<div class="col-md-6 mx-auto">
    <table class="table table-bordered mb-5 align-middle">
        <tr>
            <th>Prénom</th>
            <th class="text-end">Actions</th>
        </tr>
        <?php foreach ($data as $array): ?>
            <tr>
                <td><?= $array['prenom'] ?></td>
                <td class="text-end">
                    <a href="?action=update&id=<?= $array['id_abonne'] ?>" class="btn btn-primary">Modifier</a>
                    <a href="?action=delete&id=<?= $array['id_abonne'] ?>" class="btn btn-danger">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <form action="" method="post">
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>

            <input type="text" class="form-control <?php if (isset($message)) echo $borderDanger; ?>" id="prenom" name="prenom" placeholder="Saisir un prénom" value="<?php if (isset($arrayUpdateAbonne['prenom'])) echo $arrayUpdateAbonne['prenom']; ?>">

            <small class="text-danger"><?php if (isset($message)) echo $message; ?></small>
        </div>
        <input type="submit" value="Enregistrer" class="btn btn-dark">
    </form>
</div>

<?php
require_once('include/_footer.php');
?>