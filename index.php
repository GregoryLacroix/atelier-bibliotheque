<?php
require_once('include/_init.php');

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

echo '<pre>';
print_r($_POST);
echo '</pre>';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $borderDanger = "border border-danger";

    $idAbonne = $_POST['abonne'];
    $idLivre = $_POST['livre'];
    $dateSortie = $_POST['date_sortie'];
    $dateRendu = $_POST['date_rendu'];

    if(empty($idAbonne)){
        $error = true;
        $msgErrorIdAbonne = "Merci de sélectionner un abonné";
    }

    if(empty($idLivre)){
        $error = true;
        $msgErrorIdLivre = "Merci de sélectionner un livre";
    }
}

require_once('include/_header.php');
?>
<h1 class="text-center my-4">Bibliothèque | Emprunts</h1>

<div class="mx-auto">
    <table class="table table-bordered mb-5 align-middle">
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
            <?php foreach($data as $array): ?>
            <tr>
                <td><?=  $array['id_emprunt'] ?></td>
                <td><?=  $array['auteur'] ?></td>
                <td><?=  $array['titre'] ?></td>
                <td><?=  $array['prenom'] ?></td>
                <td><?=  $array['date_sortie'] ?></td>
                <td>
                <?php 
                if(empty($array['date_rendu'])): ?>
                    <span class='badge bg-warning'>Non rendu</span>
                <?php 
                else: 
                    echo $array['date_rendu'];
                endif
                ?>
                </td>
                <td class="text-end">
                    <a href="?action=update&id=<?= $array['id_emprunt'] ?>" class="btn btn-primary">Modifier</a>
                    <a href="?action=delete&id=<?= $array['id_emprunt'] ?>" class="btn btn-danger">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <form action="" method="post">
        <div class="mb-3">
            <!-- <label for="abonne" class="form-label">Séléctionner un abonné</label> -->
            <select class="form-select <?php if(isset($msgErrorIdAbonne)) echo $borderDanger ?>" id="abonne" name="abonne">
                <option value="" selected>Séléctionner un abonné</option>
                <?php foreach($dataSelectFormAbonne as $array): ?>
                    <option value="<?= $array['id_abonne'] ?>"><?= $array['id_abonne'] ?> - <?= $array['prenom'] ?></option>
                <?php endforeach; ?>
            </select>
            <small class="text-danger"><?php if(isset($msgErrorIdAbonne)) echo $msgErrorIdAbonne ?></small>
        </div>
        <div class="mb-3">
            <!-- <label for="livre" class="form-label">Séléctionner un livre</label> -->
            <select class="form-select <?php if(isset($msgErrorIdLivre)) echo $borderDanger ?>" id="livre" name="livre">
                <option value="" selected>Séléctionner un livre</option>
                <?php foreach($dataSelectFormLivre as $array): ?>
                    <option value="<?= $array['id_livre'] ?>"><?= $array['id_livre'] ?> - <?= $array['auteur'] ?> - <?= $array['titre'] ?></option>
                <?php endforeach; ?>
            </select>
            <small class="text-danger"><?php if(isset($msgErrorIdLivre)) echo $msgErrorIdLivre ?></small>
        </div>
        <div class="mb-3">
            <label for="date_sortie" class="form-label">Date sortie</label>
            <input type="date" class="form-control" id="date_sortie" name="date_sortie">
        </div>
        <div class="mb-3">
            <label for="date_rendu" class="form-label">Date retour</label>
            <input type="date" class="form-control" id="date_rendu" name="date_rendu">
        </div>
        <input type="submit" value="Enregistrer" class="btn btn-dark">
    </form>
</div>

<?php
require_once('include/_footer.php');
?>