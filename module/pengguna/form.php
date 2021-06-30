<?php
    include "../../inc/functions.php";

    // form_status (tambah/edit) -> get from script.js
    if ($_GET['form_status'] == 'pengguna_edit') {
        $id=$_GET['id'];
        $result=$db->get_row("SELECT * FROM pengguna WHERE id='$id'");
    }

    $id = (!empty($result->id)) ? $result->id : '';
    $username = (!empty($result->username)) ? $result->username : '';
    $status = (!empty($result->status)) ? $result->status : '';

    $form_title = ($_GET['form_status'] == 'posts_edit') ? 'EDIT DATA' : 'TAMBAH DATA';
    echo "<h6 class='text-muted'>$form_title</h6>";
?>

<div class="row">
    <div class="col-md-6">
        <form method="POST" id="form" data-form-status='<?= $_GET['form_status'] ?>'>
            <input type="hidden" name="id" id="id" class="form-control" value="<?= $id; ?>" />

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" required value="<?= $username; ?>" />
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="custom-select" required>
                            <option value="Kasir" <?= ($status=='Kasir') ? 'selected' : '' ?> >Kasir</option>
                            <option value="Gudang" <?= ($status=='Gudang') ? 'selected' : '' ?> >Gudang</option>
                            <option value="Owner" <?= ($status=='Owner') ? 'selected' : '' ?> >Owner</option>
                        </select>
                    </div>

                    <div class="form-buttons">
                        <button type="button" id="cancel-button" class="btn btn-secondary mr-2" >Batal</button>
                        <button type="submit" name="simpan" id="simpan" class="btn btn-primary">Simpan</button>
                    </div>
            </table>
        </form>
    </div>
</div>

