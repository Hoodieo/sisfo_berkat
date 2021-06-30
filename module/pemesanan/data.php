<div class="buttons mb-4 d-flex justify-content-end">
    <button id="add-button" class="btn btn-primary mr-3">Tambah</button>
</div>

<table border="1" cellspacing="0" cellpadding="5px" id="data-table" class="table table-hover">
    <thead>
        <tr>
            <th width="10">No.</th>
            <th>Tanggal</th>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga Beli</th>
            <th>Status</th>
            <th>Jenis Pembayaran</th>
            <th>Supplier</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php

   require "../../inc/functions.php";
   $no=1;
   $datas = $db->get_results("SELECT pemesanan.*, supplier.nama_supplier, produk.nama_produk FROM pemesanan LEFT JOIN produk ON pemesanan.id_produk=produk.id LEFT JOIN supplier ON pemesanan.id_supplier=supplier.id ORDER BY pemesanan.tanggal_pesan DESC");

    if ($datas) :
       foreach ($datas as $data) : ?>
        <tr>
            <td width="50"><?= $no++; ?></td>
            <td><?= date("d M Y", strtotime($data->tanggal_pesan)) ?></td>
            <td><?= ucwords($data->nama_produk); ?></td>
            <td><?= $data->qty; ?></td>
            <td><?= "Rp " . number_format($data->harga_beli,0,',','.'); ?></td>
            <td><?= ucwords($data->status); ?></td>
            <td><?= ucwords($data->jenis_pembayaran); ?></td>
            <td><?= ucwords($data->nama_supplier); ?></td>
            <td width="150">
                <button id="edit-button" class="btn btn-sm btn-warning" data-id="<?= $data->id; ?>">Edit</button>
                <button id="delete-button" class="btn btn-sm btn-danger" data-id="<?= $data->id; ?>" data-action="pemesanan_hapus">Hapus</button>
            </td>
        </tr>
    <?php endforeach;
    else: ?>
        <tr><td colspan="9" style="text-align:center;">Tidak ada data</td></tr>
    <?php endif; ?>

    </tbody>
</table>

<script>
    $('#data-table').DataTable();
</script>