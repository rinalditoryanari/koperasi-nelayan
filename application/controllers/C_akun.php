<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class C_akun extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('M_akun');
    }

    public function index()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['all_akun']   = $this->M_akun->index();

            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('akun/vakun', $data);
            $this->load->view('template/footer');
        }
    }

    public function form_akun()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['list_nelayan'] = $this->M_akun->list_nelayan();
            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('akun/vform_akun', $data);
            $this->load->view('template/footer');
        }
    }

    public function tambah_akun()
    {
        $data = array(
            'akun_id'            => $this->input->post('akun_id'),
            'username'          => $this->input->post('username'),
            'password'         => $this->input->post('password'),
            'code'         => $this->input->post('code'),
            'tipe'         => $this->input->post('tipe'),
            'id_nelayan'         => $this->input->post('id_nelayan'),

        );
        $query = $this->db->insert('akun', $data);
        if ($query = true) {
            $this->session->set_flashdata('info', 'Data Berhasil Di Simpan');
            redirect('C_akun');
        }
    }

    public function edit($akun_id)
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $isi['data'] = $this->M_akun->edit($akun_id);
            $isi['list_nelayan'] = $this->M_akun->list_nelayan();
            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('akun/vformedit_akun', $isi);
            $this->load->view('template/footer');
        }
    }
    public function update()
    {
        $akun_id = $this->input->post('akun_id');
        $data = array(
            'akun_id'                => $this->input->post('akun_id'),
            'username'              => $this->input->post('username'),
            'password'        => $this->input->post('password'),
            'code'        => $this->input->post('code'),
            'tipe'        => $this->input->post('tipe'),
            'id_nelayan'        => $this->input->post('id_nelayan'),

        );
        $query = $this->M_akun->update($akun_id, $data);
        if ($query = true) {
            $this->session->set_flashdata('info', 'Data Berhasil Di Update');
            redirect('C_akun');
        }
    }
    public function hapus_akun($id)
    {
        $a = $this->M_akun->hapus_akun($id);
        redirect('C_akun');
    }
}
