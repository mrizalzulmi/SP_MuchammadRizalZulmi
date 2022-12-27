<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class app extends CI_Controller {

	/**
	 * @author : Muchammad Rizal Zulmi
	 * @web : http://mrizalzulmi.wordpress.com
	 * @keterangan : Controller untuk manajemen data rs dinkes DKI
	 **/

	public function api_rs_rujukan()
	{
		$query = $this->db->query("SELECT a.nama_rumah_sakit, a.alamat, a.kota_madya, a.kelurahan, a.kecamatan,
									b.jenis_rumah_sakit, b.kode_pos, b.nomor_telepon, b.nomor_fax, b.website, b.email
									FROM rs_rujukan a 
									LEFT JOIN rs_dki b ON a.nama_rumah_sakit=b.nama_rumah_sakit 
									AND a.kelurahan=b.kelurahan AND a.kecamatan=b.kecamatan")->result_array();

		if ($query) {
			$list_rs = [];
			foreach($query as $rowss){

				$list_rs[] = [
					"nama_rumah_sakit" => $rowss["nama_rumah_sakit"],
					"jenis_rumah_sakit" => $rowss["jenis_rumah_sakit"],
					"alamat_rumah_sakit" => $rowss["alamat"],
					"kelurahan" =>$rowss["kelurahan"],
					"kecamatan" =>$rowss["kecamatan"],
					"kota/kab" =>$rowss["kota_madya"],
					"kodepos" =>$rowss["kode_pos"],
					"telepon" =>$rowss["nomor_telepon"],
					"nomor_fax" =>$rowss["nomor_fax"],
					"website" =>$rowss["website"],
					"email" =>$rowss["email"]
				];
			}
        }
		$data = [
			"success" => true,
			"message" => "Data Berhasil di Ambil",
			"data" => $list_rs
		];

		echo json_encode($data, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
	}

	public function api_rs_rujukan_filter()
	{
		$kelurahan = $this->input->get("kelurahan");
		$kecamatan = $this->input->get("kecamatan");
		$kota = $this->input->get("kota");

		if($kelurahan) {
			$sql = "SELECT a.nama_rumah_sakit, a.alamat, a.kota_madya, a.kelurahan, a.kecamatan,
										b.jenis_rumah_sakit, b.kode_pos, b.nomor_telepon, b.nomor_fax, b.website, b.email
										FROM rs_rujukan a 
										LEFT JOIN rs_dki b ON a.nama_rumah_sakit=b.nama_rumah_sakit 
										AND a.kelurahan=b.kelurahan AND a.kecamatan=b.kecamatan
										WHERE a.kelurahan LIKE '%".$kelurahan."%'";
		} else if($kecamatan) {
			$sql = "SELECT a.nama_rumah_sakit, a.alamat, a.kota_madya, a.kelurahan, a.kecamatan,
										b.jenis_rumah_sakit, b.kode_pos, b.nomor_telepon, b.nomor_fax, b.website, b.email
										FROM rs_rujukan a 
										LEFT JOIN rs_dki b ON a.nama_rumah_sakit=b.nama_rumah_sakit 
										AND a.kelurahan=b.kelurahan AND a.kecamatan=b.kecamatan
										WHERE a.kecamatan LIKE '%".$kecamatan."%'";
		} else if($kota) {
			$sql = "SELECT a.nama_rumah_sakit, a.alamat, a.kota_madya, a.kelurahan, a.kecamatan,
										b.jenis_rumah_sakit, b.kode_pos, b.nomor_telepon, b.nomor_fax, b.website, b.email
										FROM rs_rujukan a 
										LEFT JOIN rs_dki b ON a.nama_rumah_sakit=b.nama_rumah_sakit 
										AND a.kelurahan=b.kelurahan AND a.kecamatan=b.kecamatan
										WHERE a.kota_madya LIKE '%".$kota."%'";
		} else {
			$sql = "SELECT a.nama_rumah_sakit, a.alamat, a.kota_madya, a.kelurahan, a.kecamatan,
										b.jenis_rumah_sakit, b.kode_pos, b.nomor_telepon, b.nomor_fax, b.website, b.email
										FROM rs_rujukan a 
										LEFT JOIN rs_dki b ON a.nama_rumah_sakit=b.nama_rumah_sakit 
										AND a.kelurahan=b.kelurahan AND a.kecamatan=b.kecamatan";
		}
		
		$query = $this->db->query($sql)->result_array();

		if ($query) {
			$list_rs = [];
			foreach($query as $rowss){

				$list_rs[] = [
					"nama_rumah_sakit" => $rowss["nama_rumah_sakit"],
					"jenis_rumah_sakit" => $rowss["jenis_rumah_sakit"],
					"alamat_rumah_sakit" => $rowss["alamat"],
					"kelurahan" =>$rowss["kelurahan"],
					"kecamatan" =>$rowss["kecamatan"],
					"kota/kab" =>$rowss["kota_madya"],
					"kodepos" =>$rowss["kode_pos"],
					"telepon" =>$rowss["nomor_telepon"],
					"nomor_fax" =>$rowss["nomor_fax"],
					"website" =>$rowss["website"],
					"email" =>$rowss["email"]
				];
			}
        }
		$data = [
			"success" => true,
			"message" => "Data Berhasil di Ambil",
			"data" => $list_rs
		];

		echo json_encode($data, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
	}
	 
	// fungsi untuk menarik data dari API RS Rujukan Covid 19
	public function get_data_rs_rujukan()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://data.jakarta.go.id/read-resource/get-json/daftar-rumah-sakit-rujukan-penanggulangan-covid-19/65d650ae-31c8-4353-a72b-3312fd0cc187",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  
			$data_respon = $response;

			$obj = json_decode($data_respon,true);

			$this->db->query('truncate table rs_rujukan');	
			
			// insert into table rs_rujukan			
			foreach ($obj as $data) {
				$ins['nama_rumah_sakit'] = $data['nama_rumah_sakit'];
				$ins['alamat'] = $data['alamat'];
				$ins['kota_madya'] = $data['kota_madya'];
				$ins['kelurahan'] = $data['kelurahan'];
				$ins['kecamatan'] = $data['kecamatan'];

				$this->db->insert('rs_rujukan', $ins);				

			}
		}
	}

	// fungsi untuk menarik seluruh data RS di DKI
	public function get_data_rs_dki()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://data.jakarta.go.id/read-resource/get-json/rsdkijakarta-2017-10/8e179e38-c1a4-4273-872e-361d90b68434",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  	
			$data_respon = $response;
			$obj = json_decode($data_respon,true);

			// truncate table rs dki
			$this->db->query('truncate table rs_dki');
			
			// insert into table rs_dki
			foreach ($obj as $data) {
				$ins['nama_rumah_sakit'] = $data['nama_rumah_sakit'];
				$ins['jenis_rumah_sakit'] = $data['jenis_rumah_sakit'];
				$ins['alamat_rumah_sakit'] = $data['alamat_rumah_sakit'];
				$ins['kelurahan'] = $data['kelurahan'];
				$ins['kecamatan'] = $data['kecamatan'];
				$ins['kota'] = $data['kota/kab_administrasi'];
				$ins['kode_pos'] = $data['kode_pos'];
				$ins['nomor_telepon'] = $data['nomor_telepon'];
				$ins['nomor_fax'] = $data['nomor_fax'];
				$ins['no_hp_direktur'] = $data['no_hp_direktur/kepala_rs'];
				$ins['website'] = $data['website'];
				$ins['email'] = $data['email'];

				$this->db->insert('rs_dki', $ins);				
			}
		}
	}


	 
}

/* End of file app.php */
/* Location: ./application/controllers/app.php */