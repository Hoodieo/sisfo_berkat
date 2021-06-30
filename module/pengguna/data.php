<div class="buttons mb-4 d-flex justify-content-end">
    <button id="add-button" class="btn btn-primary mr-3">Tambah</button>
</div>

<table border="1" cellspacing="0" cellpadding="5px" id="data-table" class="table table-hover">
    <thead>
        <tr>
            <th>No.</th>
            <th>Username</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php

   require "../../inc/functions.php";
   $no=1;
   $users = $db->get_results("SELECT * FROM pengguna ORDER BY id");

    if ($users) :
       foreach ($users as $user) : ?>
        <tr>
            <td width="50"><?= $no++; ?></td>
            <td><?= $user->username; ?></td>
            <td><?= $user->status; ?></td>
            <td width="150">
                <button id="edit-button" class="btn btn-sm btn-warning" data-id="<?= $user->id; ?>">Edit</button>
                <button id="delete-button" class="btn btn-sm btn-danger" data-id="<?= $user->id; ?>" data-action="pengguna_hapus">Hapus</button>
            </td>
        </tr>
    <?php endforeach;
    else: ?>
        <tr><td colspan="4" style="text-align:center;">Tidak ada data</td></tr>
    <?php endif; ?>

    </tbody>
</table>

<script>
    $('#data-table').DataTable();
</script>