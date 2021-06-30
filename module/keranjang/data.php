
<table border="1" cellspacing="0" cellpadding="5px" id="data-table" class="table table-hover mt-3">
    <thead>
        <tr>
            <th>No.</th>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga Jual</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php

   require "../../inc/functions.php";
   $no=1;
   $products = $db->get_results("SELECT keranjang.*, produk.id as id_produk, produk.nama_produk, produk.harga_jual FROM keranjang LEFT JOIN produk ON keranjang.id_produk=produk.id ORDER BY keranjang.id ASC");

    if ($products) :
       foreach ($products as $product) :
        $subtotal = $product->harga_jual * $product->qty;
        $total += $subtotal;
       ?>
        <tr>
            <td width="50"><?= $no++; ?></td>
            <td><?= ucwords($product->nama_produk); ?></td>
            <td><?= $product->qty; ?></td>
            <td><?= "Rp " .number_format($product->harga_jual,0,',','.'); ?></td>
            <td><?= "Rp " .number_format($subtotal,0,',','.'); ?></td>
            <td>
                <a href="actions?action=hapus_produk_keranjang&id_produk=<?= $product->id_produk ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk <?= $product->nama_produk ?> dari keranjang?')">Hapus</a>
            </td>
        </tr>
    <?php endforeach; ?>

        <tr>
            <td colspan='4'>Total</td><td> Rp <?= number_format($total,0,',','.') ?></td>
            <td><a href='actions?action=akhiri_transaksi' class='btn btn-primary' onclick='return confirm("Akhiri transaksi?")'>Akhiri Transaksi</a></td>
        </tr>

    <?php else: ?>
        <tr><td colspan="6" style="text-align:center;">Tidak ada data</td></tr>
    <?php endif; ?>

    </tbody>
</table>

<script>
    $('#data-table').DataTable();
</script>