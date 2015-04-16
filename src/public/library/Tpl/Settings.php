<?

/*
 Class Settings

 Version: 0.1

 Description:

 Globale Klasse um Werte für
 Sessions / Cookies sowie Runtime zu speichern.

 Functions:

 set($key,$value,$lifetime=0)
 get($key);
 showall($filter);

 Stuff:

 Die Key's werden anhand folgender Struktur gespeichert.

 U_username		=	User Data
 S_session_val	= Session Data (wird in Session gespeichert)
 C_cookie Info	= Cookie Data (wird als Cookie gespeichert und benötigt Lifetime als 3. Argument)
 T_template_src	= Template Data


 Example:

 Settings::set("U_username","admin");
 echo Settings::get("U_username");
 */

class Settings
{
	static public function set($key = NULL, $value = NULL)
	{
		if($key !== NULL OR $value == NULL) {
			global $data;

			$aKeyNames = explode("_",$key);
			if(count($aKeyNames) > 0)
			{
				$cat = $aKeyNames[0];
				$varKey = substr($key, (strlen($aKeyNames[0]))+1);

				if($cat=="S")
				{
					//

					if($value == "" or $value == NULL) { unset($_SESSION[$varKey]); }
					else { $_SESSION[$varKey] = $value; }
				}
				elseif($cat=="C") {
					// altes Cookie holen
					if(isset($_COOKIE["all"])){
						$cookie = unserialize( base64_decode($_COOKIE["all"]) );
					} else $cookie = array(); 						// kein cookie vorhanden

					if($value == "" or $value == NULL) { unset($cookie[$varKey]); } 	// cookiedaten löschen
					else { $cookie[$varKey] = $value; } 			// cookiedaten setzen

					//info("_COOKIE[$varKey] = $value wurde gespeichert. File:".__FILE__ ." Line:".__LINE__);
					// Aktion
					$save = base64_encode(serialize($cookie));
					$_COOKIE["all"] = $save;
					setcookie("all", $save, (time() + (365*24*60*60)), "/");
				}
				else
				{
					//info("data[$varKey] = $value wurde gespeichert. File:".__FILE__ ." Line:".__LINE__);
					$data[$cat][$varKey] = $value;
				}
			} else {
				//warn("aKeyNames ist leer in set. File:".__FILE__ ." Line:".__LINE__);
			}
		} else {
			//warn("Es wurde kein key oder value an die Funktion set übergeben. File:".__FILE__ ." Line:".__LINE__);
			return false;
		}
	}

	static public function get($key = NULL)
	{
		if($key !== NULL)
		{
			global $data;

			$aKeyNames = explode("_", $key);
			if(count($aKeyNames) > 0)
			{
				$cat = $aKeyNames[0];
				$varKey = substr($key,(strlen($aKeyNames[0]))+1);

				if($cat == "S")
				{
					if(isset($_SESSION[$varKey])) return $_SESSION[$varKey];
					else {
						//debug("_SESSION[$varKey] exisitiert nicht. File:".__FILE__ ." Line:".__LINE__);
						return false;
					}
				}
				elseif($cat == "C")
				{
					if(isset($_COOKIE["PHPSESSID"]) && $varKey == "PHPSESSID") {
						return $_COOKIE["PHPSESSID"];
					} else {
						// altes Cookie holen
						if(isset($_COOKIE["all"])){
							$cookie = unserialize( base64_decode($_COOKIE["all"]) );
						} else $cookie = array(); 						// kein cookie vorhanden


						if(isset($cookie[$varKey])) return $cookie[$varKey];
						else {
							//debug("_COOKIE[$varKey] exisitiert nicht. File:".__FILE__ ." Line:".__LINE__);
							return false;
						}
					}
				}
				else
				{
					if(isset($data[$cat][$varKey])) return $data[$cat][$varKey];
					else {
						//debug("data[$cat][$varKey]] exisitiert nicht. File:".__FILE__ ." Line:".__LINE__);
						return false;
					}
				}
			} else {
				//warn("aKeyNames ist leer in get. File:".__FILE__ ." Line:".__LINE__);
				return false;
			}
		} else {
			//warn("Es wurde kein key an die Funktion get übergeben. File:".__FILE__ ." Line:".__LINE__);
			return false;
		}
	}

	static public function showAll($filter="")
	{
		global $data;
		$output = "";

		// reset dieser beiden Variablen
		$data["S"] = array();
		$data["C"] = array();

		if(is_array($data)) {
			foreach($data as $cat=>$keys)
			{
				switch($cat)
				{
					case "S":
						if(is_array($_SESSION))
						{
							$keylist = $_SESSION;
						}
						else
						{
							$keylist = Array();
						}
						$cat = "Session_Data";
						break;

					case "C":
						// altes Cookie holen
						if(isset($_COOKIE["all"])){
							$cookie = unserialize( base64_decode($_COOKIE["all"]) );
						} else $cookie = array(); 	// kein cookie vorhanden

						if(is_array($cookie))
						{
							$keylist = $cookie;
						}
						else
						{
							$keylist = Array();
						}
						$cat = "Cookie_Data";
						break;

					case "U":
						$keylist = $keys;
						$cat = "User-Data";
						break;

					case "T":
						$keylist = $keys;
						$cat = "Template_Data";
						break;
				}

				$output.="<br /><b>".$cat."</b><br />";
				if(is_array($keylist)) {
					foreach($keylist as $key=>$value)
					{
						if(is_array($value)) {
							$output.="&nbsp;<i>".$key."</i> => (<br />";
							foreach ($value as $subkey => $subvalue) {
								if(is_object($subvalue)) {

								} elseif(is_array($subvalue)) {
									$output.="&nbsp;&nbsp;<u>".$subkey."</u	> => (<br />";
									foreach ($subvalue as $subsubkey => $subsubvalue) {
										if(is_object($subsubvalue)) {

										} else {
											$output.="&nbsp;&nbsp;&nbsp;".$subsubkey." => ".$subsubvalue."<br />";
										}
									}
									$output.="&nbsp;&nbsp;)<br />";
								} else {
									$output.="&nbsp;&nbsp;".$subkey." => ".$subvalue."<br />";
								}
							}
							$output.="&nbsp;)<br />";
						} else {
							$output.="&nbsp;".$key." => ".$value."<br />";
						}
					}
				} else {
					//warn("keylist ist kein Array in showAll. File:".__FILE__ ." Line:".__LINE__);
				}

			}
		} else {
			//warn("data ist kein Array in showAll. File:".__FILE__ ." Line:".__LINE__);
		}
		return $output;
	}

	public function __call($a,$b)
	{
		global $data;
		return $data[$a];
	}
}