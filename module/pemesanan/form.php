<?php
    include "../../inc/functions.php";

    if ($_GET['form_status'] == 'pemesanan_edit') {
        $id=$_GET['id'];
        $result=$db->get_row("SELECT * FROM pemesanan WHERE id='$id'");
    }

    $id = (!empty($result->id)) ? $result->id : '';
    $tanggal_pesan = (!empty($result->tanggal_pesan)) ? $result->tanggal_pesan : '';
    $id_produk = (!empty($result->id_produk)) ? $result->id_produk : '';
    $qty = (!empty($result->qty)) ? $result->qty : '';
    $harga_beli = (!empty($result->harga_beli)) ? $result->harga_beli : '';
    $status = (!empty($result->status)) ? $result->status : '';
    $jenis_pembayaran = (!empty($result->jenis_pembayaran)) ? $result->jenis_pembayaran : '';
    $id_supplier = (!empty($result->id_supplier)) ? $result->id_supplier : '';

    $form_title = ($_GET['form_status'] == 'pemesanan_edit') ? 'EDIT DATA' : 'TAMBAH DATA';
    echo "<h6 class='text-muted'>$form_title</h6>";
?>

<div class="row">
    <div class="col-md-6">
        <form method="POST" id="form" data-form-status='<?= $_GET['form_status'] ?>'>
            <input type="hidden" name="id" id="id" class="form-control" value="<?= $id; ?>" />

                    <div class="form-group">
                        <label for="produk">Produk</label>
                        <select name="produk" id="produk" class="form-control" >
                            <option value="">Baru</option>
                            <?= getProdukOptions($id_produk) ?>
                        </select>
                    </div>

                    <?php if($_GET['form_status'] == 'pemesanan_tambah') : ?>
                    <div class="form-group" id="nama-produk-baru">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" name="nama_produk" id="nama_produk" class="form-control" value="" />
                    </div>
                    <?php endif; ?>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="harga_beli">Harga Beli</label>
                            <input type="number" name="harga_beli" id="harga_beli" class="form-control" required value="<?= $harga_beli; ?>" min="0" />
                        </div>
                        <div class="col-md-6">
                            <label for="qty">Jumlah (Qty) yang Dipesan</label>
                            <input type="number" name="qty" id="qty" class="form-control" required value="<?= $qty; ?>" min="0" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="supplier">Supplier</label>
                        <select name="supplier" id="supplier" class="form-control" >
                            <option value="">Baru</option>
                            <?= getSupplierOptions($id_supplier) ?>
                        </select>
                    </div>

                    <?php if($_GET['form_status'] == 'pemesanan_tambah') : ?>
                    <div class="form-group">
                        <label for="nama_supplier">Nama Supplier</label>
                        <input type="text" name="nama_supplier" id="nama_supplier" class="form-control" <?= ($_GET['form_status'] == 'pemesanan_tambah') ? 'required' : '' ?> value="" />
                    </div>

                    <div class="form-group">
                        <label for="alamat_supplier">Alamat Supplier</label>
                        <textarea name="alamat_supplier" id="alamat_supplier" rows="2" class="form-control" <?= ($_GET['form_status'] == 'pemesanan_tambah') ? 'required' : '' ?> ></textarea>
                    </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="sedang diproses" <?= ($status=='sedang diproses') ? 'selected' : '' ?> >Sedang Diproses</option>
                            <option value="diterima semua" <?= ($status=='diterima semua') ? 'selected' : '' ?> >Diterima Semua</option>
                            <option value="diterima sebagian" <?= ($status=='diterima sebagian') ? 'selected' : '' ?> >Diterima Sebagian</option>
                        </select>
                    </div>

                    <div class="form-group" id="jml-diterima-field" style="display: none;" >
                        <label for="jml_diterima">Jumlah (QTY) yang diterima</label>
                        <input type="number" name="jml_diterima" id="jml_diterima" class="form-control" value="1" min="1" max="<?= ($qty) ? $qty : '9999999' ?>" />
                    </div>

                    <div class="form-group">
                        <label for="pembayaran">Jenis Pembayaran</label>
                        <select name="pembayaran" id="pembayaran" class="form-control">
                            <option value="lunas" <?= ($jenis_pembayaran=='lunas') ? 'selected' : '' ?> >Lunas</option>
                            <option value="bayar sebagian" <?= ($jenis_pembayaran=='bayar sebagian') ? 'selected' : '' ?> >Bayar Sebagian</option>
                        </select>
                    </div>

                    <div class="form-buttons">
                        <a href="index?m=pemesanan" class="btn btn-secondary mr-2">Batal</a>
                        <button type="submit" name="simpan" id="simpan" class="btn btn-primary">Simpan</button>
                    </div>
            </table>
        </form>
    </div>
</div>

<script>
    const produkElement     = document.getElementById('produk');
    const supplier          = document.getElementById('supplier');
    const status            = document.getElementById('status');
    const namaProdukBaru    = document.getElementById('nama-produk-baru');
    const namaSupplierTxt   = document.getElementById('nama_supplier');
    const alamatSupplierTxt = document.getElementById('alamat_supplier');
    const jumlahDiterimaTxt = document.getElementById('jml-diterima-field');


    if (supplier.value) {
        if (namaSupplierTxt && alamatSupplierTxt) {
            namaSupplierTxt.disabled        = true;
            alamatSupplierTxt.disabled      = true;

            // get supplier name and address via AJAX
            $.ajax({
                type	: "GET",
                url		: "actions.php?action=get_supplier&id_supplier="+_supplier,
                success	: function(res){
                    const data = res.split("#");
                    namaSupplierTxt.value = data[0];
                    alamatSupplierTxt.value = data[1];
                }
            });
        }
    }


    produkElement.addEventListener('change', function(e){
        const _produk = e.target.value;

        if (_produk) {
            namaProdukBaru.style.display = 'none';
        } else {
            namaProdukBaru.style.display = 'block';
        }
    })

    supplier.addEventListener('change', function(e){
        const _supplier = e.target.value;

        if (_supplier) {
            namaSupplierTxt.disabled        = true;
            alamatSupplierTxt.disabled      = true;

            // get supplier name and address via AJAX
            $.ajax({
                type	: "GET",
                url		: "actions.php?action=get_supplier&id_supplier="+_supplier,
                success	: function(res){
                    const data = res.split("#");
                    namaSupplierTxt.value = data[0];
                    alamatSupplierTxt.value = data[1];
                }
            });
        } else {
            namaSupplierTxt.disabled        = false;
            alamatSupplierTxt.disabled      = false;

            // clear name and address
            namaSupplierTxt.value = '';
            alamatSupplierTxt.value = '';
        }
    })

    status.addEventListener('change', function(e){
        const _status = e.target.value;

        if (_status == 'diterima sebagian') {
            jumlahDiterimaTxt.style.display = 'block';
        } else {
            jumlahDiterimaTxt.style.display = 'none';
        }
    })
</script>