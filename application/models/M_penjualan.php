<?php

use JetBrains\PhpStorm\Internal\ReturnTypeContract;

if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_penjualan extends ci_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }
    public function index($isall = TRUE, $limit = NULL, $offset = NULL)
    {
        if ($this->session->userdata('tipe_akun') == '0' || $this->session->userdata('tipe_akun') == '4') {
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
                        a.`id_penjualan_header`,
                        a.`id_nelayan`,
                        b.`nama`as nama_nelayan,
                        a.`code` AS kode_penjualan,
                        a.`total` AS total_biaya,
                        a.`created_date`
                    FROM `penjualan_header` a
                    JOIN nelayan b ON a.`id_nelayan` = b.`id`
                    $stringwhere
                    $ses_nelayan

                    ORDER BY a.`id_penjualan_header` DESC
                    LIMIT 99999999";

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

    public function list_ikan()
    {
        $pilih_client = "SELECT
                            `id_ikan`,
                            `nama_ikan`,
                            `harga_ikan`
                        FROM `ikan`
                        ";
        $client = $this->db->query($pilih_client)->result_array();

        return $client;
    }

    public function get_nelayan($id)
    {
        $pilih_client = "SELECT nama FROM nelayan WHERE id = '$id';";
        $client = $this->db->query($pilih_client)->result_array();

        return $client;
    }

    public function total_pembayaran()
    {
        $total  = 0;
        $all    = $this->session->userdata('ikan_keranjang');
        foreach ($all as $as) {
            $total  = $total + $as['total'];
        }
        return $total;
    }

    public function code_penjualan()
    {
        $bulan = date('m');
        $tahun = substr(date('Y'), 2);
        $bulan_tahun = $bulan . $tahun;

        $profcode  = "  SELECT MAX(CAST(SUBSTR(CODE,7,4) AS UNSIGNED)) AS code_penjualan  FROM `penjualan_header` 
        WHERE SUBSTR(CODE,3,4) = '$bulan_tahun'";

        $prof_code = $this->db->query($profcode)->result_array();
        $cd        = json_encode($prof_code[0]['code_penjualan']);
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
        $code = 'JL' . $bulan_tahun . $final_code;
        // die();
        return $code;
    }

    public function simpan_penjualan_ikan($all)
    {
        $ses_username   = $this->session->userdata('username');
        $total          = $this->total_pembayaran();
        $kode_penjualan = $all[0]['kode_penjualan'];
        $nelayan        = $all[0]['nelayan'];
        $insertheader = "INSERT INTO `penjualan_header` (
                            `id_nelayan`,
                            `code`,
                            `total`,
                            `created_date`,
                            `created_by`,
                            `modified_date`,
                            `modified_by`
                        )
                        VALUES
                            (
                            '$nelayan',
                            '$kode_penjualan',
                            '$total',
                            now(),
                            '$ses_username',
                            now(),
                            '$ses_username'
                            );
                        ";
        $q = $this->db->query($insertheader);

        $querypenjualan_id      = "SELECT `id_penjualan_header` FROM `penjualan_header` WHERE `code` = '$kode_penjualan';";
        $execpenjualan_id       = $this->db->query($querypenjualan_id)->result_array();
        $id_penjualan_header    = $execpenjualan_id[0]['id_penjualan_header'];

        foreach ($all as $a) {
            $insertdetail = "INSERT INTO `penjualan_detail` ( `id_penjualan`, `id_ikan`, `berat`, `harga/kg`, `created_date`, `created_by`, `modified_date`, `modified_by` ) VALUES ( '$id_penjualan_header', " . $a['ikan'] . ", " . $a['jumlah'] . ", " . $a['harga_ikan'] . ", now(), '$ses_username', now(), '$ses_username' ); ";
            $w = $this->db->query($insertdetail);
        }
    }

    public function hapus_penjualan($id)
    {
        $querylog1   = "DELETE FROM penjualan_detail WHERE id_penjualan='$id';";
        $run2       = $this->db->query($querylog1);
        $querylog2   = "DELETE FROM penjualan_header WHERE id_penjualan_header='$id';";
        $run2       = $this->db->query($querylog2);
        // $this->session->set_flashdata('flash', 'Berhasil Dihapus');
    }

    public function view_detail_penjualan($id)
    {
        $pilih_client = "SELECT 
                            b.code as kode_penjualan,
                            d.nama as nama_nelayan,
                            d.nama_kapal,
                            c.nama_ikan,
                            a.berat as jumlah,
                            a.`harga/kg` as harga_ikan,
                            a.berat * a.`harga/kg` as total,
                            b.created_date
                        FROM penjualan_detail a 
                        JOIN penjualan_header b ON a.id_penjualan = b.id_penjualan_header 
                        JOIN ikan c ON c.id_ikan = a.id_ikan
                        JOIN nelayan d on b.id_nelayan = d.id  
                        WHERE 1=1
                        AND b.id_penjualan_header =  '$id';";
        $client = $this->db->query($pilih_client)->result_array();

        return $client;
    }

    public function total_penjualan($id)
    {
        $pilih_client = "SELECT 
                            b.total 
                        FROM penjualan_header b
                        WHERE 1=1
                        AND b.id_penjualan_header =  '$id';";
        $client = $this->db->query($pilih_client)->result_array();

        return $client;
    }

    /////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////
    ////////////////////////////diatas sia nelayan///////////////////////////
    /////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////


    // public function list_client()
    // {
    //     if ($this->session->userdata('client_id')) {
    //         $ses_client = $this->session->userdata('client_id');
    //     } else {
    //         $ses_client = $this->session->userdata('ID');
    //     }

    //     if ($this->session->userdata('username') == "revangga" || $this->session->userdata('username') == "rizaldi.akbar") {
    //         $pilih_client = "SELECT client_id, `name`
    //                                 FROM `client` 
    //                                 WHERE client_id <> '" . $ses_client . "' 
    //                                 AND active = 2
    //                                 ORDER BY `name` ASC";
    //         // AND client_parent_id <> 8
    //         $client = $this->db->query($pilih_client)->result_array();
    //     } else {
    //         $pilih_client = "SELECT client_id, `name` 
    //                             FROM `client` 
    //                             WHERE client_id <> '" . $ses_client . "' 
    //                             AND (client_parent_id NOT IN (1,8) OR client_id = 9999)
    //                             AND active = 2
    //                             ORDER BY NAME ASC";
    //         $client = $this->db->query($pilih_client)->result_array();
    //     }
    //     return $client;
    // }

    // public function cek_approval($email)
    // {
    //     $cek_approval = "SELECT 
    //                         sa.`approval_email_1`,
    //                         sa.`approval_email_2`,
    //                         sa.`approval_email_3`
    //                     FROM `setupapproval` sa
    //                     WHERE 1=1
    //                     OR sa.`approval_email_1` = '$email'
    //                     OR sa.`approval_email_2` = '$email'
    //                     OR sa.`approval_email_3` = '$email'
    //                     ";
    //     $app = $this->db->query($cek_approval)->result_array();
    //     // echo var_dump($app[0]);
    //     // die();
    //     return $app;
    // }

    // public function list_item($isall = TRUE, $limit = NULL, $offset = NULL)
    // {
    //     if ($this->session->userdata('client_id')) {
    //         $ses_client = $this->session->userdata('client_id');
    //     } else {
    //         $ses_client = $this->session->userdata('ID');
    //     }

    //     $keyword = str_replace("'", "''", $this->input->get('keyword'));

    //     $where = array();
    //     if (!empty($this->input->get('search') && $this->input->get('keyword'))) {
    //         $where[] = " AND " . $this->input->get('search') . " LIKE '%" . $keyword . "%'";
    //     }

    //     $is_limit = true;

    //     isLimit:

    //     $stringwhere = implode(" AND ", $where);
    //     $query = "  SELECT
    //                     i.item_id, i.code, i.name, i.barcode, c.client_id, c.name AS client_name, i.category_billing, i.publish_price
    //                 FROM item i
    //                 JOIN client c ON c.client_id = i.client_id
    //                 WHERE 1=1
    //                 AND c.client_id = $ses_client
    //                 $stringwhere
    //                 ORDER BY name ASC";
    //     if ($is_limit) {
    //         if (!$isall) {
    //             $query .= ' LIMIT ' . $limit . " offset " . $offset;
    //         }
    //     }

    //     if ($is_limit) {
    //         $res = $this->db->query($query)->result_array();
    //         $is_limit = false;
    //         goto isLimit;
    //     }

    //     $count = $this->db->query($query)->num_rows();

    //     $data = array(
    //         "total" => $count,
    //         "data" => $res
    //     );
    //     return $res;
    // }

    // public function getTeam()
    // {
    //     $getteam = "select distinct team from configclient where team NOT IN ('Closed Project','NULL')";
    //     $team = $this->db->query($getteam)->result_array();
    //     return $team;
    // }

    // public function list_name()
    // {
    //     if ($this->session->userdata('client_id')) {
    //         $ses_client = $this->session->userdata('client_id');
    //     } else {
    //         $ses_client = $this->session->userdata('ID');
    //     }

    //     $piliharray =  "SELECT client_id, `name` AS nama
    //                     FROM `client` 
    //                     WHERE client_id <> '" . $ses_client . "' 
    //                     AND (client_parent_id NOT IN (1,8) OR client_id = 9999)
    //                     AND active = 2
    //                     ORDER BY NAME ASC";
    //     return $this->db->query($piliharray)->result_array();
    // }

    // public function proses_tambah()
    // {
    //     if ($this->session->userdata('client_id')) {
    //         $ses_client = $this->session->userdata('client_id');
    //     } else {
    //         $ses_client = $this->session->userdata('ID');
    //     }
    //     date_default_timezone_set('Asia/Jakarta');
    //     $now           = date('Y-m-d H:i:s');
    //     $users_id      = $this->session->userdata('users_id');

    //     $client_post     = $this->input->post('cli_id');
    //     $target_rev    = preg_replace('([^0-9])', '', $this->input->post('target_rev'));
    //     $periode       = $this->input->post('periode');
    //     $created_date  = $now;
    //     $modified_date = $now;
    //     $created_by    = $users_id;
    //     $modified_by   = $users_id;

    //     $data = array(
    //         'client_id'     => $client_post,
    //         'target_revenue' => $target_rev,
    //         'periode'       => $periode,
    //         'created_date'  => $created_date,
    //         'modified_date' => $modified_date,
    //         'created_by'    => $created_by,
    //         'modified_by'   => $modified_by
    //     );

    //     $querylog  =   "INSERT INTO targetrevenue 
    //                             (
    //                                 client_id, target_revenue, periode, created_date, modified_date, created_by, modified_by
    //                             ) 
    //                     values  (
    //                                 '$client_post', '$target_rev', (STR_TO_DATE(CONCAT('$periode',',1'),'%M %Y,%D')), NOW(), NOW(), '$users_id', '$users_id'
    //                             )";
    //     $run2   = $this->db->query($querylog);
    //     $this->session->set_flashdata('flash', 'Berhasil Dibuat');
    // }

    // public function hapus_target_revenue($id)
    // {
    //     $querylog   = "DELETE FROM targetrevenue WHERE target_revenue_id='$id';";
    //     $run2       = $this->db->query($querylog);
    //     $this->session->set_flashdata('flash', 'Berhasil Dihapus');
    // }

    // public function list_edit_target_revenue($where)
    // {
    //     $target_rev = " SELECT 	a.`target_revenue_id`,
    //                             a.`client_id`,
    //                             b.`name`,
    //                             a.`target_revenue`,
    //                             CONCAT( MONTHNAME(CONCAT('0000-',SUBSTRING(a.`periode`, 6, 2),'-00')),' ',YEAR(CONCAT('20',SUBSTRING(a.`periode`, 3, 2),'-00-00'))) AS periode,
    //                             a.`created_date`,
    //                             a.`created_by`,
    //                             a.`modified_date`,
    //                             a.`modified_by`
    //                     FROM targetrevenue a
    //                     JOIN `client` b ON b.`client_id`= a.`client_id`
    //                     WHERE a.`target_revenue_id` = '" . $where['target_revenue_id'] . "'";
    //     return $edit_component = $this->db->query($target_rev)->result();
    // }

    // public function proses_edit()
    // {
    //     date_default_timezone_set('Asia/Jakarta');
    //     $now      = date('Y-m-d H:i:s');
    //     $users_id = $this->session->userdata('users_id');

    //     $tempperiode         = $this->input->post('periode');
    //     $bikinperiode        = "SELECT (STR_TO_DATE(CONCAT('$tempperiode',',1'),'%M %Y,%D')) AS tempperiod";
    //     $periode             = $this->db->query($bikinperiode)->result_array();
    //     $target_revenue_id   = $this->input->post('cli_id');  //berisi target_revenue_id
    //     $target_revenue      = preg_replace('([^0-9])', '', $this->input->post('target_rev'));
    //     $modified_date       = $now;
    //     $modified_by         = $users_id;

    //     $data = array(
    //         'target_revenue'  => $target_revenue,
    //         'periode'         => $periode[0]["tempperiod"],
    //         'modified_date'   => $modified_date,
    //         'modified_by'     => $modified_by
    //     );

    //     $where = array(
    //         'target_revenue_id' => $target_revenue_id,
    //     );

    //     $this->db->where('target_revenue_id', $target_revenue_id);
    //     $this->db->update('targetrevenue', $data);

    //     $this->session->set_flashdata('flash', 'Berhasil Update');
    // }

    // public function get_claim_code($id)
    // {
    //     $getteam = "SELECT claim_code FROM `claimheader` WHERE `claim_header_id` = '$id'";
    //     $team = $this->db->query($getteam)->result_array();
    //     return $team;
    // }

    // public function get_claim_id($code)
    // {
    //     $code1    = str_replace("'", "\'", $code);
    //     $getteam = "SELECT claim_header_id FROM `claimheader` WHERE `claim_code` = '$code1'";
    //     $team = $this->db->query($getteam)->result_array();
    //     return $team;
    // }

    // public function get_item_in_claim($getted_claim_header_id)
    // {
    //     $query_alliteminclaim = "SELECT 
    //                                 cd.`claim_detail_id`,
    //                                 ch.`claim_code`,
    //                                 ch.`responsiblity`,
    //                                 cd.`document_claim`,
    //                                 item.`item_id`,
    //                                 cd.`item_name`,
    //                                 cd.`quantity`,
    //                                 cd.`price`
    //                             FROM `claimdetail` cd
    //                             JOIN `claimheader` ch ON cd.`claim_header_id` = ch.`claim_header_id`
    //                             JOIN `item` ON cd.`item_code` = item.`code`
    //                             WHERE 1=1
    //                             AND ch.`claim_header_id` = '$getted_claim_header_id'";
    //     $exec_alliteminclaim   = $this->db->query($query_alliteminclaim)->result();

    //     return $exec_alliteminclaim;
    // }

    // public function get_courier()
    // {
    //     $getteam = "SELECT name FROM `courier` WHERE `courier_id` NOT IN (23,76,83,85,87,89)";
    //     $team = $this->db->query($getteam)->result();
    //     return $team;
    // }

    // public function get_list_item($client_id)
    // {
    //     $listitem = "SELECT 	
    //                     item.`item_id` AS item_id,
    //                     item.`code` AS item_sku,
    //                     item.`name` AS item_name
    //                 FROM item 
    //                 JOIN `client` ON item.`client_id` = client.`client_id`
    //                 WHERE `client`.`client_id` = '$client_id'
    //                 ";
    //     $item = $this->db->query($listitem)->result();
    //     return $item;
    // }

    // public function proses_add_claim_monitoring($params)
    // {


    //     $user_id = $this->session->userdata('username');

    //     $location_id        = $params['location_id'];
    //     $client_id          = $params['client_id'];
    //     $claim_type         = $params['claim_type'];
    //     $reason             = $params['reason'];
    //     $claimcode          = str_replace("'", "\'", $params['claimcode']);
    //     $project_name       = $params['project_name'];
    //     $doc_claim          = $params['doc_claim'];
    //     $cases              = $params['cases'];
    //     $switch_ppn         = $params['switch_ppn'];
    //     $norekclient        = $params['norekclient'];
    //     $responsiblity      = $params['responsiblity'];
    //     $metodepembayaran   = $params['metodepembayaran'];
    //     $termpayment        = $params['termpayment'];
    //     $duedate            = $params['duedate'];
    //     $noberitaacara      = $params['noberitaacara'];
    //     $tanggalberitaacara = $params['tanggalberitaacara'];
    //     $itemname           = $params['itemname'];
    //     $hargarp            = str_replace('.', '', $params['harga']);
    //     $harga              = str_replace('Rp ', '', $hargarp);
    //     $quantity           = $params['quantity'];
    //     $urlfileba          = $params['urlfileba'];
    //     $urlfileinvoice     = $params['urlfileinvoice'];
    //     $fileberitaacara    = $params['fileberitaacara'];
    //     $fileinvoiceclient  = $params['fileinvoiceclient'];

    //     //////////////////////////////get item detail//////////////////////////////////////////
    //     $getitemdetail      = " select 
    //                                 code as item_code,
    //                                 name as item_name,
    //                                 barcode
    //                             from item
    //                             where item_id = '$itemname'
    //                             ";
    //     $runcekitem = $this->db->query($getitemdetail)->result_array();
    //     $getted_item_code       = str_replace("'", "\'", $runcekitem[0]['item_code']);
    //     $getted_item_name       = str_replace("'", "\'", $runcekitem[0]['item_name']);
    //     $getted_barcode         = str_replace("'", "\'", $runcekitem[0]['barcode']);
    //     ///////////////////////////////////////////////////////////////////////////////////////

    //     $cekheader = "select claim_header_id, claim_code, claim_price from claimheader
    //                 where claim_code = \"" . $claimcode . "\"";
    //     $runcekheader = $this->db->query($cekheader)->result_array();


    //     if (empty($runcekheader)) {
    //         $insertheader  =   "INSERT INTO `claimheader` (
    //             `location_id`, 
    //             `client_id`,
    //             `claim_type`,
    //             `reason`,
    //             `claim_code`,
    //             `project_name`,
    //             `claim_price`,
    //             `case`,
    //             `ppn`,
    //             `rek_client`,
    //             `responsiblity`,
    //             `metode_pembayaran`,
    //             `term_payment`,
    //             `due_date`,
    //             `ba_number`,
    //             `ba_date`,
    //             `ba_upload`,
    //             `bill_invoice_upload`,
    //             `created_date`,
    //             `modified_date`,
    //             `created_by`,
    //             `modified_by`
    //             )
    //             VALUES
    //             (
    //               '$location_id',
    //               '$client_id',
    //               '$claim_type',
    //               '$reason',
    //               \"" . $claimcode . "\",
    //               '$project_name',
    //               0,
    //               '$cases',
    //               '$switch_ppn',
    //               '$norekclient',
    //               '$responsiblity',
    //               '$metodepembayaran',
    //               '$termpayment',
    //               '$duedate',
    //               '$noberitaacara',
    //               '$tanggalberitaacara',
    //               \"" . $urlfileba . "\",
    //               \"" . $urlfileinvoice . "\",
    //               now(),
    //               now(),
    //               '$user_id',
    //               '$user_id'
    //             )";
    //         $execinsertheader = $this->db->query($insertheader);

    //         $cekheader = "select claim_header_id, claim_code, claim_price from claimheader
    //                 where claim_code = '$claimcode'";
    //         $runcekheader = $this->db->query($cekheader)->result_array();
    //         $getted_claim_header_id    = $runcekheader[0]['claim_header_id'];
    //         $getted_claim_price        = $runcekheader[0]['claim_price'];
    //         $total_claim_price         = $getted_claim_price + ($harga * $quantity);

    //         $insertdetail   = "
    //         INSERT INTO `claimdetail` (
    //             `claim_header_id`,
    //             `document_claim`,
    //             `item_code`,
    //             `item_name`,
    //             `barcode`,
    //             `price`,
    //             `quantity`,
    //             `created_date`,
    //             `modified_date`,
    //             `created_by`,
    //             `modified_by`
    //           )
    //           VALUES
    //             (
    //               '$getted_claim_header_id',
    //               '$doc_claim',
    //               '$getted_item_code',
    //               '$getted_item_name',
    //               '$getted_barcode',
    //               '$harga',
    //               '$quantity',
    //               NOW(),
    //               NOW(),
    //               '$user_id',
    //               '$user_id'
    //             );              
    //         ";
    //         $execinsertdetail   = $this->db->query($insertdetail);

    //         $query_claimpriceupdate = "UPDATE
    //                                         `claimheader`
    //                                     SET
    //                                         `claim_price` = '$total_claim_price',
    //                                         `modified_date` = NOW(),
    //                                         `modified_by` = '$user_id'
    //                                     WHERE `claim_header_id` = '$getted_claim_header_id';
    //                                     ";
    //         $execclaimprice   = $this->db->query($query_claimpriceupdate);
    //     } else {
    //         $getted_claim_header_id    = $runcekheader[0]['claim_header_id'];
    //         $getted_claim_price        = $runcekheader[0]['claim_price'];
    //         $total_claim_price         = $getted_claim_price + ($harga * $quantity);

    //         $insertheader  =   "UPDATE
    //                         `claimheader`
    //                         SET
    //                             `location_id` = '$location_id',
    //                             `client_id` = '$client_id',
    //                             `claim_type` = '$claim_type',
    //                             `claim_code` = \"" . $claimcode . "\",
    //                             `project_name` = '$project_name',
    //                             `claim_price` = '$total_claim_price',
    //                             `case` = '$cases',
    //                             `ppn` = '$switch_ppn',
    //                             `rek_client` = '$norekclient',
    //                             `responsiblity` = '$responsiblity',
    //                             `metode_pembayaran` = '$metodepembayaran',
    //                             `term_payment` = '$termpayment',
    //                             `due_date` = '$duedate',
    //                             `ba_number` = '$noberitaacara',
    //                             `ba_date` = '$tanggalberitaacara',
    //                             `ba_upload` = \"" . $urlfileba . "\",
    //                             `bill_invoice_upload` = \"" . $urlfileinvoice . "\",
    //                             `modified_date` = now(),
    //                             `modified_by` = '$user_id'
    //                         WHERE `claim_header_id` = '$getted_claim_header_id';
    //         ";
    //         $execinsertheader   = $this->db->query($insertheader);

    //         $insertdetail   = "
    //         INSERT INTO `claimdetail` (
    //             `claim_header_id`,
    //             `document_claim`,
    //             `item_code`,
    //             `item_name`,
    //             `barcode`,
    //             `price`,
    //             `quantity`,
    //             `created_date`,
    //             `modified_date`,
    //             `created_by`,
    //             `modified_by`
    //           )
    //           VALUES
    //             (
    //               '$getted_claim_header_id',
    //               '$doc_claim',
    //               '$getted_item_code',
    //               '$getted_item_name',
    //               '$getted_barcode',
    //               '$harga',
    //               '$quantity',
    //               NOW(),
    //               NOW(),
    //               '$user_id',
    //               '$user_id'
    //             );              
    //         ";
    //         $execinsertdetail   = $this->db->query($insertdetail);
    //     }

    //     $exec_alliteminclaim = $this->get_item_in_claim($getted_claim_header_id);
    //     // echo var_dump($exec_alliteminclaim);
    //     // die();
    //     $this->session->set_flashdata('flash2', 'Berhasil Tambah Item');
    //     die(json_encode($exec_alliteminclaim));
    // }

    // public function proses_add_adjustment_claim_monitoring($params)
    // {
    //     if ($this->session->userdata('client_id')) {
    //         $ses_client = $this->session->userdata('client_id');
    //     } else {
    //         $ses_client = $this->session->userdata('ID');
    //     }

    //     $user_id = $this->session->userdata('full_name');

    //     // $params             = str_replace("'", '"', $params);

    //     $claim_header_id    = $params['claim_header_id'];
    //     $client_id          = $params['client_id'];
    //     $claimcode          = $params['claimcode'];
    //     $urlfileadjustment  = $params['urlfileadjustment'];
    //     $buktiadjustment    = $params['buktiadjustment'];

    //     $updateadjusment  =   "UPDATE
    //                         `claimheader`
    //                         SET
    //                             `status_adjustment` = 1,
    //                             `adjustment_upload` = \"" . $urlfileadjustment . "\",
    //                             `modified_date` = now(),
    //                             `modified_by` = '$user_id'
    //                         WHERE `claim_header_id` = '$claim_header_id';
    //         ";

    //     $execinsertheader   = $this->db->query($updateadjusment);

    //     // die(json_encode($exec_alliteminclaim));
    // }

    // public function proses_add_pay_to_client_claim_monitoring($params)
    // {
    //     if ($this->session->userdata('client_id')) {
    //         $ses_client = $this->session->userdata('client_id');
    //     } else {
    //         $ses_client = $this->session->userdata('ID');
    //     }

    //     $user_id = $this->session->userdata('full_name');

    //     // $params             = str_replace("'", '"', $params);

    //     $claim_header_id    = $params['claim_header_id'];
    //     $client_id          = $params['client_id'];
    //     $claimcode          = $params['claimcode'];
    //     $urltransferkeclient  = $params['urltransferkeclient'];
    //     $transferkeclient    = $params['transferkeclient'];

    //     // echo var_dump($urltransferkeclient);
    //     // die();
    //     $updatepaytoclient  =   "UPDATE
    //                         `claimheader`
    //                         SET
    //                             `status_pembayaran` = 1,
    //                             `pay_invoice_upload` = \"" . $urltransferkeclient . "\",
    //                             `modified_date` = now(),
    //                             `modified_by` = '$user_id'
    //                         WHERE `claim_header_id` = '$claim_header_id';";

    //     $execinsertheader   = $this->db->query($updatepaytoclient);

    //     // die(json_encode($execinsertheader));
    // }

    // public function proses_add_pay_to_finance_claim_monitoring($params)
    // {
    //     if ($this->session->userdata('client_id')) {
    //         $ses_client = $this->session->userdata('client_id');
    //     } else {
    //         $ses_client = $this->session->userdata('ID');
    //     }

    //     $user_id = $this->session->userdata('full_name');

    //     // $params             = str_replace("'", '"', $params);

    //     $claim_header_id    = $params['claim_header_id'];
    //     $client_id          = $params['client_id'];
    //     $claimcode          = $params['claimcode'];
    //     $duedate            = $params['duedate'];
    //     $urltransferkefinance  = $params['urltransferkefinance'];
    //     $transferkefinance    = $params['transferkefinance'];

    //     $updatepaytoclient  =   "UPDATE
    //                         `claimheader`
    //                         SET
    //                             `status_pembayaran` = 1,
    //                             `pay_invoice_upload_finance` = \"" . $urltransferkefinance . "\",
    //                             `due_date_finance` = '$duedate',
    //                             `modified_date` = now(),
    //                             `modified_by` = '$user_id'
    //                         WHERE `claim_header_id` = '$claim_header_id';";

    //     $execinsertheader   = $this->db->query($updatepaytoclient);

    //     // die(json_encode($execinsertheader));
    // }

    // public function proses_edit_claim_monitoring_forminput()
    // {
    // }

    // public function proses_delete_claim_monitoring_forminput($claim_detail_id)
    // {
    //     if ($this->session->userdata('client_id')) {
    //         $ses_client = $this->session->userdata('client_id');
    //     } else {
    //         $ses_client = $this->session->userdata('ID');
    //     }

    //     $user_id = $this->session->userdata('username');

    //     $ambilqtyitem = "   SELECT 
    //                             price,
    //                             quantity
    //                         FROM `claimdetail`
    //                         WHERE 1=1 
    //                         AND `claim_detail_id` = '$claim_detail_id'";
    //     $exec_ambilqtyitem  = $this->db->query($ambilqtyitem)->result_array();
    //     $priceitem          = $exec_ambilqtyitem[0]['price'];
    //     $quantityitem       = $exec_ambilqtyitem[0]['quantity'];
    //     $claimpriceminus    = $priceitem * $quantityitem;

    //     $ambil_claim_header = "SELECT 
    //                                 cd.`claim_detail_id`,
    //                                 ch.`claim_header_id`,
    //                                 ch.`claim_price`
    //                             FROM `claimdetail` cd
    //                             JOIN `claimheader` ch ON cd.`claim_header_id` = ch.`claim_header_id`
    //                             WHERE 1=1
    //                             AND cd.`claim_detail_id` = '$claim_detail_id'";
    //     $exec_alliteminclaim   = $this->db->query($ambil_claim_header)->result_array();

    //     $getted_claim_header_id     = $exec_alliteminclaim[0]['claim_header_id'];
    //     $getted_claim_price         = $exec_alliteminclaim[0]['claim_price'];
    //     $total_claim_price          = $getted_claim_price - $claimpriceminus;

    //     $query_claimpriceupdate = " UPDATE
    //                                     `claimheader`
    //                                 SET
    //                                     `claim_price` = '$total_claim_price',
    //                                     `modified_date` = NOW(),
    //                                     `modified_by` = '$user_id'
    //                                 WHERE `claim_header_id` = '$getted_claim_header_id';
    //                                     ";
    //     $execclaimprice   = $this->db->query($query_claimpriceupdate);

    //     $hapus_claim_detail = "DELETE 
    //                                 cd
    //                             FROM `claimdetail` cd
    //                             WHERE 1=1
    //                             AND cd.`claim_detail_id` = '$claim_detail_id'";
    //     $exec_hapusiteminclaim   = $this->db->query($hapus_claim_detail);

    //     $query_alliteminclaim = "SELECT 
    //                                 cd.`claim_detail_id`,
    //                                 ch.`claim_code`,
    //                                 ch.`responsiblity`,
    //                                 cd.`document_claim`,
    //                                 item.`item_id`,
    //                                 cd.`item_name`,
    //                                 cd.`quantity`,
    //                                 cd.`price`
    //                             FROM `claimdetail` cd
    //                             JOIN `claimheader` ch ON cd.`claim_header_id` = ch.`claim_header_id`
    //                             JOIN `item` ON cd.`item_code` = item.`code`
    //                             WHERE 1=1
    //                             AND ch.`claim_header_id` = '$getted_claim_header_id'";
    //     $exec_alliteminclaim   = $this->db->query($query_alliteminclaim)->result();
    //     die(json_encode($exec_alliteminclaim));
    // }

    // public function approve_spv_claimmonitoring($lvl, $id)
    // {
    //     $claim_header_id    = $id;
    //     $approval_level     = $lvl;
    //     $fullname           = $this->session->userdata('full_name');

    //     $queryupdate_header   = "UPDATE
    //                                 `claimheader`
    //                             SET
    //                                 `approval_spv` = 1,
    //                                 `modified_date` = NOW(),
    //                                 `modified_by` = '$fullname'
    //                             WHERE `claim_header_id` = '$claim_header_id'";
    //     $run2       = $this->db->query($queryupdate_header);

    //     $queryinsert_history   = "INSERT INTO `claimhistoryapproval` (
    //                                 `claim_header_id`,
    //                                 `approval_name`,
    //                                 `approval_level`,
    //                                 `created_date`,
    //                                 `modified_date`,
    //                                 `created_by`,
    //                                 `modified_by`
    //                             )
    //                             VALUES
    //                                 (
    //                                 '$claim_header_id',
    //                                 '$fullname',
    //                                 '$approval_level',
    //                                 NOW(),
    //                                 NOW(),
    //                                 '$fullname',
    //                                 '$fullname'
    //                                 );";
    //     $run4       = $this->db->query($queryinsert_history);
    // }

    // public function approve_claim_1($lvl, $id)
    // {
    //     $claim_header_id    = $id;
    //     $approval_level     = $lvl;
    //     $fullname           = $this->session->userdata('full_name');

    //     $queryupdate_header   = "UPDATE
    //                                 `claimheader`
    //                             SET
    //                                 `approval_1` = 1,
    //                                 `modified_date` = NOW(),
    //                                 `modified_by` = '$fullname'
    //                             WHERE `claim_header_id` = '$claim_header_id'";
    //     $run2       = $this->db->query($queryupdate_header);

    //     $queryinsert_history   = "INSERT INTO `claimhistoryapproval` (
    //                                 `claim_header_id`,
    //                                 `approval_name`,
    //                                 `approval_level`,
    //                                 `created_date`,
    //                                 `modified_date`,
    //                                 `created_by`,
    //                                 `modified_by`
    //                             )
    //                             VALUES
    //                                 (
    //                                 '$claim_header_id',
    //                                 '$fullname',
    //                                 '$approval_level',
    //                                 NOW(),
    //                                 NOW(),
    //                                 '$fullname',
    //                                 '$fullname'
    //                                 );";
    //     $run4       = $this->db->query($queryinsert_history);

    //     $queryinsert_history   = "SELECT 
    //                             ch.`claim_header_id`,
    //                             cd.`claim_detail_id`,
    //                             ch.`claim_code`,
    //                             ch.`responsiblity`,
    //                             cd.`document_claim`,
    //                             ch.`case`,
    //                             ch.`approval_1`,
    //                             ch.`approval_2`,
    //                             ch.`approval_3`,
    //                             -- ch.`approval_finance`,
    //                             -- ch.`approval_coo`,
    //                             ch.`rek_client`,
    //                             ch.`status_trf_client`,
    //                             ch.`responsiblity`,
    //                             ch.`metode_pembayaran`,
    //                             ch.`term_payment`,
    //                             ch.`due_date`,
    //                             ch.`status_pembayaran`,
    //                             ch.`status_adjustment`,
    //                             ch.`adjustment_upload`,
    //                             ch.`ba_number`,
    //                             ch.`ba_date`,
    //                             ch.`ba_upload`,
    //                             ch.`bill_invoice_upload`,
    //                             ch.`pay_invoice_upload`,
    //                             ch.`status_pembayaran_finance`,
    //                             ch.`created_date`,
    //                             ch.`modified_date`,
    //                             ch.`created_by`,
    //                             ch.`modified_by`,
    //                             cd.`claim_detail_id`,
    //                             item.`item_id`,
    //                             cd.`item_code`,
    //                             item.`name` AS item_name,
    //                             cd.`barcode`,
    //                             cd.`price` AS item_price,
    //                             cd.`quantity` AS item_quantity
    //                         FROM `claimdetail` cd
    //                         JOIN `claimheader` ch ON cd.`claim_header_id` = ch.`claim_header_id`
    //                         JOIN `item` ON cd.`item_code` = item.`code`
    //                         WHERE 1=1
    //                         AND ch.`claim_header_id` = '$id'
    //                         ";
    //     $run4   = $this->db->query($queryinsert_history)->result_array();
    //     return $run4;
    // }

    // public function approve_claim_2($lvl, $id)
    // {
    //     $claim_header_id    = $id;
    //     $approval_level     = $lvl;
    //     $fullname           = $this->session->userdata('full_name');

    //     $queryupdate_header   = "UPDATE
    //                                 `claimheader`
    //                             SET
    //                                 `approval_2` = 1,
    //                                 `modified_date` = NOW(),
    //                                 `modified_by` = '$fullname'
    //                             WHERE `claim_header_id` = '$claim_header_id'";
    //     $run2       = $this->db->query($queryupdate_header);

    //     $queryinsert_history   = "INSERT INTO `claimhistoryapproval` (
    //                                 `claim_header_id`,
    //                                 `approval_name`,
    //                                 `approval_level`,
    //                                 `created_date`,
    //                                 `modified_date`,
    //                                 `created_by`,
    //                                 `modified_by`
    //                             )
    //                             VALUES
    //                                 (
    //                                 '$claim_header_id',
    //                                 '$fullname',
    //                                 '$approval_level',
    //                                 NOW(),
    //                                 NOW(),
    //                                 '$fullname',
    //                                 '$fullname'
    //                                 );";
    //     $run4       = $this->db->query($queryinsert_history);

    //     $queryinsert_history   = "SELECT 
    //                             ch.`claim_header_id`,
    //                             cd.`claim_detail_id`,
    //                             ch.`claim_code`,
    //                             ch.`responsiblity`,
    //                             cd.`document_claim`,
    //                             ch.`case`,
    //                             ch.`approval_1`,
    //                             ch.`approval_2`,
    //                             ch.`approval_3`,
    //                             -- ch.`approval_finance`,
    //                             -- ch.`approval_coo`,
    //                             ch.`rek_client`,
    //                             ch.`status_trf_client`,
    //                             ch.`responsiblity`,
    //                             ch.`metode_pembayaran`,
    //                             ch.`term_payment`,
    //                             ch.`due_date`,
    //                             ch.`status_pembayaran`,
    //                             ch.`status_adjustment`,
    //                             ch.`adjustment_upload`,
    //                             ch.`ba_number`,
    //                             ch.`ba_date`,
    //                             ch.`ba_upload`,
    //                             ch.`bill_invoice_upload`,
    //                             ch.`pay_invoice_upload`,
    //                             ch.`created_date`,
    //                             ch.`modified_date`,
    //                             ch.`created_by`,
    //                             ch.`modified_by`,
    //                             cd.`claim_detail_id`,
    //                             item.`item_id`,
    //                             cd.`item_code`,
    //                             item.`name` AS item_name,
    //                             cd.`barcode`,
    //                             cd.`price` AS item_price,
    //                             cd.`quantity` AS item_quantity
    //                         FROM `claimdetail` cd
    //                         JOIN `claimheader` ch ON cd.`claim_header_id` = ch.`claim_header_id`
    //                         JOIN `item` ON cd.`item_code` = item.`code`
    //                         WHERE 1=1
    //                         AND ch.`claim_header_id` = '$id'
    //                         ";
    //     $run4   = $this->db->query($queryinsert_history)->result_array();
    //     return $run4;
    // }

    // public function approve_claim_3($lvl, $id)
    // {
    //     $claim_header_id    = $id;
    //     $approval_level     = $lvl;
    //     $fullname           = $this->session->userdata('full_name');

    //     $queryupdate_header   = "UPDATE
    //                                 `claimheader`
    //                             SET
    //                                 `approval_3` = 1,
    //                                 `modified_date` = NOW(),
    //                                 `modified_by` = '$fullname'
    //                             WHERE `claim_header_id` = '$claim_header_id'";
    //     $run2       = $this->db->query($queryupdate_header);

    //     $queryinsert_history   = "INSERT INTO `claimhistoryapproval` (
    //                                 `claim_header_id`,
    //                                 `approval_name`,
    //                                 `approval_level`,
    //                                 `created_date`,
    //                                 `modified_date`,
    //                                 `created_by`,
    //                                 `modified_by`
    //                             )
    //                             VALUES
    //                                 (
    //                                 '$claim_header_id',
    //                                 '$fullname',
    //                                 '$approval_level',
    //                                 NOW(),
    //                                 NOW(),
    //                                 '$fullname',
    //                                 '$fullname'
    //                                 );";
    //     $run4       = $this->db->query($queryinsert_history);

    //     $queryinsert_history   = "SELECT 
    //                             ch.`claim_header_id`,
    //                             cd.`claim_detail_id`,
    //                             ch.`claim_code`,
    //                             ch.`responsiblity`,
    //                             cd.`document_claim`,
    //                             ch.`case`,
    //                             ch.`approval_1`,
    //                             ch.`approval_2`,
    //                             ch.`approval_3`,
    //                             ch.`rek_client`,
    //                             ch.`status_trf_client`,
    //                             ch.`responsiblity`,
    //                             ch.`metode_pembayaran`,
    //                             ch.`term_payment`,
    //                             ch.`due_date`,
    //                             ch.`status_pembayaran`,
    //                             ch.`status_adjustment`,
    //                             ch.`adjustment_upload`,
    //                             ch.`ba_number`,
    //                             ch.`ba_date`,
    //                             ch.`ba_upload`,
    //                             ch.`bill_invoice_upload`,
    //                             ch.`pay_invoice_upload`,
    //                             ch.`created_date`,
    //                             ch.`modified_date`,
    //                             ch.`created_by`,
    //                             ch.`modified_by`,
    //                             cd.`claim_detail_id`,
    //                             item.`item_id`,
    //                             cd.`item_code`,
    //                             item.`name` AS item_name,
    //                             cd.`barcode`,
    //                             cd.`price` AS item_price,
    //                             cd.`quantity` AS item_quantity
    //                         FROM `claimdetail` cd
    //                         JOIN `claimheader` ch ON cd.`claim_header_id` = ch.`claim_header_id`
    //                         JOIN `item` ON cd.`item_code` = item.`code`
    //                         WHERE 1=1
    //                         AND ch.`claim_header_id` = '$id'
    //                         ";
    //     $run4   = $this->db->query($queryinsert_history)->result_array();
    //     return $run4;
    // }

    // public function notif_all_email($id)
    // {
    //     $claim_header_id    = $id;
    //     $fullname           = $this->session->userdata('full_name');

    //     $queryinsert_history   = "SELECT 
    //                             ch.`claim_header_id`,
    //                             cd.`claim_detail_id`,
    //                             ch.`claim_code`,
    //                             ch.`responsiblity`,
    //                             cd.`document_claim`,
    //                             ch.`case`,
    //                             ch.`approval_1`,
    //                             ch.`approval_2`,
    //                             ch.`approval_3`,
    //                             ch.`rek_client`,
    //                             ch.`status_trf_client`,
    //                             ch.`responsiblity`,
    //                             ch.`metode_pembayaran`,
    //                             ch.`term_payment`,
    //                             ch.`due_date`,
    //                             ch.`status_pembayaran`,
    //                             ch.`status_pembayaran_finance`,
    //                             ch.`status_adjustment`,
    //                             ch.`adjustment_upload`,
    //                             ch.`ba_number`,
    //                             ch.`ba_date`,
    //                             ch.`ba_upload`,
    //                             ch.`bill_invoice_upload`,
    //                             ch.`pay_invoice_upload`,
    //                             ch.`created_date`,
    //                             ch.`modified_date`,
    //                             ch.`created_by`,
    //                             ch.`modified_by`,
    //                             cd.`claim_detail_id`,
    //                             item.`item_id`,
    //                             cd.`item_code`,
    //                             item.`name` AS item_name,
    //                             cd.`barcode`,
    //                             cd.`price` AS item_price,
    //                             cd.`quantity` AS item_quantity,
    //                             ch.`due_date_finance`
    //                         FROM `claimdetail` cd
    //                         JOIN `claimheader` ch ON cd.`claim_header_id` = ch.`claim_header_id`
    //                         JOIN `item` ON cd.`item_code` = item.`code`
    //                         WHERE 1=1
    //                         AND ch.`claim_header_id` = '$id'
    //                         ";
    //     $run4   = $this->db->query($queryinsert_history)->result_array();
    //     return $run4;
    // }

    // public function all_claim_detail($id)
    // {
    //     $claim_header_id    = $id;
    //     $fullname           = $this->session->userdata('full_name');

    //     $queryinsert_history   = "SELECT 
    //                             ch.`claim_header_id`,
    //                             cd.`claim_detail_id`,
    //                             l.`name` as location_name,
    //                             c.`name` as client_name,
    //                             ch.`claim_code`,
    //                             ch.`project_name`,
    //                             ch.`claim_type`,
    //                             ch.`responsiblity`,
    //                             ch.`reason`,
    //                             cd.`document_claim`,
    //                             ch.`case`,
    //                             ch.`ppn`,
    //                             ch.`claim_price`,
    //                             ch.`approval_1`,
    //                             ch.`approval_2`,
    //                             ch.`approval_3`,
    //                             ch.`rek_client`,
    //                             ch.`status_trf_client`,
    //                             ch.`responsiblity`,
    //                             ch.`metode_pembayaran`,
    //                             ch.`term_payment`,
    //                             ch.`due_date`,
    //                             ch.`status_pembayaran`,
    //                             ch.`status_pembayaran_finance`,
    //                             ch.`status_adjustment`,
    //                             ch.`adjustment_upload`,
    //                             ch.`ba_number`,
    //                             ch.`ba_date`,
    //                             ch.`ba_upload`,
    //                             ch.`bill_invoice_upload`,
    //                             ch.`pay_invoice_upload`,
    //                             ch.`pay_invoice_upload_finance`,
    //                             ch.`created_date`,
    //                             ch.`modified_date`,
    //                             ch.`created_by`,
    //                             ch.`modified_by`,
    //                             cd.`claim_detail_id`,
    //                             item.`item_id`,
    //                             cd.`item_code`,
    //                             item.`name` AS item_name,
    //                             cd.`barcode`,
    //                             cd.`price` AS item_price,
    //                             cd.`quantity` AS item_quantity,
    //                             ch.`due_date_finance`,
    //                             sa.approval_name_1,
    //                             sa.approval_name_2,
    //                             sa.approval_name_3
    //                             FROM `claimdetail` cd
    //                         JOIN `claimheader` ch ON cd.`claim_header_id` = ch.`claim_header_id`
    //                         JOIN `location` l ON ch.`location_id` = l.`location_id`
    //                         JOIN `client` c ON ch.`client_id` = c.`client_id`
    //                         JOIN `item` ON cd.`item_code` = item.`code`
    //                         LEFT JOIN setupapproval sa ON sa.`location_id` = ch.`location_id`
    //                         WHERE 1=1
    //                         AND ch.`claim_header_id` = '$id'
    //                         ";
    //     $run4   = $this->db->query($queryinsert_history)->result_array();
    //     return $run4;
    // }

    // public function get_data_notif_finish_add_claim($code1)
    // {
    //     $code    = str_replace("'", "\'", $code1);
    //     $queryinsert_history   = "SELECT 
    //                             ch.`claim_header_id`,
    //                             cd.`claim_detail_id`,
    //                             ch.`claim_code`,
    //                             ch.`responsiblity`,
    //                             cd.`document_claim`,
    //                             ch.`case`,
    //                             ch.`approval_1`,
    //                             ch.`approval_2`,
    //                             ch.`approval_3`,
    //                             ch.`rek_client`,
    //                             ch.`status_trf_client`,
    //                             ch.`status_pembayaran_finance`,
    //                             ch.`responsiblity`,
    //                             ch.`metode_pembayaran`,
    //                             ch.`term_payment`,
    //                             ch.`due_date`,
    //                             ch.`status_pembayaran`,
    //                             ch.`status_adjustment`,
    //                             ch.`adjustment_upload`,
    //                             ch.`ba_number`,
    //                             ch.`ba_date`,
    //                             ch.`ba_upload`,
    //                             ch.`bill_invoice_upload`,
    //                             ch.`pay_invoice_upload`,
    //                             ch.`created_date`,
    //                             ch.`modified_date`,
    //                             ch.`created_by`,
    //                             ch.`modified_by`,
    //                             cd.`claim_detail_id`,
    //                             item.`item_id`,
    //                             cd.`item_code`,
    //                             item.`name` AS item_name,
    //                             cd.`barcode`,
    //                             cd.`price` AS item_price,
    //                             cd.`quantity` AS item_quantity
    //                         FROM `claimdetail` cd
    //                         JOIN `claimheader` ch ON cd.`claim_header_id` = ch.`claim_header_id`
    //                         JOIN `item` ON cd.`item_code` = item.`code`
    //                         WHERE 1=1
    //                         AND ch.`claim_code` = '$code'
    //                         ";
    //     $run4   = $this->db->query($queryinsert_history)->result_array();
    //     return $run4;
    // }

    // public function approve_head_ops_claimmonitoring($lvl, $id)
    // {
    //     $claim_header_id    = $id;
    //     $approval_level     = $lvl;
    //     $fullname           = $this->session->userdata('full_name');

    //     $queryupdate_header   = "UPDATE
    //                                 `claimheader`
    //                             SET
    //                                 `approval_head_ops` = 1,
    //                                 `modified_date` = NOW(),
    //                                 `modified_by` = '$fullname'
    //                             WHERE `claim_header_id` = '$claim_header_id'";
    //     $run2       = $this->db->query($queryupdate_header);

    //     $queryinsert_history   = "INSERT INTO `claimhistoryapproval` (
    //                                 `claim_header_id`,
    //                                 `approval_name`,
    //                                 `approval_level`,
    //                                 `created_date`,
    //                                 `modified_date`,
    //                                 `created_by`,
    //                                 `modified_by`
    //                             )
    //                             VALUES
    //                                 (
    //                                 '$claim_header_id',
    //                                 '$fullname',
    //                                 '$approval_level',
    //                                 NOW(),
    //                                 NOW(),
    //                                 '$fullname',
    //                                 '$fullname'
    //                                 );";
    //     $run4       = $this->db->query($queryinsert_history);
    // }

    // public function approve_finance_claimmonitoring($lvl, $id)
    // {
    //     $claim_header_id    = $id;
    //     $approval_level     = $lvl;
    //     $fullname           = $this->session->userdata('full_name');

    //     $queryupdate_header   = "UPDATE
    //                                 `claimheader`
    //                             SET
    //                                 `approval_finance` = 1,
    //                                 `modified_date` = NOW(),
    //                                 `modified_by` = '$fullname'
    //                             WHERE `claim_header_id` = '$claim_header_id'";
    //     $run2       = $this->db->query($queryupdate_header);

    //     $queryinsert_history   = "INSERT INTO `claimhistoryapproval` (
    //                                 `claim_header_id`,
    //                                 `approval_name`,
    //                                 `approval_level`,
    //                                 `created_date`,
    //                                 `modified_date`,
    //                                 `created_by`,
    //                                 `modified_by`
    //                             )
    //                             VALUES
    //                                 (
    //                                 '$claim_header_id',
    //                                 '$fullname',
    //                                 '$approval_level',
    //                                 NOW(),
    //                                 NOW(),
    //                                 '$fullname',
    //                                 '$fullname'
    //                                 );";
    //     $run4       = $this->db->query($queryinsert_history);
    // }

    // public function approve_coo_claimmonitoring($lvl, $id)
    // {
    //     $claim_header_id    = $id;
    //     $approval_level     = $lvl;
    //     $fullname           = $this->session->userdata('full_name');

    //     $queryupdate_header   = "UPDATE
    //                                 `claimheader`
    //                             SET
    //                                 `approval_coo` = 1,
    //                                 `modified_date` = NOW(),
    //                                 `modified_by` = '$fullname'
    //                             WHERE `claim_header_id` = '$claim_header_id'";
    //     $run2       = $this->db->query($queryupdate_header);

    //     $queryinsert_history   = "INSERT INTO `claimhistoryapproval` (
    //                                 `claim_header_id`,
    //                                 `approval_name`,
    //                                 `approval_level`,
    //                                 `created_date`,
    //                                 `modified_date`,
    //                                 `created_by`,
    //                                 `modified_by`
    //                             )
    //                             VALUES
    //                                 (
    //                                 '$claim_header_id',
    //                                 '$fullname',
    //                                 '$approval_level',
    //                                 NOW(),
    //                                 NOW(),
    //                                 '$fullname',
    //                                 '$fullname'
    //                                 );";
    //     $run4       = $this->db->query($queryinsert_history);
    // }

    // public function hapus_claim_monitoring($id)
    // {
    //     $claim_header_id    = $id;
    //     $fullname           = $this->session->userdata('full_name');

    //     $query_deletedetail   = "DELETE
    //                             FROM
    //                             `claimdetail`
    //                             WHERE `claim_header_id` = '$claim_header_id';";
    //     $run2       = $this->db->query($query_deletedetail);

    //     $query_deleteheader   = "UPDATE
    //                                 `claimheader`
    //                             SET
    //                                 is_delete = '1'
    //                             WHERE `claim_header_id` = '$claim_header_id';";
    //     $run4       = $this->db->query($query_deleteheader);
    // }

    // public function ambil_nama_email_approve($id, $location_id)
    // {
    //     $querycekloc        =   "SELECT
    //                                 `setup_approval_id`,
    //                                 `location_id`,
    //                                 `approval_name_1`,
    //                                 `approval_email_1`,
    //                                 `approval_name_2`,
    //                                 `approval_email_2`,
    //                                 `approval_name_3`,
    //                                 `approval_email_3`,
    //                                 `created_date`,
    //                                 `modified_date`,
    //                                 `create_by`,
    //                                 `modified_by`
    //                             FROM
    //                                 `haistari_bia`.`setupapproval`
    //                             WHERE 1=1
    //                             AND location_id = '$location_id'";
    //     $execquerycekloc    =   $this->db->query($querycekloc)->result_array();
    //     return $execquerycekloc;
    // }

    // public function get_location_id($id)
    // {
    //     $querycekloc        =   "SELECT
    //                                 `location_id`
    //                             FROM
    //                                 claimheader
    //                             WHERE 1=1
    //                             AND claim_header_id = '$id'";
    //     $execquerycekloc    =   $this->db->query($querycekloc)->result_array();
    //     $location_id        = $execquerycekloc[0]['location_id'];
    //     return $location_id;
    // }

}
