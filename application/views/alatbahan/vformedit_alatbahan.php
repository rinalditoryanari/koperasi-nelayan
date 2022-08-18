<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header card">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Alat Bahan</h1>
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
                                <form method="post" action="<?= base_url('C_alatbahan/update') ?>"
                                    class="form-horizontal">
                                    <div class="form-group row">
                                        <input type="hidden" name="id_alat" value="<?= $data['id_alat'];?>"
                                            class="form-control" id="nama">
                                        <label for="nama" class="col-sm-2 col-form-label">Nama Alat</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="nama" value="<?= $data['nama'];?>"
                                                class="form-control" id="nama" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="jenis" class="col-sm-2 col-form-label">Jenis</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="jenis" value="<?= $data['jenis'];?>"
                                                class="form-control" id="jenis" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="satuan" value="<?= $data['satuan'];?>"
                                                class="form-control" id="satuan" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="harga_per_unit" class="col-sm-2 col-form-label">Harga</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="harga_per_unit"
                                                value="<?= $data['harga_per_unit'];?>" class="form-control"
                                                id="harga_per_unit" required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="box-footer">
                                        <a href="<?= base_url('C_alatbahan')?>" class="btn btn-warning">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Update</button>

                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                    <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="<?= base_url(); ?>assets/js/penjualan.js"></script>