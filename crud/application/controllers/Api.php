<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	/**
		* @Author				: Localhost {Ferdhika Yudira}
		* @Email				: fer@dika.web.id
		* @Web					: http://dika.web.id
		* @Date					: 2015-07-09 14:48:37
	**/

	function __construct(){
		parent::__construct();
		// Load model m_api.php
		$this->load->model('m_api');

		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Method: PUT, GET, POST, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, x-xsrf-token');
	}

	public function index(){
		$coeg = array(
			'name'		=> 'Ferdhika',
			'website'	=> 'http://dika.web.id'
		);
		echo json_encode($coeg);
	}

	// Get images from gravatar coeg
	protected function foto($email=null){
		$email = trim($email);
  		$email = strtolower($email);
	  	$email_hash = md5($email);
	  	$path = "http://www.gravatar.com/avatar/".$email_hash;
		$d = 'retro'; 
		$s = 600; //ukuran
		return $path.'?d='.$d;	
	}

	public function ambil(){
		// Query from m_api.php
		$ambil = $this->m_api->ambilSemua();

		if(!empty($ambil)){
			foreach ($ambil as $kolom) {
				// data array from database
				$json[] = array(
					'no_pengunjung' 	=> $kolom['no_pengunjung'],
					'nama'		=> $kolom['nama'],
					'email' 	=> $kolom['email'],
					'alamat'		=> $kolom['alamat'],
					'foto'		=> $this->foto($kolom['email'])
				);
			}
		}else{
			$json = array();
		}
		
		// Print with json_encode()
		echo json_encode($json);
	}

	public function ambilSatu(){
		$id = $this->input->get('id');

		// Jika variabel $id tidak kosong
		if(!empty($id)){
			// field condition
			$kolom = array(
				'no_pengunjung' => $id
			);
			// query get one data from model m_api.php
			$ambil = $this->m_api->ambilSatu($kolom);
			$json = array(
				'no_pengunjung' 	=> $ambil['no_pengunjung'],
				'nama'		=> $ambil['nama'],
				'email' 	=> $ambil['email'],
				'alamat'		=> $ambil['alamat'],
				'foto'		=> $this->foto($ambil['email'])
			);
		}else{
			$json = array();
		}

		// Print with json_encode()
		echo json_encode($json);

	}

	public function simpan(){
		$postdata = file_get_contents("php://input");

		$dataObjek = json_decode($postdata);

		@$nama = $dataObjek->data->nama;
		@$email = $dataObjek->data->email;
		@$alamat = $dataObjek->data->alamat;

		if(!empty($nama)){
			$input = array(
				// field => isi
				'nama'	=> $nama,
				'email'	=> $email,
				'alamat'	=> $alamat
			);

			$simpan = $this->m_api->simpan($input);
			if($simpan){
				$json['status'] = 1;
			}else{
				$json['status'] = 0;
			}

			echo json_encode($json);
		}	

	}

	public function ubah(){
		$postdata = file_get_contents("php://input");

		$dataObjek = json_decode($postdata);

		@$id = $dataObjek->data->no_pengunjung;
		@$nama = $dataObjek->data->nama;
		@$email = $dataObjek->data->email;
		@$alamat = $dataObjek->data->alamat;

		if(!empty($nama)){
			$input = array(
				// field => isi
				'nama'	=> $nama,
				'email'	=> $email,
				'alamat'	=> $alamat
			);

			// Primary key table buku_tamu
			$idna['no_pengunjung'] = $id;

			// Query ubah di model m_api.php
			$simpan = $this->m_api->ubah($input,$idna);
			if($simpan){
				$json['status'] = 1;
			}else{
				$json['status'] = 0;
			}

			echo json_encode($json);
		}	

	}

	public function hapus(){

		@$id = $this->input->get('id');

		if(!empty($id)){
			$idna['no_pengunjung'] = $id;

			// Query hapus di model m_api.php
			$hapus = $this->m_api->hapus($idna);
			if($hapus){
				$json['status'] = 1;
			}else{
				$json['status']	= 0;
			}
		}

		echo json_encode($json);
	}
	
}