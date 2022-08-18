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
class C_nelayan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('M_nelayan');
    }

    public function index()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['all_nelayan']   = $this->M_nelayan->index();
          
           

            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('nelayan/vnelayan', $data);
            $this->load->view('template/footer');
        }
    }

    public function form_nelayan()
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $data['pilih_alat']   = $this->M_nelayan->list_alat();
            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('nelayan/vform_nelayan', $data);
            $this->load->view('template/footer');
        }
    }

    public function tambah_nelayan()
    {
        $data = array(
            'id'            => $this->input->post('id'),
            'nama'              => $this->input->post('nama'),
            'nama_kapal'        => $this->input->post('nama_kapal'),
            'jenis_kapal'       => $this->input->post('jenis_kapal'),
            'id_alat'           => $this->input->post('id_alat'),
            'GT'                => $this->input->post('GT'),
            'daerah_tangkap'    => $this->input->post('daerah_tangkap'),
            'tanda_pas'         => $this->input->post('tanda_pas'),
            'pelabuhan_bongkar' => $this->input->post('pelabuhan_bongkar'),
            'keterangan'        => $this->input->post('keterangan'),
            'status'            => $this->input->post('status')
        );
        $query = $this->db->insert('nelayan', $data);
        if ($query = true) {
        $this->session->set_flashdata('info', 'Data Berhasil Di Simpan');
        redirect('C_nelayan');
        }
    }
    
    public function edit($id)
    {
        if ($this->session->userdata("akun_id") == "") {
            $this->session->set_flashdata('flash3', 'Login terlebih dahulu');
            redirect(site_url('CLogin'));
        } else {
            $isi['data'] = $this->M_nelayan->edit($id);
            $isi['pilih_alat']   = $this->M_nelayan->list_alat();
            $this->load->view('template/header');
            $this->load->view('template/vsidebar');
            $this->load->view('nelayan/vformedit_nelayan', $isi);
            $this->load->view('template/footer');
        }
    }
    public function update()
    {
        $id = $this->input->post('id');
        $data = array(
                'id'                => $this->input->post('id'),
                'nama'              => $this->input->post('nama'),
                'nama_kapal'        => $this->input->post('nama_kapal'),
                'jenis_kapal'       => $this->input->post('jenis_kapal'),
                'id_alat'           => $this->input->post('id_alat'),
                'GT'                => $this->input->post('GT'),
                'daerah_tangkap'    => $this->input->post('daerah_tangkap'),
                'tanda_pas'         => $this->input->post('tanda_pas'),
                'pelabuhan_bongkar' => $this->input->post('pelabuhan_bongkar'),
                'keterangan'        => $this->input->post('keterangan'),
                'status'            => $this->input->post('status')
            );
        $query = $this->M_nelayan->update($id, $data);
        if ($query = true) {
            $this->session->set_flashdata('info', 'Data Berhasil Di Update');
            redirect('C_nelayan');
        }
    }
    public function hapus_nelayan($id)
    {
        $a = $this->M_nelayan->hapus_nelayan($id);
        redirect('C_nelayan');
    }

    public function download_nelayan()
    {
        
        // echo var_dump($select_date);
        // die;
        //    $select_date = '2022-05-19';
        //    echo var_dump($select_date);
        //    exit;

        $data_laporan = $this->M_nelayan->download_nelayan();
     

        $summary_header = ['NAMA', 'KAPAL', 'JENIS KAPAL', 'ALAT', 'GT', 'DAERAH', 'PAS', 'PELABUHAN', 'KET'];

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
        $filename      = "Daftar Nelayan "  .  ".xlsx";

        $writer->openToBrowser($filename);
        $headerStyle = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->build();
        $detailStyle = (new StyleBuilder())
            ->setFontSize(12)
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
    
        //Create 2 Baris Space
      //  $writer->addRow([" ", " ", " "], $kosong);
       // $writer->addRow([" ", " ", " "], $kosong);
    
        // header Sheet 1 Delivery
        //$writer->addRowWithStyle($summary_header, $headerStyle);
        // data Sheet 1 Delivery
        //$writer->addRowsWithStyle($laporan_data, $detailStyle);
    
        // close writter
        $writer->close();
    }

}