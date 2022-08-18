<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header card">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Penjualan</h1>
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
                                <form action="javascript:void(0)" data-url="<?php echo base_url('C_penjualan/simpan_ikan') ?>" method="POST" id="formDataUpload" name="formDataUpload" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Kode Penjualan</label>
                                        <div class="col-sm-8">
                                            <input value="<?= $code_penjualan ?>" type="text" class="form-control" id="kode_penjualan" name="kode_penjualan" disabled>
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
                                        <label class="col-sm-4 col-form-label">Nama Ikan</label>
                                        <div class="col-sm-8">
                                            <select name="ikan" id="ikan" class="form-control" required="" onchange="ganti_harga()">
                                                <option value="">Pilih Ikan</option>
                                                <?php foreach ($list_ikan as $lc) : ?>
                                                    <option data-harga="<?= $lc['harga_ikan']; ?>" data-nama_ikan="<?= $lc['nama_ikan']; ?>" value="<?= $lc['id_ikan']; ?>"><?= $lc['nama_ikan']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Jumlah Per Kg</label>
                                        <div class="col-sm-8">
                                            <input type="text" onkeypress="return hanyaAngka(event)" class="form-control" id="jumlah" name="jumlah">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Harga Ikan Per Kg</label>
                                        <div class="col-sm-8">
                                            <input style="text-align: right;" type="text" class="form-control" id="harga_ikan" name="harga_ikan" disabled>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <button id="tambah_ikan" type="submit" class="btn btn-primary">Tambah Ikan</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <!-- /.card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><b>Ikan Yang Dijual Oleh Nelayan</b></h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table id="ikan_form_input" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th align="center">Nama Ikan</th>
                                        <th align="center">Jumlah per Kg</th>
                                        <th align="center">Harga per Kg</th>
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
                                <form class="form-horizontal" method=POST action="<?= base_url('C_penjualan/form_bayar_nelayan'); ?>">
                                    <button id="bayar_nelayan" type="submit" class="col-sm-12 btn btn-primary">Bayar Ke Nelayan</button>
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
<script src="<?= base_url(); ?>assets/js/penjualan.js"></script>