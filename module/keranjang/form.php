<?php
    include "../../inc/functions.php";

    // form_status (tambah/edit) -> get from script.js
    if ($_GET['form_status'] == 'produk_edit') {
        $id=$_GET['id'];
        $result=$db->get_row("SELECT * FROM produk WHERE produk.id='$id'");
    }

    $id = (!empty($result->id)) ? $result->id : '';
    $nama_produk = (!empty($result->nama_produk)) ? $result->nama_produk : '';
    $qty = (!empty($result->qty)) ? $result->qty : '';
    $harga_beli = (!empty($result->harga_beli)) ? $result->harga_beli : '';
    $harga_jual = (!empty($result->harga_jual)) ? $result->harga_jual : '';
    $id_supplier = (!empty($result->id_supplier)) ? $result->id_supplier : '';

    $form_title = ($_GET['form_status'] == 'produk_edit') ? 'EDIT DATA' : 'TAMBAH DATA';
    echo "<h6 class='text-muted'>$form_title</h6>";
?>

<div class="row">
    <div class="col-md-6">
        <form method="POST" id="form" data-form-status='<?= $_GET['form_status'] ?>'>
            <input type="hidden" name="id" id="id" class="form-control" value="<?= $id; ?>" />

                    <div class="form-group">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" name="nama_produk" id="nama_produk" class="form-control" required value="<?= $nama_produk; ?>" />
                    </div>

                    <div class="form-group">
                        <label for="qty">Jumlah Stok (Qty)</label>
                        <input type="number" name="qty" id="qty" class="form-control" min="0" required value="<?= $qty; ?>" />
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="harga_beli">Harga Beli</label>
                            <input type="number" name="harga_beli" id="harga_beli" class="form-control" required value="<?= $harga_beli; ?>" min="0" />
                        </div>
                        <div class="col-md-6">
                            <label for="harga_jual">Harga Jual</label>
                            <input type="number" name="harga_jual" id="harga_jual" class="form-control" required value="<?= $harga_jual; ?>" min="0" />
                            <span class="text-danger" id="alert-harga-jual"></span>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="id_supplier">Supplier</label>
                        <select name="id_supplier" id="id_supplier" class="form-control">
                            <?= getSupplierOptions($id_supplier) ?>
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

<script>
    const hargaBeliTxt = document.getElementById('harga_beli');
    const hargaJualTxt = document.getElementById('harga_jual');
    const alertHargaJual = document.getElementById('alert-harga-jual');
    const simpanBtn = document.getElementById('simpan');

    checkHargaJualDanBeli(hargaBeliTxt.value, hargaJualTxt.value);

    hargaJualTxt.addEventListener('keyup', function(e){
        let hargaBeli = hargaBeliTxt.value;
        let hargaJual = hargaJualTxt.value;

        checkHargaJualDanBeli(hargaBeli, hargaJual);
    })

    function checkHargaJualDanBeli(hargaBeli, hargaJual) {
        if (hargaJual && hargaBeli) {
            if (parseInt(hargaBeli) > parseInt(hargaJual)) {
                alertHargaJual.textContent = 'Harga jual harus lebih tinggi dari harga beli.';
                simpan.disabled = true;
            } else {
                alertHargaJual.textContent = '';
                simpan.disabled = false;
            }
        } else {
            simpan.disabled = true;
        }
    }

</script>