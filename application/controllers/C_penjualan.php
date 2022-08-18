<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class C_penjualan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        include APPPATH . 'third_party/fpdf/fpdf.php';
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('M_penjualan');
    }

    public function index()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['all_penjualan']   = $this->M_penjualan->index();

            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('penjualan/vpenjualan', $data);
            $this->load->view('template/footer');
        }
    }

    function jadi_rupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }

    public function form_penjualan()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['list_nelayan'] = $this->M_penjualan->list_nelayan();
            $data['list_ikan'] = $this->M_penjualan->list_ikan();
            $data['code_penjualan'] = $this->M_penjualan->code_penjualan();

            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('penjualan/vform_penjualan', $data);
            $this->load->view('template/footer');
        }
    }

    public function simpan_ikan()
    {
        $kode_penjualan     = $_POST['kode_penjualan'];
        $nelayan            = $_POST['nelayan'];
        $ikan               = $_POST['ikan'];
        $nama_ikan          = $_POST['nama_ikan'];
        $jumlah             = $_POST['jumlah'];
        $harga              = $_POST['harga_ikan'];
        $harga123           = str_replace('.', '', $harga);
        $harga_ikan         = str_replace('Rp ', '', $harga123);

        if (
            $kode_penjualan == "" || $nelayan == "" || $ikan == "" || $nama_ikan == "" || $jumlah == "" || $harga_ikan == ""
        ) {
            $json   =   array(
                'kode' => 'Error',
                'message' => 'Lengkapi Semua Data'
            );
            die(json_encode($json));
        }

        if ($jumlah == 0 || $jumlah == '0') {
            $json   =   array(
                'kode' => 'Error',
                'message' => 'Jumlah Tidak Boleh Kosong'
            );
            die(json_encode($json));
        }

        $params = array(
            "kode_penjualan" => $kode_penjualan,
            "nelayan"       => $nelayan,
            "ikan"          => $ikan,
            "nama_ikan"     => $nama_ikan,
            "jumlah"        => $jumlah,
            "harga_ikan"    => $harga_ikan,
            "total"         => $harga_ikan * $jumlah
        );

        if ($this->session->userdata('ikan_keranjang')) {
            $i = count($this->session->userdata('ikan_keranjang'));
            $all    = $this->session->userdata('ikan_keranjang');
            $keranjang              = [];
            foreach ($all as $as) {
                $keranjang[]        = [
                    'kode_penjualan'    => $as['kode_penjualan'],
                    'nelayan'           => $as['nelayan'],
                    'ikan'              => $as['ikan'],
                    'nama_ikan'         => $as['nama_ikan'],
                    'jumlah'            => $as['jumlah'],
                    'harga_ikan'        => $as['harga_ikan'],
                    'total'             => $as['total']
                ];
            }
            $keranjang[$i]        = [
                'kode_penjualan'    => $kode_penjualan,
                'nelayan'           => $nelayan,
                'ikan'              => $ikan,
                'nama_ikan'         => $nama_ikan,
                'jumlah'            => $jumlah,
                'harga_ikan'        => $harga_ikan,
                'total'             => $harga_ikan * $jumlah
            ];
            $this->session->set_userdata('ikan_keranjang', $keranjang);
        } else {
            $i = 0;
            $keranjang = array(
                $params
            );
            $this->session->set_userdata('ikan_keranjang', $keranjang);
        }
        // return $this->session->userdata('ikan_keranjang');
        die(json_encode($this->session->userdata('ikan_keranjang')));
    }

    public function hapus_ikan($kode_penjualan, $nelayan, $ikan, $nama_ikan, $jumlah, $harga_ikan, $total)
    {
        $params = array(
            "kode_penjualan" => $kode_penjualan,
            "nelayan"       => $nelayan,
            "ikan"          => $ikan,
            "nama_ikan"     => $nama_ikan,
            "jumlah"        => $jumlah,
            "harga_ikan"    => $harga_ikan,
            "total"         => $total
        );

        $all    = $this->session->userdata('ikan_keranjang');
        $keranjang              = [];
        $loop                   = 0;
        foreach ($all as $as) {
            if (
                $as['kode_penjualan'] == $kode_penjualan &&
                $as['nelayan'] == $nelayan &&
                $as['ikan'] == $ikan &&
                $as['nama_ikan'] == $nama_ikan &&
                $as['jumlah'] == $jumlah &&
                $as['harga_ikan'] == $harga_ikan &&
                $as['total'] == $total &&
                $loop == 0
            ) {
                $loop = 1;
                continue;
            } else {
                $keranjang[]        = [
                    'kode_penjualan'    => $as['kode_penjualan'],
                    'nelayan'           => $as['nelayan'],
                    'ikan'              => $as['ikan'],
                    'nama_ikan'         => $as['nama_ikan'],
                    'jumlah'            => $as['jumlah'],
                    'harga_ikan'        => $as['harga_ikan'],
                    'total'             => $as['total']
                ];
            }
        };
        $this->session->set_userdata('ikan_keranjang', $keranjang);
        die(json_encode($this->session->userdata('ikan_keranjang')));
    }

    public function form_bayar_nelayan()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['keranjang_ikan'] = $this->session->userdata('ikan_keranjang');
            $data['total_biaya']    = $this->M_penjualan->total_pembayaran();
            $data['kode_penjualan'] = $data['keranjang_ikan'][0]['kode_penjualan'];
            $datanama               = $this->M_penjualan->get_nelayan($data['keranjang_ikan'][0]['nelayan']);
            $data['nelayan']        = $datanama[0]['nama'];

            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('penjualan/vform_pembayaran', $data);
            $this->load->view('template/footer');
        }
    }

    public function bayar_nelayan()
    {
        $all = $this->session->userdata('ikan_keranjang');
        $execinput  = $this->M_penjualan->simpan_penjualan_ikan($all);
        $this->session->set_userdata('ikan_keranjang', null);
        redirect("C_penjualan");
    }

    public function hapus_penjualan($id)
    {
        $a = $this->M_penjualan->hapus_penjualan($id);
        redirect('C_penjualan');
    }

    public function view_detail_penjualan($id)
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['keranjang_ikan'] = $this->M_penjualan->view_detail_penjualan($id);
            $total                  = $this->M_penjualan->total_penjualan($id);
            $data['total_biaya']    = $total[0]['total'];
            $data['kode_penjualan'] = $data['keranjang_ikan'][0]['kode_penjualan'];
            $data['nelayan']   = $data['keranjang_ikan'][0]['nama_nelayan'];

            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('penjualan/vdetail_penjualan', $data);
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

    public function download_pdf_penjualan($id)
    {
        $ses_user       = $this->session->userdata('username');
        if ($ses_user == null) {
            $this->session->set_flashdata('flash', 'Please Login First');
        } else {
            $penjualan_detail   = $this->M_penjualan->view_detail_penjualan($id);

            ////////////////////// create pdf file //////////////////////
            $pdf = new FPDF('P', 'mm', 'A4');
            $pdf->AddPage();
            // $pdf->

            foreach ($penjualan_detail as $cm) {
                $pdf->SetFont('Arial', '', 20);
                $pdf->Cell(180, 12, 'Bukti Penjualan', 0, 1, 'C');
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(180, 12, 'Koperasi Nelayan Sumber Laut Mandiri', 0, 1, 'C');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(10, 4, '', 0, 1);
                $pdf->Cell(20, 8, 'Nama Nelayan :', 0, 1, 'L');
                $pdf->Cell(100, 6, $cm['nama_nelayan'], 0, 0, 'L');
                $pdf->Cell(25, 6, 'Kode Penjualan', 0, 0, 'L');
                $pdf->Cell(5, 6, ':', 0, 0, 'L');
                $pdf->Cell(0, 6, $cm['kode_penjualan'], 0, 1, 'R'); // kode_penjualan
                $pdf->Cell(100, 6, 'Nama Kapal', 0, 0, 'L');
                $pdf->Cell(25, 6, 'Tanggal', 0, 0, 'L');
                $pdf->Cell(5, 6, ':', 0, 0, 'L');
                $pdf->Cell(0, 6, $cm['created_date'], 0, 1, 'R'); //created date header
                $pdf->Cell(100, 6, $cm['nama_kapal'], 0, 0, 'L');
                break;
            }

            $pdf->Cell(20, 8, '', 0, 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetFillColor(255, 255, 0);

            $pdf->Cell(70, 6, 'Nama Ikan', 1, 0, 'C', true);
            $pdf->Cell(30, 6, 'Jumlah (Kg)', 1, 0, 'C', true);
            $pdf->Cell(40, 6, 'Harga per Kg', 1, 0, 'C', true);
            $pdf->Cell(50, 6, 'Total', 1, 1, 'C', true);
            $pdf->SetFont('Arial', '', 9);

            $hasiltotal = 0;
            $pdf->SetWidths(array(70, 30, 40, 50));
            foreach ($penjualan_detail as $cm) {
                $pdf->RowPenjualan(array($cm['nama_ikan'], $cm['jumlah'], ("Rp. " . number_format($cm['harga_ikan'], 0, ',', '.')), ("Rp. " . number_format($cm['jumlah'] * $cm['harga_ikan'], 0, ',', '.'))));
                $hasiltotal = $hasiltotal + ($cm['jumlah'] * $cm['harga_ikan']);
            }
            $hasiltotal     = ("Rp. " . number_format($hasiltotal, 0, ',', '.'));

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(140, 6, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(0, 6, $hasiltotal, 1, 1, 'R');

            // $pdf->Cell(20, 4, '', 0, 1);
            // $pdf->SetFont('Arial', '', 9);
            // $pdf->Cell(70, 6, 'Terbilang : ' . $this->terbilang($hasiltotal) . ' Rupiah', 0, 1, 'L');

            $kode_penjualan = $penjualan_detail[0]['kode_penjualan'];
            $tanggal = $penjualan_detail[0]['created_date'];
            // $pdf->Output('Bukti_Penjualan_' . $kode_penjualan . '_' . $tanggal . '.pdf');
            $pdf->Output();
        }
    }
}
