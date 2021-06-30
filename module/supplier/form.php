<?php
    include "../../inc/functions.php";

    // form_status (tambah/edit) -> get from script.js
    if ($_GET['form_status'] == 'supplier_edit') {
        $id=$_GET['id'];
        $result=$db->get_row("SELECT * FROM supplier WHERE id='$id'");
    }

    $id = (!empty($result->id)) ? $result->id : '';
    $nama_supplier = (!empty($result->nama_supplier)) ? $result->nama_supplier : '';
    $alamat = (!empty($result->alamat)) ? $result->alamat : '';
    $no_hp = (!empty($result->no_hp)) ? $result->no_hp : '';

    $form_title = ($_GET['form_status'] == 'posts_edit') ? 'EDIT DATA' : 'TAMBAH DATA';
    echo "<h6 class='text-muted'>$form_title</h6>";
?>

<div class="row">
    <div class="col-md-6">
        <form method="POST" id="form" data-form-status='<?= $_GET['form_status'] ?>'>
            <input type="hidden" name="id" id="id" class="form-control" value="<?= $id; ?>" />

                    <div class="form-group">
                        <label for="nama_supplier">Nama Supplier</label>
                        <input type="text" name="nama_supplier" id="nama_supplier" class="form-control" required value="<?= $nama_supplier; ?>" />
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="2" class="form-control" required><?= $alamat; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="nohp">No Telp/Handphone</label>
                        <input type="text" name="nohp" id="nohp" class="form-control" value="<?= $no_hp; ?>" />
                    </div>

                    <div class="form-buttons">
                        <button type="button" id="cancel-button" class="btn btn-secondary mr-2" >Batal</button>
                        <button type="submit" name="simpan" id="simpan" class="btn btn-primary">Simpan</button>
                    </div>
            </table>
        </form>
    </div>
</div>

