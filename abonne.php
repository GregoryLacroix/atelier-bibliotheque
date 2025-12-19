<?php
require_once('include/_header.php');
?>
<h1 class="text-center my-4">Bibliothèque | Abonnés</h1>

<div class="col-md-6 mx-auto">
    <table class="table table-bordered mb-5 align-middle">
        <tr>
            <th>Prénom</th>
            <th class="text-end">Actions</th>
        </tr>
        <tr>
            <td>Lorem</td>
            <td class="text-end">
                <a href="" class="btn btn-primary">Modifier</a>
                <a href="" class="btn btn-danger">Supprimer</a>
            </td>
        </tr>
    </table>

    <form action="" method="post">
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Saisir un prénom">
        </div>
        <input type="submit" value="Enregistrer" class="btn btn-dark">
    </form>
</div>

<?php
require_once('include/_footer.php');
?>