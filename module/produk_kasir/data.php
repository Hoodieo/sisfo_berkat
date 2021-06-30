<?php
   require "../../inc/functions.php";
   $no=1;
   $products = $db->get_results("SELECT produk.*, supplier.nama_supplier FROM produk LEFT JOIN supplier ON produk.id_supplier=supplier.id WHERE produk.harga_jual>0 ORDER BY produk.id ASC");
    $keranjang = $db->get_row("SELECT count(id) as jumlah_brg FROM keranjang");
?>

<div class="buttons mb-4 d-flex justify-content-end">
    <a href="index?m=keranjang" class="btn btn-secondary mr-3">Keranjang (<?= $keranjang->jumlah_brg ?>)</a>
</div>

<table border="1" cellspacing="0" cellpadding="5px" id="data-table" class="table table-hover">
    <thead>
        <tr>
            <th>No.</th>
            <th>Foto Produk</th>
            <th>Produk</th>
            <th>Stok</th>
            <th>Harga Jual</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
<?php
    if ($products) :
       foreach ($products as $product) : ?>
        <tr>
            <td width="50"><?= $no++; ?></td>
            <td class="text-center"><img src="assets/upload/<?= $product->content; ?>" alt="Not available" width="120" height="80"></td>
            <td><?= ucwords($product->nama_produk); ?></td>
            <td><?= $product->qty; ?></td>
            <td><?= "Rp " . number_format($product->harga_jual,0,',','.'); ?></td>
            <td>
                <form action="actions?action=tambah_keranjang" method="POST">
                <input type="hidden" name="id_produk" value="<?= $product->id ?>">
                <div class="form-group">
                    <label>Qty</label>
                    <input type="number" min="0" max="<?= $product->qty; ?>" name="qty" required>
                </div>

                <button type="submit" class="btn btn-sm btn-primary" <?= ($product->qty < 1) ? 'disabled' : '' ?> >Tambah ke keranjang</button>
                </form>
            </td>
        </tr>
    <?php endforeach;
    else: ?>
        <tr><td colspan="7" style="text-align:center;">Tidak ada data produk</td></tr>
    <?php endif; ?>

    </tbody>
</table>

<script>
    $('#data-table').DataTable();
</script>