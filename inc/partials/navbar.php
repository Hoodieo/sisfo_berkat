<nav class="navbar navbar-header navbar-expand navbar-light">
    <a class="sidebar-toggler" href="#"><span class="navbar-toggler-icon"></span></a>
    <button class="btn navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav d-flex align-items-center navbar-light ml-auto">
            <!-- <li class="dropdown"> -->
                <a href="index?m=profile"class="nav-link nav-link-lg nav-link-user">
                    <div class="avatar mr-1">
                        <img src="assets/images/avatar.png" alt="" srcset="">
                    </div>
                    <div class="d-none d-md-block d-lg-inline-block">
                        <?= (isset($_SESSION['username'])) ? ucwords($_SESSION['username']) : 'Unknown'; ?>
                    </div>
                </a>
            <!-- </li> -->
        </ul>
    </div>
</nav>