<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header card">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laporan Per Hari</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row mt-4">
        <div class="col">
            <form action="<?= base_url('C_laporan/download_laporan_per_hari'); ?>" class="form-inline">
                <!-- <input type="date" format="yyyy-mm-dd" name="select_date" class="form-control ml-3" id="select_date"> -->
                <input oninput="select_date1()" type="date" format="yyyy-mm-dd" name="select_date" class="form-control ml-3" id="select_date">
                <?php if ($this->session->userdata('tipe_akun') == '2') { ?>
                    <button style="z-index: 10;" type="submit" class="btn btn-outline-success ml-3">Laporan</button>
                <?php } ?>
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

                    <!-- BAR CHART -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Jumlah Penjualan Ikan Perhari</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart" align="center">
                                <canvas id="myChart" style="width:100%; max-width:800px; "></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- <canvas id="myChart" style="width:50%; max-width:800px; "></canvas> -->

                    <?php if ($this->session->userdata('tipe_akun') == '2') { ?>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Data Laporan</h3>

                                <div class="card-tools">
                                    <form method="GET" action="<?= base_url('C_laporan'); ?>">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="text" name="table_search" class="form-control float-right">
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

                                            <th>Date</th>
                                            <th>Jenis Ikan</th>
                                            <th>Berat</th>
                                            <th>Harga/Kg</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (empty($all_laporan['data'])) : ?>
                                            <tr>
                                                <td colspan="5">
                                                    <div class="alert alert-danger" role="alert">Data Not Found!</div>
                                                </td>
                                            </tr>
                                        <?php
                                        endif;
                                        $no = 0;
                                        foreach ($all_laporan['data'] as $a) :
                                        ?>
                                            <tr>
                                                <td><?= $a['tanggal']; ?></td>
                                                <td align=""><?= $a['nama_ikan']; ?></td>
                                                <td align=""><?= $a['jumlah']; ?></td>
                                                <td align=""><?= "Rp. " . number_format($a['harga/kg'], 0, ',', '.'); ?></td>
                                                <td align=""><?= "Rp. " . number_format($a['total'], 0, ',', '.'); ?></td>

                                            </tr>
                                            </tfoot>
                                        <?php
                                        endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    <?php } ?>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- <script src="plugins/chart.js/Chart.min.js"></script> -->


<script>
    var xValues = [];
    var yValues = [];
    var barColors = [];

    new Chart("myChart", {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: '#2BBBAD',
                data: yValues
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: "Penjualan Ikan Per Hari "
            }
        }
    });
</script>

<script>
    function select_date1() {
        var select_date = $("#select_date").val();
        uri = 'https://koperasinelayan.com/C_laporan/report_daily';
        $.ajax({
            url: uri + "/" + select_date,
            async: true,
            cache: false,
            data: {
                select_date: select_date
            },
            type: 'POST',
            processData: false,
            contentType: false,
            dataType: 'json',

            success: function(res) {
                var ctx = document.getElementById("myChart").getContext('2d');

                if (window.myCharts != undefined)
                    window.myCharts.destroy();
                window.myCharts = new Chart(ctx, {
                    chart: {
                        renderTo: 'myChart',
                        // zoomType: 'xy'
                    },
                    type: "bar",
                    data: {
                        labels: res.nama_ikan,
                        datasets: [{
                            backgroundColor: '#2BBBAD',
                            data: res.jumlah_ikan
                        }]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: "Penjualan Ikan Per Hari"
                        }
                    }
                });
            }
        });
    }

    function removeData(chart) {
        chart.data.labels.pop();
        chart.data.datasets.forEach((dataset) => {
            dataset.data.pop();
        });
        chart.update();
    }
</script>