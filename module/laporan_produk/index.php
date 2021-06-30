<h3>Laporan Produk</h3>

<div id="content-data">

<div class="buttons mb-3 d-flex justify-content-end">
    <a href="module/laporan_produk/report" class="btn btn-outline-secondary">Cetak</a>
</div>

<table border="1" cellspacing="0" cellpadding="5px" id="data-table" class="table table-hover">
    <thead>
        <tr>
            <th>No.</th>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Supplier</th>
        </tr>
    </thead>
    <tbody>

    <?php
   $no=1;
   $products = $db->get_results("SELECT produk.*, supplier.nama_supplier FROM produk LEFT JOIN supplier ON produk.id_supplier=supplier.id ORDER BY produk.harga_jual ASC");

    if ($products) :
       foreach ($products as $product) : ?>
        <tr>
            <td width="50"><?= $no++; ?></td>
            <td><?= ucwords($product->nama_produk); ?></td>
            <td><?= $product->qty; ?></td>
            <td><?= "Rp " . number_format($product->harga_beli,0,',','.'); ?></td>
            <td><?= "Rp " . number_format($product->harga_jual,0,',','.'); ?></td>
            <td><?= $product->nama_supplier; ?></td>
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
</div>

