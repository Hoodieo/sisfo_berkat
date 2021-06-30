<div id="sidebar" class='active'>
    <div class="sidebar-wrapper active">
    <div class="sidebar-header">
        <a href="index" style="text-decoration: none;"><h4>Toko Berkat</h4></a>
    </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class='sidebar-title'>Menu</li>

                <?php if ($_SESSION['status']=='kasir') : ?>
                    <li class="sidebar-item <?=checkMenuActive('produk_kasir')?>">
                    <a href="index?m=produk_kasir" class='sidebar-link'>
                        <i data-feather="triangle" width="20"></i>
                        <span>Produk</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($_SESSION['status']!='kasir') : ?>
                <li class="sidebar-item <?=checkMenuActive('produk')?>">
                    <a href="index?m=produk" class='sidebar-link'>
                        <i data-feather="triangle" width="20"></i>
                        <span>Produk</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($_SESSION['status']=='kasir') : ?>
                <li class="sidebar-item <?=checkMenuActive('penjualan')?>">
                    <a href="index?m=penjualan" class='sidebar-link'>
                        <i data-feather="triangle" width="20"></i>
                        <span>Penjualan</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($_SESSION['status']!='kasir') : ?>
                <li class="sidebar-item <?=checkMenuActive('pemesanan')?>">
                    <a href="index?m=pemesanan" class='sidebar-link'>
                        <i data-feather="triangle" width="20"></i>
                        <span>Pemesanan</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($_SESSION['status']=='owner') : ?>
                <li class="sidebar-item <?=checkMenuActive('supplier')?>">
                    <a href="index?m=supplier" class='sidebar-link'>
                        <i data-feather="triangle" width="20"></i>
                        <span>Supplier</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($_SESSION['status']=='owner') : ?>
                <li class="sidebar-item <?=checkMenuActive('pengguna')?>">
                    <a href="index?m=pengguna" class='sidebar-link'>
                        <i data-feather="triangle" width="20"></i>
                        <span>Pengguna</span>
                    </a>
                </li>
                <?php endif; ?>

                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i data-feather="triangle" width="20"></i>
                        <span>Laporan</span>
                    </a>

                    <ul class="submenu ">
                        <?php if ($_SESSION['status']=='kasir' || $_SESSION['status']=='owner') : ?>
                        <li><a href="index?m=laporan_penjualan">Penjualan</a></li>
                        <?php endif; ?>

                        <?php if ($_SESSION['status']!='kasir') : ?>
                        <li><a href="index?m=laporan_produk">Produk</a></li>
                        <li><a href="index?m=laporan_pemesanan">Pemesanan</a></li>
                        <?php endif; ?>
                    </ul>
                </li>

                <li class="sidebar-item <?=checkMenuActive('change_password')?>">
                    <a href="index?m=change_password" class='sidebar-link'>
                        <i data-feather="triangle" width="20"></i>
                        <span>Ganti Password</span>
                    </a>
                </li>

                <li class="sidebar-item <?=checkMenuActive('logout')?>">
                    <a href="actions?action=logout" class='sidebar-link text-danger'>
                        <i data-feather="log-out" width="20"></i>
                        <span class="text-danger">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>