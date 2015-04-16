<?

function make_alias($str="")
{
	if($str <> "") {
		$search = array(
			" ", ".", ",", "-",
			"ä", "ü", "ö", "Ä", "Ü", "Ö", "ß",	// for German
			"ú", "ñ", "ó", "í", "é", "á"		// for Spain
		);
		$replace = array(
			"_", "_" ,"_", "_",
			"ae", "ue", "oe", "AE", "Ue", "Oe", "ss",
			"u", "n", "o", "i", "e", "a"
		);

		$zeichenkette = str_replace($search, $replace, $str);

		$suchmuster = "/[a-zA-Z0-9_]/";
		preg_match_all($suchmuster, $zeichenkette, $treffer);
		$zeichenkette= implode($treffer[0]);

		$zeichenkette= trim(preg_replace('#__*#si', '-', $zeichenkette), '_');
		return strtolower($zeichenkette);
	} else return "";
}


function dd($date)
{
	$new = substr($date,0,4);
	$new.="-".substr($date,4,2);
	$new.="-".substr($date,6,2);
	$new.=substr($date,8);
	return $new;
}

function getAllLanguages($type = "frontend")
{
	$backend = Array("en","de","es","fr","nl");
	//"sv"
	$frontend = Array("fr","de","en","es","nl");
	
	if($type=="backend")
	{
		return $backend;
	} else {
		return $frontend;
	}
}

function getSortedCountryList($lang = "")
{
	if($lang=="")
	{
		$lang = Settings::get("S_lang");
	}

	$trans = Zend_Registry::get("Zend_Translate");

	if ($trans->isAvailable($lang) )
	{
		$trans->setLocale($lang);		
		
		$countries = getCountryList();		
			
		foreach($countries as $code => $country)
		{
			$name = $trans->translate("country_".strtolower($code));
				
			$arrCountryList[ $name ] = $code ;
		}
		ksort($arrCountryList);
		$arrCountryList = array_flip($arrCountryList);
	} else {
		return Array();
	}
	return $arrCountryList;
}

function getCountryList()
{
	require_once(APPLICATION_PATH."/modules/default/models/Countries.php");
	$obj_c=new Default_Model_Countries();
	
	$payoutCountries = $obj_c->getAllCountries();
	if($payoutCountries)
	{
		return $payoutCountries;
	} else {
		return Array();
	}
	
	return array(
        'AD' => 'Andorra',
        'AE' => 'United Arab Emirates',
        'AF' => 'Afghanistan',
        'AG' => 'Antigua and Barbuda',
        'AL' => 'Albania',
        'AM' => 'Armenia',
        'AO' => 'Angola',
        'AR' => 'Argentina',
        'AT' => 'Austria',
        'AU' => 'Australia',
        'AZ' => 'Azerbaijan',
        'BA' => 'Bosnia-Herzegovina',
        'BB' => 'Barbados',
        'BD' => 'Bangladesh',
        'BE' => 'Belgium',
        'BF' => 'Burkina Faso',
        'BG' => 'Bulgaria',
        'BH' => 'Bahrain',
        'BI' => 'Burundi',
        'BJ' => 'Benin',
        'BN' => 'Brunei',
        'BO' => 'Bolivia',
        'BR' => 'Brazil',
        'BS' => 'Bahamas',
        'BT' => 'Bhutan',
        'BW' => 'Botswana',
        'BY' => 'Belarus',
        'BZ' => 'Belize',
        'CA' => 'Canada',
        'CD' => 'Congo (Democratic Rep.)',
        'CF' => 'Central African Republic',
        'CG' => 'Congo (Brazzaville)',
        'CH' => 'Switzerland',
        'CI' => 'Cote d\'Ivoire',
        'CL' => 'Chile',
        'CM' => 'Cameroon',
        'CN' => 'China',
        'CO' => 'Colombia',
        'CR' => 'Costa Rica',
        'CU' => 'Cuba',
        'CV' => 'Cape Verde',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DE' => 'Germany',
        'DJ' => 'Djibouti',
        'DK' => 'Denmark',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'DZ' => 'Algeria',
        'EC' => 'Ecuador',
        'EE' => 'Estonia',
        'EG' => 'Egypt',
        'EH' => 'Western Sahara',
        'ER' => 'Eritrea',
        'ES' => 'Spain',
        'ET' => 'Ethiopia',
        'FI' => 'Finland',
        'FJ' => 'Fiji',
        'FM' => 'Micronesia',
        'FR' => 'France',
        'GA' => 'Gabon',
        'GB' => 'United Kingdom',
        'GD' => 'Grenada',
        'GE' => 'Georgia',
        'GH' => 'Ghana',
        'GL' => 'Greenland',
        'GM' => 'Gambia',
        'GN' => 'Guinea',
        'GQ' => 'Equatorial Guinea',
        'GR' => 'Greece',
        'GT' => 'Guatemala',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HN' => 'Honduras',
        'HR' => 'Croatia',
        'HT' => 'Haiti',
        'HU' => 'Hungary',
        'ID' => 'Indonesia',
        'IE' => 'Ireland',
        'IL' => 'Israel',
        'IN' => 'India',
        'IQ' => 'Iraq',
        'IR' => 'Iran',
        'IS' => 'Iceland',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JO' => 'Jordan',
        'JP' => 'Japan',
        'KE' => 'Kenya',
        'KG' => 'Kyrgyzstan',
        'KH' => 'Cambodia',
        'KI' => 'Kiribati',
        'KM' => 'Comoros',
        'KN' => 'Saint Kitts and Nevis',
        'KP' => 'North Korea',
        'KR' => 'South Korea',
        'KW' => 'Kuwait',
        'KY' => 'Cayman Islands',
        'KZ' => 'Kazakhstan',
        'LA' => 'Laos',
        'LB' => 'Lebanon',
        'LC' => 'Saint Lucia',
        'LI' => 'Liechtenstein',
        'LK' => 'Sri Lanka',
        'LR' => 'Liberia',
        'LS' => 'Lesotho',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'LV' => 'Latvia',
        'LY' => 'Libya',
        'MA' => 'Morocco',
        'MD' => 'Moldova',
        'ME' => 'Montenegro',
        'MG' => 'Madagascar',
        'MH' => 'Marshall Islands',
        'MK' => 'Macedonia',
        'ML' => 'Mali',
        'MM' => 'Myanmar',
        'MN' => 'Mongolia',
        'MR' => 'Mauritania',
        'MT' => 'Malta',
        'MU' => 'Mauritius',
        'MV' => 'Maldives',
        'MW' => 'Malawi',
        'MX' => 'Mexico',
        'MY' => 'Malaysia',
        'MZ' => 'Mozambique',
        'NA' => 'Namibia',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NI' => 'Nicaragua',
        'NL' => 'Netherlands',
        'NO' => 'Norway',
        'NP' => 'Nepal',
        'NR' => 'Nauru',
        'NZ' => 'New Zealand',
        'OM' => 'Oman',
        'PA' => 'Panama',
        'PE' => 'Peru',
        'PG' => 'Papua New Guinea',
        'PH' => 'Philippines',
        'PK' => 'Pakistan',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PW' => 'Palau',
        'PY' => 'Paraguay',
        'QA' => 'Qatar',
        'RO' => 'Romania',
        'RS' => 'Serbia',
        'RU' => 'Russia',
        'RW' => 'Rwanda',
        'SA' => 'Saudi Arabia',
        'SB' => 'Solomon Islands',
        'SC' => 'Seychelles',
        'SD' => 'Sudan',
        'SE' => 'Sweden',
        'SG' => 'Singapore',
        'SI' => 'Slovenia',
        'SK' => 'Slovakia',
        'SL' => 'Sierra Leone',
        'SM' => 'San Marino',
        'SN' => 'Senegal',
        'SO' => 'Somalia',
        'SR' => 'Suriname',
        'ST' => 'Sao Tome and Principe',
        'SV' => 'El Salvador',
        'SY' => 'Syria',
        'SZ' => 'Swaziland',
        'TD' => 'Chad',
        'TG' => 'Togo',
        'TH' => 'Thailand',
        'TJ' => 'Tajikistan',
        'TL' => 'Timor-Leste',
        'TM' => 'Turkmenistan',
        'TN' => 'Tunisia',
        'TO' => 'Tonga',
        'TR' => 'Turkey',
        'TT' => 'Trinidad and Tobago',
        'TV' => 'Tuvalu',
        'TW' => 'Taiwan',
        'TZ' => 'Tanzania',
        'UA' => 'Ukraine',
        'UG' => 'Uganda',
        'US' => 'United States',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VC' => 'Saint Vincent and the Grenadines',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'VU' => 'Vanuatu',
        'WS' => 'Samoa',
        'YE' => 'Yemen',
        'ZA' => 'South Africa',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
    );
}



function debug($msg)
{
	if(Zend_Registry::isRegistered('Zend_Log'))
	{
		try
		{
			$logger = Zend_Registry::get('Zend_Log');
			$logger->debug($msg);
		} catch(EXCEPTION $e)
		{
			return;
		}	
	} else {
		return;
	}	
}

function info($msg)
{
	if(Zend_Registry::isRegistered('Zend_Log'))
	{
		try
		{
			$logger = Zend_Registry::get('Zend_Log');
			$logger->info($msg);
		} catch(EXCEPTION $e)
		{
			return;
		}	
	} else {
		return;
	}	
}

function note($msg)
{
	if(Zend_Registry::isRegistered('Zend_Log'))
	{
		try
		{
			$logger = Zend_Registry::get('Zend_Log');
			$logger->notice($msg);
		} catch(EXCEPTION $e)
		{
			return;
		}	
	} else {
		return;
	}	
}


function warn($msg)
{
	if(Zend_Registry::isRegistered('Zend_Log'))
	{
		try
		{
			$logger = Zend_Registry::get('Zend_Log');
			$logger->warn($msg);
		} catch(EXCEPTION $e)
		{
			return;
		}	
	} else {
		return;
	}	
}


function error($msg)
{
	if(Zend_Registry::isRegistered('Zend_Log'))
	{
		try
		{
			$logger = Zend_Registry::get('Zend_Log');
			$logger->err($msg);
		} catch(EXCEPTION $e)
		{
			return;
		}	
	} else {
		return;
	}	
}

function critical($msg)
{
	if(Zend_Registry::isRegistered('Zend_Log'))
	{
		try
		{
			$logger = Zend_Registry::get('Zend_Log');
			$logger->crit($msg);
		} catch(EXCEPTION $e)
		{
			return;
		}	
	} else {
		return;
	}	
}

function alert($msg)
{
	if(Zend_Registry::isRegistered('Zend_Log'))
	{
		try
		{
			$logger = Zend_Registry::get('Zend_Log');
			$logger->alert($msg);
		} catch(EXCEPTION $e)
		{
			return;
		}	
	} else {
		return;
	}	
}


function isAdmin($superAdmin=false)
{
	if(Zend_Auth::getInstance()->hasIdentity())
	{
		$role = Zend_Auth::getInstance()->getIdentity()->role;
		switch($role)
		{
			case "admin":
				if($superAdmin)
				{
					return false;
				} else {
					return true;
				}
			case "superadmin":
				return true;
				break;				
			default:
				return false;
				break;
		}
	} else {
		return false;
	}
}

function isLoggedin()
{
	return (Zend_Auth::getInstance()->hasIdentity()? true:false);
}

function hasAccess($resource,$role=null)
{
	return true;
	$acl = Zend_Registry::get("Zend_Acl");
	//return true;	

	if($role===null)
	{
		$auth = Zend_Auth::getInstance();

		if($auth->hasIdentity())
		{
			$role = $auth->getIdentity()->role;
		}
		else
		{
			$role = "guest";
		}
	}

	try
	{
		return $acl->isAllowed($role,$resource);
	}
	catch(EXCEPTION $e)
	{
		echo $e;
		exit;
		return false;
	}
}