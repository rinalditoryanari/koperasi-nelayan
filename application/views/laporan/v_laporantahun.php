<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header card">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laporan Per Tahun</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row mt-4">
                <div class="col">
                    <form action="<?= base_url('C_laporan/download_laporan_per_tahun'); ?>" class="form-inline">
                        <select oninput="select_date1()" name="select_year" class="form-control" id="select_year" required>
                            <option value="">Pilih Tahun</option>
                            <?php foreach ($select_tahun as $a) { ?>
                                <option value="<?= $a['select_year']; ?>"><?php echo $a['select_year']; ?></option>
                            <?php } ?>
                        </select>
                        <?php if ($this->session->userdata('tipe_akun') == '2') { ?>
                            <button style="z-index: 10;" type="submit" class="btn btn-outline-success ml-3">
                                Laporan Tahunan
                            </button>
                        <?php } ?>
                    </form>
                </div>
            </div>
            <br>
            <!-- /.row -->
            <div class="row">
                <div class="col-12">
                    <!-- BAR CHART -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Jumlah Penjualan Ikan Per Tahun</h3>

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

                    <?php if ($this->session->userdata('tipe_akun') == '2') { ?>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Data Laporan</h3>

                                <div class="card-tools">
                                    <div class="card-tools">
                                        <form method="GET" action="<?= base_url('C_laporan/tahunan'); ?>">
                                            <div class="input-group input-group-sm" style="width: 150px;">
                                                <input type="text" name="table_search_tahun" class="form-control float-right" placeholder="Tahun">
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
                                                    <td><?= $a['tahun']; ?></td>
                                                    <td align=""><?= $a['nama_ikan']; ?></td>
                                                    <td align=""><?= $a['jumlah']; ?></td>
                                                    <td align=""><?= "Rp. " . number_format($a['harga/kg'], 0, ',', '.'); ?>
                                                    </td>
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
                            <!-- /.card -->
                        </div>
                    <?php } ?>

                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

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
                text: "Penjualan Ikan Per Tahun"
            }
        }
    });
</script>

<script>
    function select_date1() {
        var select_date = $("#select_year").val();
        uri = 'https://koperasinelayan.com/C_laporan/report_yearly';
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
                            text: "Penjualan Ikan Per Tahun"
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