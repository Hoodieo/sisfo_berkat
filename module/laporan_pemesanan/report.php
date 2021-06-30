<?php
    require "../../inc/functions.php";

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

    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=".$judul.".xls");

    $html = '<div id="preview-data-report"><h4 align="center" class="mb-4">'.$judul.'</h4>
        <h6>'.$subtitle.'</h6>';

    $html .= '<table class="table table-bordered table-resposive" border="1" cellspacing="0" cellpadding="5px" id="data-table">
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