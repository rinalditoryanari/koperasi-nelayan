<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header card">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Nelayan</h1>
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
                                <form method="post" action="<?= base_url('C_nelayan/tambah_nelayan') ?>"
                                    class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="nama" class="form-control" id="nama"
                                                placeholder="Nama" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="nama_kapal" class="col-sm-2 col-form-label">Nama Kapal</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="nama_kapal" class="form-control" id="nama_kapal"
                                                placeholder="Nama Kapal" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="jenis_kapal" class="col-sm-2 col-form-label">Jenis Kapal</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="jenis_kapal" class="form-control" id="jenis_kapal"
                                                placeholder="Jenis Kapal" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="alat_tangkap" name="alat_tangkap" id="alat_tangkap"
                                            class="col-sm-2 col-form-label">Alat Tangkap</label>
                                        <div class="col-sm-10">
                                            <select name="id_alat" id="id_alat" class="form-control" required="">
                                                <option value="">Pilih Alat/Bahan</option>
                                                <?php foreach ($pilih_alat as $a) : ?>
                                                <option value="<?= $a['id_alat']; ?>"><?= $a['nama_alat']; ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="GT" class="col-sm-2 col-form-label">GTl</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="GT" class="form-control" id="GT" placeholder="GT"
                                                required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="daerah_tangkap" class="col-sm-2 col-form-label">Daerah
                                            Tangkap</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="daerah_tangkap" class="form-control"
                                                id="daerah_tangkap" placeholder="Daerah Tangkap" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="tanda_pas" class="col-sm-2 col-form-label">Tanda PAS</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="tanda_pas" class="form-control" id="tanda_pas"
                                                placeholder="Tanda PAS">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="pelabuhan_bongkar" class="col-sm-2 col-form-label">Pelabuhan
                                            Bongkar</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="pelabuhan_bongkar" class="form-control"
                                                id="pelabuhan_bongkar" placeholder="Pelabuhan Bongkar" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="keterangan" class="form-control" id="keterangan"
                                                placeholder="Keterangan">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="keterangan" class="col-sm-2 col-form-label">Status</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="status" class="form-control" id="status"
                                                placeholder="Status">
                                        </div>
                                        <div class="box-footer">
                                            <a href="<?= base_url('C_nelayan')?>" class="btn btn-warning">Cancel</a>
                                            <button type="submit" class="btn btn-primary">Simpan</button>

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