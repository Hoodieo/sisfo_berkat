<div class="buttons mb-4 d-flex justify-content-end">
    <button id="add-button" class="btn btn-primary mr-3">Tambah</button>
</div>

<table border="1" cellspacing="0" cellpadding="5px" id="data-table" class="table table-hover">
    <thead>
        <tr>
            <th>No.</th>
            <th>Tanggal</th>
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
   $datas = $db->get_results("SELECT penjualan.*, produk.nama_produk, produk.harga_jual, (penjualan.qty_jual*produk.harga_jual) as subtotal FROM penjualan LEFT JOIN produk ON penjualan.id_produk=produk.id  ORDER BY penjualan.tanggal DESC");

    if ($datas) :
       foreach ($datas as $data) : ?>
        <tr>
            <td width="50"><?= $no++; ?></td>
            <td><?= date("d M Y", strtotime($data->tanggal)) ?></td>
            <td><?= ucwords($data->nama_produk); ?></td>
            <td><?= $data->qty_jual; ?></td>
            <td><?= "Rp " . number_format($data->harga_jual,0,',','.'); ?></td>
            <td><?= "Rp " . number_format($data->subtotal,0,',','.'); ?></td>
            <td width="150">
                <button id="edit-button" class="btn btn-sm btn-warning" data-id="<?= $data->id; ?>">Edit</button>
                <button id="delete-button" class="btn btn-sm btn-danger" data-id="<?= $data->id; ?>" data-action="penjualan_hapus">Hapus</button>
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