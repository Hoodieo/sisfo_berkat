<?php
require "inc/functions.php";

$module     = explode('_', $_GET['action'])[0];
$_action    = explode('_', $_GET['action'])[1];

// ACTION LOGIN, LOGOUT, UPLOAD IMAGE, CHANGE PASSWORD, UPDATE PROFILE
switch ($_GET['action']){

    // LOGIN
    case 'check_login':
        $username	= $_POST['username'];
        $password	= $_POST['password'];

        $result = $db->get_row("SELECT * FROM pengguna WHERE username='$username' AND password='$password'");

        if ($result) {
            $_SESSION['userid']     = $result->id;
            $_SESSION['username']   = $result->username;
            $_SESSION['password']   = $result->password;
            $_SESSION['status']     = $result->status;

            // true = login successfully (redirect to index)
            echo true;
        } else {
            echo false;
        }
        break;

    // LOGOUT
    case 'logout':
        session_unset();
        session_destroy();

        redirect_js('login');
        break;

    // UPLOAD IMAGE
    case 'upload_image':
        $temp = "assets/upload/";
        if (!file_exists($temp))
            mkdir($temp);

        $filename       = $_POST['newfilename'];
        $fileupload     = $_FILES['fileupload']['tmp_name'];
        $ImageName      = $_FILES['fileupload']['name'];
        $ImageType      = $_FILES['fileupload']['type'];

        if (!empty($fileupload)){
            move_uploaded_file($_FILES["fileupload"]["tmp_name"], $temp.$filename); // Menyimpan file

            echo "File berhasil diupload!"; //<message>_<alert-style>
        } else {
            echo "Gagal upload file!";
        }
        break;

    // CHANGE-UPDATE PASSWORD
    case 'change_password':
        $old_password   = $_POST['password_old'];
        $new_password   = $_POST['password_new'];
        $con_password   = $_POST['password_conf'];

        $user = $db->get_row("SELECT * FROM users WHERE id='$_SESSION[userid]' AND password='$old_password'");

        if ($new_password == $con_password) {
            if ($user) {
                $query = $db->query("UPDATE users SET password='$new_password' WHERE id='$_SESSION[userid]'");
                echo "Password diupdate!#info";
                exit;
            } else {
                echo "Password salah!#danger";
                exit;
            }
        } else {
            echo "Password tidak sama!#danger";
            exit;
        }
        break;

    // UPDATE MY PROFILE DATA
    case 'profile_update':
        $id         = trim($_POST['userid']);
        $username   = trim($_POST['username']);

        $query = $db->query("UPDATE users SET username='$username' WHERE id='$id'");
        $_SESSION['username'] = $username;

        if ($query) {
            echo "Profil diupdate!#info";
            exit;
        } else {
            echo "Failed to update data. Details:".$query."#danger";
            exit;
        }
        break;

    // GET SUPPLIER DATA
    case 'get_supplier':
        $id_supplier = $_GET['id_supplier'];

        $result = $db->get_row("SELECT nama_supplier, alamat FROM supplier WHERE id=$id_supplier");
        echo $result->nama_supplier.'#'.$result->alamat;
        break;

    // TAMBAH KERANJANG
    case 'tambah_keranjang':
        $id_produk = $_POST['id_produk'];
        $qty = $_POST['qty'];

        $result_produk = $db->get_row("SELECT qty FROM produk WHERE id=$id_produk");
        $result_keranjang = $db->get_row("SELECT qty FROM keranjang WHERE id_produk=$id_produk");

        if ($result) {
            $_qty = $result_keranjang->qty + $qty;
            // update qty produk
            $db->query("UPDATE keranjang SET qty=$_qty WHERE id_produk=$id_produk");

        } else {
            // add new produk to keranjang
            $db->query("INSERT INTO keranjang(id, id_produk, qty) VALUES (NULL, $id_produk, $qty)");
        }

        $new_qty = $result_produk->qty - $qty;
        $db->query("UPDATE produk SET qty=$new_qty WHERE id=$id_produk");

        echo "<script>alert('Berhasil menambahkan ke keranjang!')</script>";
        redirect_js('index?m=produk_kasir');
        break;

    // HAPUS PRODUK DARI KERANJANG
    case 'hapus_produk_keranjang':
        $id_produk = $_GET['id_produk'];

        $result_produk = $db->get_row("SELECT qty FROM produk WHERE id=$id_produk");
        $result_keranjang = $db->get_row("SELECT qty FROM keranjang WHERE id_produk=$id_produk");
        $new_qty = $result_produk->qty + $result_keranjang->qty;

        $db->query("UPDATE produk SET qty=$new_qty WHERE id=$id_produk");
        $db->query("DELETE FROM keranjang WHERE id_produk=$id_produk");
        echo "<script>alert('Produk berhasil dihapus dari keranjang!')</script>";
        redirect_js('index?m=keranjang');
        break;

    // AKHIRI TRANSAKSI
    case 'akhiri_transaksi':
        $current_date   = date('Y-m-d');
        $results = $db->get_results("SELECT * FROM keranjang");

        foreach ($results as $result) {
            $db->query("INSERT INTO penjualan(id, tanggal, qty_jual, id_produk) VALUES (NULL, '$current_date', $result->qty, $result->id_produk)");
        }

        $db->query('TRUNCATE keranjang');
        echo "<script>alert('Transaksi sukses!')</script>";
        redirect_js('index?m=produk_kasir');
        break;

    // PREVIEW LAPORAN PENJUALAN
    case 'preview_lap_penjualan':
        $judul = '';
        $subtitle = '';
        $where = '';

        if ($_GET['laporan'] == 'harian') {
            $judul = 'Laporan Penjualan Harian';
            $subtitle = 'Tanggal: '.date("d M Y", strtotime($_GET['tanggal']));
            $where = " WHERE penjualan.tanggal='$_GET[tanggal]'" ;

        } else if ($_GET['laporan'] == 'bulanan') {
            $judul = 'Laporan Penjualan Bulanan';
            $subtitle = 'Bulan: '.date("M Y", strtotime($_GET['bulan']));
            $new_date = $_GET['bulan'].'-01';
            $where = "WHERE CONCAT(YEAR(tanggal),'-',MONTH(tanggal)) = CONCAT(YEAR('".$new_date."'),'-',MONTH('".$new_date."'))";

        } else if ($_GET['laporan'] == 'tahunan') {
            $judul = 'Laporan Penjualan Tahun '.$_GET['tahun'];
            $where = "WHERE YEAR(tanggal)='$_GET[tahun]'";
        }

        $query = "SELECT penjualan.*, produk.nama_produk, produk.harga_jual, (penjualan.qty_jual*produk.harga_jual) as subtotal FROM penjualan LEFT JOIN produk ON penjualan.id_produk=produk.id $where ORDER BY penjualan.tanggal DESC";

        $datas = $db->get_results($query);

        $total_penjualan = 0;
        $str_total_penjualan = "Rp " . number_format($total_penjualan,0,',','.');

        $html = '<div id="preview-data-report"><h4 align="center" class="mb-4">Preview '.$judul.'</h4>
            <h6>'.$subtitle.'</h6>';

        $html .= '<table class="table table-bordered table-resposive">
        <thead>
            <tr>
            <th scope="col">No.</th>
            <th scope="col">Tanggal</th>
            <th scope="col">Produk</th>
            <th scope="col">Qty</th>
            <th scope="col">Harga Jual</th>
            <th scope="col">Subtotal</th>
            </tr>
        </thead>
        <tbody>';

        if (count($datas) < 1) {
        $html .= '<tr><td colspan="6" style="text-align:center;">Tidak ada data</td></tr>';
        } else {
            foreach ($datas as $data) {
                $tanggal = date("d M Y", strtotime($data->tanggal));
                $harga_jual = "Rp " . number_format($data->harga_jual,0,',','.');
                $subtotal = "Rp " . number_format($data->subtotal,0,',','.');

                $total_penjualan += $data->subtotal;

                $html .= '<tr>
                            <td>'.++$i.'</td>
                            <td>'.$tanggal.'</td>
                            <td>'.ucwords($data->nama_produk).'</td>
                            <td>'.$data->qty_jual.'</td>
                            <td>'.$harga_jual.'</td>
                            <td>'.$subtotal.'</td>
                        </tr>';
            }
        }

        $html .= '<tr>
                    <td colspan="5" class="text-right">Total</td>
                    <td> Rp '.number_format($total_penjualan,0,',','.').'</td>
                </tr></tbody></table></div>';

        echo $html;
        break;

    // PREVIEW LAPORAN PEMESANAN
    case 'preview_lap_pemesanan':
        $judul = '';
        $subtitle = '';
        $where = '';

        if ($_GET['laporan'] == 'harian') {
            $judul = 'Laporan Pemesanan Harian';
            $subtitle = 'Tanggal: '.date("d M Y", strtotime($_GET['tanggal']));
            $where = " WHERE pemesanan.tanggal_pesan='$_GET[tanggal]'" ;

        } else if ($_GET['laporan'] == 'bulanan') {
            $judul = 'Laporan Pemesanan Bulanan';
            $subtitle = 'Bulan: '.date("M Y", strtotime($_GET['bulan']));
            $new_date = $_GET['bulan'].'-01';
            $where = "WHERE CONCAT(YEAR(tanggal_pesan),'-',MONTH(tanggal_pesan)) = CONCAT(YEAR('".$new_date."'),'-',MONTH('".$new_date."'))";

        } else if ($_GET['laporan'] == 'tahunan') {
            $judul = 'Laporan Pemesanan Tahun '.$_GET['tahun'];
            $where = "WHERE YEAR(tanggal_pesan)='$_GET[tahun]'";
        }

        $query = "SELECT pemesanan.*, supplier.nama_supplier, produk.nama_produk FROM pemesanan LEFT JOIN produk ON pemesanan.id_produk=produk.id LEFT JOIN supplier ON pemesanan.id_supplier=supplier.id $where ORDER BY pemesanan.tanggal_pesan DESC";

        $datas = $db->get_results($query);

        $html = '<div id="preview-data-report"><h4 align="center" class="mb-4">Preview '.$judul.'</h4>
            <h6>'.$subtitle.'</h6>';

        $html .= '<table class="table table-bordered table-resposive">
        <thead>
            <tr>
            <th scope="col">No.</th>
            <th scope="col">Tanggal</th>
            <th scope="col">Produk</th>
            <th scope="col">Qty</th>
            <th scope="col">Harga Beli</th>
            <th scope="col">Status</th>
            <th scope="col">Jenis Pembayaran</th>
            <th scope="col">Supplier</th>
            </tr>
        </thead>
        <tbody>';

        if (count($datas) < 1) {
        $html .= '<tr><td colspan="8" style="text-align:center;">Tidak ada data</td></tr>';
        } else {
            foreach ($datas as $data) {
                $tanggal = date("d M Y", strtotime($data->tanggal_pesan));
                $harga_beli = "Rp " . number_format($data->harga_beli,0,',','.');

                $html .= '<tr>
                            <td>'.++$i.'</td>
                            <td>'.$tanggal.'</td>
                            <td>'.ucwords($data->nama_produk).'</td>
                            <td>'.$data->qty.'</td>
                            <td>'.$harga_beli.'</td>
                            <td>'.ucwords($data->status).'</td>
                            <td>'.ucwords($data->jenis_pembayaran).'</td>
                            <td>'.ucwords($data->nama_supplier).'</td>
                        </tr>';
            }
        }
        $html .= '</tbody></table></div>';
        echo $html;
        break;
}


// MODULE ACTIONS
switch ($module){
    // =================== PENGGUNA ===================
    case 'pengguna':
        $id       = trim($_POST['id']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $status   = trim($_POST['status']);

        switch ($_action) {
            case 'tambah':
                $result = $db->get_row("SELECT * FROM pengguna WHERE username='$username'");

                if (!$result) {
                    $query = $db->query("INSERT INTO pengguna(id, username, password, status) VALUES (NULL,'$username','123456','$status')");

                    if ($query) { echo "Data berhasil disimpan!#info"; }
                } else {
                    echo "Gagal simpan data. Username tidak tersedia!#danger";
                }
                break;

            case 'edit':
                $result = $db->get_row("SELECT * FROM pengguna WHERE username='$username' AND id=$_SESSION[userid]");

                if (!$result) {
                    $query = $db->query("UPDATE pengguna SET username='$username', status='$status' WHERE id='$id'");

                    if ($query) { echo "Data berhasil diupdate!#info"; }
                } else {
                    echo "Gagal update data. Username tidak tersedia!#danger";
                }
                break;

            case 'hapus':
                $query = $db->query("DELETE FROM pengguna WHERE id='$id'");

                if ($query) {
                    echo "Data dihapus!#info";
                }
                break;
        }
    break;

    // =================== SUPPLIER ===================
    case 'supplier':
        $id             = trim($_POST['id']);
        $nama_supplier  = trim($_POST['nama_supplier']);
        $alamat         = trim($_POST['alamat']);
        $no_hp          = trim($_POST['nohp']);

        switch ($_action) {
            case 'tambah':
                $query = $db->query("INSERT INTO supplier(id, nama_supplier, alamat, no_hp) VALUES (NULL,'$nama_supplier','$alamat','$no_hp')");

                if ($query) { echo "Data berhasil disimpan!#info"; }
                break;

            case 'edit':
                $query = $db->query("UPDATE supplier SET nama_supplier='$nama_supplier', alamat='$alamat', no_hp='$no_hp' WHERE id='$id'");

                if ($query) { echo "Data berhasil diupdate!#info"; }
                break;

            case 'hapus':
                $query = $db->query("DELETE FROM supplier WHERE id='$id'");

                if ($query) { echo "Data dihapus!#info"; }
                break;
        }
    break;

    // =================== PRODUK ===================
    case 'produk':
        $id             = trim($_POST['id']);
        $nama_produk    = trim($_POST['nama_produk']);
        $qty            = trim($_POST['qty']);
        $harga_beli     = trim($_POST['harga_beli']);
        $harga_jual     = trim($_POST['harga_jual']);
        $id_supplier    = trim($_POST['id_supplier']);
        $content        = ($_POST['image_url']) ? $_POST['image_url'] : $_POST['tmp_image_url'] ;

        switch ($_action) {
            case 'tambah':
                if ($harga_beli > $harga_jual) {
                    echo "Gagal menyimpan data. Harga jual harus lebih tinggi dari harga beli#danger";
                    exit();
                }

                $query = $db->query("INSERT INTO produk(id, nama_produk, qty, harga_beli, harga_jual, id_supplier, content) VALUES (NULL,'$nama_produk', $qty, $harga_beli, $harga_jual, $id_supplier, '$content')");

                if ($query) { echo "Data berhasil disimpan!#info"; }
                break;

            case 'edit':
                if ($harga_beli > $harga_jual) {
                    echo "Gagal update data. Harga jual harus lebih tinggi dari harga beli#danger";
                    exit();
                }

                $query = $db->query("UPDATE produk SET nama_produk='$nama_produk', qty=$qty, harga_beli=$harga_beli, harga_jual=$harga_jual, id_supplier='$id_supplier', content='$content' WHERE id='$id'");

                if ($query) { echo "Data berhasil diupdate!#info"; }
                break;

            case 'hapus':
                $query = $db->query("DELETE FROM produk WHERE id='$id'");

                if ($query) { echo "Data dihapus!#info"; }
                break;
        }
    break;

    // =================== PEMESANAN ===================
    case 'pemesanan':
        $id             = trim($_POST['id']);
        $id_produk      = trim($_POST['produk']);
        $nama_produk    = trim($_POST['nama_produk']);
        $qty            = trim($_POST['qty']);
        $harga_beli     = trim($_POST['harga_beli']);

        $id_supplier    = trim($_POST['supplier']);
        $nama_supplier  = trim($_POST['nama_supplier']);
        $alamat_supplier = trim($_POST['alamat_supplier']);

        $status         = trim($_POST['status']);
        $jml_diterima   = trim($_POST['jml_diterima']);
        $jenis_pembayaran = trim($_POST['pembayaran']);
        $current_date   = date('Y-m-d');

        switch ($_action) {
            case 'tambah':
                // check jumlah (qty) jika status 'diterima sebagian'
                if ($jml_diterima > $qty) {
                    echo "Gagal menyimpan data. Jumlah yang diterima melebihi jumlah yang dipesan#danger";
                    exit();
                }

                // check and get id supplier
                if (!$id_supplier) {
                    // add new supplier
                    $db->query("INSERT INTO supplier(id, nama_supplier, alamat, no_hp) VALUES (NULL,'$nama_supplier','$alamat_supplier','')");

                    // get id of new supplier
                    $result = $db->get_row("SELECT id FROM supplier WHERE nama_supplier='$nama_supplier' AND alamat='$alamat_supplier'");
                    $id_supplier = $result->id;
                }

                // check and get id product
                if (!$id_produk) {
                    // data product baru
                    $db->query("INSERT INTO produk(id, nama_produk, qty, harga_beli, harga_jual, id_supplier) VALUES (NULL,'$nama_produk', 0, $harga_beli, 0, $id_supplier)");

                    // get id of new product
                    $result = $db->get_row("SELECT id FROM produk WHERE nama_produk='$nama_produk' AND harga_beli=$harga_beli AND id_supplier=$id_supplier");
                    $id_produk = $result->id;
                }

                $query = $db->query("INSERT INTO pemesanan(id, tanggal_pesan, id_produk, qty, harga_beli, status, jenis_pembayaran, id_supplier) VALUES (NULL, '$current_date', $id_produk, $qty, $harga_beli, '$status', '$jenis_pembayaran', $id_supplier)");

                if ($query) { echo "Data berhasil disimpan!#info"; }
                break;

            case 'edit':
                if ($status == 'diterima sebagian') {
                    // pesan ulang
                    $_qty = $qty - $jml_diterima;

                    if ($_qty > 0) {
                        $query = $db->query("INSERT INTO pemesanan(id, tanggal_pesan, id_produk, qty, harga_beli, status, jenis_pembayaran, id_supplier) VALUES (NULL, '$current_date', $id_produk, $_qty, $harga_beli, 'sedang diproses', '$jenis_pembayaran', $id_supplier)");
                    }

                    // tambahkan stok ke produk baru
                    $result = $db->get_row("SELECT qty FROM produk WHERE id=$id_produk");

                    $qty_baru = $result->qty + $jml_diterima;
                    $db->query("UPDATE produk SET qty=$qty_baru WHERE id='$id_produk'");
                }

                // jika status
                if ($status == 'diterima semua') {
                    $result = $db->get_row("SELECT qty FROM produk WHERE id=$id_produk");

                    $qty_baru = $result->qty + $qty;
                    $db->query("UPDATE produk SET qty=$qty_baru WHERE id='$id_produk'");
                }

                $query = $db->query("UPDATE pemesanan SET id_produk=$id_produk, qty=$qty, harga_beli=$harga_beli, status='$status', jenis_pembayaran='$jenis_pembayaran', id_supplier=$id_supplier WHERE id='$id'");

                if ($query) { echo "Data berhasil diupdate!#info"; }
                break;

            case 'hapus':
                $query = $db->query("DELETE FROM pemesanan WHERE id='$id'");

                if ($query) { echo "Data dihapus!#info"; }
                break;
        }
    break;

    // =================== PENJUALAN ===================
    case 'penjualan':
        $id         = trim($_POST['id']);
        $id_produk  = trim($_POST['id_produk']);
        $qty        = trim($_POST['qty']);
        $current_date   = date('Y-m-d');

        switch ($_action) {
            case 'tambah':
                // check product stock
                $result = $db->get_row("SELECT qty FROM produk WHERE id=$id_produk");
                $new_qty = $result->qty - $qty;

                if ($qty > $result->qty) {
                    echo "Gagal menyimpan data. Jumlah stok tidak mencukupi#danger";
                } else {
                    $query = $db->query("INSERT INTO penjualan(id, tanggal, qty_jual, id_produk) VALUES (NULL, '$current_date', $qty, $id_produk)");

                    $query = $db->query("UPDATE produk SET qty=$new_qty WHERE id=$id_produk");

                    echo "Data berhasil disimpan!#info";
                }
                break;

            case 'edit':
                $query = $db->query("UPDATE penjualan SET id_produk=$id_produk, qty_jual=$qty WHERE id=$id");
                echo "Data berhasil diupdate!#info";
                break;

            case 'hapus':
                $result_jual = $db->get_row("SELECT id_produk, qty_jual FROM penjualan WHERE id=$id");
                $result_produk = $db->get_row("SELECT qty FROM produk WHERE id=$result_jual->id_produk");
                $new_qty = $result_produk->qty + $result_jual->qty_jual;

                $db->query("DELETE FROM penjualan WHERE id=$id");
                $db->query("UPDATE produk SET qty=$new_qty WHERE id=$result_jual->id_produk");


                echo "Data dihapus!#info";
                break;
        }
    break;
}
?>