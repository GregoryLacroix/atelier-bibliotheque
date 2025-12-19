<?php
require_once('include/_init.php');

echo '<pre>';
print_r($_POST);
echo '</pre>';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $message = '';

    $prenom = $_POST['prenom'];
    if(!empty($prenom)){
        // requete insertion
    }else{
        $message = "Merci de saisir un prénom";
    }
}

$pdoStatement = $connect_db->query("SELECT * FROM abonne");
$data = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

require_once('include/_header.php');
?>
<h1 class="text-center my-4">Bibliothèque | Abonnés</h1>

<div class="col-md-6 mx-auto">
    <table class="table table-bordered mb-5 align-middle">
        <tr>
            <th>Prénom</th>
            <th class="text-end">Actions</th>
        </tr>
        <?php foreach($data as $array): ?>
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
            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Saisir un prénom">
            <small class="text-danger"><?php if(isset($message)) echo $message; ?></small>
        </div>
        <input type="submit" value="Enregistrer" class="btn btn-dark">
    </form>
</div>

<?php
require_once('include/_footer.php');
?>