<?php
header('Access-Control-Allow-Origin: *');

require_once(dirname(__FILE__) . '/Zend/Soap/Client.php');
require_once(dirname(__FILE__) . '/Zend/Soap/Server.php');
require_once(dirname(__FILE__) . '/Zend/Exception.php');
require_once(dirname(__FILE__) . '/Zend/Soap/AutoDiscover.php');

require_once(dirname(__FILE__) . '/Zm/Auth.php');
require_once(dirname(__FILE__) . '/Zm/Account.php');
require_once(dirname(__FILE__) . '/Zm/Domain.php');
require_once(dirname(__FILE__) . '/Zm/Server.php');

 


ini_set('soap.wsdl_cache_enabled', '0');
set_time_limit(0);


/*
$domain = "gie-superh.fr";
$zimbraserver = "mail.".$domain;
$zimbraadminemail = "admin@".$domain;
$zimbraadminpassword = "ulysse!20!03";
*/

class vvproxy_new {






	function  vvproxy_new() {

		$this->wsdl = 'http://10.21.0.200:8000/flexservices/services/vvws_new.php?wsdl';
		$client = new Zend_Soap_Client($this->wsdl);

	}

	
	function zim_auth($zmServer, $zmUserEmail, $zmUserPassword,$zmType) {
        // une authenticication admin:
        //$auth = new Zm_Auth($zimbraServer, $zimbraAdminEmail, $zimbraAdminPassword, "admin");
        // ou une authentification  user:
        $auth = new Zm_Auth($zmServer, $zmUserEmail, $zmUserPassword, $zmType);
        // then login
        $l = $auth->login();
        if (is_a($l, "Exception")) {
            $result = "Error : cannot login to $zmServer\n".$l->getMessage()."\n";
           // exit();
        } else {
            $result =true;
        }
        return $result;
	
    }
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $uuid
	 * @param string $name
	 * @param string $pgver
	 * @param string $platf
	 * @param string $version
	 * @param stringe $dimw
	 * @param string $dimh
	 * @param string $dimaw
	 * @param string $dimah
	 * @param string $dcolor
	 * @param string $ddatec
	 * @param string $dtimec
	 * @return boolean
	 */
	function mob_devconnect($uuid,$name,$pgver,$platf,$version,$dimw,$dimh,$dimaw,$dimah,$dcolor,$ddatec,$dtimec) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->mob_devconnect($uuid,$name,$pgver,$platf,$version,$dimw,$dimh,$dimaw,$dimah,$dcolor,$ddatec,$dtimec);

		
		return $result;
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param string $guid
	 * @param string $name
	 * @param string $prenom
	 * @param string $platf
	 * @param string $cdate
	 * @param string $ctime
	 * @return boolean
	 */
	function mob_connect($guid,$name,$prenom,$platf,$cdate,$ctime) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->mob_connect($guid,$name,$prenom,$platf,$cdate,$ctime);
		return $result;
	}
	
	function mob_pageEnseignes() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->mob_pageEnseignes();
		
		return $result;
	}
	/**
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	function adldapTest($username,$password) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->adldapTest($username,$password);

		return $result;
	}


	/**
	 * @param string $username
	 * @param string $password
	 * @return array
	 */
	function adldapUsrGrp($username,$password) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->adldapUsrGrp($username,$password);

		return $result;	
	}


	function getMailSupport() {
		$mbox = imap_open("{mail.hdistribution.fr:993/imap/ssl}INBOX", "glpi", "glpiglpi");
		$headers = imap_headers($mbox);
		$result=array();
		if ($headers == false) {
    			$result=false;
		} else {
			
    			foreach ($headers as $val) {
       			  array_push($result,$val);
    			}
		}
		imap_close($mbox);
		return json_encode($result);
	}

		
	/**
	 * @param string $ou
	 * @return array
	 */
	function adldapOU($ou) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->adldapOU($ou);

		return $result;
	}
	
	
	
	
	
	/**
	 * @param string $username
	 * @param string $password
	 * @return array
	 */
	function adldapUsrInfos($username,$password) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->adldapUsrInfos($username,$password);

		return $result;
	}
	

	function adldapUserInf($username) {

		/*$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->adldapUserInf($username);

		return $result;
		*/
		require_once(dirname(__FILE__).'/adLDAP.php');
		$adldap = new adLDAP();
		$authUser = $adldap->user()->authenticate("Administrateur", "indi@nflute2004");
	
		if ($authUser == true) {
			$result=array();
			$cols =  $adldap->user()->info($username, array("distinguishedname","samaccountname","givenname","userprincipalname","company","showinaddressbook","mail","displayname","title","memberof"));
			array_push($result,$cols[0]["distinguishedname"][0]);
			array_push($result,$cols[0]["samaccountname"][0]);
			array_push($result,$cols[0]["givenname"][0]);
			array_push($result,$cols[0]["userprincipalname"][0]);
			array_push($result,$cols[0]["company"][0]);
			array_push($result,$cols[0]["showinaddressbook"][0]);
			array_push($result,$cols[0]["mail"][0]);
			array_push($result,$cols[0]["displayname"][0]);
			array_push($result,$cols[0]["title"][0]);
			array_push($result,$cols[0]["memberof"]);
		} else {
			$result=false;
		}
		return $result;
	}





	function orkcentral_ca($annee,$mois,$jour,$nmag)	{
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->orkcentral_ca($annee,$mois,$jour,$nmag);

		return $result;
	}

	
	function zmGetRssMsg($ip,$user) {
	   require_once dirname(__FILE__) .  '/Net/SSH2.php';
	   $ssh = new Net_SSH2($ip);
       if ( ! $ssh->login('root', 'linux2004') ) {
           $result = 'Login Failed';
       }
       $cmd = "su - zimbra -c 'zmmailbox -z -m ".$user." gru /inbox?fmt=rss'";
       if ($exec = $ssh->exec($cmd)) {
           $result=$exec; 
       } else {
            $result='error';
       }
       return $result;
	
    }
    
    
    function zmGetJsonMsg($ip,$user) {
	   require_once dirname(__FILE__) .  '/Net/SSH2.php';
	   $ssh = new Net_SSH2($ip);
       if ( ! $ssh->login('root', 'linux2004') ) {
           $result = 'Login Failed';
       }
       $cmd = "su - zimbra -c 'zmmailbox -z -m ".$user." gru /inbox?fmt=json'";
       if ($exec = $ssh->exec($cmd)) {
           $result=$exec; 
       } else {
            $result='error';
       }
       return $result;
	
    }
	
	/**
	 *
	 * Enter description here ...
	 * @param string $ensnom
	 * @param string $annee
	 * @param string $mois
	 * @param string $jour
	 * @return array
	 */
	function ork_ca($ensnom,$annee,$mois,$jour) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->ork_ca($ensnom,$annee,$mois,$jour);
		
		return $result;
	
	}


	function ork_ca_evo_mois($ensnom) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->ork_ca_evo_mois($ensnom);
		
		return $result;
	
	}

	
	function ork_ca_sec($guid,$ensnom,$annee,$mois,$jour) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->ork_ca_sec($guid,$ensnom,$annee,$mois,$jour);
		
		return $result;
	}

	function orkmob($guid,$ensnom,$annee,$mois,$jour) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->orkmob($guid,$ensnom,$annee,$mois,$jour);
		
		return $result;
	}

	function ork_secmag($guid,$ensnom,$annee,$mois,$jour) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->ork_secmag($guid,$ensnom,$annee,$mois,$jour);
		
		return $result;
	}

	function ork_secmob($guid,$ensnom,$annee,$mois,$jour) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->ork_secmob($guid,$ensnom,$annee,$mois,$jour);
		
		return $result;
	}

	
	function mob_getInfos($guid) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->mob_getInfos($guid);
		
		return $result;

	}

	/**
	 *
	 * Enter description here ...
	 * @param string $guid
	 * @param string $platf
	 * @param string $cdate
	 * @param string $ctime
	 * @return boolean
	 */
	function mob_check_guid($guid,$platf,$cdate,$ctime) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->mob_check_guid($guid,$platf,$cdate,$ctime);
		
		return $result;
	}

	
	/**
	 * @param string $requete
	 * @return string
	 */
	function query_ashhhgd($requete) {

		return $requete;
	}


	/**
	 * @param array $tab
	 * @return boolean
	 */
	function arrayToXlsx($tab) {
		/*
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->arrayToXlsx($tab);
		
        $fh = fopen('./herve.xlsx', 'w');
		fwrite($fh, new ByteArray($result[0]));
		fclose($fh);
        
		return $result;
		*/
		/** PHPExcel */
		require_once 'Classes/PHPExcel.php';
		
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		// Set properties
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
									 ->setLastModifiedBy("Maarten Balliauw")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");
		
		
		// Add some data
		
				$objPHPExcel->setActiveSheetIndex(0);
				$worksheet = $objPHPExcel->getActiveSheet();
				
				for ($row_index=0;$row_index<(count($tab)-1);$row_index++) {
					for ($c=0;$c<count($tab[$row_index]);$c++) {
				  		$cell_value = $tab[$row_index][$c];
				 	 	$worksheet->setCellValue(chr($c+65).$row_index, $cell_value);
					}
				}
				//$worksheet->setCellValue('A1',"'".chr(0+65).(1)."'");
				
			/*	 
			// Add some data
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', $tab[1][1])
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');
			*/						 
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('Simple');
		
		
		
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		
		// Redirect output to a clientâ€™s web browser (Excel2007)
		//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//header('Content-Disposition: attachment;filename="01simple.xlsx"');
		//header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('./herve.xlsx');
		//$objWriter->save('php://output');		

		//$result= array();
		if (file_exists('herve.xlsx'))	 {
			//$res = new ByteArray(file_get_contents('herve.xlsx'));
			$result = true;
		} else {
			$result = false;
		}	

		//array_push($result,$res);
		
		return $result;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $donnes
	 * @param string $file
	 * @return boolean
	 */
	function writeDataFile($donnes,$file) {

		$fh = fopen('./uploads/'.$file, 'w');
		fwrite($fh, new ByteArray($donnes));//new ByteArray();
		fclose($fh);
		return true;
		//$client = new Zend_Soap_Client($this->wsdl);
		//$result = $client->writeDataFile($donnes,$file);

		//return $result;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $subject
	 * @param string $toEmail
	 * @param string $attachments
	 * @param string $body
	 * @return boolean
	 */
	function sendMail($subject,$toEmail,$attachments,$body) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->sendMail($subject,$toEmail,$attachments,$body);

		return $result;
	}

	function mailTo($subject,$toEmail,$body) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->mailTo($subject,$toEmail,$body);

		return $result;
	}

	function mailSend($subject,$toEmail,$body,$fromName,$fromEmail,$smtpServer) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->mailSend($subject,$toEmail,$body,$fromName,$fromEmail,$smtpServer);

		return $result;
	}

	
	/**
	 * 
	 * Enter description here ...
	 * @param string $ip
	 * @param string $user
	 * @param string $passwd
	 * @param string $cmd
	 * @return string
	 */
	function ssh2Exec($ip,$user,$passwd,$cmd) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->ssh2Exec($ip,$user,$passwd,$cmd);

		return $result;
	}

	/**
	 * @param string $ip
	 * @param string $user
	 * @param string $passwd
	 * @param string $path
	 * @param string $filename
	 * @param string $data
	 * @return string 
	 */
	function ssh2FilePut($ip,$user,$passwd,$path,$filename,$data)  {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->ssh2FilePut($ip,$user,$passwd,$path,$filename,$data);

		return $result;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @return array
	 */
	function mailListe() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->mailListe();

		return $result;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @return boolean
	 */
	function mailGIEupdate() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->mailGIEupdate();

		return $result;
	}
	
	
	function lstsplf1000() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->lstSplf1000();

		return $result;
	}
	/**
	 * @param string $user
	 * @param string $pass
	 * @return boolean
        */
	function isConnect($user,$pass) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->isConnect($user,$pass);

		return $result;
	}


	function readFtpFile($srvip,$login,$passw,$filename) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->readFtpFile($srvip,$login,$passw,$filename);

		return $result;
	}

	function readSonealFile($srvip,$login,$passw,$filename) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->readSonealFile($srvip,$login,$passw,$filename);

		return $result;
	}

	function openFtpDir($srvip,$login,$passw) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->openFtpDir($srvip,$login,$passw);

		return $result;
	}

	function ftpListDir($srvip,$login,$passw,$dir) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->ftpListDir($srvip,$login,$passw,$dir);

		return $result;
	}

	function chgSplf($splfname,$numero,$utilisateur,$travail,$dest,$numfile) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->chgSplf($splfname,$numero,$utilisateur,$travail,$dest,$numfile);

		return $result;
	}

	function as400GetFromFTP($ip,$user,$passwd,$filename,$bib,$file400) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->as400GetFromFTP($ip,$user,$passwd,$filename,$bib,$file400);

		return $result;
	}

	function crtdupoutq($biborig,$outqorig,$bibdest,$outqdest,$ip) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->crtdupoutq($biborig,$outqorig,$bibdest,$outqdest,$ip);

		return $result;
	}

	function crtprthp15n($ip,$impname) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->crtprthp15n($ip,$impname);

		return $result;
	}

	function crtprtT650($ip,$impname) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->crtprtT650($ip,$impname);

		return $result;
	}

	function crtprtT640($ip,$impname) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->crtprtT640($ip,$impname);

		return $result;
	}

	function crtprtT630($ip,$impname) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->crtprtT630($ip,$impname);

		return $result;
	}

	function crtprtLxkC($ip,$impname) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->crtprtLxkC($ip,$impname);

		return $result;
	}

	function crtprthp1320($ip,$impname) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->crtprthp1320($ip,$impname);

		return $result;
	}

	function crtprtlx2591N($ip,$impname) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->crtprtlx2591N($ip,$impname);

		return $result;
	}

	function crtprtZebra($impname,$ip) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->crtprtZebra($impname,$ip);

		return $result;
	}

	function crtprtMonarch($outqdest,$ip) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->crtprtMonarch($outqdest,$ip);

		return $result;
	}

	function crtprt2581($impname,$ip) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->crtprt2581($impname,$ip);

		return $result;
	}

	function killJob($numero,$utilisateur,$travail) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->killJob($numero,$utilisateur,$travail);

		return $result;
	}

	function notExistPrt($imp) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->notExistPrt($imp);

		return $result;
	}

	function wrkfbib($bib,$filter) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->wrkfbib($bib,$filter);

		return $result;
	}

	function filembr($bib,$file) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->filembr($bib,$file);

		return $result;
	}

	function lstOutqXml($mask) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->lstOutqXml($mask);

		return $result;
	}

	function lstOutqArr($mask) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->lstOutqArr($mask);

		return $result;
	}

	function wrkactjobArr() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->wrkactjobArr();

		return $result;
	}

	function wrkjobqArr() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->wrkjobqArr();

		return $result;
	}

	function wrkjobqArrA(){
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->wrkjobqArrA();

		return $result;
	}

	function jobqArr($bib,$jobq) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->jobqArr($bib,$jobq);

		return $result;
	}

	function chgJobq($job,$user,$numjob,$jobq,$bib) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->chgJobq($job,$user,$numjob,$jobq,$bib);

		return $result;
	}

	function actjobstatebArr($state) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->actjobstatebArr($state);

		return $result;
	}


	function isDevPrt($imp) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->isDevPrt($imp);

		return $result;
	}

	function startEditeur($imp) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->startEditeur($imp);

		return $result;
	}

	function stopEditeur($imp) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->stopEditeur($imp);

		return $result;
	}

	function vvtecpgd() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->vvtecpgd();

		return $result;
	}

	function mgedicasino() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->mgedicasino();

		return $result;
	}

	function pegasTo309() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->pegasTo309();

		return $result;
	}

	function cmdsystem($cmdsys) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->cmdsystem($cmdsys);

		return $result;
	}

	function isysval($sysval) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->isysval($sysval);

		return $result;
	}

	function rtvusrprf($user) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->rtvusrprf($user);

		return $result;
	}

	function rtvallusrprf() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->rtvallusrprf();

		return $result;
	}

	function rtvallsql() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->rtvallsql();

		return $result;
	}


	function readOpFidCli($numcli) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->readOpFidCli($numcli);

		return $result;
	}

	function readReservations($numcli) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->readReservations($numcli);

		return $result;
	}



	function query_as400JSON($requete) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->query_as400JSON($requete);

		return $result;
	}



	function query_multi03($requete) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->query_multi03(str_replace('|','/',$requete));

		return $result;
	}

	function query2xml($requete) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->query2xml($requete);

		return $result;
	}

	function query2array($qr) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->query2array($qr);

		return $result;
	}

	function query_delete($sqlstr) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->query_delete($sqlstr);

		return $result;
	}

	function query_update($sqlstr) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->query_update($sqlstr);

		return $result;
	}

	function contenuOutq($outq) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->contenuOutq($outq);

		return $result;
	}

	function lire_spool($outq) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->lire_spool($outq);

		return $result;
	}
	
	function cptPegasTecpro($nummag,$repsrc) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->cptPegasTecpro($nummag,$repsrc);

		return $result;
	}
	
	function wrkjoblck() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->wrkjoblck();

		return $result;
	}
	
	function startTecFac() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->startTecFac();

		return $result;
	}

	function isTecFac() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->isTecFac();

		return $result;
	}
	
	function test() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->test();

		return $result;
	}

	/**
	 * 
	 * Enter description here ...
	 * @param string $imp
	 * @return string
	 */
	function getIpImp($imp) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->getIpImp($imp);

		return $result;
	}

	/**
	 * 
	 * Enter description here ...
	 * @param string $imp
	 * @return string
	 */
	function getIpRmtPrt($imp) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->getIpRmtPrt($imp);

		return $result;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $imp
	 * @return string
	 */
	function getIpDevPrt($imp) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->getIpDevPrt($imp);

		return $result;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $ip
	 * @return boolean
	 */
	function chkIp($ip) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->chkIp($ip);

		return $result;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $imp
	 * @return array|boolean
	 */
	function getInfoWtr($imp) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->getInfoWtr($imp);

		return $result;
	}
	
	
	
	
	/**
	 * 
	 * Enter description here ...
	 * @return array
	 */
	function lstEdtMsgw() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->lstEdtMsgw();

		return $result;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $imp
	 * @return boolean
	 */
	function dblqPrtMsgw($imp) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->dblqPrtMsgw($imp);

		return $result;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return boolean
	 */
	function autoEdtMsgw() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->autoEdtMsgw();

		return $result;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return array
	 */
	function listJobActifs() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->listJobActifs();

		return $result;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $imp
	 * @return boolean
	 */
	function isImpMsgw($imp) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->isImpMsgw($imp);

		return $result;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $imp
	 * @return boolean
	 */
	function holdEditeur($imp) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->holdEditeur($imp);

		return $result;
	}


	/**
	 * 
	 * Enter description here ...
	 * @param string $user
	 * @return boolean
	 */
	function debloqUser($user) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->debloqUser($user);

		return $result;
	}



	/**
	 * 
	 * Enter description here ...
	 * @param string $dev
	 * @return boolean
	 */
	function debloqDevice($dev) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->debloqDevice($dev);

		return $result;
	}

	/**
	 * 
	 * Enter description here ...
	 * @return boolean
	 */
	function debloqEcomax() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->debloqEcomax();

		return $result;
	}

	/**
	 * 
	 * Enter description here ...
	 * @param string $prtname
	 * @return boolean
	 */
	function dblqPrtEcomax($prtname) {
		$client = new Zend_Soap_Client($this->wsdl);

		$result = false;
		if ( $client->stopEditeur($prtname) ) {
		    $result = $client->startEditeur($prtname);
		}
                

		return $result;
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $ip
	 * @param string $act
	 * @param string $cnum
	 * @return string
	 */
	function testorkaisse($ip,$act,$cnum) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->testorkaisse($ip,$act,$cnum);

		return $result;
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $ip
	 * @return string
	 */
	function testnbcaisses($ip) {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->testnbcaisses($ip);

		return $result;

	}

	/**
	 *
	 * Enter description here ...
	 * @return array
	 */
	function testSendExchg() {
		$client = new Zend_Soap_Client($this->wsdl);
		$result = $client->testSendExchg();

		return $result;

	}

	

        
	
	
	
	
	
	
}
?>
