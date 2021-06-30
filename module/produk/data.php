<div class="buttons mb-4 d-flex justify-content-end">
    <button id="add-button" class="btn btn-primary mr-3">Tambah</button>
</div>

<table border="1" cellspacing="0" cellpadding="5px" id="data-table" class="table table-hover">
    <thead>
        <tr>
            <th>No.</th>
            <th>Foto Produk</th>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Supplier</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php

   require "../../inc/functions.php";
   $no=1;
   $products = $db->get_results("SELECT produk.*, supplier.nama_supplier FROM produk LEFT JOIN supplier ON produk.id_supplier=supplier.id ORDER BY produk.harga_jual ASC");

    if ($products) :
       foreach ($products as $product) : ?>
        <tr>
            <td width="50"><?= $no++; ?></td>
            <td class="text-center"><img src="assets/upload/<?= $product->content; ?>" alt="Not available" width="120" height="80"></td>
            <td><?= ucwords($product->nama_produk); ?></td>
            <td><?= $product->qty; ?></td>
            <td><?= "Rp " . number_format($product->harga_beli,0,',','.'); ?></td>
            <td><?= "Rp " . number_format($product->harga_jual,0,',','.'); ?></td>
            <td><?= $product->nama_supplier; ?></td>
            <td width="150">
                <button id="edit-button" class="btn btn-sm btn-warning" data-id="<?= $product->id; ?>">Edit</button>
                <button id="delete-button" class="btn btn-sm btn-danger" data-id="<?= $product->id; ?>" data-action="produk_hapus">Hapus</button>
            </td>
        </tr>
    <?php endforeach;
    else: ?>
        <tr><td colspan="7" style="text-align:center;">Tidak ada data</td></tr>
    <?php endif; ?>

    </tbody>
</table>

<script>
    $('#data-table').DataTable();
</script>