<?php
class Spkp_pjas_ukp4 extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        $this->load->model('spkp_pjas_ukp4_model');
        $this->load->helper('html');
        $this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');
    }
    
    function index($thn=""){
        $this->authentication->verify('spkp_pjas_ukp4','show');
        
        $data = array();
        $data['title'] = "UKP4";
        $data['thn'] = $thn!="" ? $thn : date("Y");
        $data['option_thn'] = "";
        
        for($i=date("Y");$i>=(date("Y")-5);$i--){
			$data['option_thn'] .= "<option value='$i' ".($data['thn']==$i ? "selected" : "").">$i</option>";
		}
        
		$data['add_permission']=$this->authentication->verify_check('spkp_pjas_ukp4','add');
        $data['form'] = $this->parser->parse("spkp_pjas_ukp4/form",$data,true);
        $data['content'] = $this->parser->parse("spkp_pjas_ukp4/show",$data,true);

		$this->template->show($data,"home");
    }
    
    function json_judul($thn){
        die(json_encode($this->spkp_pjas_ukp4_model->json_judul($thn)));
    }
    
    function add_upload(){
        $this->authentication->verify('spkp_pjas_ukp4','add');
		$data['action']="add";

		echo $this->parser->parse("spkp_pjas_ukp4/form",$data,true);
    }
    
    function doadd_upload(){
        $this->authentication->verify('spkp_pjas_ukp4','add');
        
        $this->form_validation->set_rules('judul', 'Judul', 'trim|required');
        $this->form_validation->set_rules('tahun', 'Tahun', 'trim|required');
        
		if($this->form_validation->run()== FALSE){
			echo "ERROR_".validation_errors();
		}else{
			if(!isset($_FILES['filename']['name']) || $_FILES['filename']['name']==""){
				echo "ERROR_File / Dokumen harus ada.";
			}else{
				if(count($_FILES)>0){
				    $path = './public/files/spkp_pjas_ukp4';
                    if(!is_dir($path)){
                        mkdir($path);
                    }
                    
					$path .=$this->session->userdata('id');
					if (!is_dir($path)) {
						mkdir($path);
					}
					
					@unlink($path."/".$_FILES['filename']['name']);
					$config['upload_path'] = $path;
					$config['allowed_types'] = '*';
					$config['max_size']	= '999999';
					$config['overwrite'] = false;
					$this->load->library('upload', $config);
					$upload = $this->upload->do_upload('filename');
					if($upload === FALSE) {
						echo "ERROR_".$this->upload->display_errors();
					}else{
						$upload_data = $this->upload->data();
						$id=$this->spkp_pjas_ukp4_model->insert_upload($upload_data);
						if($id!=false){
							echo "OK_".$id;
						}else{
							echo "ERROR_Database Error";
						}
					}
				}else{
					echo "ERROR_Upload failed";
				}
			}
		}
    }
    
    function edit_upload($id){
        $this->authentication->verify('spkp_pjas_ukp4','add');

		$data = $this->spkp_pjas_ukp4_model->get_data_row($id);
		$data['action']="edit";

		echo $this->parser->parse("spkp_pjas_ukp4/form",$data,true);
    }
    
    function doedit_upload($id){
        $this->authentication->verify('spkp_pjas_ukp4','edit');
        
        $this->form_validation->set_rules('judul', 'Judul', 'trim|required');
        $this->form_validation->set_rules('tahun', 'Tahun', 'trim|required');
		
        if($this->form_validation->run()== FALSE){
			echo "ERROR_".validation_errors();
		}else{
			if(count($_FILES)>0){
			    $path = './public/files/spkp_pjas_ukp4';
                if(!is_dir($path)){
                    mkdir($path);
                }
                    
				$path = './public/files/spkp_pjas_ukp4/'.$this->session->userdata('id');
				if (!is_dir($path)) {
					mkdir($path);
				}
				@unlink($path."/".$_FILES['filename']['name']);
				$config['upload_path'] = $path;
				$config['allowed_types'] = '*';
				$config['max_size']	= '999999';
				$config['overwrite'] = false;
				$this->load->library('upload', $config);
				$upload = $this->upload->do_upload('filename');
				if($upload === FALSE) {
					echo "ERROR_".$this->upload->display_errors();
				}else{
					$upload_data = $this->upload->data();
					if($this->spkp_pjas_ukp4_model->update_upload($id,$upload_data)){
						echo "OK_".$id;
					}else{
						echo "ERROR_Database Error";
					}
				}
			}else{
				if($this->spkp_pjas_ukp4_model->update_upload($id)){
					echo "OK_".$id;
				}else{
					echo "ERROR_Database Error";
				}
			}
		}
    }
    
    function delete_upload($id){
        $this->authentication->verify('spkp_pjas_ukp4','del');
        
		$data = $this->spkp_pjas_ukp4_model->get_data_row($id);
		$path = './public/files/spkp_pjas_ukp4/'.$this->session->userdata('id')."/".$data['filename'];

		if($this->spkp_pjas_ukp4_model->delete_upload($id)){
			if(file_exists($path)){
				unlink($path);
			}
			echo "OK_".$id;
		}else{
			echo "ERROR_Database Error";
		}
    }
    
    function html_upload($thn){
        $this->authentication->verify('spkp_pjas_ukp4','show');
		
		$data = $this->spkp_pjas_ukp4_model->json_judul($thn);

		$data['Rows'] = $data[0]['Rows'];
		$this->parser->parse("spkp_pjas_ukp4/html",$data);
    }
    
    function excel_upload($thn){
        $this->authentication->verify('spkp_pjas_ukp4','show');

		$data = $this->spkp_pjas_ukp4_model->json_judul($thn);

		$rows = $data[0]['Rows'];
		$data['title'] = "Daftar File UKP4";
        $data['thn'] = "Tahun ".$thn;
        
		$path = dirname(__FILE__).'/../../public/doc_xls_';
		$TBS = new clsTinyButStrong;
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$TBS->ResetVarRef(false);
		$TBS->VarRef =  &$data;
		$template = $path.'templates/pjas_ukp4.xlsx';
		$TBS->LoadTemplate($template);

		$TBS->MergeBlock('data', $rows);
		$output_file_name = $path.'export/report_pjas_ukp4.xlsx';
		$TBS->Show(OPENTBS_FILE, $output_file_name);
		// echo $output_file_name;
        echo '../public/doc_xls_export/report_pjas_ukp4.xlsx';
		
    }
    
    function download($id=0){
		$this->authentication->verify('spkp_pjas_ukp4','edit');

		$data = $this->spkp_pjas_ukp4_model->get_data_row($id);
		
        echo $this->parser->parse("spkp_pjas_ukp4/download",$data,true);
	}

	function dodownload($id=0){
		$this->authentication->verify('spkp_pjas_ukp4','edit');

		$data = $this->spkp_pjas_ukp4_model->get_data_row($id);
		$path = './public/files/spkp_pjas_ukp4/'.$this->session->userdata('id')."/".$data['filename'];

		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Transfer-Encoding: binary");
		header("Content-disposition: attachment; filename=" . $data['filename']);
		header("Content-type: application/octet-stream");
		readfile($path);
	}
}
?>