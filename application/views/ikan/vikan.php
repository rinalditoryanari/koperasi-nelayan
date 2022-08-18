<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header card">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Ikan</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <?php if ($this->session->userdata('tipe_akun') == '2') { ?>
            <a href="<?= base_url('C_ikan/form_ikan') ?>"><button type="button" class="btn btn-primary">+ Data
                    Ikan</button></a>
            <br>
            <br>
            <?php } ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Ikan</h3>
                            <div class="card-tools">
                                <form method="GET" action="<?= base_url('C_ikan'); ?>">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="table_search" class="form-control float-right"
                                            placeholder="Nama Ikan">
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
                                        <th>Nama Ikan</th>
                                        <th>Harga/Kg</th>
                                        <th>Gambar</th>
                                        <?php if ($this->session->userdata('tipe_akun') == '2') { ?>
                                        <th>Action</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($all_ikan['data'])) : ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="alert alert-danger" role="alert">Data Not Found!</div>
                                        </td>
                                    </tr>
                                    <?php
                                    endif;
                                    $no = 0;
                                    foreach ($all_ikan['data'] as $a) :
                                    ?>
                                    <tr>
                                        <td align=""><?= ++$no; ?></td>
                                        <td><?= $a['nama_ikan']; ?></td>
                                        <td align=""><?= "Rp. " . number_format($a['harga_ikan'], 0, ',', '.'); ?></td>
                                        <td><img src="<?php echo base_url() . '/gambar/' . $a['gambar']  ; ?>" width="
                                                100">
                                        </td>
                                        <?php if ($this->session->userdata('tipe_akun') == '2') { ?>
                                        <td align="">
                                            <a class="tombol-edit"
                                                href="<?= base_url('C_ikan/edit/' . $a['id_ikan']); ?>"><i
                                                    class="fas fa-edit"></i></a>
                                            <a class="tombol-hapus"
                                                href="<?= base_url('C_ikan/hapus_ikan/' . $a['id_ikan']); ?>"
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
                            <?= $this->pagination->create_links(); ?>
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