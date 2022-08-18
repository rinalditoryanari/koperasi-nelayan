<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header card">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Peminjaman</h1>
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
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
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
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-ban"></i>Data Salah!</h5>
                                        Pastikan jumlah tidak 0
                                    </div>
                                <?php
                                endif;
                                ?>
                                <!-- select -->
                                <form action="javascript:void(0)" data-url="<?php echo base_url('C_peminjaman/simpan_alat_bahan') ?>" method="POST" id="formDataUpload" name="formDataUpload" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Kode Peminjaman</label>
                                        <div class="col-sm-8">
                                            <input value="<?= $code_peminjaman ?>" type="text" class="form-control" id="kode_peminjaman" name="kode_peminjaman" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Nama Nelayan - Nama Kapal</label>
                                        <div class="col-sm-8">
                                            <select name="nelayan" id="nelayan" class="form-control" required="">
                                                <option value="">Pilih Nama Nelayan - Nama Kapal</option>
                                                <?php foreach ($list_nelayan as $lc) : ?>
                                                    <option value="<?= $lc['id_nelayan']; ?>"><?= $lc['nama_nelayan']; ?> - <?= $lc['kapal_nelayan']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Nama Alat/Bahan</label>
                                        <div class="col-sm-8">
                                            <select name="alat_bahan" id="alat_bahan" class="form-control" required="" onchange="ganti_harga()">
                                                <option value="">Pilih Alat/Bahan</option>
                                                <?php foreach ($list_alat_bahan as $lc) : ?>
                                                    <option data-harga_per_unit=" <?= $lc['harga_per_unit']; ?>" data-nama_alat_bahan="<?= $lc['nama_alat']; ?>" value="<?= $lc['id_alat']; ?>"><?= $lc['nama_alat']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Jumlah</label>
                                        <div class="col-sm-8">
                                            <input type="text" onkeypress="return hanyaAngka(event)" class="form-control" id="jumlah" name="jumlah">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Harga Alat Per Unit</label>
                                        <div class="col-sm-8">
                                            <input style="text-align: right;" type="text" class="form-control" id="harga_alat_bahan" name="harga_alat_bahan" disabled>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <button id="tambah_alat_bahan" type="submit" class="btn btn-primary">Tambah Alat/Bahan</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <!-- /.card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><b>Alat Yang Dipinjam Oleh Nelayan</b></h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table id="alat_bahan_form_input" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th align="center">Nama Alat/Bahan</th>
                                        <th align="center">Jumlah</th>
                                        <th align="center">Harga per unit</th>
                                        <th align="center">Total</th>
                                        <th align="center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card">
                            <!-- <div class="col-sm-12"> -->
                            <div class="card-header">
                                <form class="form-horizontal" method=POST action="<?= base_url('C_peminjaman/form_pinjamkan_nelayan'); ?>">
                                    <button id="pinjam_nelayan" type="submit" class="col-sm-12 btn btn-primary">Pinjamkan Ke Nelayan</button>
                                </form>
                            </div>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="<?= base_url(); ?>assets/js/peminjaman.js"></script>