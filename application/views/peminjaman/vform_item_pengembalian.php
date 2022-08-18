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
                    <form class="form-horizontal" method=POST action="<?= base_url('C_peminjaman/simpan_pengembalian_alat_bahan'); ?>">
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
                                    <?php
                                    if ($this->session->flashdata('flash2')) :
                                    ?>
                                        <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-ban"></i>Data Salah!</h5>
                                            Pastikan jumlah dan Harga Pengembalian Tidak Lebih Besar Dari Jumlah dan Harga Yang Di Pinjam
                                        </div>
                                    <?php
                                    endif;
                                    ?>
                                    <!-- select -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Kode Peminjaman</label>
                                        <div class="col-sm-8">
                                            <input value="<?= $id_peminjaman_header ?>" type="text" class="form-control" id="id_peminjaman_header" name="id_peminjaman_header" hidden>
                                            <input value="<?= $id_peminjaman_detail ?>" type="text" class="form-control" id="id_peminjaman_detail" name="id_peminjaman_detail" hidden>
                                            <input value="<?= $jumlah_pinjam ?>" type="text" class="form-control" id="jumlah_pinjam" name="jumlah_pinjam" hidden>
                                            <input value="<?= $harga_item_pinjam ?>" type="text" class="form-control" id="harga_item_pinjam" name="harga_item_pinjam" hidden>
                                            <input value="<?= $kode_peminjaman ?>" type="text" class="form-control" id="kode_penjualan" name="kode_penjualan" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Nama Nelayan</label>
                                        <div class="col-sm-8">
                                            <input value="<?= $nama_nelayan ?>" type="text" class="form-control" id="nelayan" name="nelayan" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Nama Alat/Bahan</label>
                                        <div class="col-sm-8">
                                            <input value="<?= $nama_item ?>" type="text" class="form-control" id="nama_alat_bahan" name="nama_alat_bahan" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Jumlah Alat/Bahan Yang Di PInjam</label>
                                        <div class="col-sm-8">
                                            <input value="<?= $jumlah_pinjam ?>" max="<?= $jumlah_pinjam ?>" onkeypress="return hanyaAngka(event)" type="text" class="form-control" id="" name="" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Harga Alat/Bahan Saat Di PInjam</label>
                                        <div class="col-sm-8">
                                            <input value="<?= $harga_item_pinjam ?>" max="<?= $harga_item_pinjam ?>" onkeypress="return hanyaAngka(event)" type="text" class="form-control" id="" name="" disabled>
                                            <input value="<?= $harga_item_pinjam ?>" max="<?= $harga_item_pinjam ?>" onkeypress="return hanyaAngka(event)" type="text" class="form-control" id="harga_item_kembali" name="harga_item_kembali" hidden>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Jumlah Alat/Bahan Yang Kembali</label>
                                        <div class="col-sm-8">
                                            <input value="<?= $jumlah_kembali ?>" max="<?= $jumlah_pinjam ?>" onkeypress="return hanyaAngka(event)" type="text" class="form-control" id="jumlah_kembali" name="jumlah_kembali">
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Harga Alat/Bahan Saat Kembali</label>
                                        <div class="col-sm-8">
                                        </div>
                                    </div> -->
                                    <!-- <div class="card-header"> -->
                                    <button style="z-index: 10;" id="pinjamkan_nelayan" type="submit" class="col-sm-12 btn btn-primary">Verifikasi Pengembalian Alat/Bahan</button>
                                    <!-- </div> -->
                                </div>

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