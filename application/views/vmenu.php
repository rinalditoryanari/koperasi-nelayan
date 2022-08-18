<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header card">
        <div class="container-fluid ">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><?= $judul ?> </h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            Aplikasi untuk melakukan transaksi penjualan, peminjaman dan pengembalian
                        </div>
                    </div>
                    <!-- /.card -->


                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
<!-- /.content-wrapper -->
<script>
const baseUrl = "<?php echo base_url();?>"

$.ajax({

    url: baseUrl + 'welcome/chart_data',
    dataType: 'json',
    method: 'get',
    success: data => {
        console.log(data)
    }

})
</script>