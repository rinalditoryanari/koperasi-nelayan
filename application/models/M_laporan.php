<?php

use JetBrains\PhpStorm\Internal\ReturnTypeContract;

if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_laporan extends ci_Model
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
            $where[] = " AND c.nama_ikan LIKE '%" . $keyword . "%'";
        }

        $is_limit = true;

        isLimit:

        $stringwhere = implode(" AND ", $where);

        $query = "  SELECT 
                    a.`created_date` as tanggal,
                    c.`nama_ikan`,
                    sum(b.`berat`) as jumlah,
                    sum(b.`berat` * b.`harga/kg`) as total,
                    b.`harga/kg`
                    FROM penjualan_header a
                    LEFT JOIN penjualan_detail b on a.`id_penjualan_header` =  b.`id_penjualan`
                    LEFT JOIN ikan c on b.`id_ikan` = c.`id_ikan`
                    WHERE 1=1
                    $stringwhere 
                    -- AND a.'created_date 
                    GROUP BY a.`created_date`,c.`nama_ikan`;
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

    public function bulan($isall = true, $limit = null, $offset = null)
    {
        if ($this->session->userdata('client_id')) {
            $ses_client = $this->session->userdata('client_id');
        } else {
            $ses_client = $this->session->userdata('ID');
        }

        $keyword = str_replace("'", "\'", $this->input->get('table_search_bulan'));

        $where = array();
        if (!empty($this->input->get('table_search_bulan'))) {
            $where[] = " AND a.created_date LIKE '%" . $keyword . "%'";
        }

        $is_limit = true;

        isLimit:

        $stringwhere = implode(" AND ", $where);

        $query = "  SELECT 
        DATE_FORMAT(a.`created_date`, '%M %Y') as bulan,
        c.`nama_ikan`,
        sum(b.`berat`) as jumlah,
        sum(b.`berat` * b.`harga/kg`) as total,
        b.`harga/kg`
        FROM penjualan_header a
        LEFT JOIN penjualan_detail b on a.`id_penjualan_header` =  b.`id_penjualan`
        LEFT JOIN ikan c on b.`id_ikan` = c.`id_ikan`
        WHERE 1=1 
        $stringwhere
        GROUP BY DATE_FORMAT(a.`created_date`, '%M %Y'),c.`nama_ikan`;;
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

    public function tahun($isall = true, $limit = null, $offset = null)
    {
        if ($this->session->userdata('client_id')) {
            $ses_client = $this->session->userdata('client_id');
        } else {
            $ses_client = $this->session->userdata('ID');
        }

        $keyword = str_replace("'", "\'", $this->input->get('table_search_tahun'));

        $where = array();
        if (!empty($this->input->get('table_search_tahun'))) {
            $where[] = " AND a.created_date LIKE '%" . $keyword . "%'";
        }

        $is_limit = true;

        isLimit:

        $stringwhere = implode(" AND ", $where);

        $query = " SELECT 
        DATE_FORMAT(a.`created_date`, '%Y') as tahun,
        c.`nama_ikan`,
        sum(b.`berat`) as jumlah,
        sum(b.`berat` * b.`harga/kg`) as total,
        b.`harga/kg`
        FROM penjualan_header a
        LEFT JOIN penjualan_detail b on a.`id_penjualan_header` =  b.`id_penjualan`
        LEFT JOIN ikan c on b.`id_ikan` = c.`id_ikan`
        WHERE 1=1 
        $stringwhere
        GROUP BY DATE_FORMAT(a.`created_date`, '%Y'),c.`nama_ikan`;
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

    public function download_laporan_per_hari($select_date)
    {
        $query = "  SELECT
                        a.`created_date` AS tanggal,
                        c.`nama_ikan`,
                        SUM(b.`berat`) AS jumlah,
                        b.`harga/kg` AS harga_per_kg,  
                        SUM(b.`berat` * b.`harga/kg`) AS total
                        
                    FROM
                    penjualan_header a
                    LEFT JOIN penjualan_detail b ON a.`id_penjualan_header` = b.`id_penjualan`
                    LEFT JOIN ikan c ON b.`id_ikan` = c.`id_ikan`
                    WHERE 1 = 1
                    AND a.`created_date` = '$select_date'
                    GROUP BY a.`created_date`, c.`nama_ikan`
                    UNION ALL
                    SELECT
                        'total' AS tanggal,
                        '' AS nama_ikan,
                        '' AS jumlah,
                        '' AS `harga/kg`,
                        SUM(total) AS total
                    FROM
                        (SELECT
                    --     a.`created_date` as tanggal,
                    --     c.`nama_ikan`,
                    --     sum(b.`berat`) as jumlah,
                    --     b.`harga/kg`,
                            SUM(b.`berat` * b.`harga/kg`) AS total
                        FROM
                        penjualan_header a
                        LEFT JOIN penjualan_detail b ON a.`id_penjualan_header` = b.`id_penjualan`
                        LEFT JOIN ikan c ON b.`id_ikan` = c.`id_ikan`
                        WHERE 1 = 1
                        AND a.`created_date` = '$select_date'
                        GROUP BY a.`created_date`, c.`nama_ikan`
                        ) a";

        return  $this->db->query($query)->result();
    }

    public function chart_laporan_per_hari($select_date)
    {
        $query = "  SELECT
                        c.`nama_ikan`,
                        SUM(b.`berat`) AS jumlah,
                        b.`harga/kg`,  
                        SUM(b.`berat` * b.`harga/kg`) AS total
                    FROM
                    penjualan_header a
                    LEFT JOIN penjualan_detail b ON a.`id_penjualan_header` = b.`id_penjualan`
                    LEFT JOIN ikan c ON b.`id_ikan` = c.`id_ikan`
                    WHERE 1 = 1
                    AND a.`created_date` = '$select_date'
                    GROUP BY c.`nama_ikan`
                    ";

        return  $this->db->query($query)->result_array();
    }

    public function chart_laporan_per_bulan($select_date)
    {
        $query = "  SELECT
                        c.`nama_ikan`,
                        SUM(b.`berat`) AS jumlah,
                        b.`harga/kg`,  
                        SUM(b.`berat` * b.`harga/kg`) AS total
                    FROM
                    penjualan_header a
                    LEFT JOIN penjualan_detail b ON a.`id_penjualan_header` = b.`id_penjualan`
                    LEFT JOIN ikan c ON b.`id_ikan` = c.`id_ikan`
                    WHERE 1 = 1
                    AND DATE_FORMAT(a.`created_date`, '%M %Y') = '$select_date'
                    GROUP BY c.`nama_ikan`
                    ";

        return  $this->db->query($query)->result_array();
    }

    public function chart_laporan_per_tahun($select_date)
    {
        $query = "  SELECT
                        c.`nama_ikan`,
                        SUM(b.`berat`) AS jumlah,
                        b.`harga/kg`,  
                        SUM(b.`berat` * b.`harga/kg`) AS total
                    FROM
                    penjualan_header a
                    LEFT JOIN penjualan_detail b ON a.`id_penjualan_header` = b.`id_penjualan`
                    LEFT JOIN ikan c ON b.`id_ikan` = c.`id_ikan`
                    WHERE 1 = 1
                    AND DATE_FORMAT(a.`created_date`, '%Y') = '$select_date'
                    GROUP BY c.`nama_ikan`
                    ";

        return  $this->db->query($query)->result_array();
    }

    public function download_perbekalan_per_hari($select_date)
    {
        $query_total_penjualan = " SELECT
                                        'Total Penjualan' AS tanggal,
                                        '' AS nama_ikan,
                                        '' AS jumlah,
                                        '' AS `harga/kg`,
                                        SUM(total) AS total
                                    FROM
                                        (SELECT
                                        SUM(b.`berat` * b.`harga/kg`) AS total
                                        FROM penjualan_header a
                                        LEFT JOIN penjualan_detail b ON a.`id_penjualan_header` = b.`id_penjualan`
                                        LEFT JOIN ikan c ON b.`id_ikan` = c.`id_ikan`
                                        WHERE 1 = 1
                                        AND a.`created_date` = '$select_date'
                                        GROUP BY a.`created_date`, c.`nama_ikan`
                                        ) a";
        $exec_total_penjualan = $this->db->query($query_total_penjualan)->result_array();
        $total_penjualan = $exec_total_penjualan[0]['total'];

        $query_total_perbekalan = "SELECT 
                                    'Total Perbekalan' AS tanggal,
                                    '' AS nama_ikan,
                                    '' AS jumlah,
                                    '' AS `harga/kg`,
                                    SUM(total_pinjam) - SUM(total_kembali) AS total
                                FROM peminjaman_header a
                                WHERE a.`modified_date` = '$select_date';";
        $exec_total_perbekalan = $this->db->query($query_total_perbekalan)->result_array();
        $total_perbekalan = $exec_total_perbekalan[0]['total'];

        $jumlah = $total_penjualan - $total_perbekalan;

        $jumlahpersen = $jumlah * 11 / 100;

        $jumlahkurangpersen = $jumlah -  $jumlahpersen;

        $bagianabk = $jumlahkurangpersen * 50 / 100;
        $bagianmitra = $jumlahkurangpersen * 25 / 100;
        $hasilbersih = $jumlahkurangpersen * 25 / 100;

        $q = " SELECT 'Total Penjualan' as a, '$total_penjualan' as b UNION ALL 
                                    SELECT 'Total Perbekalan' as a, '$total_perbekalan' as b UNION ALL 
                                    SELECT 'Jumlah Penjualan - Perbekalan' as a, $jumlah  as b UNION ALL 
                                    SELECT 'Jumlah * 11%' as a, '$jumlahpersen' as b UNION ALL 
                                    SELECT 'Total Dikurang Persen' as a, '$jumlahkurangpersen' as b UNION ALL 
                                    SELECT 'Total Untuk ABK' as a, '$bagianabk' as b UNION ALL 
                                    SELECT 'Total Untuk Mitra' as a, '$bagianmitra' as b UNION ALL 
                                    SELECT 'Hasil Bersih Koperasi' as a, '$hasilbersih' as b;";



        return  $this->db->query($q)->result();
    }

    public function list_bulan_tersedia()
    {
        $query = "  SELECT
                        date_format(a.created_date, '%M ' '%Y')	as select_month
                    FROM penjualan_header a
                    GROUP by date_format(a.created_date, '%M', '%Y');";
        return  $this->db->query($query)->result_array();
    }

    public function list_tahun_tersedia()
    {
        $query = "  SELECT
                        date_format(a.created_date, '%Y')	as select_year
                    FROM penjualan_header a
                    GROUP by date_format(a.created_date, '%Y');";
        return  $this->db->query($query)->result_array();
    }


    public function download_laporan_per_bulan($select_month)
    {
        $query = "  SELECT
            a.created_date,
            a.total AS penjualan,
            IF(b.total IS NULL, 0, b.total) AS perbekalan,
            a.total - (IF(b.total IS NULL, 0, b.total)) AS sisa_perbekalan,
            ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100) AS potongan_persen,
            (a.total - (IF(b.total IS NULL, 0, b.total)))-(ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100)) AS sudah_potongan,
            ROUND(((a.total - (IF(b.total IS NULL, 0, b.total)))-(ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100))) * 25/100) bagian_mitra,
            ROUND(((a.total - (IF(b.total IS NULL, 0, b.total)))-(ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100))) * 25/100) hasil_bersih
        FROM 
            (SELECT 
                a.created_date,
                SUM(a.total) AS total
            FROM penjualan_header a
            WHERE DATE_FORMAT(a.`created_date`, '%M %Y') = '$select_month'
            GROUP BY DATE(a.created_date)
        ) a
        LEFT JOIN 
            (SELECT
                a.modified_date,
                a.created_date,
                SUM(a.total_pinjam) - SUM(a.total_kembali) AS total
                FROM peminjaman_header a
                WHERE DATE_FORMAT(a.`created_date`, '%M %Y') = '$select_month'
                GROUP BY DATE(a.created_date)
            ) b ON a.created_date = b.created_date
        UNION ALL
        SELECT 
            'Total' AS created_date,
            SUM(penjualan),
            SUM(perbekalan),
            SUM(sisa_perbekalan),
            SUM(potongan_persen),
            SUM(sudah_potongan),
            SUM(bagian_mitra),
            SUM(hasil_bersih)
        FROM (SELECT
                a.created_date,
                a.total AS penjualan,
                IF(b.total IS NULL, 0, b.total) AS perbekalan,
                a.total - (IF(b.total IS NULL, 0, b.total)) AS sisa_perbekalan,
                ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100) AS potongan_persen,
                (a.total - (IF(b.total IS NULL, 0, b.total)))-(ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100)) AS sudah_potongan,
                ROUND(((a.total - (IF(b.total IS NULL, 0, b.total)))-(ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100))) * 25/100) bagian_mitra,
                ROUND(((a.total - (IF(b.total IS NULL, 0, b.total)))-(ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100))) * 25/100) hasil_bersih
            FROM 
                (SELECT 
                    a.created_date,
                    SUM(a.total) AS total
                FROM penjualan_header a
                WHERE DATE_FORMAT(a.`created_date`, '%M %Y') = '$select_month'
                GROUP BY DATE(a.created_date)
            ) a
        LEFT JOIN 
            (SELECT
                    a.modified_date,
                    a.created_date,
                    SUM(a.total_pinjam) - SUM(a.total_kembali) AS total
                FROM peminjaman_header a
                WHERE DATE_FORMAT(a.`created_date`, '%M %Y') = '$select_month'
                GROUP BY DATE(a.created_date)
            ) b ON a.created_date = b.created_date)a";

        return  $this->db->query($query)->result();
    }

    public function download_laporan_per_tahun($select_year)
    {
        $query = " SELECT
                date_format(a.created_date, '%M') AS Bulan,
                a.total AS penjualan,
                IF(b.total IS NULL, 0, b.total) AS perbekalan,
                a.total - (IF(b.total IS NULL, 0, b.total)) AS sisa_perbekalan,
                ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100) AS potongan_persen,
                (a.total - (IF(b.total IS NULL, 0, b.total)))-(ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100)) AS sudah_potongan,
                ROUND(((a.total - (IF(b.total IS NULL, 0, b.total)))-(ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100))) * 25/100) bagian_mitra,
                ROUND(((a.total - (IF(b.total IS NULL, 0, b.total)))-(ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100))) * 25/100) hasil_bersih
            FROM 
                (SELECT 
                    a.created_date,
                    SUM(a.total) AS total
                FROM penjualan_header a
                WHERE DATE_FORMAT(a.`created_date`, '%Y') = '$select_year'
                GROUP BY DATE_FORMAT(a.`created_date`, '%Y %M')
            ) a
            LEFT JOIN 
                (SELECT
                    a.modified_date,
                    a.created_date,
                    SUM(a.total_pinjam) - SUM(a.total_kembali) AS total
                FROM peminjaman_header a
                WHERE DATE_FORMAT(a.`created_date`, '%Y') = '$select_year'
                GROUP BY DATE_FORMAT(a.`created_date`, '%Y %M')
            ) b ON a.created_date = b.created_date
            UNION ALL
            SELECT 
                'Total' AS created_date,
                SUM(penjualan),
                SUM(perbekalan),
                SUM(sisa_perbekalan),
                SUM(potongan_persen),
                SUM(sudah_potongan),
                SUM(bagian_mitra),
                SUM(hasil_bersih)
            FROM (SELECT
                a.created_date,
                a.total AS penjualan,
                IF(b.total IS NULL, 0, b.total) AS perbekalan,
                a.total - (IF(b.total IS NULL, 0, b.total)) AS sisa_perbekalan,
                ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100) AS potongan_persen,
                (a.total - (IF(b.total IS NULL, 0, b.total)))-(ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100)) AS sudah_potongan,
                ROUND(((a.total - (IF(b.total IS NULL, 0, b.total)))-(ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100))) * 25/100) bagian_mitra,
                ROUND(((a.total - (IF(b.total IS NULL, 0, b.total)))-(ROUND((a.total - (IF(b.total IS NULL, 0, b.total))) * 11/100))) * 25/100) hasil_bersih
            FROM 
                (SELECT 
                    a.created_date,
                    SUM(a.total) AS total
                FROM penjualan_header a
                WHERE DATE_FORMAT(a.`created_date`, '%Y') = '$select_year'
                GROUP BY DATE_FORMAT(a.`created_date`, '%Y %M')
            ) a
            LEFT JOIN 
                (SELECT
                    a.modified_date,
                    a.created_date,
                    SUM(a.total_pinjam) - SUM(a.total_kembali) AS total
                FROM peminjaman_header a
                WHERE DATE_FORMAT(a.`created_date`, '%Y') = '$select_year'
                GROUP BY DATE_FORMAT(a.`created_date`, '%Y %M')
            ) b ON a.created_date = b.created_date)a;
                ";
        return  $this->db->query($query)->result();
    }
}
