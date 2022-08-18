<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class C_peminjaman extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        include APPPATH . 'third_party/fpdf/fpdf.php';
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('M_peminjaman');
    }

    public function index()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['all_peminjaman']   = $this->M_peminjaman->index();

            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('peminjaman/vpeminjaman', $data);
            $this->load->view('template/footer');
        }
    }

    function jadi_rupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }

    public function form_peminjaman()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['list_nelayan'] = $this->M_peminjaman->list_nelayan();
            $data['list_alat_bahan'] = $this->M_peminjaman->list_alat();
            $data['code_peminjaman'] = $this->M_peminjaman->code_peminjaman();

            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('peminjaman/vform_peminjaman', $data);
            $this->load->view('template/footer');
        }
    }

    public function simpan_alat_bahan()
    {
        $kode_peminjaman    = $_POST['kode_peminjaman'];
        $id_nelayan         = $_POST['id_nelayan'];
        $alat_bahan         = $_POST['alat_bahan'];
        $nama_alat_bahan    = $_POST['nama_alat_bahan'];
        $jumlah             = $_POST['jumlah'];
        $harga              = $_POST['harga_alat_bahan'];
        $harga123           = str_replace('.', '', $harga);
        $harga_alat_bahan   = str_replace('Rp ', '', $harga123);

        if (
            $kode_peminjaman == "" || $id_nelayan == "" || $alat_bahan == "" || $nama_alat_bahan == "" || $jumlah == "" || $harga_alat_bahan == ""
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
            "kode_peminjaman"   => $kode_peminjaman,
            "id_nelayan"        => $id_nelayan,
            "alat_bahan"        => $alat_bahan,
            "nama_alat_bahan"   => $nama_alat_bahan,
            "jumlah"            => $jumlah,
            "harga_alat_bahan"  => $harga_alat_bahan,
            "total"             => $harga_alat_bahan * $jumlah
        );

        if ($this->session->userdata('keranjang_pinjam')) {
            $i = count($this->session->userdata('keranjang_pinjam'));
            $all    = $this->session->userdata('keranjang_pinjam');
            $keranjang              = [];
            foreach ($all as $as) {
                $keranjang[]        = [
                    'kode_peminjaman'    => $as['kode_peminjaman'],
                    'id_nelayan'         => $as['id_nelayan'],
                    'alat_bahan'         => $as['alat_bahan'],
                    'nama_alat_bahan'    => $as['nama_alat_bahan'],
                    'jumlah'             => $as['jumlah'],
                    'harga_alat_bahan'   => $as['harga_alat_bahan'],
                    'total'              => $as['total']
                ];
            }
            $keranjang[$i]        = [
                'kode_peminjaman'       => $kode_peminjaman,
                'id_nelayan'            => $id_nelayan,
                'alat_bahan'            => $alat_bahan,
                'nama_alat_bahan'       => $nama_alat_bahan,
                'jumlah'                => $jumlah,
                'harga_alat_bahan'      => $harga_alat_bahan,
                'total'                 => $harga_alat_bahan * $jumlah
            ];
            $this->session->set_userdata('keranjang_pinjam', $keranjang);
        } else {
            $i = 0;
            $keranjang = array(
                $params
            );
            $this->session->set_userdata('keranjang_pinjam', $keranjang);
        }
        // return $this->session->userdata('ikan_keranjang');
        die(json_encode($this->session->userdata('keranjang_pinjam')));
    }

    public function hapus_alat_bahan($kode_peminjaman, $id_nelayan, $alat_bahan, $nama_alat_bahan, $jumlah, $harga_alat_bahan, $total)
    {
        $params = array(
            "kode_peminjaman"   => $kode_peminjaman,
            "id_nelayan"        => $id_nelayan,
            "alat_bahan"        => $alat_bahan,
            "nama_alat_bahan"   => $nama_alat_bahan,
            "jumlah"            => $jumlah,
            "harga_alat_bahan"  => $harga_alat_bahan,
            "total"             => $total
        );

        $params = str_replace('%20', ' ', $params);
        $nama_alat_bahan = str_replace('%20', ' ', $nama_alat_bahan);

        // var_dump($params);

        $all    = $this->session->userdata('keranjang_pinjam');
        $keranjang              = [];
        $loop                   = 0;
        foreach ($all as $as) {
            // var_dump($as);
            // die;
            if (
                $as['kode_peminjaman'] == $kode_peminjaman &&
                $as['id_nelayan'] == $id_nelayan &&
                $as['alat_bahan'] == $alat_bahan &&
                $as['nama_alat_bahan'] == $nama_alat_bahan &&
                $as['jumlah'] == $jumlah &&
                $as['harga_alat_bahan'] == $harga_alat_bahan &&
                $as['total'] == $total &&
                $loop == 0
            ) {
                $loop = 1;
                continue;
            } else {
                $keranjang[]        = [
                    'kode_peminjaman'       => $as['kode_peminjaman'],
                    'id_nelayan'            => $as['id_nelayan'],
                    'alat_bahan'            => $as['alat_bahan'],
                    'nama_alat_bahan'       => $as['nama_alat_bahan'],
                    'jumlah'                => $as['jumlah'],
                    'harga_alat_bahan'      => $as['harga_alat_bahan'],
                    'total'                 => $as['total']
                ];
            }
        };
        $this->session->set_userdata('keranjang_pinjam', $keranjang);
        die(json_encode($this->session->userdata('keranjang_pinjam')));
    }

    public function form_pinjamkan_nelayan()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['keranjang_pinjam'] = $this->session->userdata('keranjang_pinjam');
            // var_dump($data['keranjang_pinjam']);
            // die;
            $data['total_biaya']    = $this->M_peminjaman->total_peminjaman();
            $data['kode_peminjaman'] = $data['keranjang_pinjam'][0]['kode_peminjaman'];
            $datanama               = $this->M_peminjaman->get_nelayan($data['keranjang_pinjam'][0]['id_nelayan']);
            $data['nelayan']        = $datanama[0]['nama'];

            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('peminjaman/vform_pinjamkan', $data);
            $this->load->view('template/footer');
        }
    }

    public function pinjamkan_nelayan()
    {
        $all = $this->session->userdata('keranjang_pinjam');
        if ($all) {
            $execinput  = $this->M_peminjaman->simpan_peminjaman_alat_bahan($all);
        }
        $this->session->set_userdata('keranjang_pinjam', null);
        redirect("C_peminjaman");
    }

    public function hapus_peminjaman($id)
    {
        $a = $this->M_peminjaman->hapus_peminjaman($id);
        redirect('C_peminjaman');
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
            $this->load->view('peminjaman/vdetail_peminjaman', $data);
            $this->load->view('template/footer');
        }
    }

    public function form_pengembalian_item($id)
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['detail_item_pengembalian']   = $this->M_peminjaman->detail_item_pengembalian($id);
            $data['id_peminjaman_header']   = $data['detail_item_pengembalian'][0]['id_peminjaman_header'];
            $data['id_peminjaman_detail']   = $data['detail_item_pengembalian'][0]['id_peminjaman_detail'];
            $data['kode_peminjaman']        = $data['detail_item_pengembalian'][0]['kode_peminjaman'];
            $data['nama_nelayan']           = $data['detail_item_pengembalian'][0]['nama_nelayan'];
            $data['nama_item']              = $data['detail_item_pengembalian'][0]['nama_item'];
            $data['jumlah_pinjam']          = $data['detail_item_pengembalian'][0]['jumlah_pinjam'];
            $data['jumlah_kembali']         = $data['detail_item_pengembalian'][0]['jumlah_kembali'];
            $data['harga_item_pinjam']      = $data['detail_item_pengembalian'][0]['harga/unit_pinjam'];
            $data['harga_unit_kembali']     = $data['detail_item_pengembalian'][0]['harga/unit_kembali'];

            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('peminjaman/vform_item_pengembalian', $data);
            $this->load->view('template/footer');
        }
    }

    public function simpan_pengembalian_alat_bahan()
    {
        $id_peminjaman_header       = $_POST['id_peminjaman_header'];
        $id_peminjaman_detail       = $_POST['id_peminjaman_detail'];
        $jumlah_pinjam              = $_POST['jumlah_pinjam'];
        $harga_item_pinjam          = $_POST['harga_item_pinjam'];
        $harga_item_pinjam          = str_replace('.', '', $harga_item_pinjam);
        $harga_item_pinjam          = str_replace('Rp ', '', $harga_item_pinjam);
        $jumlah_kembali             = $_POST['jumlah_kembali'];
        $harga_item_kembali         = $_POST['harga_item_kembali'];
        $harga_item_kembali         = str_replace('.', '', $harga_item_kembali);
        $harga_item_kembali         = str_replace('Rp ', '', $harga_item_kembali);

        if ($id_peminjaman_detail == "" || $jumlah_kembali == "") {
            $this->session->set_flashdata('flash4', 'Akun Belum Terdaftar');
            redirect(site_url('C_peminjaman/form_pengembalian_item/' . $id_peminjaman_detail));
        }

        if ($jumlah_kembali > $jumlah_pinjam) {
            $this->session->set_flashdata('flash2', 'Masukan Data Dengan Benar');
            redirect(site_url('C_peminjaman/form_pengembalian_item/' . $id_peminjaman_detail));
        }

        $params = array(
            "id_peminjaman_header"      => $id_peminjaman_header,
            "id_peminjaman_detail"      => $id_peminjaman_detail,
            "jumlah_kembali"            => $jumlah_kembali,
            "harga_item_kembali"        => $harga_item_kembali,
        );

        // var_dump($params);
        // die;

        $this->M_peminjaman->simpan_pengembalian($params);

        redirect(site_url('C_peminjaman/form_pengembalian_dan_pembayaran/' . $id_peminjaman_header));
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

    public function download_pdf_peminjaman($id)
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
                $pdf->Cell(180, 12, 'Bukti Peminjaman', 0, 1, 'C');
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
                $pdf->Cell(25, 6, 'Tanggal Peminjaman', 0, 0, 'L');
                $pdf->Cell(5, 6, ':', 0, 0, 'L');
                $pdf->Cell(0, 6, $cm['tanggal_pinjam'], 0, 1, 'R'); //created date header
                $pdf->Cell(100, 6, $cm['nama_kapal'], 0, 0, 'L');
                break;
            }

            $pdf->Cell(20, 8, '', 0, 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetFillColor(255, 255, 0);

            $pdf->Cell(70, 6, 'Nama Alat/Bahan', 1, 0, 'C', true);
            $pdf->Cell(30, 6, 'Jumlah Pinjam', 1, 0, 'C', true);
            $pdf->Cell(40, 6, 'Harga per Pcs', 1, 0, 'C', true);
            $pdf->Cell(50, 6, 'Total', 1, 1, 'C', true);
            $pdf->SetFont('Arial', '', 9);

            $hasiltotal = 0;
            $pdf->SetWidths(array(70, 30, 40, 50));
            foreach ($peminjaman_detail as $cm) {
                $pdf->RowPeminjaman(array($cm['nama_alat_bahan'], $cm['jumlah_pinjam'], ("Rp. " . number_format($cm['harga/unit_pinjam'], 0, ',', '.')), ("Rp. " . number_format($cm['jumlah_pinjam'] * $cm['harga/unit_pinjam'], 0, ',', '.'))));
                $hasiltotal = $hasiltotal + ($cm['jumlah_pinjam'] * $cm['harga/unit_pinjam']);
            }
            $hasiltotal     = ("Rp. " . number_format($hasiltotal, 0, ',', '.'));

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(140, 6, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(0, 6, $hasiltotal, 1, 1, 'R');

            // $pdf->Cell(20, 4, '', 0, 1);
            // $pdf->SetFont('Arial', '', 9);
            // $pdf->Cell(70, 6, 'Terbilang : ' . $this->terbilang($hasiltotal) . ' Rupiah', 0, 1, 'L');

            $kode_peminjaman = $peminjaman_detail[0]['kode_peminjaman'];
            // $pdf->Output('Bukti Peminjaman ' . $kode_peminjaman . '.pdf', 'D');
            $pdf->Output();
        }
    }
}
