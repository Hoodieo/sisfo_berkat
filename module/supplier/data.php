<div class="buttons mb-4 d-flex justify-content-end">
    <button id="add-button" class="btn btn-primary mr-3">Tambah</button>
</div>

<table border="1" cellspacing="0" cellpadding="5px" id="data-table" class="table table-hover">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama Supplier</th>
            <th>Alamat</th>
            <th>No Telp/Handphone</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php

   require "../../inc/functions.php";
   $no=1;
   $users = $db->get_results("SELECT * FROM supplier ORDER BY id");

    if ($users) :
       foreach ($users as $user) : ?>
        <tr>
            <td width="50"><?= $no++; ?></td>
            <td><?= $user->nama_supplier; ?></td>
            <td><?= $user->alamat; ?></td>
            <td><?= ($user->no_hp) ? $user->no_hp : '-'; ?></td>
            <td width="150">
                <button id="edit-button" class="btn btn-sm btn-warning" data-id="<?= $user->id; ?>">Edit</button>
                <button id="delete-button" class="btn btn-sm btn-danger" data-id="<?= $user->id; ?>" data-action="supplier_hapus">Hapus</button>
            </td>
        </tr>
    <?php endforeach;
    else: ?>
        <tr><td colspan="5" style="text-align:center;">Tidak ada data</td></tr>
    <?php endif; ?>

    </tbody>
</table>

<script>
    $('#data-table').DataTable();
</script>