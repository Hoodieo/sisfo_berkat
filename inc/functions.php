<?php
error_reporting(~E_NOTICE);
session_start();
date_default_timezone_set('Asia/Jakarta');

ini_set('max_execution_time', 60 * 1);
ini_set('memory_limit', '256M');
ini_set('upload_max_filesize', '32M');

include 'config.php';
include 'db.php';
$db = new DB($config['server'], $config['username'], $config['password'], $config['database_name']);
include 'general.php';

check_last_activity();

function checkMenuActive($menu){
    if (!isset($_GET['m']) && $menu=='index' ) {
        return 'active';
    } elseif (isset($_GET['m']) && $menu==$_GET['m']) {
        return 'active';
    }

    return null;
}

function getMonthInd($month){
    switch ($month) {
        case 1:
            $month = 'Januari';
            break;
        case 2:
            $month = 'Februari';
            break;
        case 3:
            $month = 'Maret';
            break;
        case 4:
            $month = 'April';
            break;
        case 5:
            $month = 'Mei';
            break;
        case 6:
            $month = 'Juni';
            break;
        case 7:
            $month = 'Juli';
            break;
        case 8:
            $month = 'Agustus';
            break;
        case 9:
            $month = 'September';
            break;
        case 10:
            $month = 'Oktober';
            break;
        case 11:
            $month = 'November';
            break;

        default:
            $month = 'Desember';
            break;
    }
    return $month;
}

function getSupplierOptions($selected = ''){
    global $db;
    $a = '';
    $rows = $db->get_results("SELECT * FROM supplier ORDER BY id");
    foreach($rows as $row){
        if($row->id==$selected)
            $a.="<option value='$row->id' selected>".ucwords($row->nama_supplier)."</option>";
        else
            $a.="<option value='$row->id'>".ucwords($row->nama_supplier)."</option>";
    }
    return $a;
}

function getProdukOptions($selected = ''){
    global $db;
    $a = '';
    $rows = $db->get_results("SELECT * FROM produk ORDER BY id");
    foreach($rows as $row){
        if($row->id==$selected)
            $a.="<option value='$row->id' selected>".ucwords($row->nama_produk)."</option>";
        else
            $a.="<option value='$row->id'>".ucwords($row->nama_produk)."</option>";
    }
    return $a;
}

function getProdukStokOptions($selected = ''){
    global $db;
    $a = '';
    $rows = $db->get_results("SELECT * FROM produk ORDER BY id");
    foreach($rows as $row){
        if($row->id==$selected)
            $a.="<option value='$row->id' selected>".ucwords($row->nama_produk)." [Stok: $row->qty]</option>";
        else
            $a.="<option value='$row->id'>".ucwords($row->nama_produk)." [Stok: $row->qty]</option>";
    }
    return $a;
}