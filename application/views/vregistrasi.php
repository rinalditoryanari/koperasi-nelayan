<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <!-- <a href="../../index2.html" class="h1"><b>Admin</b>LTE</a> -->
                <a class="h1"><b>Form</b><br />Pendaftaran</a>
            </div>

            <body background="assets/img/bg-nelayan-r.jpg">
                <div class="card-body">
                    <form action="<?php echo base_url('CLogin/cekRegistrasi'); ?>" method="post">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input value="<?php echo $guest_code ?>" type="text" name="" class="form-control" id="" placeholder="<?php echo $guest_code ?>" required disabled>
                                <input value="<?php echo $guest_code ?>" type="text" name="nama" class="form-control" id="nama" placeholder="<?php echo $guest_code ?>" required hidden>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <select name="id_alat" id="id_alat" class="form-control" required="">
                                    <option value="">Pilih Alat Tangkap</option>
                                    <?php foreach ($pilih_alat as $a) : ?>
                                        <option value="<?= $a['id_alat']; ?>"><?= $a['nama_alat']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Username" id="username" name="username">
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Tulis Ulang Password" id="password2" name="password2">
                        </div>
                        <div class="row">
                            <div class="col-12 center">
                                <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
                if ($this->session->flashdata('flash4')) :
                ?>
                    <div class="alert alert-danger alert-dismissible" style="margin-left: 5%; margin-right: 5%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i>Daftar gagal !</h5>
                        Username sudah digunakan!
                    </div>
                <?php
                endif;
                ?>
                <?php
                if ($this->session->flashdata('flash3')) :
                ?>
                    <div class="alert alert-danger alert-dismissible" style="margin-left: 5%; margin-right: 5%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i>Salah Password!</h5>
                        Pastikan password sama
                    </div>
                <?php
                endif;
                ?>
                <?php
                if ($this->session->flashdata('flash2')) :
                ?>
                    <div class="alert alert-success alert-dismissible" style="margin-left: 5%; margin-right: 5%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i>Harap Login!</h5>
                        Pastikan username dan password benar
                    </div>
                <?php
                endif;
                ?>
        </div>
    </div>






    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/s_login.js"></script>
</body>

</html>