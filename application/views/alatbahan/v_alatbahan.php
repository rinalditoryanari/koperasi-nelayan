<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header card">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Alat Bahan</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <?php if ($this->session->userdata('tipe_akun') == '2') { ?>
            <a href="<?= base_url('C_alatbahan/form_alatbahan') ?>"><button type="button" class="btn btn-primary">+ Data
                    Alat Bahan</button></a>
            <br>
            <br>
            <?php } ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Alat dan Bahan</h3>
                            <div class="card-tools">
                                <form method="GET" action="<?= base_url('C_alatbahan'); ?>">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="table_search" class="form-control float-right"
                                            placeholder="Nama Alat & Bahan">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Jenis</th>
                                        <th>Satuan</th>
                                        <th>Harga / Unit</th>
                                        <?php if ($this->session->userdata('tipe_akun') == '2') { ?>
                                        <th>Action</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($all_alatbahan['data'])) : ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="alert alert-danger" role="alert">Data Not Found!</div>
                                        </td>
                                    </tr>
                                    <?php
                                    endif;
                                    $no = 0;
                                    foreach ($all_alatbahan['data'] as $a) :
                                    ?>
                                    <tr>
                                        <td align=""><?= ++$no; ?></td>
                                        <td><?= $a['nama']; ?></td>
                                        <td align=""><?= $a['jenis']; ?></td>
                                        <td align=""><?= $a['satuan']; ?></td>
                                        <td align=""><?= "Rp. " . number_format($a['harga_per_unit'], 0, ',', '.'); ?>
                                        </td>
                                        <?php if ($this->session->userdata('tipe_akun') == '2') { ?>
                                        <td align="">
                                            <a class="tombol-edit"
                                                href="<?= base_url('C_alatbahan/edit/' . $a['id_alat']); ?>"><i
                                                    class="fas fa-edit"></i></a>
                                            <a class="tombol-hapus"
                                                href="<?= base_url('C_alatbahan/hapus_alatbahan/' . $a['id_alat']); ?>"
                                                onclick="return confirm('Yakin Mau Di Hapus?');"><i
                                                    class="fas fa-trash-alt"></i></a>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                    </tfoot>
                                    <?php
                                    endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->