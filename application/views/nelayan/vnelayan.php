<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header card">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Nelayan</h1>
                </div>  
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <a href="<?= base_url('C_nelayan/form_nelayan') ?>"><button type="button" class="btn btn-primary ml-3">+ Data
            Nelayan</button></a>
    <br>

    <div class="row mt-4">
        <div class="col">
            <form action="<?= base_url('C_nelayan/download_nelayan'); ?>" class="form-inline">
                <button style="z-index: 10;" type="submit" class="btn btn-outline-success ml-3">Laporan</button>
                <br>
                <br>
            </form>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- /.row -->


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Nelayan</h3>

                            <div class="card-tools">
                                <form method="GET" action="<?= base_url('C_nelayan'); ?>">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="table_search" class="form-control float-right"
                                            placeholder="Nama Nelayan">
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
                                        <th>Kapal</th>
                                        <th>Jenis Kapal</th>
                                        <th>Alat</th>
                                        <th>GT</th>
                                        <th>Daerah</th>
                                        <th>PAS</th>
                                        <th>Pelabuhan</th>
                                        <th>Ket</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($all_nelayan['data'])) : ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="alert alert-danger" role="alert">Data Not Found!</div>
                                        </td>
                                    </tr>
                                    <?php
                                    endif;
                                    $no = 0;
                                    foreach ($all_nelayan['data'] as $a) :
                                    ?>
                                    <tr>
                                        <td align="center"><?= ++$no; ?></td>
                                        <td><?= $a['nama_nelayan']; ?></td>
                                        <td align="center"><?= $a['nama_kapal']; ?></td>
                                        <td><?= $a['jenis_kapal']; ?></td>
                                        <td><?= $a['alat_tangkap']; ?></td>
                                        <td><?= $a['GT']; ?></td>
                                        <td><?= $a['daerah_tangkap']; ?></td>
                                        <td><?= $a['tanda_pas']; ?></td>
                                        <td><?= $a['pelabuhan_bongkar']; ?></td>
                                        <td><?= $a['keterangan']; ?></td>
                                        <td><?= $a['status']; ?></td>
                                        <td align="center">
                                            <a class="tombol-edit"
                                                href="<?= base_url('C_nelayan/edit/' . $a['id']); ?>"><i
                                                    class="fas fa-edit"></i></a>
                                            <a class="tombol-hapus"
                                                href="<?= base_url('C_nelayan/hapus_nelayan/' . $a['id']); ?>"
                                                onclick="return confirm('Yakin Mau Di Hapus?');"><i
                                                    class="fas fa-trash-alt"></i></a>
                                        </td>
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