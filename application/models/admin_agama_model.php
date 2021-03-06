<?php
class Admin_agama_model extends CI_Model{

	var $tabel    = 'mas_agama';
    
    function __construct(){
        parent::__construct();
        $this->load->library('encrypt');
    }
    
    function json_agama(){
        $query = "select * from mas_agama order by id_agama asc";
        
        return $this->crud->jqxGrid($query);
    }
    
    function get_data_row($id_agama){
		$data = array();
		$options = array('id_agama' => $id_agama);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
	function insert_entry(){
		$data['nama']=$this->input->post('nama');
		
		return $this->db->insert($this->tabel, $data);
    }

    function update_entry($id_agama){
		$data['nama']=$this->input->post('nama');
        
		return $this->db->update($this->tabel, $data, array('id_agama' => $this->input->post('id_agama')));
    }
	
	function delete_entry($id_agama){
		$this->db->where(array('id_agama' => $id_agama));

		return $this->db->delete($this->tabel);
	}



}
