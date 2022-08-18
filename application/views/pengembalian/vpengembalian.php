<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pengembalian</h1>
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
                                <form method="GET" action="<?= base_url('C_peminjaman'); ?>">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="table_search" class="form-control float-right" placeholder="Kode Peminjaman">
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
                                        <th>
                                            <center>No</center>
                                        </th>
                                        <th>Kode Peminjaman</th>
                                        <th align="center">Nelayan</th>
                                        <th>
                                            <center>Total</center>
                                        </th>
                                        <th>
                                            <center>Tanggal Pengembalian</center>
                                        </th>
                                        <th>
                                            <center>Status</center>
                                        </th>
                                        <?php if ($this->session->userdata('tipe_akun') == 1 || $this->session->userdata('tipe_akun') == 2) {
                                            echo '<th><center>Action</center></th>';
                                        } else {
                                            echo '<th><center>Action</center></th>';
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($all_pengembalian['data'])) : ?>
                                        <tr>
                                            <td colspan="6">
                                                <div class="alert alert-danger" role="alert">Data Not Found!</div>
                                            </td>
                                        </tr>
                                    <?php
                                    endif;
                                    $no = 0;
                                    foreach ($all_pengembalian['data'] as $a) :
                                    ?>
                                        <tr>
                                            <td align="center"><?= ++$no; ?></td>
                                            <td><?= $a['code_peminjaman']; ?></td>
                                            <td align="left"><?= $a['nama_nelayan']; ?></td>
                                            <td align="right">
                                                <?= "Rp. " . number_format($a['total_kembali'], 0, ',', '.'); ?></td>
                                            <td align="center"><?= $a['tanggal_kembali']; ?></td>

                                            <td align="center">
                                                <?php if ($a['status'] == '0') {
                                                    echo 'Masih Di Pinjam';
                                                } else {
                                                    echo 'Sudah Kembali';
                                                } ?>
                                            </td>
                                            <td align="center">
                                                <a href="<?= base_url('C_pengembalian/download_pdf_pengembalian/' . $a['id_peminjaman_header']); ?>"><i class="fas fa-fw fa-file-pdf"></i></a>
                                                <a href="<?= base_url('C_pengembalian/form_pengembalian_dan_pembayaran/' . $a['id_peminjaman_header']); ?>"><i class="fas fa-search"></i></a>

                                                <?php if ($this->session->userdata('tipe_akun') == 1 || $this->session->userdata('tipe_akun') == 2) { ?>
                                                    <a class="tombol-hapus" href="<?= base_url('C_peminjaman/hapus_peminjaman/' . $a['id_peminjaman_header']); ?>"><i class="fas fa-trash-alt"></i></a>
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