<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/third_party/spout/src/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\StyleBuilder;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Common\Entity\Style\Color;

class C_laporan extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        include APPPATH . 'third_party/fpdf/fpdf.php';
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('M_laporan');
    }

    public function index()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['all_laporan']    = $this->M_laporan->index();


            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('laporan/v_laporan', $data);
            $this->load->view('template/footer');
        }
    }

    public function bulanan()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['all_laporan']   = $this->M_laporan->bulan();
            $data['select_bulan']   = $this->M_laporan->list_bulan_tersedia();
            // var_dump($data['select_bulan']);
            // die;



            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('laporan/v_laporanbulan', $data);
            $this->load->view('template/footer');
        }
    }

    public function tahunan()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['all_laporan']   = $this->M_laporan->tahun();
            $data['select_tahun']   = $this->M_laporan->list_tahun_tersedia();


            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('laporan/v_laporantahun', $data);
            $this->load->view('template/footer');
        }
    }

    public function download_laporan_per_hari_()
    {
        $select_date = $_GET['select_date'];
        // echo var_dump($select_date);
        // die;
        //    $select_date = '2022-05-19';
        //    echo var_dump($select_date);
        //    exit;

        $data_laporan = $this->M_laporan->download_laporan_per_hari($select_date);
        $data_perbekalan = $this->M_laporan->download_perbekalan_per_hari($select_date);


        $summary_header = ['TANGGAL', 'JENIS IKAN', 'JUMLAH (KG)', 'HARGA/KG', 'TOTAL'];
        $perhitungan_header = ['PENGELUARAN', 'BIAYA'];

        $laporan_data = array();
        foreach ($data_laporan as $value) {
            $detail = array();
            foreach ($value as $k => $v) {
                array_push($detail, $v);
            }
            $laporan_data[] = $detail;
        }

        $perbekalan_data = array();
        foreach ($data_perbekalan as $value) {
            $detail = array();
            foreach ($value as $k => $v) {
                array_push($detail, $v);
            }
            $perbekalan_data[] = $detail;
        }

        $writer = WriterFactory::create(Type::XLSX);
        // $select_date     ;
        $filename      = "Laporan Per Tanggal " . $select_date .  ".xlsx";


        $writer->openToBrowser($filename);
        $border = (new BorderBuilder())
            ->setBorderBottom(Border::WIDTH_THIN)
            ->setBorderLeft(Border::WIDTH_THIN)
            ->setBorderRight(Border::WIDTH_THIN)
            ->setBorderTop(Border::WIDTH_THIN)
            ->build();
        $headerStyle = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->setBorder($border)
            ->build();
        $detailStyle = (new StyleBuilder())
            ->setFontSize(12)
            ->setBorder($border)
            ->setShouldWrapText(false)
            ->build();
        $kosong = (new StyleBuilder())
            ->setFontSize(12)
            ->setShouldWrapText(false)
            ->build();


        // write ke Sheet 1
        $writer->getCurrentSheet()->setName('LAPORAN');
        // header Sheet 1
        $writer->addRowWithStyle($summary_header, $headerStyle);
        // data Sheet 1 Delivery
        $writer->addRowsWithStyle($laporan_data, $detailStyle);

        // Create 2 Baris Space
        $writer->addRow([" ", " ", " "], $kosong);
        $writer->addRow([" ", " ", " "], $kosong);

        $writer->addRowWithStyle($perhitungan_header, $headerStyle);
        // data Sheet 1 Delivery
        $writer->addRowsWithStyle($perbekalan_data, $detailStyle);

        // header Sheet 1 Delivery
        //$writer->addRowWithStyle($summary_header, $headerStyle);
        // data Sheet 1 Delivery
        //$writer->addRowsWithStyle($laporan_data, $detailStyle);

        // close writter
        $writer->close();
    }
    
    public function download_laporan_per_hari()
    {


        $ses_user       = $this->session->userdata('username');
        if ($ses_user == null) {
            $this->session->set_flashdata('flash', 'Please Login First');
        } else {
            //////// get data /////////

            $select_date = $_GET['select_date'];

            $data_laporan = $this->M_laporan->download_laporan_per_hari($select_date);
            $data_perbekalan = $this->M_laporan->download_perbekalan_per_hari($select_date);
            // var_dump($data_perbekalan);
            // die;

            $pdf = new FPDF('P', 'mm', 'A4');
            $pdf->AddPage();

            $pdf->SetFont('Arial', '', 20);
            $pdf->Cell(180, 12, 'Laporan Keuangan Tanggal ' . $select_date, 0, 1, 'C');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(180, 12, 'Koperasi Nelayan Sumber Laut Mandiri', 0, 1, 'C');

            $pdf->Cell(20, 6, '', 0, 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetFillColor(255, 255, 0);

            $pdf->Cell(180, 6, 'PENJUALAN', 0, 1, 'L');

            $pdf->Cell(50, 6, 'TANGGAL', 1, 0, 'C', true);
            $pdf->Cell(50, 6, 'JENIS IKAN', 1, 0, 'C', true);
            $pdf->Cell(30, 6, 'JUMLAH (KG)', 1, 0, 'C', true);
            $pdf->Cell(30, 6, 'HARGA/KG', 1, 0, 'C', true);
            $pdf->Cell(30, 6, 'TOTAL', 1, 0, 'C', true);
            $pdf->Cell(40, 6, '', 0, 1);
            $pdf->SetFont('Arial', '', 8);

            $hasiltotal = 0;
            $pdf->SetWidths(array(50, 50, 30, 30, 30));
            foreach ($data_laporan as $cm) {
                $pdf->RowPenjualan(array($cm->tanggal, $cm->nama_ikan, $cm->jumlah, ("Rp. " . number_format(floatval($cm->harga_per_kg), 0, ',', '.')), ("Rp. " . number_format(floatval($cm->total), 0, ',', '.'))));
            }


            $pdf->Cell(40, 6, '', 0, 1);
            $pdf->Cell(180, 6, 'DETAIL PEMASUKAN DAN PENGELUARAN', 0, 1, 'L');
            $pdf->Cell(95, 6, 'DETAIL', 1, 0, 'C', true);
            $pdf->Cell(95, 6, 'BIAYA', 1, 0, 'C', true);
            $pdf->Cell(40, 6, '', 0, 1);

            $pdf->SetWidths(array(95, 95));
            foreach ($data_perbekalan as $cm) {
                $pdf->RowLaporanTahunan(array($cm->a, ("Rp. " . number_format(floatval($cm->b), 0, ',', '.'))));
            }
            // $hasiltotal     = ("Rp. " . number_format($hasiltotal, 0, ',', '.'));

            // $pdf->SetFont('Arial', '', 9);
            // $pdf->Cell(140, 6, 'TOTAL', 1, 0, 'L');
            // $pdf->Cell(0, 6, $hasiltotal, 1, 1, 'R');

            $pdf->Cell(20, 4, '', 0, 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(70, 6, 'Dibuat Tanggal : ' . date("Y-m-d"), 0, 1, 'L');

            // $kode_penjualan = $penjualan_detail[0]['kode_penjualan'];
            // $tanggal = $penjualan_detail[0]['created_date'];
            // $pdf->Output('Bukti_Penjualan_' . $kode_penjualan . '_' . $tanggal . '.pdf');
            $pdf->Output();
        }
    }

    public function download_laporan_per_bulan_()
    {


        $select_month = $_GET['select_month'];

        //    $select_date = '2022-05-19';
        //    echo var_dump($select_date);
        //    exit;

        $data_laporan = $this->M_laporan->download_laporan_per_bulan($select_month);


        $summary_header = ['TANGGAL', 'PENJUALAN', 'PERBEKELAN', 'SISA PERBEKALAN', 'POTONGAN 11%', 'SUDAH POTONGAN', 'BAGIAN MITRA 25%', 'HASIL BERSIH'];


        $laporan_data = array();
        foreach ($data_laporan as $value) {
            $detail = array();
            foreach ($value as $k => $v) {
                array_push($detail, $v);
            }
            $laporan_data[] = $detail;
        }



        $writer = WriterFactory::create(Type::XLSX);
        // $select_date     ;
        $filename      = "Laporan Per Bulan " . $select_month .  ".xlsx";


        $writer->openToBrowser($filename);
        $border = (new BorderBuilder())
            ->setBorderBottom(Border::WIDTH_THIN)
            ->setBorderLeft(Border::WIDTH_THIN)
            ->setBorderRight(Border::WIDTH_THIN)
            ->setBorderTop(Border::WIDTH_THIN)
            ->build();
        $headerStyle = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->setBorder($border)
            ->build();
        $detailStyle = (new StyleBuilder())
            ->setFontSize(12)
            ->setBorder($border)
            ->setShouldWrapText(false)
            ->build();
        $kosong = (new StyleBuilder())
            ->setFontSize(12)
            ->setShouldWrapText(false)
            ->build();


        // write ke Sheet 1
        $writer->getCurrentSheet()->setName('LAPORAN');
        // header Sheet 1
        $writer->addRowWithStyle($summary_header, $headerStyle);
        // data Sheet 1 Delivery
        $writer->addRowsWithStyle($laporan_data, $detailStyle);

        // Create 2 Baris Space

        // data Sheet 1 Delivery

        // header Sheet 1 Delivery
        //$writer->addRowWithStyle($summary_header, $headerStyle);
        // data Sheet 1 Delivery
        //$writer->addRowsWithStyle($laporan_data, $detailStyle);

        // close writter
        $writer->close();
    }
    
    public function download_laporan_per_bulan()
    {


        $ses_user       = $this->session->userdata('username');
        if ($ses_user == null) {
            $this->session->set_flashdata('flash', 'Please Login First');
        } else {
            //////// get data /////////

            $select_month = $_GET['select_month'];

            $data_laporan = $this->M_laporan->download_laporan_per_bulan($select_month);

            // var_dump($data_laporan);
            // die;

            // $penjualan_detail   = $this->M_penjualan->view_detail_penjualan($id);
            ////////////////////// create pdf file //////////////////////

            $pdf = new FPDF('P', 'mm', 'A4');
            $pdf->AddPage();

            $pdf->SetFont('Arial', '', 20);
            $pdf->Cell(180, 12, 'Laporan Keuangan Per Bulan ' . $select_month, 0, 1, 'C');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(180, 12, 'Koperasi Nelayan Sumber Laut Mandiri', 0, 1, 'C');

            $pdf->Cell(20, 8, '', 0, 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(255, 255, 0);

            $pdf->Cell(19, 6, 'TANGGAL', 1, 0, 'C', true);
            $pdf->Cell(25, 6, 'PEMASUKAN', 1, 0, 'C', true);
            $pdf->Cell(25, 6, 'PERBEKALAN', 1, 0, 'C', true);
            $pdf->Cell(25, 6, 'SISA PERBEKALAN', 1, 0, 'C', true);
            $pdf->Cell(25, 6, 'POTONGAN 11%', 1, 0, 'C', true);
            $pdf->Cell(25, 6, 'SUDAH POTONGAN', 1, 0, 'C', true);
            $pdf->Cell(25, 6, 'BAGIAN MITRA 25%', 1, 0, 'C', true);
            $pdf->Cell(25, 6, 'HASIL BERSIH', 1, 1, 'C', true);
            $pdf->SetFont('Arial', '', 8);

            $hasiltotal = 0;
            $pdf->SetWidths(array(19, 25, 25, 25, 25, 25, 25, 25));
            foreach ($data_laporan as $cm) {
                $pdf->RowLaporanTahunan(array($cm->created_date, ("Rp. " . number_format($cm->penjualan, 0, ',', '.')), ("Rp. " . number_format($cm->perbekalan, 0, ',', '.')), ("Rp. " . number_format($cm->sisa_perbekalan, 0, ',', '.')), ("Rp. " . number_format($cm->potongan_persen, 0, ',', '.')), ("Rp. " . number_format($cm->sudah_potongan, 0, ',', '.')), ("Rp. " . number_format($cm->bagian_mitra, 0, ',', '.')), ("Rp. " . number_format($cm->hasil_bersih, 0, ',', '.'))));
            }
            // $hasiltotal     = ("Rp. " . number_format($hasiltotal, 0, ',', '.'));

            // $pdf->SetFont('Arial', '', 9);
            // $pdf->Cell(140, 6, 'TOTAL', 1, 0, 'L');
            // $pdf->Cell(0, 6, $hasiltotal, 1, 1, 'R');

            $pdf->Cell(20, 4, '', 0, 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(70, 6, 'Dibuat Tanggal : ' . date("Y-m-d"), 0, 1, 'L');

            // $kode_penjualan = $penjualan_detail[0]['kode_penjualan'];
            // $tanggal = $penjualan_detail[0]['created_date'];
            // $pdf->Output('Bukti_Penjualan_' . $kode_penjualan . '_' . $tanggal . '.pdf');
            $pdf->Output();
        }
    }

    public function download_laporan_per_tahun_()
    {


        $select_year = $_GET['select_year'];

        //    $select_date = '2022-05-19';
        // echo var_dump($select_year);
        // exit;

        $data_laporan = $this->M_laporan->download_laporan_per_tahun($select_year);


        $summary_header = ['BULAN', 'PENJUALAN', 'PERBEKELAN', 'SISA PERBEKALAN', 'POTONGAN 11%', 'SUDAH POTONGAN', 'BAGIAN MITRA 25%', 'HASIL BERSIH'];


        $laporan_data = array();
        foreach ($data_laporan as $value) {
            $detail = array();
            foreach ($value as $k => $v) {
                array_push($detail, $v);
            }
            $laporan_data[] = $detail;
        }



        $writer = WriterFactory::create(Type::XLSX);
        // $select_date     ;
        $filename      = "Laporan Per Tahun " . $select_year .  ".xlsx";


        $writer->openToBrowser($filename);
        $border = (new BorderBuilder())
            ->setBorderBottom(Border::WIDTH_THIN)
            ->setBorderLeft(Border::WIDTH_THIN)
            ->setBorderRight(Border::WIDTH_THIN)
            ->setBorderTop(Border::WIDTH_THIN)
            ->build();
        $headerStyle = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->setBorder($border)
            ->build();
        $detailStyle = (new StyleBuilder())
            ->setFontSize(12)
            ->setBorder($border)
            ->setShouldWrapText(false)
            ->build();
        $kosong = (new StyleBuilder())
            ->setFontSize(12)
            ->setShouldWrapText(false)
            ->build();


        // write ke Sheet 1
        $writer->getCurrentSheet()->setName('LAPORAN');
        // header Sheet 1
        $writer->addRowWithStyle($summary_header, $headerStyle);
        // data Sheet 1 Delivery
        $writer->addRowsWithStyle($laporan_data, $detailStyle);

        // Create 2 Baris Space

        // data Sheet 1 Delivery

        // header Sheet 1 Delivery
        //$writer->addRowWithStyle($summary_header, $headerStyle);
        // data Sheet 1 Delivery
        //$writer->addRowsWithStyle($laporan_data, $detailStyle);

        // close writter
        $writer->close();
    }
    
    public function download_laporan_per_tahun()
    {


        $ses_user       = $this->session->userdata('username');
        if ($ses_user == null) {
            $this->session->set_flashdata('flash', 'Please Login First');
        } else {
            //////// get data /////////

            $select_year = $_GET['select_year'];
            $data_laporan = $this->M_laporan->download_laporan_per_tahun($select_year);

            // var_dump($data_laporan);
            // die;

            // $penjualan_detail   = $this->M_penjualan->view_detail_penjualan($id);
            ////////////////////// create pdf file //////////////////////

            $pdf = new FPDF('P', 'mm', 'A4');
            $pdf->AddPage();

            $pdf->SetFont('Arial', '', 20);
            $pdf->Cell(180, 12, 'Laporan Keuangan Per Tahun ' . $select_year, 0, 1, 'C');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(180, 12, 'Koperasi Nelayan Sumber Laut Mandiri', 0, 1, 'C');

            $pdf->Cell(20, 8, '', 0, 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(255, 255, 0);

            $pdf->Cell(16, 6, 'BULAN', 1, 0, 'C', true);
            $pdf->Cell(25, 6, 'PEMASUKAN', 1, 0, 'C', true);
            $pdf->Cell(25, 6, 'PERBEKALAN', 1, 0, 'C', true);
            $pdf->Cell(25, 6, 'SISA PERBEKALAN', 1, 0, 'C', true);
            $pdf->Cell(25, 6, 'POTONGAN 11%', 1, 0, 'C', true);
            $pdf->Cell(25, 6, 'SUDAH POTONGAN', 1, 0, 'C', true);
            $pdf->Cell(25, 6, 'BAGIAN MITRA 25%', 1, 0, 'C', true);
            $pdf->Cell(25, 6, 'HASIL BERSIH', 1, 1, 'C', true);
            $pdf->SetFont('Arial', '', 8);

            $hasiltotal = 0;
            $pdf->SetWidths(array(16, 25, 25, 25, 25, 25, 25, 25));
            foreach ($data_laporan as $cm) {
                $pdf->RowLaporanTahunan(array($cm->Bulan, ("Rp. " . number_format($cm->penjualan, 0, ',', '.')), ("Rp. " . number_format($cm->perbekalan, 0, ',', '.')), ("Rp. " . number_format($cm->sisa_perbekalan, 0, ',', '.')), ("Rp. " . number_format($cm->potongan_persen, 0, ',', '.')), ("Rp. " . number_format($cm->sudah_potongan, 0, ',', '.')), ("Rp. " . number_format($cm->bagian_mitra, 0, ',', '.')), ("Rp. " . number_format($cm->hasil_bersih, 0, ',', '.'))));
            }
            // $hasiltotal     = ("Rp. " . number_format($hasiltotal, 0, ',', '.'));

            // $pdf->SetFont('Arial', '', 9);
            // $pdf->Cell(140, 6, 'TOTAL', 1, 0, 'L');
            // $pdf->Cell(0, 6, $hasiltotal, 1, 1, 'R');

            $pdf->Cell(20, 4, '', 0, 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(70, 6, 'Dibuat Tanggal : ' . date("Y-m-d"), 0, 1, 'L');

            // $kode_penjualan = $penjualan_detail[0]['kode_penjualan'];
            // $tanggal = $penjualan_detail[0]['created_date'];
            // $pdf->Output('Bukti_Penjualan_' . $kode_penjualan . '_' . $tanggal . '.pdf');
            $pdf->Output();
        }
    }

    public function report_daily($select_date)
    {
        $report_daily_ikan = $this->M_laporan->chart_laporan_per_hari($select_date);
        $nama_ikan = array_column($report_daily_ikan, 'nama_ikan');
        $jumlah_ikan = array_column($report_daily_ikan, 'jumlah');
        $harga_kg = array_column($report_daily_ikan, 'harga/kg');
        $total = array_column($report_daily_ikan, 'total');

        echo json_encode([
            'nama_ikan' => $nama_ikan,
            'jumlah_ikan' => $jumlah_ikan,
            'harga_kg' => $harga_kg,
            'total' => $total,
        ]);
    }

    public function report_monthly($select_date)
    {
        $select_date = str_replace("%20", " ", $select_date);
        $report_daily_ikan = $this->M_laporan->chart_laporan_per_bulan($select_date);
        $nama_ikan = array_column($report_daily_ikan, 'nama_ikan');
        $jumlah_ikan = array_column($report_daily_ikan, 'jumlah');
        $harga_kg = array_column($report_daily_ikan, 'harga/kg');
        $total = array_column($report_daily_ikan, 'total');

        echo json_encode([
            'nama_ikan' => $nama_ikan,
            'jumlah_ikan' => $jumlah_ikan,
            'harga_kg' => $harga_kg,
            'total' => $total,
        ]);
    }

    public function report_yearly($select_date)
    {
        $select_date = str_replace("%20", " ", $select_date);
        $report_daily_ikan = $this->M_laporan->chart_laporan_per_tahun($select_date);
        $nama_ikan = array_column($report_daily_ikan, 'nama_ikan');
        $jumlah_ikan = array_column($report_daily_ikan, 'jumlah');
        $harga_kg = array_column($report_daily_ikan, 'harga/kg');
        $total = array_column($report_daily_ikan, 'total');

        echo json_encode([
            'nama_ikan' => $nama_ikan,
            'jumlah_ikan' => $jumlah_ikan,
            'harga_kg' => $harga_kg,
            'total' => $total,
        ]);
    }
}
