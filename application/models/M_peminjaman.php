<?php

use JetBrains\PhpStorm\Internal\ReturnTypeContract;

if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_peminjaman extends ci_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index($isall = TRUE, $limit = NULL, $offset = NULL)
    {
        if ($this->session->userdata('tipe_akun') == '0') {
            $id_nelayan  = $this->session->userdata('id_nelayan');
            $ses_nelayan = "AND a.`id_nelayan` = '$id_nelayan'";
        } else {
            $ses_nelayan = '';
        }

        $keyword = str_replace("'", "\'", $this->input->get('table_search'));

        $where = array();
        if (!empty($this->input->get('table_search'))) {
            $where[] = " AND a.code LIKE '%" . $keyword . "%'";
        }

        $is_limit = true;

        isLimit:

        $stringwhere = implode(" AND ", $where);

        $query = "  SELECT
                        a.`id_peminjaman_header`,
                        a.`id_nelayan`,
                        b.`nama` as nama_nelayan,
                        a.`code` as code_peminjaman,
                        a.`total_pinjam`,
                        a.`status`,
                        a.`created_date` as tanggal_pinjam,
                        a.`created_by`,
                        a.`modified_date` as tanggal_kembali,
                        a.`modified_by`
                    FROM
                        `peminjaman_header` a
                    JOIN nelayan b ON a.`id_nelayan` = b.`id`
                    $stringwhere
                    $ses_nelayan
                    AND a.`status` = 0
                    ORDER BY a.`id_peminjaman_header` DESC
                    
                    ";

        // echo $query;
        // die();
        if ($is_limit) {
            if (!$isall) {
                $query .= ' LIMIT ' . $limit . " offset " . $offset;
            }
        }

        if ($is_limit) {
            $res = $this->db->query($query)->result_array();
            $is_limit = false;
            goto isLimit;
        }

        $count = $this->db->query($query)->num_rows();

        $data = array(
            "total" => $count,
            "data" => $res
        );
        // var_dump($data);
        // die;
        return $data;
    }

    public function list_nelayan()
    {
        $pilih_client = "SELECT	
                            `id` as id_nelayan,
                            `nama` as nama_nelayan,
                            `nama_kapal` as kapal_nelayan
                        FROM `nelayan`
                        WHERE `status` = 1";
        $client = $this->db->query($pilih_client)->result_array();

        return $client;
    }

    public function list_alat()
    {
        $pilih_client = "SELECT `id_alat`, `nama` as nama_alat, `jenis`, `satuan`, `harga_per_unit` FROM `alat` ;";
        $client = $this->db->query($pilih_client)->result_array();
        return $client;
    }

    public function code_peminjaman()
    {
        $bulan = date('m');
        $tahun = substr(date('Y'), 2);
        $bulan_tahun = $bulan . $tahun;

        $profcode  = "  SELECT MAX(CAST(SUBSTR(CODE,7,4) AS UNSIGNED)) AS code_peminjaman  FROM `peminjaman_header` 
        WHERE SUBSTR(CODE,3,4) = '$bulan_tahun'";

        $prof_code = $this->db->query($profcode)->result_array();
        $cd        = json_encode($prof_code[0]['code_peminjaman']);
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
        $code = 'PJ' . $bulan_tahun . $final_code;
        // die();
        return $code;
    }

    public function total_peminjaman()
    {
        $total  = 0;
        $all    = $this->session->userdata('keranjang_pinjam');
        if ($all) {
            foreach ($all as $as) {
                $total  = $total + $as['total'];
            }
        }
        return $total;
    }

    public function get_nelayan($id)
    {
        $pilih_client = "SELECT nama FROM nelayan WHERE id = '$id';";
        $client = $this->db->query($pilih_client)->result_array();

        return $client;
    }

    public function simpan_peminjaman_alat_bahan($all)
    {
        // var_dump($all);
        // die;
        $ses_username   = $this->session->userdata('username');
        $total          = $this->total_peminjaman();
        $kode_peminjaman = $all[0]['kode_peminjaman'];
        $nelayan        = $all[0]['id_nelayan'];
        $insertheader = "INSERT INTO `peminjaman_header` (
                            `id_nelayan`,
                            `code`,
                            `total_pinjam`,
                            `created_date`,
                            `created_by`,
                            `modified_date`,
                            `modified_by`
                            )
                            VALUES
                            (
                            $nelayan,
                            '$kode_peminjaman',
                            $total,
                            now(),
                            '$ses_username',
                            now(),
                            '$ses_username'
                            );
                        ";
        $q = $this->db->query($insertheader);

        $querypeminjaman_id      = "SELECT `id_peminjaman_header` FROM `peminjaman_header` WHERE `code` = '$kode_peminjaman';";
        $execpeminjaman_id       = $this->db->query($querypeminjaman_id)->result_array();
        $id_peminjaman_header    = $execpeminjaman_id[0]['id_peminjaman_header'];

        foreach ($all as $a) {
            $insertdetail = "INSERT INTO `peminjaman_detail` (
                `id_peminjaman_header`,
                `id_alat`,
                `jumlah_pinjam`,
                `harga/unit_pinjam`,
                `status`,
                `created_date`,
                `created_by`,
                `modified_date`,
                `modified_by`
                )
                VALUES
                (
                '$id_peminjaman_header',
                '" . $a['alat_bahan'] . "',
                '" . $a['jumlah'] . "',
                '" . $a['harga_alat_bahan'] . "',
                0,
                now(),
                '$ses_username',
                now(),
                '$ses_username'
                );
                ";
            // echo $insertdetail;
            // echo '<br>';
            $w = $this->db->query($insertdetail);
        }
        // die;
    }

    public function hapus_peminjaman($id)
    {
        $querylog1   = "DELETE FROM peminjaman_detail WHERE id_peminjaman='$id';";
        $run2       = $this->db->query($querylog1);
        $querylog2   = "DELETE FROM peminjaman_header WHERE id_peminjaman_header='$id';";
        $run2       = $this->db->query($querylog2);
        // $this->session->set_flashdata('flash', 'Berhasil Dihapus');
    }

    public function keranjang_pinjam($id)
    {
        $pilih_client = "SELECT
                            a.`id_peminjaman_header`,
                            a.`id_peminjaman_detail`,
                            b.`code` AS kode_peminjaman,
                            d.`nama` AS nama_nelayan,
                            d.`nama_kapal`,
                            c.`nama` AS nama_alat_bahan,
                            a.`jumlah_pinjam`,
                            a.`harga/unit_pinjam`,
                            a.`jumlah_kembali`,
                            a.`harga/unit_kembali`,
                            (a.`harga/unit_pinjam` * a.`jumlah_pinjam`) AS total_pinjam_per_item,
                            (a.`harga/unit_kembali` * a.`jumlah_kembali`) AS total_kembali_per_item,
                            a.`status`,
                            b.`total_pinjam` AS total_peminjaman,
                            b.`total_kembali` AS total_pengembalian,
                            b.`created_date` AS tanggal_pinjam,
                            b.`modified_date` as tanggal_kembali
                        FROM peminjaman_detail a
                        JOIN peminjaman_header b ON a.`id_peminjaman_header` = b.`id_peminjaman_header`
                        JOIN alat c ON a.`id_alat` = c.`id_alat`
                        JOIN nelayan d ON d.`id` = b.`id_nelayan`
                        WHERE 1=1
                        AND b.`id_peminjaman_header` = '$id';";
        // echo $pilih_client;
        // die;
        $client = $this->db->query($pilih_client)->result_array();

        return $client;
    }

    public function detail_item_pengembalian($id)
    {
        $pilih_client = "SELECT 
                            a.`id_peminjaman_detail`,
                            c.`id_nelayan`,
                            a.`id_alat`,
                            c.`id_peminjaman_header`,
                            c.`code` AS kode_peminjaman,
                            d.`nama` AS nama_nelayan,
                            b.`nama` AS nama_item,
                            a.`jumlah_pinjam`,
                            a.`harga/unit_pinjam`,
                            a.`jumlah_kembali`,
                            a.`harga/unit_kembali`,
                            c.`total_pinjam`,
                            a.`status`,
                            c.`created_date` as tanggal_pinjam,
                            c.`modified_date` as tanggal_kembali
                        FROM peminjaman_detail a
                        JOIN `alat` b ON a.`id_alat` = b.`id_alat`
                        JOIN peminjaman_header c ON a.`id_peminjaman_header` = c.`id_peminjaman_header`
                        JOIN nelayan d ON c.`id_nelayan` = d.`id`
                        WHERE 1=1
                        AND `id_peminjaman_detail` = '$id';";
        // echo $pilih_client;
        // die;
        $client = $this->db->query($pilih_client)->result_array();

        return $client;
    }

    public function simpan_pengembalian($params)
    {
        $ses_username = $this->session->userdata('username');
        $jumlah_kembali = $params['jumlah_kembali'];
        $harga_item_kembali = $params['harga_item_kembali'];
        $id_peminjaman_header = $params['id_peminjaman_header'];
        $id_peminjaman_detail = $params['id_peminjaman_detail'];


        $update_detail =   "UPDATE
                        `peminjaman_detail`
                    SET
                        `jumlah_kembali` = '$jumlah_kembali',
                        `harga/unit_kembali` = '$harga_item_kembali',
                        `status` = 1,
                        `modified_date` = NOW(),
                        `modified_by` = '$ses_username'
                    WHERE `id_peminjaman_detail` = '$id_peminjaman_detail';";
        $client1 = $this->db->query($update_detail);

        $total_kembali = $this->total_pengembalian($id_peminjaman_header);
        $total_kembali = $total_kembali[0]['total_kembali'];

        $update_header =   "UPDATE
                        `peminjaman_header`
                    SET
                        `total_kembali` = '$total_kembali',
                        `status` = 1,
                        `modified_date` = NOW(),
                        `modified_by` = '$ses_username'
                    WHERE `id_peminjaman_header` = '$id_peminjaman_header';";
        $client2 = $this->db->query($update_header);
    }

    public function total_pengembalian($id)
    {
        $pilih_client = "SELECT 
                            SUM(a.`jumlah_kembali` * a.`harga/unit_kembali`) AS total_kembali
                        FROM `peminjaman_detail` a
                        WHERE 1=1
                        AND a.`id_peminjaman_header` = '$id'";
        $client = $this->db->query($pilih_client)->result_array();
        return $client;
    }
}
