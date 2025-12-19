<?php
require_once('include/_init.php');

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];

        // On selectionne tout dans la table emprunt en fonction de l'id de l'abonne dans l'url, celui que l'on souhaite supprimé
        $data = $connect_db->prepare("DELETE FROM emprunt WHERE id_emprunt = :id_emprunt");
        $data->bindValue(':id_emprunt', $id, PDO::PARAM_INT);
        $data->execute();

        $_SESSION['msgValidate'] = "L'emprunt a été supprimé";

        header('location: index.php');
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'update') {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];
        $data = $connect_db->prepare("SELECT * FROM emprunt WHERE id_emprunt = :id_emprunt");
        $data->bindValue(':id_emprunt', $id, PDO::PARAM_INT);
        $data->execute();

        // echo "Nombre de résultat : " . $data->rowCount();
        if (!$data->rowCount()) {
            header('location: index.php');
            exit;
        }

        $arrayUpdateEmprunt = $data->fetch(PDO::FETCH_ASSOC);
        // echo '<pre>';
        // print_r($arrayUpdateEmprunt);
        // echo '</pre>';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idAbonne = $_POST['abonne'];
            $idLivre = $_POST['livre'];
            $dateSortie = $_POST['date_sortie'];
            $dateRendu = $_POST['date_rendu'];

            if (empty($dateRendu)) {
                $dateRendu = null;
            }

            $pdoStatement = $connect_db->prepare("UPDATE emprunt SET livre_id = :livre_id, abonne_id = :abonne_id, date_sortie = :date_sortie, date_rendu = :date_rendu WHERE id_emprunt = :id_emprunt");
            $pdoStatement->bindValue(':livre_id', $idLivre, PDO::PARAM_INT);
            $pdoStatement->bindValue(':abonne_id', $idAbonne, PDO::PARAM_INT);
            $pdoStatement->bindValue(':date_sortie', $dateSortie, PDO::PARAM_STR);
            $pdoStatement->bindValue(':date_rendu', $dateRendu, PDO::PARAM_STR);
            $pdoStatement->bindValue(':id_emprunt', $id, PDO::PARAM_INT);
            $pdoStatement->execute();

            $_SESSION['msgValidate'] = "L'emprunt a été modifié";

            header('location: index.php');
            exit;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borderDanger = "border border-danger";

    $idAbonne = $_POST['abonne'];
    $idLivre = $_POST['livre'];
    $dateSortie = $_POST['date_sortie'];
    $dateRendu = $_POST['date_rendu'];

    if (empty($idAbonne)) {
        $error = true;
        $msgErrorIdAbonne = "Merci de sélectionner un abonné";
    }

    if (empty($idLivre)) {
        $error = true;
        $msgErrorIdLivre = "Merci de sélectionner un livre";
    }

    if (empty($dateSortie)) {
        $error = true;
        $msgErrorDateSortie = "Merci de saisir une date";
    }

    if (empty($dateRendu)) {
        $dateRendu = null;
    }

    if (!isset($error)) {
        $pdoStatement = $connect_db->prepare("INSERT INTO emprunt (abonne_id, livre_id, date_sortie, date_rendu) VALUE (:abonne_id, :livre_id, :date_sortie, :date_rendu)");
        $pdoStatement->bindValue(':abonne_id', $idAbonne, PDO::PARAM_INT);
        $pdoStatement->bindValue(':livre_id', $idLivre, PDO::PARAM_INT);
        $pdoStatement->bindValue(':date_sortie', $dateSortie, PDO::PARAM_STR);
        $pdoStatement->bindValue(':date_rendu', $dateRendu, PDO::PARAM_STR);
        $pdoStatement->execute();

        $_SESSION['msgValidate'] = "L'emprunt a été enregistré";

        header('location: index.php');
        exit;
    }
}

// Selection des emprunt
$pdoStatement = $connect_db->query(
    "
SELECT emprunt.id_emprunt, livre.auteur, livre.titre, abonne.prenom, emprunt.date_sortie, emprunt.date_rendu
FROM abonne
INNER JOIN emprunt 
ON abonne.id_abonne = emprunt.abonne_id
INNER JOIN livre
ON emprunt.livre_id = livre.id_livre
ORDER BY date_sortie
"
);

$data = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

// Séléction des abonnés pour le selecteur du formulaire 
$pdoStatement = $connect_db->query("SELECT * FROM abonne");
$dataSelectFormAbonne = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

// Séléction des livres pour le selecteur du formulaire 
$pdoStatement = $connect_db->query("SELECT * FROM livre");
$dataSelectFormLivre = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

require_once('include/_header.php');
?>
<h1 class="text-center my-4">Bibliothèque | Emprunts</h1>

<p class="text-center text-success fw-bold">
    <?php
    if (isset($_SESSION['msgValidate'])) echo $_SESSION['msgValidate'];
    unset($_SESSION['msgValidate']); ?>
</p>

<div class="mx-auto">
    <table class="table table-bordered mb-5 align-middle" id="table-bibliotheque">
        <thead>
            <tr>
                <th>N° emprunt</th>
                <th>Auteur</th>
                <th>Titre</th>
                <th>Abonne</th>
                <th>Date sortie</th>
                <th>Date retour</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $array): ?>
                <tr>
                    <td><?= $array['id_emprunt'] ?></td>
                    <td><?= $array['auteur'] ?></td>
                    <td><?= $array['titre'] ?></td>
                    <td><?= $array['prenom'] ?></td>
                    <td><?= $array['date_sortie'] ?></td>
                    <td>
                        <?php
                        if (empty($array['date_rendu'])): ?>
                            <span class='badge bg-warning'>Non rendu</span>
                        <?php
                        else:
                            echo $array['date_rendu'];
                        endif
                        ?>
                    </td>
                    <td class="d-flex gap-2">
                        <a href="?action=update&id=<?= $array['id_emprunt'] ?>" class="btn btn-primary">Modifier</a>
                        <a href="?action=delete&id=<?= $array['id_emprunt'] ?>" class="btn btn-danger">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <form action="" method="post" class="mt-5">
        <div class="mb-3">
            <!-- <label for="abonne" class="form-label">Séléctionner un abonné</label> -->
            <select class="form-select <?php if (isset($msgErrorIdAbonne)) echo $borderDanger ?>" id="abonne" name="abonne">

                <option value="">Séléctionner un abonné</option>

                <?php foreach ($dataSelectFormAbonne as $array): ?>

                    <option value="<?= $array['id_abonne'] ?>" <?php if (isset($arrayUpdateEmprunt['abonne_id']) && $arrayUpdateEmprunt['abonne_id'] === $array['id_abonne']) echo 'selected'; ?>><?= $array['id_abonne'] ?> - <?= $array['prenom'] ?></option>

                <?php endforeach; ?>
            </select>
            <small class="text-danger"><?php if (isset($msgErrorIdAbonne)) echo $msgErrorIdAbonne ?></small>
        </div>
        <div class="mb-3">
            <!-- <label for="livre" class="form-label">Séléctionner un livre</label> -->
            <select class="form-select <?php if (isset($msgErrorIdLivre)) echo $borderDanger ?>" id="livre" name="livre">

                <option value="">Séléctionner un livre</option>

                <?php foreach ($dataSelectFormLivre as $array): ?>
                    <option value="<?= $array['id_livre'] ?>" <?php if (isset($arrayUpdateEmprunt['livre_id']) && $arrayUpdateEmprunt['livre_id'] === $array['id_livre']) echo 'selected'; ?>><?= $array['id_livre'] ?> - <?= $array['auteur'] ?> - <?= $array['titre'] ?></option>
                <?php endforeach; ?>
            </select>
            <small class="text-danger"><?php if (isset($msgErrorIdLivre)) echo $msgErrorIdLivre ?></small>
        </div>
        <div class="mb-3">
            <label for="date_sortie" class="form-label">Date sortie</label>
            <input type="date" class="form-control <?php if (isset($msgErrorDateSortie)) echo $borderDanger ?>" id="date_sortie" name="date_sortie" value="<?php if (isset($arrayUpdateEmprunt['date_sortie'])) echo $arrayUpdateEmprunt['date_sortie'] ?>">
            <small class="text-danger"><?php if (isset($msgErrorDateSortie)) echo $msgErrorDateSortie ?></small>
        </div>
        <div class="mb-3">
            <label for="date_rendu" class="form-label">Date retour</label>
            <input type="date" class="form-control" id="date_rendu" name="date_rendu" value="<?php if (isset($arrayUpdateEmprunt['date_rendu']) && $arrayUpdateEmprunt['date_rendu'] !== null) echo $arrayUpdateEmprunt['date_rendu'] ?>">
        </div>
        <input type="submit" value="Enregistrer" class="btn btn-dark">
    </form>
</div>

<?php
require_once('include/_footer.php');
?>