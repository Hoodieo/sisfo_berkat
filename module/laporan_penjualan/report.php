<?php
    require "../../inc/functions.php";

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

    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=".$judul.".xls");

    $total_penjualan = 0;
    $str_total_penjualan = "Rp " . number_format($total_penjualan,0,',','.');
?>

    <div id="preview-data-report"><h2 class="mb-4"><?= $judul ?></h2>
        <h4><?= $subtitle ?></h4>

    <table class="table table-bordered table-resposive" border="1" cellspacing="0" cellpadding="5px" id="data-table">
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
    <tbody>

    <?php
    if (count($datas) < 1) {
        echo '<tr><td colspan="6" style="text-align:center;">Tidak ada data</td></tr>';
    } else {
        foreach ($datas as $data) {
            $tanggal = date("d M Y", strtotime($data->tanggal));
            $harga_jual = "Rp " . number_format($data->harga_jual,0,',','.');
            $subtotal = "Rp " . number_format($data->subtotal,0,',','.');

            $total_penjualan += $data->subtotal;

            echo '<tr>
                        <td>'.++$i.'</td>
                        <td>'.$tanggal.'</td>
                        <td>'.ucwords($data->nama_produk).'</td>
                        <td>'.$data->qty_jual.'</td>
                        <td>'.$harga_jual.'</td>
                        <td>'.$subtotal.'</td>
                    </tr>';
        }
    }
    ?>


        <tr>
            <td colspan="5" class="text-right">Total</td>
            <td> Rp <?= number_format($total_penjualan,0,',','.') ?></td>
        </tr>
        </tbody>
        </table>
        </div>