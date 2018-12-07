<?php

function formatMessage( $type, $message )
{
    if($type == 'error') $type = "danger";
    return '<div class="alert alert-'.$type.'"><strong>'.$message.'</strong></div>' . "\n";
}
    
    function messageFormat($type, $message_str) {
        return "<div class='alert alert-".$type." text-center'><strong>".$message_str."</strong></div>";
    }
	
	function excape($string) {
		
		echo isset($_POST[$string]) ? htmlentities($_POST[$string],  ENT_QUOTES, 'UTF-8') : "";
		
	}
	
	function exxcape($string, $clear) {
		
		if(isset($_POST[$string]) && $clear == "success") {
			echo "";
		}elseif(isset($_POST[$string]) && $clear == ""){
			echo htmlentities($_POST[$string],  ENT_QUOTES, 'UTF-8');
		}
		
	}
	
	function escape($string) {
		
		return htmlentities(trim($string), ENT_QUOTES, 'UTF-8');
		
	}
	
	function num_only($string) {
		
		return preg_replace("#[^0-9]#", "", $string);
		
	}
	
	function alpha_only($string) {
		
		return preg_replace("#[^a-zA-Z]#i", "", $string);
		
	}

    function get_file_ext($file_name) {
        
        return substr(strrchr($file_name,'.'),1);
        
    }

	function encode($str) {
		
		return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');

	}

	function decode($str) {

		return htmlspecialchars_decode($str, ENT_QUOTES);

	}
	
	function validate_matNumber($mat_no) {
		return preg_match("/([A-Za-z]{3})\/(\d{4})\/(\d{4})/", $mat_no);
	}

	function valid($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	
	function return_val($str) {
		
		if(isset($_POST[$str])) {
			
			echo htmlspecialchars(trim($_POST[$str]), ENT_QUOTES, 'UTF-8');
			
		}else{
			
			echo "";
			
		}
		
	}
	
	//windows does not support some characters for file naming. 
	function filerenaming($string){
		$string = str_replace("?","!",$string);
		$string = str_replace(":","@",$string);
		$string = str_replace("/","#",$string);
		$string = str_replace("\\","$",$string);
		$string = str_replace("\"","%",$string);
		$string = str_replace("*","^",$string);
		$string = str_replace("<","_",$string);
		$string = str_replace(">","~",$string);
		$string = str_replace("|","`",$string);
		return $string;
	}
	
	
	//return their names to how they were
	function unfilerenaming($string){
		$string = str_replace("!","?",$string);
		$string = str_replace("@",":",$string);
		$string = str_replace("#","/",$string);
		$string = str_replace("$","\\",$string);
		$string = str_replace("%","\"",$string);
		$string = str_replace("^","*",$string);
		$string = str_replace("_","<",$string);
		$string = str_replace("~",">",$string);
		$string = str_replace("`","|",$string);
		return $string;
	}
	
	function num($string) {
		
		return preg_replace("#[^0-9]{4}#", "", $string);
		
	}
	
	function create_str($arr = array()) {
		
		$result = "";
		
		$x = 1;
		
		foreach($arr as $key => $value) {
			
			if($x == count($arr)) {
				
				$result .= $value;
				
			}else{
				
				$result .= $value.", ";
				
			}
			
		}
		
		return $result;
		
	}
	
	function create_str_obj($arr = array()) {
		
		$result = "";
		
		$x = 1;
		
		foreach($arr as $key => $value) {
			
			if($x == count($arr)) {
				
				$result .= $value;
				
			}else{
				
				$result .= $value." [ ";
				
			}
			
		}
		
		return $result;
		
	}
	
	function create_list($str) {
		
		$result = "<ol>";
		
		$arr = explode("[", $str);
		
		$x = 1;
		
		foreach($arr as $value) {
			
			$result .= "<li>$value</li>";
			
		}
		
		$result .= "</ol>";
		
		return $result;
		
	}

	function states() {
		
		require "db_helper.php";
		$get = $db->query("SELECT * FROM states");
		$result = "";

		if($get->num_rows) {

			while($row = $get->fetch_assoc()) {

				$result .= "<option value='".$row['state_name']."'>".$row['state_name']."</option>";

			}

			echo $result;

		}
	}
	
	function generateRandomString($type = 'alnum', $len = 0)
	{					
			switch($type)
			{
				case 'alnum'	:
				case 'numeric'	:
				case 'nozero'	:
						switch ($type)
						{
							case 'alnum'	:	$pool = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
								break;
							case 'alpha'	:	$pool = 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
								break;
							case 'numeric'	:	$pool = '0123456789';
								break;
							case 'nozero'	:	$pool = '123456789';
								break;
						}
						
						$str = '';
						for ($i=0; $i < $len; $i++)
						{
							$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
						}

						return $str;
				  break;
				case 'unique' : return md5(uniqid(mt_rand()));
				  break;
			}
			//return true;
	}

    function genItemID($num_rows, $cat_code) {
        $item_serial = "";
        if(num_only($num_rows) != "") {
            if($num_rows < 10) {
                $item_serial = "0000".$num_rows;
            }elseif($item_serial < 100) {
                $item_serial = "000".$num_rows;
            }elseif($item_serial < 1000) {
                $item_serial = "00".$num_rows;
            }elseif($item_serial < 10000) {
                $item_serial = "0".$num_rows;
            }else{
                $item_serial = $num_rows;
            }
            return $cat_code."/".date('Y')."/".$item_serial;
            //return $cat_code.substr(strftime("%Y", time()), -2)."/".$item_serial;
        }
        
    }
	
	function subjects() {
		$subjects = array('English Language', 'Mathematics', 'Computer Studies', 'Civic Education', 'Biology', 'Chemistry', 'Physics', 'Further Maths', 'Agricultural Science', 'Physical Education', 'Health Education', 'Accounting', 'Store Management', 'Office Practice', 'Insurance', 'Economics', 'Commerce', 'Technical Drawing', 'Metal Work', 'Basic Electricity', 'Electronics', 'Auto Mechanics', 'Building Construction', 'Wood Work', 'Home Management', 'Food and Nutrition', 'Clothing and Textile', 'Trade', 'Nigerian Language', 'Lit-in-English', 'Geography', 'Government', 'CRS', 'IRS', 'History', 'Visual Art', 'Music', 'French', 'Arabic');
		
		sort($subjects);
		$option = "";
		foreach($subjects as $subject) {
			$option .= "<option value='".$subject."'>".$subject."</option>";
		}
		
		echo $option;
	}
	
	function subjects_utme($string) {
		$subjects = array('Mathematics', 'Computer Studies', 'Civic Education', 'Biology', 'Chemistry', 'Physics', 'Further Maths', 'Agricultural Science', 'Physical Education', 'Health Education', 'Accounting', 'Store Management', 'Office Practice', 'Insurance', 'Economics', 'Commerce', 'Technical Drawing', 'Metal Work', 'Basic Electricity', 'Electronics', 'Auto Mechanics', 'Building Construction', 'Wood Work', 'Home Management', 'Food and Nutrition', 'Clothing and Textile', 'Trade', 'Nigerian Language', 'Lit-in-English', 'Geography', 'Government', 'CRS', 'IRS', 'History', 'Visual Art', 'Music', 'French', 'Arabic');
		
		sort($subjects);
		$option = "";
		foreach($subjects as $subject) {
			if(escape($string) == escape($subject)) {
				$option .= "<option value='".$subject."' selected>".$subject."</option>";
			}else{
				$option .= "<option value='".$subject."'>".$subject."</option>";
			}
		}
		
		echo $option;
	}
	
	function grade_utme($string) {
		$grades = array('A', 'B', 'C', 'D', 'E', 'F', 'P', 'A/R');
		
		$option = "";
		foreach($grades as $grade) {
			if(escape($string) == escape($grade)) {
				$option .= "<option value='".$grade."' selected>".$grade."</option>";
			}else{
				$option .= "<option value='".$grade."'>".$grade."</option>";
			}
		}
		
		echo $option;
	}
	
	function states_lgas() {
		$state_lga = array(
			'Abia' => ['Select item...', 'Aba North', 'Aba South', 'Arochukwu', 'Bende', 'Ikwuano', 'Isiala Ngwa North', 'Isiala Ngwa South', 'Isuikwuato', 'Obi Ngwa', 'Ohafia', 'Osisioma', 'Ugwunagbo', 'Ukwa East', 'Ukwa West', 'Umuahia North', 'muahia South', 'Umu Nneochi'],
			'Adamawa' => ['Select item...', 'Demsa', 'Fufure', 'Ganye', 'Gayuk', 'Gombi', 'Grie', 'Hong', 'Jada', 'Larmurde', 'Madagali', 'Maiha', 'Mayo Belwa', 'Michika', 'Mubi North', 'Mubi South', 'Numan', 'Shelleng', 'Song', 'Toungo', 'Yola North', 'Yola South'],
			'AkwaIbom' => ['Select item...', 'Abak', 'Eastern Obolo', 'Eket', 'Esit Eket', 'Essien Udim', 'Etim Ekpo', 'Etinan', 'Ibeno', 'Ibesikpo Asutan', 'Ibiono-Ibom', 'Ika', 'Ikono', 'Ikot Abasi', 'Ikot Ekpene', 'Ini', 'Itu', 'Mbo', 'Mkpat-Enin', 'Nsit-Atai', 'Nsit-Ibom', 'Nsit-Ubium', 'Obot Akara', 'Okobo', 'Onna', 'Oron', 'Oruk Anam', 'Udung-Uko', 'Ukanafun', 'Uruan', 'Urue-Offong Oruko', 'Uyo'],
			'Anambra' => ['Select item...', 'Aguata', 'Anambra East', 'Anambra West', 'Anaocha', 'Awka North', 'Awka South', 'Ayamelum', 'Dunukofia', 'Ekwusigo', 'Idemili North', 'Idemili South', 'Ihiala', 'Njikoka', 'Nnewi North', 'Nnewi South', 'Ogbaru', 'Onitsha North', 'Onitsha South', 'Orumba North', 'Orumba South', 'Oyi'],
			'Bauchi' => ['Select item...', 'Alkaleri', 'Bauchi', 'Bogoro', 'Damban', 'Darazo', 'Dass', 'Gamawa', 'Ganjuwa', 'Giade', 'Itas-Gadau', 'Jama are', 'Katagum', 'Kirfi', 'Misau', 'Ningi', 'Shira', 'Tafawa Balewa', ' Toro', ' Warji', ' Zaki'],
			'Bayelsa' => ['Select item...', 'Brass', 'Ekeremor', 'Kolokuma Opokuma', 'Nembe', 'Ogbia', 'Sagbama', 'Southern Ijaw', 'Yenagoa'],
			'Benue' => ['Select item...', 'Agatu', 'Apa', 'Ado', 'Buruku', 'Gboko', 'Guma', 'Gwer East', 'Gwer West', 'Katsina-Ala', 'Konshisha', 'Kwande', 'Logo', 'Makurdi', 'Obi', 'Ogbadibo', 'Ohimini', 'Oju', 'Okpokwu', 'Oturkpo', 'Tarka', 'Ukum', 'Ushongo', 'Vandeikya'],
			'Borno' => ['Select item...', 'Abadam', 'Askira-Uba', 'Bama', 'Bayo', 'Biu', 'Chibok', 'Damboa', 'Dikwa', 'Gubio', 'Guzamala', 'Gwoza', 'Hawul', 'Jere', 'Kaga', 'Kala-Balge', 'Konduga', 'Kukawa', 'Kwaya Kusar', 'Mafa', 'Magumeri', 'Maiduguri', 'Marte', 'Mobbar', 'Monguno', 'Ngala', 'Nganzai', 'Shani'],
			'Crossriver' => ['Select item...', 'Abi', 'Akamkpa', 'Akpabuyo', 'Bakassi', 'Bekwarra', 'Biase', 'Boki', 'Calabar Municipal', 'Calabar South', 'Etung', 'Ikom', 'Obanliku', 'Obubra', 'Obudu', 'Odukpani', 'Ogoja', 'Yakuur', 'Yala'],
			'Delta' => ['Select item...', 'Aniocha North', 'Aniocha South', 'Bomadi', 'Burutu', 'Ethiope East', 'Ethiope West', 'Ika North East', 'Ika South', 'Isoko North', 'Isoko South', 'Ndokwa East', 'Ndokwa West', 'Okpe', 'Oshimili North', 'Oshimili South', 'Patani', 'Sapele', 'Udu', 'Ughelli North', 'Ughelli South', 'Ukwuani', 'Uvwie', 'Warri North', 'Warri South', 'Warri South West'],
			'Ebonyi' => ['Select item...', 'Abakaliki', 'Afikpo North', 'Afikpo South', 'Ebonyi', 'Ezza North', 'Ezza South', 'Ikwo', 'Ishielu', 'Ivo', 'Izzi', 'Ohaozara', 'Ohaukwu', 'Onicha'],
			'Edo' => ['Select item...', 'Akoko-Edo', 'Egor', 'Esan Central', 'Esan North-East', 'Esan South-East', 'Esan West', 'Etsako Central', 'Etsako East', 'Etsako West', 'Igueben', 'Ikpoba Okha', 'Orhionmwon', 'Oredo', 'Ovia North-East', 'Ovia South-West', 'Owan East', 'Owan West', 'Uhunmwonde'],
			'Ekiti' => ['Select item...', 'Ado Ekiti', 'Efon', 'Ekiti East', 'Ekiti South-West', 'Ekiti West', 'Emure', 'Gbonyin', 'Ido Osi', 'Ijero', 'Ikere', 'Ikole', 'Ilejemeje', 'Irepodun-Ifelodun', 'Ise-Orun', 'Moba', 'Oye'],
			'Enugu' => ['Select item...', 'Aninri', 'Awgu', 'Enugu East', 'Enugu North', 'Enugu South', 'Ezeagu', 'Igbo Etiti', 'Igbo Eze North', 'Igbo Eze South', 'Isi Uzo', 'Nkanu East', 'Nkanu West', 'Nsukka', 'Oji River', 'Udenu', 'Udi', 'Uzo Uwani'],
			'FCT' => ['Select item...', 'Abaji', 'Bwari', 'Gwagwalada', 'Kuje', 'Kwali', 'Municipal Area Council'],
			'Gombe' => ['Select item...', 'Akko', 'Balanga', 'Billiri', 'Dukku', 'Funakaye', 'Gombe', 'Kaltungo', 'Kwami', 'Nafada', 'Shongom', 'Yamaltu-Deba'],
			'Imo' => ['Select item...', 'Aboh Mbaise', 'Ahiazu Mbaise', 'Ehime Mbano', 'Ezinihitte', 'Ideato North', 'Ideato South', 'Ihitte-Uboma', 'Ikeduru', 'Isiala Mbano', 'Isu', 'Mbaitoli', 'Ngor Okpala', 'Njaba', 'Nkwerre', 'Nwangele', 'Obowo', 'Oguta', 'Ohaji-Egbema', 'Okigwe', 'Orlu', 'Orsu', 'Oru East', 'Oru West', 'Owerri Municipal', 'Owerri North', 'Owerri West', 'Unuimo'],
			'Jigawa' => ['Select item...', 'Auyo', 'Babura', 'Biriniwa', 'Birnin Kudu', 'Buji', 'Dutse', 'Gagarawa', 'Garki', 'Gumel', 'Guri', 'Gwaram', 'Gwiwa', 'Hadejia', 'Jahun', 'Kafin Hausa', 'Kazaure', 'Kiri Kasama', 'Kiyawa', 'Kaugama', 'Maigatari', 'Malam Madori', 'Miga', 'Ringim', 'Roni', 'Sule Tankarkar', 'Taura', 'Yankwashi'],
			'Kaduna' => ['Select item...', 'Birnin Gwari', 'Chikun', 'Giwa', 'Igabi', 'Ikara', 'Jaba', 'Jema a', 'Kachia', 'Kaduna North', 'Kaduna South', 'Kagarko', 'Kajuru', 'Kaura', 'Kauru', 'Kubau', 'Kudan', 'Lere', 'Makarfi', 'Sabon Gari', 'Sanga', 'Soba', 'Zangon Kataf', 'Zaria'],
			'Kano' => ['Select item...', 'Ajingi', 'Albasu', 'Bagwai', 'Bebeji', 'Bichi', 'Bunkure', 'Dala', 'Dambatta', 'Dawakin Kudu', 'Dawakin Tofa', 'Doguwa', 'Fagge', 'Gabasawa', 'Garko', 'Garun Mallam', 'Gaya', 'Gezawa', 'Gwale', 'Gwarzo', 'Kabo', 'Kano Municipal', 'Karaye', 'Kibiya', 'Kiru', 'Kumbotso', 'Kunchi', 'Kura', 'Madobi', 'Makoda', 'Minjibir', 'Nasarawa', 'Rano', 'Rimin Gado', 'Rogo', 'Shanono', 'Sumaila', 'Takai', 'Tarauni', 'Tofa', 'Tsanyawa', 'Tudun Wada', 'Ungogo', 'Warawa', 'Wudil'],
			'Katsina' => ['Select item...', 'Bakori', 'Batagarawa', 'Batsari', 'Baure', 'Bindawa', 'Charanchi', 'Dandume', 'Danja', 'Dan Musa', 'Daura', 'Dutsi', 'Dutsin Ma', 'Faskari', 'Funtua', 'Ingawa', 'Jibia', 'Kafur', 'Kaita', 'Kankara', 'Kankia', 'Katsina', 'Kurfi', 'Kusada', 'Mai Adua', 'Malumfashi', 'Mani', 'Mashi', 'Matazu', 'Musawa', 'Rimi', 'Sabuwa', 'Safana', 'Sandamu', 'Zango'],
			'Kebbi' => ['Select item...', 'Aleiro', 'Arewa Dandi', 'Argungu', 'Augie', 'Bagudo', 'Birnin Kebbi', 'Bunza', 'Dandi', 'Fakai', 'Gwandu', 'Jega', 'Kalgo', 'Koko Besse', 'Maiyama', 'Ngaski', 'Sakaba', 'Shanga', 'Suru', 'Wasagu Danko', 'Yauri', 'Zuru'],
			'Kogi' => ['Select item...', 'Adavi', 'Ajaokuta', 'Ankpa', 'Bassa', 'Dekina', 'Ibaji', 'Idah', 'Igalamela Odolu', 'Ijumu', 'Kabba Bunu', 'Kogi', 'Lokoja', 'Mopa Muro', 'Ofu', 'Ogori Magongo', 'Okehi', 'Okene', 'Olamaboro', 'Omala', 'Yagba East', 'Yagba West'],
			'Kwara' => ['Select item...', 'Asa', 'Baruten', 'Edu', 'Ekiti', 'Ifelodun', 'Ilorin East', 'Ilorin South', 'Ilorin West', 'Irepodun', 'Isin', 'Kaiama', 'Moro', 'Offa', 'Oke Ero', 'Oyun', 'Pategi'],
			'Lagos' => ['Select item...', 'Agege', 'Ajeromi-Ifelodun', 'Alimosho', 'Amuwo-Odofin', 'Apapa', 'Badagry', 'Epe', 'Eti Osa', 'Ibeju-Lekki', 'Ifako-Ijaiye', 'Ikeja', 'Ikorodu', 'Kosofe', 'Lagos Island', 'Lagos Mainland', 'Mushin', 'Ojo', 'Oshodi-Isolo', 'Shomolu', 'Surulere'],
			'Nassarawa' => ['Select item...', 'Akwanga', 'Awe', 'Doma', 'Karu', 'Keana', 'Keffi', 'Kokona', 'Lafia', 'Nasarawa', 'Nasarawa Egon', 'Obi', 'Toto', 'Wamba'],
			'Niger' => ['Select item...', 'Agaie', 'Agwara', 'Bida', 'Borgu', 'Bosso', 'Chanchaga', 'Edati', 'Gbako', 'Gurara', 'Katcha', 'Kontagora', 'Lapai', 'Lavun', 'Magama', 'Mariga', 'Mashegu', 'Mokwa', 'Moya', 'Paikoro', 'Rafi', 'Rijau', 'Shiroro', 'Suleja', 'Tafa', 'Wushishi'],
			'Ogun' => ['Select item...', 'Abeokuta North', 'Abeokuta South', 'Ado-Odo Ota', 'Egbado North', 'Egbado South', 'Ewekoro', 'Ifo', 'Ijebu East', 'Ijebu North', 'Ijebu North East', 'Ijebu Ode', 'Ikenne', 'Imeko Afon', 'Ipokia', 'Obafemi Owode', 'Odeda', 'Odogbolu', 'Ogun Waterside', 'Remo North', 'Shagamu'],
			'Ondo' => ['Select item...', 'Akoko North-East', 'Akoko North-West', 'Akoko South-West', 'Akoko South-East', 'Akure North', 'Akure South', 'Ese Odo', 'Idanre', 'Ifedore', 'Ilaje', 'Ile Oluji-Okeigbo', 'Irele', 'Odigbo', 'Okitipupa', 'Ondo East', 'Ondo West', 'Ose', 'Owo'],
			'Osun' => ['Select item...', 'Atakunmosa East', 'Atakunmosa West', 'Aiyedaade', 'Aiyedire', 'Boluwaduro', 'Boripe', 'Ede North', 'Ede South', 'Ife Central', 'Ife East', 'Ife North', 'Ife South', 'Egbedore', 'Ejigbo', 'Ifedayo', 'Ifelodun', 'Ila', 'Ilesa East', 'Ilesa West', 'Irepodun', 'Irewole', 'Isokan', 'Iwo', 'Obokun', 'Odo Otin', 'Ola Oluwa', 'Olorunda', 'Oriade', 'Orolu', 'Osogbo'],
			'Oyo' => ['Select item...', 'Afijio', 'Akinyele', 'Atiba', 'Atisbo', 'Egbeda', 'Ibadan North', 'Ibadan North-East', 'Ibadan North-West', 'Ibadan South-East', 'Ibadan South-West', 'Ibarapa Central', 'Ibarapa East', 'Ibarapa North', 'Ido', 'Irepo', 'Iseyin', 'Itesiwaju', 'Iwajowa', 'Kajola', 'Lagelu', 'Ogbomosho North', 'Ogbomosho South', 'Ogo Oluwa', 'Olorunsogo', 'Oluyole', 'Ona Ara', 'Orelope', 'Ori Ire', 'Oyo', 'Oyo East', 'Saki East', 'Saki West', 'Surulere'],
			'Plateau' => ['Select item...', 'Bokkos', 'Barkin Ladi', 'Bassa', 'Jos East', 'Jos North', 'Jos South', 'Kanam', 'Kanke', 'Langtang South', 'Langtang North', 'Mangu', 'Mikang', 'Pankshin', 'Qua an Pan', 'Riyom', 'Shendam', 'Wase'],
			'Rivers' => ['Select item...', 'Abua Odual', 'Ahoada East', 'Ahoada West', 'Akuku-Toru', 'Andoni', 'Asari-Toru', 'Bonny', 'Degema', 'Eleme', 'Emuoha', 'Etche', 'Gokana', 'Ikwerre', 'Khana', 'Obio Akpor', 'Ogba Egbema Ndoni', 'Ogu Bolo', 'Okrika', 'Omuma', 'Opobo Nkoro', 'Oyigbo', 'Port Harcourt', 'Tai'],
			'Sokoto' => ['Select item...', 'Binji', 'Bodinga', 'Dange Shuni', 'Gada', 'Goronyo', 'Gudu', 'Gwadabawa', 'Illela', 'Isa', 'Kebbe', 'Kware', 'Rabah', 'Sabon Birni', 'Shagari', 'Silame', 'Sokoto North', 'Sokoto South', 'Tambuwal', 'Tangaza', 'Tureta', 'Wamako', 'Wurno', 'Yabo'],
			'Taraba' => ['Select item...', 'Ardo Kola', 'Bali', 'Donga', 'Gashaka', 'Gassol', 'Ibi', 'Jalingo', 'Karim Lamido', 'Kumi', 'Lau', 'Sardauna', 'Takum', 'Ussa', 'Wukari', 'Yorro', 'Zing'],
			'Yobe' => ['Select item...', 'Bade', 'Bursari', 'Damaturu', 'Fika', 'Fune', 'Geidam', 'Gujba', 'Gulani', 'Jakusko', 'Karasuwa', 'Machina', 'Nangere', 'Nguru', 'Potiskum', 'Tarmuwa', 'Yunusari', 'Yusufari'],
			'Zamfara' => ['Select item...', 'Anka', 'Bakura', 'Birnin Magaji Kiyaw', 'Bukkuyum', 'Bungudu', 'Gummi', 'Gusau', 'Kaura Namoda', 'Maradun', 'Maru', 'Shinkafi', 'Talata Mafara', 'Chafe', 'Zurmi'],
		);
		
		return $state_lga;
	}
	
	function my_states($selected) {
		$state_arr = array('Abia', 'Adamawa', 'AkwaIbom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno', 'CrossRiver', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'Gombe', 'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara'
		);
		$state_option = "";
		foreach($state_arr as $state) {
			if(ucwords($state) == ucwords($selected)) {
				$state_option .= "<option value='".$state."' selected>".$state."</option>";
			}else{
				$state_option .= "<option value='".$state."'>".$state."</option>";
			}
		}
		echo $state_option;
	}
	
	function gen_date($date_str) {
		$now = date('Y');
		$date_option = "";
		for($i = 1960; $i <= $now; $i++) {
			if($i == $date_str) {
				$date_option .= "<option value='".$i."' selected>".$i."</option>";
			}else{
				$date_option .= "<option value='".$i."'>".$i."</option>";
			}
		}
		return $date_option;
	}
	
	function gen_month($month_str) {
		$mnth_arr = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
		$now = date('m');
		$date_option = "";
		$x = 1;
		foreach($mnth_arr as $value) {
			if($x == $month_str) {
				$date_option .= "<option value='".$x."' selected>".ucwords($value)."</option>";
			}else{
				$date_option .= "<option value='".$x."'>".ucwords($value)."</option>";
			}
			$x++;
		}
		return $date_option;
	}

?>