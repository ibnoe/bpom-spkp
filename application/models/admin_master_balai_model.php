<?php
class Admin_master_balai_model extends CI_Model {

    var $tabel    = 'mas_balai';

    function __construct() {
        parent::__construct();
		$this->load->library('encrypt');
    }
    
	function get_list_data(){
		$page = $this->input->post('page');
        $rp = $this->input->post('rp');
        $sortname = $this->input->post('sortname');
        $sortorder = $this->input->post('sortorder');
        $qtype = $this->input->post('qtype');
        $qparam = $this->input->post('query');

        $swhere = '';
        if (!$page) $page = 1;
        if (!$rp) $rp = 10;
        if (!$sortname) $sortname = 'id_balai';
        if (!$sortorder) $sortorder = 'asc';
        if (!$qtype) $qtype = 'id_balai';
        if ($qparam) $swhere = 'WHERE '.$qtype.' LIKE \'%'.$qparam.'%\'';
        $sqlOrder = "ORDER BY $sortname $sortorder";
		$offset = ($page - 1) * $rp;
        
        $sql = "SELECT id_balai,nama_balai,alamat,kd_pos,email
				FROM mas_balai " . $swhere . $sqlOrder . "
				LIMIT $offset, $rp ";
        
        $query = $this->db->query($sql);
        $return['records'] = $query;
        
        $sqlc = "SELECT COUNT(id_balai) AS record_count
				FROM mas_balai " . $swhere;
				
		$queryc = $this->db->query($sqlc);
		$row = $queryc->row();        
        $return['record_count'] = $row->record_count;
        
        return $return;
	}
	
    function get_count($options=array())
    {
		$this->db->like($options);
        $query = $this->db->get($this->tabel);
		return count($query->result_array());
    }

    function get_data($start,$limit,$options=array())
    {
		$this->db->like($options);
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }


 	function get_data_row($id_balai){
		$data = array();
		$options = array('id_balai' => $id_balai);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

   function insert_entry()
    {
		$data['nama_balai']=$this->input->post('nama_balai');
		$data['alamat']=$this->input->post('alamat');
		$data['propinsi']=$this->input->post('propinsi');
		$data['kd_pos']=$this->input->post('kd_pos');
		$data['telp']=$this->input->post('telp');
		$data['fax']=$this->input->post('fax');
		$data['email']=$this->input->post('email');
		$data['nip_kepala']=$this->input->post('nip_kepala');
		$data['nama_kepala']=$this->input->post('nama_kepala');
		
		return $this->db->insert($this->tabel, $data);
    }

    function update_entry($id_balai)
    {
		$data['nama_balai']=$this->input->post('nama_balai');
		$data['alamat']=$this->input->post('alamat');
		$data['propinsi']=$this->input->post('propinsi');
		$data['kd_pos']=$this->input->post('kd_pos');
		$data['telp']=$this->input->post('telp');
		$data['fax']=$this->input->post('fax');
		$data['email']=$this->input->post('email');
		$data['nip_kepala']=$this->input->post('nip_kepala');
		$data['nama_kepala']=$this->input->post('nama_kepala');
        
		return $this->db->update($this->tabel, $data, array('id_balai' => $this->input->post('id_balai')));
    }
	

	function delete_entry($id_balai)
	{
		$this->db->where(array('id_balai' => $id_balai));

		return $this->db->delete($this->tabel);
	}

}