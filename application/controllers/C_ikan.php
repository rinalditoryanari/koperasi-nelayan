<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class C_ikan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('M_ikan');
       
    }

    public function index()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['all_ikan']   = $this->M_ikan->index(10, 1);

            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('ikan/vikan', $data);
            $this->load->view('template/footer');
            $this->load->library('pagination');


            $config['base_url'] = 'https://localhost/Koperasinelayan/C_ikan/index';
            $config['total_rows'] = $this->M_ikan->count_allikan();
            $config['per_page'] = 10;

            $this->pagination->initialize($config);

            $data['start'] = $this->uri->segment(3);
            $data['ikan'] = $this->M_ikan->index($config['per_page'], $data['start']);

           


        }
    }

    public function form_ikan()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('ikan/vform_ikan');
            $this->load->view('template/footer');
        }
    }

    public function tambah_ikan()
    {
            $config['upload_path']          = './gambar/';
            $config['allowed_types']        = 'gif|jpg|png|PNG|jpeg|jfif';
            $config['max_size']             = 10000;
            $config['max_width']            = 10000;
            $config['max_height']           = 10000;

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('gambar'))
            {
                
                $nama_ikan = $this->input->post('nama_ikan', TRUE);
                $harga_ikan = $this->input->post('harga_ikan', TRUE);

                $data = array(
                        
                        'nama_ikan'  => $nama_ikan,
                        'harga_ikan' => $harga_ikan,
                      
                );
            
               $this->db->insert('ikan', $data);
               $this->session->set_flashdata('pesan', '<div class="alert alert-success"
                role="alert"> Data Berhasil Ditambah! </div>');
                redirect('C_ikan');
                
            }
            else
            {
                $gambar = $this->upload->data();
                $gambar = $gambar['file_name'];
                
                $nama_ikan = $this->input->post('nama_ikan', TRUE);
                $harga_ikan = $this->input->post('harga_ikan', TRUE);

                $data = array(
                        
                        'nama_ikan'  => $nama_ikan,
                        'harga_ikan' => $harga_ikan,
                        'gambar'     => $gambar,
                );
            
               $this->db->insert('ikan', $data);
               $this->session->set_flashdata('pesan', '<div class="alert alert-success"
                role="alert"> Data Berhasil Ditambah! </div>');
                redirect('C_ikan');
        }
    }
    public function edit($id)
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $isi['data'] = $this->M_ikan->edit($id);
            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('ikan/vformedit_ikan', $isi);
            $this->load->view('template/footer');
        }
    }
    public function update()
    {
        $id_ikan= $this->input->post('id_ikan');
        $config['upload_path']          = './gambar/';
        $config['allowed_types']        = 'gif|jpg|png|PNG|jpeg|jfif';
        $config['max_size']             = 10000;
        $config['max_width']            = 10000;
        $config['max_height']           = 10000;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('gambar'))
        {
            $nama_ikan = $this->input->post('nama_ikan', TRUE);
            $harga_ikan = $this->input->post('harga_ikan', TRUE);

            $data = array(
                    
                    'nama_ikan'  => $nama_ikan,
                    'harga_ikan' => $harga_ikan,
                   
            );
           $this->db->where('id_ikan', $id_ikan);
           $this->db->update('ikan', $data);
           $this->session->set_flashdata('pesan', '<div class="alert alert-success"
            role="alert"> Data Berhasil Diubah! </div>');
            redirect('C_ikan');
            
        }
        else
        {
            $gambar = $this->upload->data();
            $gambar = $gambar['file_name'];
            $nama_ikan = $this->input->post('nama_ikan', TRUE);
            $harga_ikan = $this->input->post('harga_ikan', TRUE);

            $data = array(
                    
                    'nama_ikan'  => $nama_ikan,
                    'harga_ikan' => $harga_ikan,
                    'gambar'     => $gambar,
            );
            $this->db->where('id_ikan', $id_ikan);
           $this->db->update('ikan', $data);
           $this->session->set_flashdata('pesan', '<div class="alert alert-success"
            role="alert"> Data Berhasil Diubah! </div>');
            redirect('C_ikan');
    }
    }
    public function hapus_ikan($id)
    {
        $a = $this->M_ikan->hapus_ikan($id);
        redirect('C_ikan');
    }


}