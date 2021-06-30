<?php
    include "../../inc/functions.php";

    // form_status (tambah/edit) -> get from script.js
    if ($_GET['form_status'] == 'penjualan_edit') {
        $id=$_GET['id'];
        $result=$db->get_row("SELECT * FROM penjualan WHERE id='$id'");
    }

    $id = (!empty($result->id)) ? $result->id : '';
    $id_produk = (!empty($result->id_produk)) ? $result->id_produk : '';
    $qty = (!empty($result->qty_jual)) ? $result->qty_jual : '';

    $form_title = ($_GET['form_status'] == 'penjualan_edit') ? 'EDIT DATA' : 'TAMBAH DATA';
    echo "<h6 class='text-muted'>$form_title</h6>";
?>

<div class="row">
    <div class="col-md-6">
        <form method="POST" id="form" data-form-status='<?= $_GET['form_status'] ?>'>
            <input type="hidden" name="id" id="id" class="form-control" value="<?= $id; ?>" />

                    <div class="form-group">
                        <label for="id_produk">Nama Produk</label>
                        <select name="id_produk" id="id_produk" class="form-control">
                            <?= getProdukStokOptions($id_produk); ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="qty">Jumlah (QTY)</label>
                        <input type="number" name="qty" id="qty" class="form-control" value="<?= $qty; ?>" min="1"/>
                    </div>

                    <div class="form-buttons">
                        <button type="button" id="cancel-button" class="btn btn-secondary mr-2" >Batal</button>
                        <button type="submit" name="simpan" id="simpan" class="btn btn-primary">Simpan</button>
                    </div>
            </table>
        </form>
    </div>
</div>

