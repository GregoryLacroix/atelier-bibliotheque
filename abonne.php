<?php
require_once('include/_init.php');

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

            $messageValidation = "L'abonné a été modifié";
            $arrayUpdateAbonne['prenom'] = '';
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $borderDanger = 'border border-danger';

    $prenom = $_POST['prenom'];
    if (!empty($prenom)) {
        $pdoStatement = $connect_db->prepare("INSERT INTO abonne (prenom) VALUES (:prenom)");
        $pdoStatement->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $pdoStatement->execute();

        $messageValidation = "L'abonné a été enregistré";
    } else {
        $message = "Merci de saisir un prénom";
    }
}

$pdoStatement = $connect_db->query("SELECT * FROM abonne");
$data = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

require_once('include/_header.php');
?>
<h1 class="text-center my-4">Bibliothèque | Abonnés</h1>

<p class="text-center text-success fw-bold"><?php if (isset($messageValidation)) echo $messageValidation;  ?></p>

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