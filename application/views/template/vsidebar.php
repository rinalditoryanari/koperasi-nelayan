<body class="">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Logout Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>
                        <span class="badge badge-warning navbar-badge"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item">
                            <?php echo $this->session->userdata('code_akun') ?>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?= base_url('CLogin/logout'); ?>" class="dropdown-item">
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link" style="margin-left: 5%;">
                <span class="brand-text font-weight-light">Koperasi Nelayan</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">


                        <li class="nav-item ">
                            <a href="<?= base_url('C_menu') ?>" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    <?php if ($this->session->userdata('tipe_akun') == '2') {
                                        echo 'Beranda Super';
                                    } else if ($this->session->userdata('tipe_akun') == '1') {
                                        echo 'Beranda Admin';
                                    } else if ($this->session->userdata('tipe_akun') == '0' || $this->session->userdata('tipe_akun') == '4') {
                                        echo 'Beranda Nelayan';
                                    } ?>
                                </p>
                            </a>
                        </li>

                        <?php if ($this->session->userdata('tipe_akun') == '2') { ?>
                            <li class="nav-item">
                                <a href="<?= base_url('C_nelayan') ?>" class="nav-link">
                                    <i class="nav-icon fas fa-ship"></i>
                                    <p>
                                        <?php if ($this->session->userdata('tipe_akun') == '2') {
                                            echo 'Data Nelayan Super';
                                        } else if ($this->session->userdata('tipe_akun') == '1') {
                                            echo 'Data Nelayan Admin';
                                        } else if ($this->session->userdata('tipe_akun') == '0') {
                                            echo 'Data Nelayan Saya';
                                        } ?>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('C_ikan') ?>" class="nav-link">
                                    <i class="nav-icon fas fa-fish"> </i>
                                    <p>
                                        <p>
                                            <?php if ($this->session->userdata('tipe_akun') == '2') {
                                                echo 'Data Ikan Super';
                                            } else if ($this->session->userdata('tipe_akun') == '1') {
                                                echo 'Data Ikan Admin';
                                            } else if ($this->session->userdata('tipe_akun') == '0') {
                                                echo 'Data Ikan Saya';
                                            } ?>
                                        </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('C_akun') ?>" class="nav-link">
                                    <i class="nav-icon fas fa-user-circle"></i>
                                    <p>
                                        <p>
                                            <?php if ($this->session->userdata('tipe_akun') == '2') {
                                                echo 'Data Akun Super';
                                            } else if ($this->session->userdata('tipe_akun') == '1') {
                                                echo 'Data Akun Admin';
                                            } else if ($this->session->userdata('tipe_akun') == '0') {
                                                echo 'Data Akun Saya';
                                            } ?>
                                        </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('C_alatbahan') ?>" class="nav-link">
                                    <i class="nav-icon fas fa-anchor"></i>
                                    <p>
                                        <p>
                                            <?php if ($this->session->userdata('tipe_akun') == '2') {
                                                echo 'Data Alat Bahan Super';
                                            } else if ($this->session->userdata('tipe_akun') == '1') {
                                                echo 'Data Alat Bahan';
                                            } else if ($this->session->userdata('tipe_akun') == '0') {
                                                echo 'Data Alat Bahan Saya';
                                            } ?>
                                        </p>
                                </a>
                            </li>
                        <?php } ?>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa-solid fa-shrimp"></i>
                                <p>
                                    <?php if ($this->session->userdata('tipe_akun') == '2') {
                                        echo 'Penjualan Super';
                                    } else if ($this->session->userdata('tipe_akun') == '1') {
                                        echo 'Penjualan Admin';
                                    } else if ($this->session->userdata('tipe_akun') == '0' || $this->session->userdata('tipe_akun') == '4') {
                                        echo 'Penjualan Saya';
                                    } ?>
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('C_penjualan') ?>" class="nav-link">

                                        <i class="nav-icon fas fa-table"></i>
                                        <p>
                                            <?php if ($this->session->userdata('tipe_akun') == '2') {
                                                echo 'Data Penjualan Super';
                                            } else if ($this->session->userdata('tipe_akun') == '1') {
                                                echo 'Transaksi Penjualan';
                                            } else if ($this->session->userdata('tipe_akun') == '0' || $this->session->userdata('tipe_akun') == '4') {
                                                echo 'Histori Penjualan Saya';
                                            } ?>
                                        </p>
                                    </a>
                                </li>
                                <?php if ($this->session->userdata('tipe_akun') == '1' || $this->session->userdata('tipe_akun') == '2') { ?>
                                    <li class="nav-item">
                                        <a href="<?= base_url('C_penjualan/form_penjualan') ?>" class="nav-link">
                                            <i class="nav-icon fas fa-edit"></i>
                                            <p>Tambah Penjualan</p>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php if ($this->session->userdata('tipe_akun') <> '4') {
                        ?>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa-solid fa-screwdriver-wrench"></i>
                                    <p> <?php if ($this->session->userdata('tipe_akun') == '2') {
                                            echo 'Peminjaman Super';
                                        } else if ($this->session->userdata('tipe_akun') == '1') {
                                            echo 'Peminjaman Admin';
                                        } else if ($this->session->userdata('tipe_akun') == '0') {
                                            echo 'Peminjaman Saya';
                                        } ?>
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url('C_peminjaman') ?>" class="nav-link">

                                            <i class="nav-icon fas fa-table"></i>
                                            <p> <?php if ($this->session->userdata('tipe_akun') == '2') {
                                                    echo 'Data Peminjaman Super';
                                                } else if ($this->session->userdata('tipe_akun') == '1') {
                                                    echo 'Transaksi Peminjaman';
                                                } else if ($this->session->userdata('tipe_akun') == '0') {
                                                    echo 'Histori Peminjaman Saya';
                                                } ?></p>
                                        </a>
                                    </li>
                                    <?php if ($this->session->userdata('tipe_akun') == '1' || $this->session->userdata('tipe_akun') == '2') { ?>
                                        <li class="nav-item">
                                            <a href="<?= base_url('C_peminjaman/form_peminjaman') ?>" class="nav-link">
                                                <i class="nav-icon fas fa-edit"></i>
                                                <p>Tambah Peminjaman</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa-solid fa-basket-shopping"></i>
                                    <p> <?php if ($this->session->userdata('tipe_akun') == '2') {
                                            echo 'Pengembalian Super';
                                        } else if ($this->session->userdata('tipe_akun') == '1') {
                                            echo 'Pengembalian Admin';
                                        } else if ($this->session->userdata('tipe_akun') == '0') {
                                            echo 'Pengembalian Saya';
                                        } ?>
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url('C_pengembalian') ?>" class="nav-link">
                                            <i class="nav-icon fas fa-table"></i>
                                            <p> <?php if ($this->session->userdata('tipe_akun') == '2') {
                                                    echo 'Data Pengembalian Super';
                                                } else if ($this->session->userdata('tipe_akun') == '1') {
                                                    echo 'Transaksi Pengembalian';
                                                } else if ($this->session->userdata('tipe_akun') == '0') {
                                                    echo 'Histori Pengembalian Saya';
                                                } ?></p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php
                        } ?>

                        <?php if ($this->session->userdata('tipe_akun') == '2' || $this->session->userdata('tipe_akun') == '0' || $this->session->userdata('tipe_akun') == '4') { ?>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p><?php if ($this->session->userdata('tipe_akun') == '2') {
                                            echo 'Laporan Super';
                                        } else if ($this->session->userdata('tipe_akun') == '1') {
                                            echo 'Laporan Admin';
                                        } else if ($this->session->userdata('tipe_akun') == '0') {
                                            echo 'Rekomendasi Tangkap Ikan';
                                        } ?>
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url('C_laporan') ?>" class="nav-link">

                                            <i class="nav-icon fas fa-table"></i>
                                            <p>Harian</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url('C_laporan/bulanan') ?>" class="nav-link">

                                            <i class="nav-icon fas fa-table"></i>
                                            <p>Bulanan</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url('C_laporan/tahunan') ?>" class="nav-link">

                                            <i class="nav-icon fas fa-table"></i>
                                            <p>Tahunan</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>