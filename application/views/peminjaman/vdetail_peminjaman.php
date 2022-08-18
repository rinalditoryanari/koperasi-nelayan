<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header card">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Peminjaman</h1>
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
                    <form class="form-horizontal" method=POST
                        action="<?= base_url('C_peminjaman/pinjamkan_nelayan'); ?>">
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
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Kode Peminjaman</label>
                                        <div class="col-sm-8">
                                            <input value="<?= $kode_peminjaman ?>" type="text" class="form-control"
                                                id="kode_penjualan" name="kode_penjualan" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Nama Nelayan</label>
                                        <div class="col-sm-8">
                                            <input value="<?= $nelayan ?>" type="text" class="form-control" id="nelayan"
                                                name="nelayan" disabled>
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
                                            <th>
                                                <center>Alat/Bahan</center>
                                            </th>
                                            <th>
                                                <center>Jumlah Pinjaman</center>
                                            </th>
                                            <th>
                                                <center>Harga per Item Pinjam</center>
                                            </th>
                                            <th>
                                                <center>Total Peminjaman</center>
                                            </th>
                                            <th>
                                                <center>Jumlah Kembali</center>
                                            </th>

                                            <th>
                                                <center>Total Pengembalian</center>
                                            </th>
                                            <th>
                                                <center>Status</center>
                                            </th>
                                            <?php if($this->session->userdata('tipe_akun') == 1 || $this->session->userdata('tipe_akun') == 2) 
                                            {?>
                                            <th>
                                                <center>Action</center>
                                            </th>
                                            <?php }else {?>
                                            <th>
                                                <center>Total</center>
                                            </th>
                                            <?php }?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($keranjang_pinjam as $as) {
                                        ?>
                                        <tr>
                                            <td align="center"><?= $as['nama_alat_bahan'] ?></td>
                                            <td align="center"><?= $as['jumlah_pinjam'] ?></td>
                                            <td align="right">
                                                <?= "Rp. " . number_format($as['harga/unit_pinjam'], 0, ',', '.'); ?>
                                            </td>
                                            <td align="right">
                                                <?= "Rp. " . number_format($as['total_pinjam_per_item'], 0, ',', '.'); ?>
                                            </td>
                                            <td align="center"><?= $as['jumlah_kembali'] ?></td>

                                            <td align="right">
                                                <?= "Rp. " . number_format($as['total_kembali_per_item'], 0, ',', '.'); ?>
                                            </td>
                                            <td align="center">
                                                <?php if ($as['status'] == '0') {
                                                        echo 'Masih Di Pinjam';
                                                    } else {
                                                        echo 'Sudah Kembali';
                                                    } ?>
                                            </td>
                                            <?php if($this->session->userdata('tipe_akun') == 1 || $this->session->userdata('tipe_akun') == 2) 
                                            {?>
                                            <td align="center">
                                                <a
                                                    href="<?= base_url('C_peminjaman/form_pengembalian_item/' . $as['id_peminjaman_detail']); ?>"><i
                                                        class="fas fa-edit">
                                                    </i>
                                                </a>
                                            <td>
                                                <?php } else { ?>
                                            <td align="center">

                                            <td>
                                                <?php }?>
                                        </tr>
                                        <?php
                                        } ?>
                                        <tr>
                                            <td align="center"><b>Pinjaman - Pengembalian</b></td>
                                            <td align="center" colspan="2"></td>
                                            <td align="right">
                                                <b><?= "Rp. " . number_format($total_biaya_pinjam, 0, ',', '.'); ?></b>
                                            </td>
                                            <td align="center" colspan="">-</td>
                                            <td align="right">
                                                <b><?= "Rp. " . number_format($total_biaya_kembali, 0, ',', '.'); ?></b>
                                            </td>
                                            <td align="center" colspan="">=</td>
                                            <td align="right">
                                                <b><?= "Rp. " . number_format($total_biaya_pinjam - $total_biaya_kembali, 0, ',', '.'); ?></b>
                                            </td>
                                            <td align="center" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td align="center"><b>Total Nelayan Bayar</b></td>
                                            <td align="center" colspan="6"></td>
                                            <td align="right">
                                                <b><?= "Rp. " . number_format($total_biaya_pinjam - $total_biaya_kembali, 0, ',', '.'); ?></b>
                                            </td>
                                            <td align="center" colspan="1"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <?php if($this->session->userdata('tipe_akun') == 1 || $this->session->userdata('tipe_akun') == 2) 
                                            {?>
                            <div class="card">
                                <div class="card-header">
                                    <button style="z-index: 10;" id="pinjamkan_nelayan" type="submit"
                                        class="col-sm-12 btn btn-primary">Selesai Verifikasi Pengembalian
                                        Alat/Bahan</button>
                                </div>
                            </div>
                            <?php } ?>
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