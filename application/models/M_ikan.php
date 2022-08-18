<?php

use JetBrains\PhpStorm\Internal\ReturnTypeContract;
use phpDocumentor\Reflection\Types\This;

if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_ikan extends ci_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index($isall = true, $limit = null, $offset = null)
    {
        if ($this->session->userdata('client_id')) {
            $ses_client = $this->session->userdata('client_id');
        } else {
            $ses_client = $this->session->userdata('ID');
        }

        $keyword = str_replace("'", "\'", $this->input->get('table_search'));

        $where = array();
        if (!empty($this->input->get('table_search'))) {
            $where[] = " AND nama_ikan LIKE '%" . $keyword . "%'";
        }

        $is_limit = true;

        isLimit:

        $stringwhere = implode(" AND ", $where);

        $query = "  SELECT
                      * 
                    FROM `ikan` 
                    where 1=1
                    $stringwhere 

                    ORDER BY `id_ikan` DESC

                   
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

    public function hapus_ikan($id_ikan)
    {
        $querylog1   = "DELETE FROM ikan WHERE id_ikan='$id_ikan';";
        $run2       = $this->db->query($querylog1);
      
        // $this->session->set_flashdata('flash', 'Berhasil Dihapus');
    }
    public function edit($id)
    {
        $this->db->where('id_ikan', $id);
        return $this->db->get('ikan')->row_array();
    }
    public function update($id_ikan, $data)
    {
        $this->db->where('id_ikan', $id_ikan);
        $this->db->update('ikan', $data);
    }

    public function count_allikan()
    {
        return $this->db->get('ikan')->num_rows();
    }
}