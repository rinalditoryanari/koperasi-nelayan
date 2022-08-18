<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <!-- <a href="../../index2.html" class="h1"><b>Admin</b>LTE</a> -->
                <img src="assets/img/fish-svgrepo-com.svg" style="max-width: 30%;">
                <br>
                <a class="h1"><b>Koperasi Nelayan</b></a>
            </div>
            <body background="assets/img/bg-nelayan-r.jpg">
            <div class="card-body">

                <form action="<?php echo base_url('CLogin/cekLogin'); ?>" method="post">

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" id="username" name="username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 center">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                    </div>
                </form>
                <div style="margin-top: 2%;">
                        Belum punya Akun, Daftar akun <a href="<?php echo base_url('CLogin/form_registrasi'); ?>">disini</a>
                </div>
            </div>
            <?php
            if ($this->session->flashdata('flash4')) :
            ?>
                <div class="alert alert-danger alert-dismissible" style="margin-left: 5%; margin-right: 5%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i>Login Gagal!</h5>
                    Pastikan username dan password benar
                </div>
            <?php
            endif;
            ?>
            <?php
            if ($this->session->flashdata('flash3')) :
            ?>
                <div class="alert alert-danger alert-dismissible" style="margin-left: 5%; margin-right: 5%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i>Harap Login!</h5>
                    Pastikan username dan password benar
                </div>
            <?php
            endif;
            ?>
            <?php
            if ($this->session->flashdata('flash2')) :
            ?>
                <div class="alert alert-success alert-dismissible" style="margin-left: 5%; margin-right: 5%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i>Berhasil!</h5>
                    Daftar Akun Berhasil
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