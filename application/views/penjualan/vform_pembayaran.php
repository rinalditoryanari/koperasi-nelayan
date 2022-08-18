<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header card">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pembayaran</h1>
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
                    <!-- <form action="javascript:void(0)" data-url="<?php echo base_url('C_penjualan/simpan_penjualan') ?>" method="POST" id="formDataUpload" name="formDataUpload" enctype="multipart/form-data"> -->
                    <form class="form-horizontal" method=POST action="<?= base_url('C_penjualan/bayar_nelayan'); ?>">
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
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Kode Penjualan</label>
                                        <div class="col-sm-6">
                                            <input value="<?= $kode_penjualan ?>" type="text" class="form-control" id="kode_penjualan" name="kode_penjualan" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Kode Penjualan</label>
                                        <div class="col-sm-6">
                                            <input value="<?= $nelayan ?>" type="text" class="form-control" id="nelayan" name="nelayan" disabled>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><b>Detail Pembayaran</b></h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table id="ikan_form_input" class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th align="center">Ikan</th>
                                            <th align="center">Jumlah per Kg</th>
                                            <th align="center">Harga per Kg</th>
                                            <th align="center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($keranjang_ikan as $as) {
                                        ?>
                                            <tr>
                                                <td align="center"><?= $as['nama_ikan'] ?></td>
                                                <td align="center"><?= $as['jumlah'] ?></td>
                                                <td align="center"><?= "Rp. " . number_format($as['harga_ikan'], 0, ',', '.'); ?></td>
                                                <td align="center"><?= "Rp. " . number_format($as['total'], 0, ',', '.'); ?></td>
                                            </tr>
                                        <?php
                                        } ?>
                                        <tr>
                                            <td align="left"><b>Total</b></td>
                                            <td align="center" colspan="2"></td>
                                            <td align="center"><b><?= "Rp. " . number_format($total_biaya, 0, ',', '.'); ?></b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card">
                                <!-- <div class="col-sm-12"> -->
                                <div class="card-header">
                                    <button style="z-index: 10;" id="bayar_nelayan" type="submit" class="col-sm-12 btn btn-primary">Bayar Ke Nelayan dan Terima Ikan</button>
                                </div>
                                <!-- </div> -->
                            </div>
                        </div>
                    </form>

                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="<?= base_url(); ?>assets/js/penjualan.js"></script>