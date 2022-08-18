<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class CLogin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('M_alatbahan');

    }

    public function index()
    {

        $this->load->view('template/header');
        $this->load->view('vlogin');
    }

    public function cekLogin()
    {
        $user = $this->input->post("username");
        $pass = $this->input->post("password");

        $cekakun        = "SELECT * FROM `akun`
                            WHERE 1=1
                            AND akun.`username` = '$user'
                            AND akun.`password` = '$pass'
                        ";
        $execcekakun    = $this->db->query($cekakun)->result_array();

        if ($execcekakun) {
            $akun_id     = $execcekakun[0]['akun_id'];
            $username    = $execcekakun[0]['username'];
            $code_akun   = $execcekakun[0]['code'];
            $tipe_akun   = $execcekakun[0]['tipe'];
            $id_nelayan  = $execcekakun[0]['id_nelayan'];

            $this->session->set_userdata('akun_id', $akun_id);
            $this->session->set_userdata('username', $username);
            $this->session->set_userdata('code_akun', $code_akun);
            $this->session->set_userdata('tipe_akun', $tipe_akun);
            $this->session->set_userdata('id_nelayan', $id_nelayan);
            redirect("C_menu");
        } else {
            $this->session->set_flashdata('flash4', 'Akun Belum Terdaftar');
            redirect(site_url('CLogin'));
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('akun_id');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('code_akun');
        $this->session->unset_userdata('tipe_akun');
        // $this->sessiom->destroy();
        // redirect('CLogin');
        redirect(site_url());
    }
    
    public function form_registrasi()
    {
        $data['guest_code'] = $this->code_guest();
        $data['pilih_alat'] = $this->M_alatbahan->list_alat_regist();
        $this->load->view('template/header');
        $this->load->view('vregistrasi', $data);
    }

    public function cekRegistrasi()
    {
        $nama               = $this->input->post("nama");
        $id_alat            = $this->input->post("id_alat");
        $user               = $this->input->post("username");
        $pass               = $this->input->post("password");
        $pass2              = $this->input->post("password2");

        // echo $nama;
        // echo $id_alat;
        // echo $user;
        // echo $pass;
        // echo $pass2;
        // die;

        if ($pass != $pass2) {
            $this->session->set_flashdata('flash3', 'asdsa');
            redirect('CLogin/form_registrasi');
            die;
        }

        $cekakun        = "SELECT * FROM `akun` WHERE 1=1 AND akun.`username` = '$user' ";
        $execcekakun    = $this->db->query($cekakun)->result_array();

        if ($execcekakun) {
            $this->session->set_flashdata('flash4', 'asdas');
            redirect('CLogin/form_registrasi');
            die;
        }

        $insertnelayan  = "INSERT INTO `nelayan` (
                `nama`,
                `nama_kapal`,
                `jenis_kapal`,
                `id_alat`,
                `GT`,
                `daerah_tangkap`,
                `tanda_pas`,
                `pelabuhan_bongkar`,
                `keterangan`,
                `status`
                )
                VALUES
                (
                '" . $nama . "',
                '',
                '',
                '" . $id_alat . "',
                '',
                '',
                '',
                '',
                '',
                1
                );
        ";
        $execinsertnelayan  = $this->db->query($insertnelayan);

        $ambilid        = "SELECT id FROM nelayan WHERE nama = '$nama'";
        $execambilid    = $this->db->query($ambilid)->result_array();
        $id_nelayan     = $execambilid[0]['id'];

        $masukanakun        = "INSERT INTO `akun` ( `username`, `password`, `code`, `tipe`, `id_nelayan` ) VALUES ( '$user', '$pass', '$nama', 4, '$id_nelayan' );";
        $execmasukanakun    = $this->db->query($masukanakun);

        $this->session->set_flashdata('flash2', 'asdas');
        redirect(site_url('CLogin'));
    }

    public function code_guest()
    {
        $bulan = date('m');
        $tahun = substr(date('Y'), 2);
        $bulan_tahun = $bulan . $tahun;

        $profcode  = "  SELECT MAX(CAST(SUBSTR(nama,10,4) AS UNSIGNED)) AS guest_code  FROM `nelayan` 
        WHERE SUBSTR(nama,6,4) = '$bulan_tahun'";

        $prof_code = $this->db->query($profcode)->result_array();
        $cd        = json_encode($prof_code[0]['guest_code']);
        $code      = json_decode($cd) + 1;

        if ($code > 0) {
            if (strlen($code) == 1) {
                $final_code = "000" . $code;
            } else if (strlen($code) == 2) {
                $final_code = "00" . $code;
            } else if (strlen($code) == 3) {
                $final_code = "0" . $code;
            } else if (strlen($code) == 4) {
                $final_code = $code;
            }
        } else {
            $final_code = "0001";
        }
        $code = 'GUEST' . $bulan_tahun . $final_code;
        // echo $code;
        // die();
        return $code;
    }
}
