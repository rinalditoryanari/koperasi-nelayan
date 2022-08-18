<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header card">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Penjualan</h1>
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
                        <div class="card-header">
                            <h3 class="card-title">Data Transaksi</h3>

                            <div class="card-tools">
                                <form method="GET" action="<?= base_url('C_penjualan'); ?>">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="table_search" class="form-control float-right" placeholder="Kode Transaksi">
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
                                        <th>Code</th>
                                        <th>Neyalan</th>
                                        <th>Total</th>
                                        <th>Tanggal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($all_penjualan['data'])) : ?>
                                        <tr>
                                            <td colspan="6">
                                                <div class="alert alert-danger" role="alert">Data Not Found!</div>
                                            </td>
                                        </tr>
                                    <?php
                                    endif;
                                    $no = 0;
                                    foreach ($all_penjualan['data'] as $a) :
                                    ?>
                                        <tr>
                                            <td align=""><?= ++$no; ?></td>
                                            <td><?= $a['kode_penjualan']; ?></td>
                                            <td align=""><?= $a['nama_nelayan']; ?></td>
                                            <td align=""><?= "Rp. " . number_format($a['total_biaya'], 0, ',', '.'); ?></td>
                                            <td align=""><?= $a['created_date']; ?></td>
                                            <td align="">
                                                <a href="<?= base_url('C_penjualan/download_pdf_penjualan/' . $a['id_penjualan_header']); ?>"><i class="fas fa-fw fa-file-pdf"></i></a>
                                                <?php if ($this->session->userdata('tipe_akun') == 1 || $this->session->userdata('tipe_akun') == 2) { ?>
                                                    <a class="" href="<?= base_url('C_penjualan/view_detail_penjualan/' . $a['id_penjualan_header']); ?>"><i class="fas fa-search"></i></a>
                                                    <a class="tombol-hapus" href="<?= base_url('C_penjualan/hapus_penjualan/' . $a['id_penjualan_header']); ?>"><i class="fas fa-trash-alt"></i></a>
                                                <?php } else { ?>
                                                    <a class="" href="<?= base_url('C_penjualan/view_detail_penjualan/' . $a['id_penjualan_header']); ?>"><i class="fas fa-search"></i></a>
                                                <?php } ?>
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