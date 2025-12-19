<?php
require_once('include/_header.php');
?>
<h1 class="text-center my-4">Bibliothèque | Emprunts</h1>

<div class="mx-auto">
    <table class="table table-bordered mb-5 align-middle">
        <tr>
            <th>Auteur</th>
            <th>Titre</th>
            <th>Abonne</th>
            <th>Date sortie</th>
            <th>Date retour</th>
            <th class="text-end">Actions</th>
        </tr>
        <tr>
            <td>Lorem</td>
            <td>Lorem</td>
            <td>Lorem</td>
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
            <label for="livre" class="form-label">Livre</label>
            <select class="form-select" id="livre" name="livre">
                <option selected>Séléctionner un abonné</option>
                <option value="1">3 - Lorem</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="abonne" class="form-label">Abonné</label>
            <select class="form-select" id="abonne" name="abonne">
                <option selected>Séléctionner un livre</option>
                <option value="1">105 - Lorem - Lorem</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="date_sortie" class="form-label">Date sortie</label>
            <input type="date" class="form-control" id="date_sortie" name="date_sortie">
        </div>
        <div class="mb-3">
            <label for="date_retour" class="form-label">Date retour</label>
            <input type="date" class="form-control" id="date_retour" name="date_retour">
        </div>
        <input type="submit" value="Enregistrer" class="btn btn-dark">
    </form>
</div>

<?php
require_once('include/_footer.php');
?>