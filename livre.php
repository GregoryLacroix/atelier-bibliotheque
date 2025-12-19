<?php
require_once('include/_header.php');
?>
<h1 class="text-center my-4">Bibliothèque | Livres</h1>

<div class="col-md-8 mx-auto">
    <table class="table table-bordered mb-5 align-middle">
        <tr>
            <th>Auteur</th>
            <th>Titre</th>
            <th class="text-end">Actions</th>
        </tr>
        <tr>
            <td>Lorem</td>
            <td>Lorem</td>
            <td class="text-end">
                <a href="" class="btn btn-primary">Modifier</a>
                <a href="" class="btn btn-danger">Supprimer</a>
            </td>
        </tr>
    </table>

    <form action="" method="post">
        <div class="mb-3">
            <label for="auteur" class="form-label">Auteur</label>
            <input type="text" class="form-control" id="auteur" name="auteur" placeholder="Saisir un auteur">
        </div>
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" class="form-control" id="titre" name="titre" placeholder="Saisir un titre">
        </div>
        <input type="submit" value="Enregistrer" class="btn btn-dark">
    </form>
</div>

<?php
require_once('include/_footer.php');
?>