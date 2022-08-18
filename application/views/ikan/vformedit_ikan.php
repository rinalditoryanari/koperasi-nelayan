<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header card">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Ikan</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="col-sm-12">
                            <div class="content-header">
                                <?php
                                if ($this->session->flashdata('flash4')) :
                                ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    <h5><i class="icon fas fa-ban"></i>Lengkapi Semua Data!</h5>
                                    Isi semua data yang dibutuhkan
                                </div>
                                <?php
                                endif;
                                ?>
                                <?php
                                if ($this->session->flashdata('flash3')) :
                                ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    <h5><i class="icon fas fa-ban"></i>Data Salah!</h5>
                                    Pastikan jumlah tidak 0
                                </div>
                                <?php
                                endif;
                                ?>
                                <!-- select -->
                                <?php echo form_open_multipart('C_ikan/update') ?>
                                <div class="form-group row">
                                    <input type="hidden" name="id_ikan" value="<?= $data['id_ikan'];?>"
                                        class="form-control" id="nama_ikan">
                                    <label for="nama_ikan" class="col-sm-2 col-form-label">Nama Ikan</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="nama_ikan" value="<?= $data['nama_ikan'];?>"
                                            class="form-control" id="nama_ikan" placeholder="Nama Ikan" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="harga_ikan" class="col-sm-2 col-form-label">Harga Ikan</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="harga_ikan" class="form-control" size="20"
                                            value="<?= $data['harga_ikan'];?>" id="harga_ikan" placeholder="Harga Ikan"
                                            required>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="gambar" class="col-sm-2 col-form-label">Gambar</label>
                                    <div class="col-sm-10">
                                        <input type="file" name="gambar" class="form-control" id="gambar">
                                    </div>
                                    <img src="<?php echo base_url() .  '/gambar/' . $data['gambar']; ?>" width="100">
                                </div>

                                <div class="box-footer">
                                    <a href="<?= base_url('C_ikan')?>" class="btn btn-warning">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update</button>

                                </div>

                                <?php echo form_close() ?>
                            </div>

                        </div>
                    </div>
                    <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="<?= base_url(); ?>assets/js/penjualan.js"></script>