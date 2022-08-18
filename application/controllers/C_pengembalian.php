<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class C_pengembalian extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        include APPPATH . 'third_party/fpdf/fpdf.php';
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('M_pengembalian');
        $this->load->model('M_peminjaman');
    }

    public function index()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['all_pengembalian']   = $this->M_pengembalian->index();

            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('pengembalian/vpengembalian', $data);
            $this->load->view('template/footer');
        }
    }

    public function form_pengembalian_dan_pembayaran($id)
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['keranjang_pinjam']   = $this->M_peminjaman->keranjang_pinjam($id);
            $data['kode_peminjaman']    = $data['keranjang_pinjam'][0]['kode_peminjaman'];
            $data['nelayan']            = $data['keranjang_pinjam'][0]['nama_nelayan'];
            $data['total_biaya_pinjam'] = $data['keranjang_pinjam'][0]['total_peminjaman'];
            $data['total_biaya_kembali'] = $data['keranjang_pinjam'][0]['total_pengembalian'];

            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('pengembalian/vdetail_pengembalian', $data);
            $this->load->view('template/footer');
        }
    }

    public function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = $this->penyebut($nilai - 10) . " Belas";
        } else if ($nilai < 100) {
            $temp = $this->penyebut($nilai / 10) . " Puluh" . $this->penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . $this->penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->penyebut($nilai / 100) . " Ratus" . $this->penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . $this->penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->penyebut($nilai / 1000) . " Ribu" . $this->penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->penyebut($nilai / 1000000) . " Juta" . $this->penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->penyebut($nilai / 1000000000) . " Milyar" . $this->penyebut(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->penyebut($nilai / 1000000000000) . " Trilyun" . $this->penyebut(fmod($nilai, 1000000000000));
        }
        return $temp;
    }

    public  function terbilang($nilai)
    {
        if ($nilai < 0) {
            $hasil = "minus " . trim($this->penyebut($nilai));
        } else
            $hasil = trim($this->penyebut($nilai));
        return $hasil;
    }

    public function download_pdf_pengembalian($id)
    {
        $ses_user       = $this->session->userdata('username');
        if ($ses_user == null) {
            $this->session->set_flashdata('flash', 'Please Login First');
        } else {
            $peminjaman_detail   = $this->M_peminjaman->keranjang_pinjam($id);

            ////////////////////// create pdf file //////////////////////
            $pdf = new FPDF('P', 'mm', 'A4');
            $pdf->AddPage();
            foreach ($peminjaman_detail as $cm) {
                $pdf->SetFont('Arial', '', 20);
                $pdf->Cell(180, 12, 'Bukti Pengembalian', 0, 1, 'C');
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(180, 12, 'Koperasi Nelayan Sumber Laut Mandiri', 0, 1, 'C');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(10, 4, '', 0, 1);
                $pdf->Cell(20, 8, 'Nama Nelayan :', 0, 1, 'L');
                $pdf->Cell(100, 6, $cm['nama_nelayan'], 0, 0, 'L');
                $pdf->Cell(25, 6, 'Kode Peminjaman', 0, 0, 'L');
                $pdf->Cell(5, 6, ':', 0, 0, 'L');
                $pdf->Cell(0, 6, $cm['kode_peminjaman'], 0, 1, 'R'); // kode_peminjaman
                $pdf->Cell(100, 6, 'Nama Kapal', 0, 0, 'L');
                $pdf->Cell(25, 6, 'Tanggal Kembali', 0, 0, 'L');
                $pdf->Cell(5, 6, ':', 0, 0, 'L');
                $pdf->Cell(0, 6, $cm['tanggal_kembali'], 0, 1, 'R'); //created date header
                $pdf->Cell(100, 6, $cm['nama_kapal'], 0, 0, 'L');
                break;
            }

            $pdf->Cell(20, 8, '', 0, 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetFillColor(255, 255, 0);

            $pdf->Cell(70, 6, 'Nama Alat/Bahan', 1, 0, 'C', true);
            $pdf->Cell(30, 6, 'Jumlah Kembali', 1, 0, 'C', true);
            $pdf->Cell(40, 6, 'Harga per Pcs', 1, 0, 'C', true);
            $pdf->Cell(50, 6, 'Total', 1, 1, 'C', true);
            $pdf->SetFont('Arial', '', 9);

            $hasiltotal = 0;
            $pdf->SetWidths(array(70, 30, 40, 50));
            foreach ($peminjaman_detail as $cm) {
                $pdf->RowPeminjaman(array($cm['nama_alat_bahan'], $cm['jumlah_kembali'], ("Rp. " . number_format($cm['harga/unit_kembali'], 0, ',', '.')), ("Rp. " . number_format($cm['jumlah_kembali'] * $cm['harga/unit_kembali'], 0, ',', '.'))));
                $hasiltotal = $hasiltotal + ($cm['jumlah_kembali'] * $cm['harga/unit_kembali']);
            }
            $hasiltotal     = ("Rp. " . number_format($hasiltotal, 0, ',', '.'));

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(140, 6, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(0, 6, $hasiltotal, 1, 1, 'R');

            // $pdf->Cell(20, 4, '', 0, 1);
            // $pdf->SetFont('Arial', '', 9);
            // $pdf->Cell(70, 6, 'Terbilang : ' . $this->terbilang($hasiltotal) . ' Rupiah', 0, 1, 'L');

            $kode_peminjaman = $peminjaman_detail[0]['kode_peminjaman'];
            // $pdf->Output('Bukti Pengembalian ' . $kode_peminjaman . '.pdf', 'D');
            $pdf->Output();
        }
    }
}
