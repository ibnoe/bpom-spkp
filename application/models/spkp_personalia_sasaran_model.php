<?php
class Spkp_personalia_sasaran_model extends CI_Model {
    
    var $tabel = "app_users_profile";
     
    function __construct(){
        parent::__construct();
        $this->load->library('encrypt');
    }
    
    function json_user(){
        $query = "SELECT @i:=@i+1 AS urut, id, username, nip, gelar, nama, id_number, birthdate, birthplace,
                  gendre, agama,kepercayaan, kawin, phone_number, mobile, email, address, propinsi, kota, kecamatan, 
                  desa, badan_tinggi, badan_berat, badan_rambut, badan_muka, badan_kulit, badan_khas, 
                  badan_cacat, kegemaran, image FROM app_users_profile WHERE status=1 ORDER BY id asc";
        
        $data = $this->crud->jqxGrid($query);
		return ($data);

    }
    
	function json_sasaran($id){
		$query="SELECT @i:=@i+1 AS urut,FROM_UNIXTIME(pegawai_sasaran.id,'%Y/%m/%d %T') AS waktu,
				IF(pegawai_sasaran.update>1,FROM_UNIXTIME(pegawai_sasaran.update,'%Y/%m/%d %T'), 'NULL') AS waktu_update,
				pegawai_sasaran.*, app_users_list.username FROM pegawai_sasaran 
				INNER JOIN app_users_list ON app_users_list.id=pegawai_sasaran.uploader 
				WHERE (pegawai_sasaran.uid=".$this->session->userdata("id")." || pegawai_sasaran.uploader=".$this->session->userdata("id")." || pegawai_sasaran.status=1) AND pegawai_sasaran.uid=".$id;
		
		$data = $this->crud->jqxGrid($query);
		return ($data);
	}


    function get_user($id){
        $query = $this->db->get_where($this->tabel, array('id'=>$id),1);
        
        return $query->row_array();
    }
    
 	function get_data_row($id){
		$data = array();

		$this->db->where('id', $id);

		$query = $this->db->get("pegawai_sasaran");
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

	function doadd($uid,$upload_data){
		$data['id'] = time();
		$data['uid'] = $uid;
		$data['uploader'] = $this->session->userdata('id');
		$data['update'] = 0;
		$data['judul'] = $this->input->post('judul');
		$data['keterangan'] = $this->input->post('keterangan');
		$data['status'] = $this->input->post('status');
		$data['ip'] = $_SERVER['REMOTE_ADDR'];
		$data['filename'] = $upload_data['file_name'];
		$data['filesize'] = $upload_data['file_size'];
        
		if($this->db->insert('pegawai_sasaran', $data)){
			return $data['id'];
		}else{
			return false;
		}
	}

	function doedit($id,$upload_data=0){
		$data['uploader'] = $this->session->userdata('id');
		$data['update'] = time();
		$data['judul'] = $this->input->post('judul');
		$data['keterangan'] = $this->input->post('keterangan');
		$data['status'] = $this->input->post('status');
		$data['ip'] = $_SERVER['REMOTE_ADDR'];
		if($upload_data!=0){
			$data['filename'] = $upload_data['file_name'];
			$data['filesize'] = $upload_data['file_size'];
		}
        
		return $this->db->update('pegawai_sasaran', $data, array('id'=>$id));
	}

	function dodelete($id)
	{
		$this->db->where('id', $id);

		return $this->db->delete('pegawai_sasaran');
	}

	function update_profile($id,$type,$data){
        $val['nip']=$this->input->post('nip');
        $val['gelar']=$this->input->post('gelar');
        $val['nama']=$this->input->post('nama');
        $val['id_number']=$this->input->post('id_number');
        $val['birthdate']=$this->input->post('birthdate');
        $val['birthplace']=$this->input->post('birthplace');
        $val['gendre']=$this->input->post('gendre');
        $val['agama']=$this->input->post('agama');
        $val['kepercayaan']=$this->input->post('kepercayaan');
        $val['kawin']=$this->input->post('kawin');
        $val['phone_number']=$this->input->post('phone_number');
        $val['mobile']=$this->input->post('mobile');
        $val['email']=$this->input->post('email');
        
        $val['address']=$this->input->post('address');
        $val['propinsi']=$this->input->post('propinsi');
        $val['kota']=$this->input->post('kota');
        $val['kecamatan']=$this->input->post('kecamatan');
        $val['desa']=$this->input->post('desa');
        
        $val['badan_tinggi']=$this->input->post('badan_tinggi');
        $val['badan_berat']=str_replace('_','',$this->input->post('badan_berat'));
        $val['badan_rambut']=$this->input->post('badan_rambut');
        $val['badan_muka']=$this->input->post('badan_muka');
        $val['badan_kulit']=$this->input->post('badan_kulit');
        $val['badan_khas']=$this->input->post('badan_khas');
        $val['badan_cacat']=$this->input->post('badan_cacat');
        $val['kegemaran']=$this->input->post('kegemaran');
        
        if($type=="1"){
             $val['image'] = $data['file_name'];
        }
       
        $this->db->update($this->tabel, $val, array('id'=> $id));
    }
}
?>