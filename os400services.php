<?php
session_start ();

class os400services {


	/*******************************************************************************
	 *
	 *
	 */

	function os400services() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

			$this->conn = $conn;

			if (is_resource ( $this->conn )) {
				// ENTER YOUR CODE HERE!
				return "Connection reussie";
				if (! i5_close ( $this->conn )) {
					// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
					return "Erreur de connection : " . i5_errormsg ();
				}
			} else {
				// Connection to i5 server failed, use i5_errormsg() to get the failure reason
				return "Erreur de connection : " . i5_errormsg ();
			}
		} else {
			return "Erreur de connection : " . i5_errormsg ();
		}
	}


	/**
	 *
	 * Enter description here ...
	 * @param string $ip
	 * @param string $impname
	 * @return boolean|string
	 */
	function crtprtlx2591N($ip,$impname) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			$step1 = "CRTDEVPRT DEVD($impname) DEVCLS(*LAN) TYPE(3812) MODEL(1) LANATTACH(*IP) PORT(9100) PPRSRC1(*CONT132) PPRSRC2(*NONE) FONT(011) FORMFEED(*AUTOCUT) PRTERRMSG(*INFO) MFRTYPMDL(*LEX2391) RMTLOCNAME('$ip') SYSDRVPGM(*IBMSNMPDRV)";
			$step2 = i5_command($step1);
			// demarrage editeur
			$step3 = i5_command("VRYCFG CFGOBJ($impname) CFGTYPE(*DEV) STATUS(*ON)");
			$step4 = i5_command("STRPRTWTR $impname");
		}
		if (!i5_close($conn)) {
			return false;
		}
		return $step2;
	}


	function readFtpFile($srvip,$login,$passw,$filename) {

	 	$ftp_server = $srvip;
        $ftp_user = $login;
        $ftp_passwd = $passw;

        $result = false;
        require_once "ftp.api.php";
        if ( $ftp = ftp_connect($ftp_server) ) {
            if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {
            	 $result = ftp_get($ftp,$filename,$filename,FTP_BINARY);
            /*
            echo ftp_pwd($ftp) . "\n";
            echo date("r",ftp_mdtm($ftp,"7juli.txt.gz")) . "\n";
            echo ftp_size($ftp,"7juli.txt.gz")."\n";
            if (function_exists("ftp_raw")) echo ftp_raw($ftp,"SYST")."\n"; //PHP 5 CVS only
            ftp_mkdir($ftp,"ftp_test");
            if (function_exists("ftp_chmod")) ftp_chmod($ftp,777,"ftp_test"); //PHP 5 CVS only
            ftp_rename($ftp,"ftp_test","ftp__test");
            ftp_rename($ftp,"ftp__test","ftp_test");
            ftp_site($ftp,"CHMOD 777 ftp_test");
            ftp_exec($ftp,"touch ftp_file.txt");
            ftp_delete($ftp,"ftp_file.txt");
            ftp_chdir($ftp,"ftp_test");
            ftp_cdup($ftp);
            print_r(ftp_nlist($ftp,""));
            echo "\n";
            print_r(ftp_rawlist($ftp,""));
            echo "\n";

            ftp_put($ftp,"logo.gif","logo.gif",FTP_BINARY);
            ftp_delete($ftp,"logo.gif");
            ftp_rmdir($ftp,"ftp_test");
            */
            } else {
            	$result = "erreur d'authentification ";
            }

        } else {
        	   $result = "erreur de connection";
        }

        ftp_close($ftp);

	 if (file_exists("./".$filename)) {
		$result = new ByteArray(file_get_contents("./".$filename));
	 }

	 return $result;

	}

	/**
	 * sauvegarde du fichier en bytearray
	 * et envoi par ftp sur serveur distant
	 */
	function sendBaToFTP($ba,$srvip,$login,$passw,$filename)
	{
		//if(!file_exists($this->output_dir) || !is_writeable($this->output_dir))
		//	trigger_error ("please create a 'temp' directory first with write access", E_USER_ERROR);

		$data = $ba->data;

		file_put_contents("./badges/".$filename, $data);

		$ftp_server = $srvip;
		$ftp_user = $login;
		$ftp_passwd = $passw;

		$result = false;
		require_once "ftp.api.php";
		if ( $ftp = ftp_connect($ftp_server) ) {
			if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {
				$result = ftp_put($ftp,$filename,"./badges/".$filename,FTP_BINARY);
			}
		} else {
			$result = false;
		}
		ftp_close($ftp);

		return $result;
	}




	/**
	 * sauvegarde du fichier en bytearray
	 * et envoi par ftp sur serveur distant
	 */
	function sendPersBaToFTP($ba,$srvip,$login,$passw,$filename,$destfilname)
	{
		//if(!file_exists($this->output_dir) || !is_writeable($this->output_dir))
		//	trigger_error ("please create a 'temp' directory first with write access", E_USER_ERROR);

		$data = $ba->data;



		file_put_contents("./badges/".$filename, $data);

		$ftp_server = $srvip;
		$ftp_user = $login;
		$ftp_passwd = $passw;

		$result = false;
		require_once "ftp.api.php";
		if ( $ftp = ftp_connect($ftp_server) ) {
			if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {
				//ftp_chdir($ftp,"RH");
				ftp_chdir($ftp,"ContratsFid");
				//ftp_exec($ftp,"cd RH");
				//ftp_exec($ftp,"cd ContratsFid");
				$result = ftp_put($ftp,$destfilname,"./badges/".$filename,FTP_BINARY);
			}
		} else {
			$result = false;
		}
		ftp_close($ftp);

		return $result;
	}

	function sendBadgeBaToFTP($ba,$srvip,$login,$passw,$filename,$destfilname)
	{
		//if(!file_exists($this->output_dir) || !is_writeable($this->output_dir))
		//	trigger_error ("please create a 'temp' directory first with write access", E_USER_ERROR);
		require_once "resizer.php";
		$data = $ba->data;


		$ratio = 'exact'; //options: exact, portrait, landscape, auto, crop
		$width = 393;
		$height = 493;

		file_put_contents("./badges/".$filename, $data);
		//$target = "images/";

		$target = "./badges/".$filename;

		//move_uploaded_file($_FILES['mainPhoto']['tmp_name'], $target);

		$resizeObj = new resize($target);
		$resizeObj -> resizeImage($width, $height, $ratio);

		//$newImage = $_FILES['mainPhoto']['name'];

		$save = $target;
		$resizeObj -> saveImage($save, 100);


		//file_put_contents("./badges/".$filename, $data);

		$ftp_server = $srvip;
		$ftp_user = $login;
		$ftp_passwd = $passw;

		$result = false;
		require_once "ftp.api.php";
		if ( $ftp = ftp_connect($ftp_server) ) {
			if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {
				ftp_chdir($ftp,"Photos");
				//ftp_chdir($ftp,"ContratsFid");
				//ftp_exec($ftp,"cd RH");
				//ftp_exec($ftp,"cd ContratsFid");
				$result = ftp_put($ftp,$destfilname,"./badges/".$filename,FTP_BINARY);
			}
		} else {
			$result = false;
		}
		ftp_close($ftp);

		return $result;
	}


	function putFtpFile($srvip,$login,$passw,$filename,$filedest) {

		$ftp_server = $srvip;
		$ftp_user = $login;
		$ftp_passwd = $passw;

		$result = false;
		require_once "ftp.api.php";
		if ( $ftp = ftp_connect($ftp_server) ) {
			if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {

				if (file_exists("./badges/".$filename)) {
					//$result = new ByteArray(file_get_contents("./".$filename));
					//ftp_exec($ftp,"cd badges");
					ftp_chdir($ftp,"Photos");
					$result = ftp_put($ftp,$filedest,"./badges/".$filename,FTP_BINARY);
				}

			} else {
				$result = "erreur d'authentification ";
			}

		} else {
			$result = "erreur de connection";
		}

		ftp_close($ftp);


		return $result;

	}

	function putFtpBadgeFile($srvip,$login,$passw,$filename,$filedest) {

		$ftp_server = $srvip;
		$ftp_user = $login;
		$ftp_passwd = $passw;

		$result = false;
		require_once "ftp.api.php";
		if ( $ftp = ftp_connect($ftp_server) ) {
			if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {

				if (file_exists("./".$filename)) {
					//$result = new ByteArray(file_get_contents("./".$filename));
					//ftp_exec($ftp,"cd badges");
					//ftp_chdir($ftp,"Photos");
					$result = ftp_put($ftp,$filedest,"./".$filename,FTP_BINARY);
				}

			} else {
				$result = "erreur d'authentification ";
			}

		} else {
			$result = "erreur de connection";
		}

		ftp_close($ftp);


		return $result;

	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return string
	 */
	function adldapTest($username,$password) {
		require_once "adLDAP.php";
		$adldap = new adLDAP();
		$authUser = $adldap->user()->authenticate($username, $password);
		if ($authUser == true) {
		  $result=true;
		}
		else {
		  $result=false;
		}
		return $result;
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return array
	 */
	function adldapUsrGrp($username,$password) {
		require_once "adLDAP.php";
		$adldap = new adLDAP();
		$authUser = $adldap->user()->authenticate($username, $password);
		if ($authUser == true) {
			//$result=true;
			$result=$adldap->user()->groups($username);
		}
		else {
			$result=false;
		}
		return $result;
	}

	/**
	 * @param string $ou
	 * @return array
	 */
	function adldapOU($ou) {
		require_once "adLDAP.php";
		$adldap = new adLDAP();
		$authUser = $adldap->user()->authenticate("Administrateur", "indi@nflute2004");

		if ($authUser == true) {
			//$result=true;
			$result = $adldap->folder()->listing(array($ou), adLDAP::ADLDAP_FOLDER, false);
			//adLDAP::ADLDAP_CONTAINER, false);
		}
		else {
			$result=false;
		}
		return $result;
	}





	/**
	 * @param string $username
	 * @param string $password
	 * @return array
	 */
	function adldapUsrInfos($username,$password) {
		require_once "adLDAP.php";
		$adldap = new adLDAP();
		$authUser = $adldap->user()->authenticate($username, $password);
		if ($authUser == true) {
			//$result=true;
			//$result=$adldap->user()->info($username, array("*"));
			$adldap->close();
			$adldap1 = new adLDAP();
			$adldap1->user()->authenticate("Administrateur", "indi@nflute2004");
			$col=$adldap1->user()->infoCollection($username, array("*"));
			$result=array();
			$res=array();
			$dn = $col->dn;
			$groups = $adldap1->user()->groups($username);
			foreach ( $dn as $key=>$val) {
				array_push($res,$key);
			}
			array_push($result,array($col->displayName,$col->mail,$col->cn,$groups,$col->distinguishedName,$col->objectCategory,$col->firstName,$col->surName,$col->enabled));
		} else {
			$result=false;
		}
		return $result;
	}

	/**
	 * @param string $username
	 * @return array
	 */
	function adldapUserInf($username) {
		require_once "adLDAP.php";
		$adldap = new adLDAP();
		$authUser = $adldap->user()->authenticate("Administrateur", "indi@nflute2004");

		if ($authUser == true) {
			$result=array();
			$cols =  $adldap->user()->info($username, array("username","logon_name","firstname","surname","company","department","mail","displayname","enable","primarygroupid"));
			array_push($result,$cols[0]["username"][0]);
			array_push($result,$cols[0]["logon_name"][0]);
			array_push($result,$cols[0]["firstname"][0]);
			array_push($result,$cols[0]["surname"][0]);
			array_push($result,$cols[0]["company"][0]);
			array_push($result,$cols[0]["department"][0]);
			array_push($result,$cols[0]["mail"][0]);
			array_push($result,$cols[0]["displayname"][0]);
			array_push($result,$cols[0]["enable"][0]);
			array_push($result,$cols[0]["primarygroupid"][0]);
			//adLDAP::ADLDAP_CONTAINER, false);
		}
		else {
			$result=false;
		}
		return $result;
	}


	/**
	 * @param string $vuser
	 * @return boolean
	 */
	function isUser($vuser) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );


		if ( i5_command("DSPUSRPRF USRPRF($vuser)") ) {
			$result = true;
		} else {
			$result = false;
		}
		@i5_close($this->conn);

		return $result;
	}


	/**
	 *
	 * Enter description here ...
	 * @param string $vnom
	 * @param string $vprenom
	 * @param string $vid
	 * @return boolean
	 */
	function update_vvfidpersusers($vnom,$vprenom,$vid) {
		$updt = $this->query_AS400("SELECT CLIZK6,CLIL FROM CECILE/COPHYCLI  WHERE STDOS='399' AND STGRP='1' AND CLIRS1 LIKE 'NOUVEAU%'  AND CLIRS2 LIKE 'FICHE%' AND CLIZK6 LIKE '929998%' ORDER BY CLIZK6 ASC FETCH FIRST 1 ROWS ONLY");
		//$updt = $this->query_AS400("select * from (select clirs1, clirs2,clizk6 from pgmcomet/cophycli  where stgrp='1' and stdos='399')a inner join (select * from  vvbase/vvrecusers )b on trim(a.clizk6)=trim(b.numfid)");

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'CECILE;VVBASE',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn_updt = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {





			i5_transaction(I5_ISOLEVEL_NONE,$conn_updt);

			//$res =  array();
			//for ($i=0;$i<count($updt);$i++) {
			@i5_query("UPDATE CECILE/COPHYCLI SET CLICNF='O' , CLIZK3='I' , CLIRS1='".$vnom."' , CLIRS2='".$vprenom."' WHERE STDOS='399' AND STGRP='1'  AND CLIL='".$updt[0]["CLIL"]."'");
			@i5_query("UPDATE VVBASE/VVCPBADGES SET NUMN='".$updt[0]["CLIZK6"]."' , ETAT='ATTN'  WHERE ID='".$vid."'");
				//@i5_query("UPDATE  VVBASE/VVRECUSERS SET NUMFID='".$updt[$i]["CLIZK6"]."' WHERE UPPER(NOM)='".strtoupper($updt[$i]["CLIRS1"])."' AND UPPER(PRENOM)='".strtoupper($updt[$i]["CLIRS2"])."'");
				//array_push($res,"UPDATE  VVBASE/VVRECUSERS SET NUMFID='".$updt[$i]["CLIZK6"]."' WHERE UPPER(NOM)='".strtoupper($updt[$i]["CLIRS1"])."' AND UPPER(PRENOM)='".strtoupper($updt[$i]["CLIRS2"])."'");
			//}


			$result = !(i5_commit($conn_updt));

			if (!i5_close($conn_updt)) {
				return false;
			}

			return $result;
		}
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $vnom
	 * @param string $vprenom
	 * @param string $vid
	 * @return boolean
	 */
	function update_vvfidcardpro($vnom,$vid) {
		$updt = $this->query_AS400("SELECT CLIZK6,CLIL FROM CECILE/COPHYCLI  WHERE STDOS='399' AND STGRP='1' AND CLIRS1 LIKE 'NOUVEAU%'  AND CLIRS2 LIKE 'FICHE%' AND CLIZK6 LIKE '929998%' ORDER BY CLIZK6 ASC FETCH FIRST 1 ROWS ONLY");
		//$updt = $this->query_AS400("select * from (select clirs1, clirs2,clizk6 from pgmcomet/cophycli  where stgrp='1' and stdos='399')a inner join (select * from  vvbase/vvrecusers )b on trim(a.clizk6)=trim(b.numfid)");

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'CECILE;VVBASE',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn_updt = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {





			i5_transaction(I5_ISOLEVEL_NONE,$conn_updt);

			//$res =  array();
			//for ($i=0;$i<count($updt);$i++) {
			@i5_query("UPDATE CECILE/COPHYCLI SET CLICNF='P' , CLIZK3='I' , CLIRS1='".$vnom."'  WHERE STDOS='399' AND STGRP='1'  AND CLIL='".$updt[0]["CLIL"]."'");
			@i5_query("UPDATE VVBASE/VVCPPROS SET NUMN='".$updt[0]["CLIZK6"]."' , ETAT='ATTN'  WHERE ID='".$vid."'");
			//@i5_query("UPDATE  VVBASE/VVRECUSERS SET NUMFID='".$updt[$i]["CLIZK6"]."' WHERE UPPER(NOM)='".strtoupper($updt[$i]["CLIRS1"])."' AND UPPER(PRENOM)='".strtoupper($updt[$i]["CLIRS2"])."'");
			//array_push($res,"UPDATE  VVBASE/VVRECUSERS SET NUMFID='".$updt[$i]["CLIZK6"]."' WHERE UPPER(NOM)='".strtoupper($updt[$i]["CLIRS1"])."' AND UPPER(PRENOM)='".strtoupper($updt[$i]["CLIRS2"])."'");
			//}


			$result = !(i5_commit($conn_updt));

			if (!i5_close($conn_updt)) {
				return false;
			}

			return $result;
		}
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $bib
	 * @param string $vnom
	 * @param string $vprenom
	 * @param string $vid
	 * @return boolean
	 */
	function update_fidpersusers($bib,$vnom,$vprenom,$vid) {
		$updt = $this->query_AS400("SELECT CLIZK6,CLIL FROM ".$bib."/COPHYCLI  WHERE STDOS='399' AND STGRP='1' AND CLIRS1 LIKE 'NOUVEAU%'  AND CLIRS2 LIKE 'FICHE%' AND CLIZK6 LIKE '929998%' ORDER BY CLIZK6 ASC FETCH FIRST 1 ROWS ONLY");
		//$updt = $this->query_AS400("select * from (select clirs1, clirs2,clizk6 from pgmcomet/cophycli  where stgrp='1' and stdos='399')a inner join (select * from  vvbase/vvrecusers )b on trim(a.clizk6)=trim(b.numfid)");

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'$bib;VVBASE',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn_updt = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {





			i5_transaction(I5_ISOLEVEL_NONE,$conn_updt);

			//$res =  array();
			//for ($i=0;$i<count($updt);$i++) {
			@i5_query("UPDATE ".$bib."/COPHYCLI SET CLICNF='O' , CLIZK3='I' , CLIRS1='".$vnom."' , CLIRS2='".$vprenom."' WHERE STDOS='399' AND STGRP='1'  AND CLIL='".$updt[0]["CLIL"]."'");
			@i5_query("INSERT INTO ".$bib."/FCPHYCON (STGRP,STDOS,CONCIV,CONA,CONJ,CONM,CONNOM,CONPRE,CLIL,CONNUM,CONVIP,CONPRO) VALUES ('1','399','M','1900','01','01','".$vnom."','".$vprenom."','".$updt[0]["CLIL"]."','1','C','I')");
			@i5_query("UPDATE VVBASE/VVCPBADGES SET NUMN='".$updt[0]["CLIZK6"]."' , ETAT='ATTN'  WHERE ID='".$vid."'");
			//@i5_query("UPDATE  VVBASE/VVRECUSERS SET NUMFID='".$updt[$i]["CLIZK6"]."' WHERE UPPER(NOM)='".strtoupper($updt[$i]["CLIRS1"])."' AND UPPER(PRENOM)='".strtoupper($updt[$i]["CLIRS2"])."'");
			//array_push($res,"UPDATE  VVBASE/VVRECUSERS SET NUMFID='".$updt[$i]["CLIZK6"]."' WHERE UPPER(NOM)='".strtoupper($updt[$i]["CLIRS1"])."' AND UPPER(PRENOM)='".strtoupper($updt[$i]["CLIRS2"])."'");
			//}


			$result = !(i5_commit($conn_updt));

			if (!i5_close($conn_updt)) {
				return false;
			}

			return $result;
		}
	}
	/**
	 *
	 * Enter description here ...
	 * @param string $bib
	 * @param string $vnom
	 * @param string $vprenom
	 * @param string $vid
	 * @return boolean
	 */
	function update_fidcardpro($bib,$vnom,$vid) {
		$updt = $this->query_AS400("SELECT CLIZK6,CLIL FROM ".$bib."/COPHYCLI  WHERE STDOS='399' AND STGRP='1' AND CLIRS1 LIKE 'NOUVEAU%'  AND CLIRS2 LIKE 'FICHE%' AND CLIZK6 LIKE '929998%' ORDER BY CLIZK6 ASC FETCH FIRST 1 ROWS ONLY");
		//$updt = $this->query_AS400("select * from (select clirs1, clirs2,clizk6 from pgmcomet/cophycli  where stgrp='1' and stdos='399')a inner join (select * from  vvbase/vvrecusers )b on trim(a.clizk6)=trim(b.numfid)");

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'$bib;VVBASE',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn_updt = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {





			i5_transaction(I5_ISOLEVEL_NONE,$conn_updt);

			//$res =  array();
			//for ($i=0;$i<count($updt);$i++) {
			@i5_query("UPDATE ".$bib."/COPHYCLI SET CLICNF='P' , CLIZK3='I' , CLIRS1='".$vnom."'  WHERE STDOS='399' AND STGRP='1'  AND CLIL='".$updt[0]["CLIL"]."'");
			@i5_query("UPDATE VVBASE/VVCPPROS SET NUMN='".$updt[0]["CLIZK6"]."' , ETAT='ATTN'  WHERE ID='".$vid."'");
			//@i5_query("UPDATE  VVBASE/VVRECUSERS SET NUMFID='".$updt[$i]["CLIZK6"]."' WHERE UPPER(NOM)='".strtoupper($updt[$i]["CLIRS1"])."' AND UPPER(PRENOM)='".strtoupper($updt[$i]["CLIRS2"])."'");
			//array_push($res,"UPDATE  VVBASE/VVRECUSERS SET NUMFID='".$updt[$i]["CLIZK6"]."' WHERE UPPER(NOM)='".strtoupper($updt[$i]["CLIRS1"])."' AND UPPER(PRENOM)='".strtoupper($updt[$i]["CLIRS2"])."'");
			//}


			$result = !(i5_commit($conn_updt));

			if (!i5_close($conn_updt)) {
				return false;
			}

			return $result;
		}
	}



	function readFtpDirFile($srvip,$login,$passw,$filename) {

	 	$ftp_server = $srvip;
        $ftp_user = $login;
        $ftp_passwd = $passw;

        $result = false;
        require_once "ftp.api.php";
        if ( $ftp = ftp_connect($ftp_server) ) {
            if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {
            	if ( strstr($filename,'|')==false ) {
            	 	$result = ftp_get($ftp,$filename,$filename,FTP_BINARY);
            	 	$filetochk = $filename;
            	} else {
	            	$dirs = explode("|",$filename);
	            	$nbdirs = count($dirs);
	            	for($i=0;$i<=($nbdirs-2);$i++) {
	            		ftp_chdir($ftp,$dirs[$i]);
	            	}
	            	$result = ftp_get($ftp,$dirs[$nbdirs-1],$dirs[$nbdirs-1],FTP_BINARY);
	            	$filetochk = $dirs[$nbdirs-1];
            	}

            } else {
            	$result = "erreur d'authentification ";
            }

        } else {
        	   $result = "erreur de connection";
        }

        ftp_close($ftp);


	 if (file_exists("./".$filetochk)) {
		$result = new ByteArray(file_get_contents("./".$filetochk));
	 }

	 return $result;

	}


	function readFtpDirFileNo($srvip,$login,$passw,$filename) {

	 	$ftp_server = $srvip;
        $ftp_user = $login;
        $ftp_passwd = $passw;

        $result = false;
        require_once "ftp.api.php";
        if ( $ftp = ftp_connect($ftp_server) ) {
            if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {
            	if ( strstr($filename,'|')==false ) {
            	 	$result = ftp_get($ftp,$filename,$filename,FTP_BINARY);
            	 	$filetochk = $filename;
            	} else {
	            	$dirs = explode("|",$filename);
	            	$nbdirs = count($dirs);
	            	for($i=0;$i<=($nbdirs-2);$i++) {
	            		ftp_chdir($ftp,$dirs[$i]);
	            	}
	            	$result = ftp_get($ftp,$dirs[$nbdirs-1],$dirs[$nbdirs-1],FTP_BINARY);
	            	$filetochk = $dirs[$nbdirs-1];
            	}

            } else {
            	$result = "erreur d'authentification ";
            }

        } else {
        	   $result = "erreur de connection";
        }

        ftp_close($ftp);


	 //if (file_exists("./".$filetochk)) {
		$result = true;
	 //}

	 return $result;

	}

	function delFtpDirFile($srvip,$login,$passw,$filename) {

	 	$ftp_server = $srvip;
        $ftp_user = $login;
        $ftp_passwd = $passw;

        $result = false;
        require_once "ftp.api.php";
        if ( $ftp = ftp_connect($ftp_server) ) {
            if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {
            	if ( strstr($filename,'|')==false ) {
            	 	$result = ftp_delete($ftp,$filename);
            	 	//$filetochk = $filename;
            	} else {
	            	$dirs = explode("|",$filename);
	            	$nbdirs = count($dirs);
	            	for($i=0;$i<=($nbdirs-2);$i++) {
	            		ftp_chdir($ftp,$dirs[$i]);
	            	}
	            	$result = ftp_delete($ftp,$dirs[$nbdirs-1]);
	            	//$filetochk = $dirs[$nbdirs-1];
            	}

            } else {
            	$result = false;
            }

        } else {
        	   $result = false;
        }

        ftp_close($ftp);



	 return $result;

	}


	function infFtpDirFile($srvip,$login,$passw,$filename) {

	 	$ftp_server = $srvip;
        $ftp_user = $login;
        $ftp_passwd = $passw;

        $result = array();
        require_once "ftp.api.php";
        if ( $ftp = ftp_connect($ftp_server) ) {
            if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {
            	if ( strstr($filename,'|')==false ) {
            		array_push($result,date("r",ftp_mdtm($ftp,$filename)));
            		array_push($result,ftp_size($ftp,$filename));
            	 	ftp_get($ftp,$filename,$filename,FTP_BINARY);
            	 	$filetochk = $filename;
            	} else {
	            	$dirs = explode("|",$filename);
	            	$nbdirs = count($dirs);
	            	for($i=0;$i<=($nbdirs-2);$i++) {
	            		ftp_chdir($ftp,$dirs[$i]);
	            	}
	            	array_push($result,date("r",ftp_mdtm($ftp,$dirs[$nbdirs-1])));
            		array_push($result,ftp_size($ftp,$dirs[$nbdirs-1]));
	            	ftp_get($ftp,$dirs[$nbdirs-1],$dirs[$nbdirs-1],FTP_BINARY);
	            	$filetochk = $dirs[$nbdirs-1];
            	}

            } else {
            	$result = "erreur d'authentification ";
            }

        } else {
        	   $result = "erreur de connection";
        }

        ftp_close($ftp);

		if (file_exists("./".$filetochk)) {
			array_push($result,new ByteArray(file_get_contents("./".$filetochk)));
		}


	 return $result;

	}



	function rFtpFileToFTP($srvip,$login,$passw,$filename,$srvipdest,$logindest,$passwdest,$filedest) {

	 	$ftp_server = $srvip;
        $ftp_user = $login;
        $ftp_passwd = $passw;

        $ftp_server_dest = $srvipdest;
        $ftp_user_dest = $logindest;
        $ftp_passwd_dest = $passwdest;

        $result = false;
        require_once "ftp.api.php";
        if ( $ftp = ftp_connect($ftp_server) ) {
            if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {
            	if ( strstr($filename,'|')==false ) {
            	 	$result = ftp_get($ftp,$filename,$filename,FTP_BINARY);
            	 	$filetochk = $filename;
            	} else {
	            	$dirs = explode("|",$filename);
	            	$nbdirs = count($dirs);
	            	for($i=0;$i<=($nbdirs-2);$i++) {
	            		ftp_chdir($ftp,$dirs[$i]);
	            	}
	            	$result = ftp_get($ftp,$dirs[$nbdirs-1],$dirs[$nbdirs-1],FTP_BINARY);
	            	$filetochk = $dirs[$nbdirs-1];
            	}

            } else {
            	$result = "erreur d'authentification ";
            }

        } else {
        	   $result = "erreur de connection";
        }

        //ftp_delete($ftp,$filetochk);

        ftp_close($ftp);


	 if (file_exists("./".$filetochk)) {
		 if ( $ftpdest = ftp_connect($ftp_server_dest) ) {
	            if (ftp_login($ftpdest,$ftp_user_dest,$ftp_passwd_dest)) {
	            	if ( strstr($filedest,'|')==false ) {
	            		//$pos=ftp_size($ftpdest,$filedest);
	            	 	$result = ftp_put($ftpdest,$filedest,$filetochk,FTP_BINARY);
	            	 	//$filetochk = $filename;
	            	} else {
		            	$dirs = explode("|",$filedest);
		            	$nbdirs = count($dirs);
		            	for($i=0;$i<=($nbdirs-2);$i++) {
		            		ftp_chdir($ftpdest,$dirs[$i]);
		            	}
		            	//$pos=ftp_size($ftpdest,$dirs[$nbdirs-1]);
		            	$result = ftp_put($ftpdest,$dirs[$nbdirs-1],$filetochk,FTP_BINARY);
		            	//$filetochk = $dirs[$nbdirs-1];
	            	}

	            } else {
	            	$result = "erreur d'authentification ";
	            }

	        } else {
	        	   $result = "erreur de connection";
	        }
	 	ftp_close($ftpdest);

		$result = true;
	 }

	 return $result;

	}

	/**
	 *
	 * Enter description here ...
	 * @return boolean
	 */
	function mailGIEupdate() {
		include 'Net/SSH2.php';
		$result = false;

		$ssh = new Net_SSH2('10.2.100.100');
		if (!$ssh->login('root', 'indianflute')) {
			$result = false;
		}

		$result=$ssh->exec('/var/www/html/annuaire/vvmajannuaire.sh');

		$result = true;

		return $result;
		//echo $ssh->exec('ls -la');
	}


	/**
	 *
	 * Enter description here ...
	 * @return array
	 */
	function mailListe() {
		include 'Net/SSH2.php';

		$ssh = new Net_SSH2('10.2.100.100');
		if (!$ssh->login('root', 'indianflute')) {
			$result = 'Login Failed';
		}

		$result=$ssh->exec("cat /var/www/html/annuaire/contact.dat");

		$tab = explode("\n",utf8_encode($result));

		$chps = explode("\t",utf8_encode($tab[0]));


		for ($i=0;$i<count($tab)-1;$i++) {
			$res[$i]= explode("\t",$tab[$i]);
		}



		return $res;
	}

	/**
	 *
	 * Enter description here ...
	 * @return array
	 */
	function mailListeInit() {
		include 'Net/SSH2.php';

		$ssh = new Net_SSH2('10.2.100.100');
		if (!$ssh->login('root', 'indianflute')) {
			$result = 'Login Failed';
		}

		$result=$ssh->exec("cat /var/www/html/annuaire/contact.dat");

		$tab = explode("\n",utf8_encode($result));

		$chps = explode("\t",utf8_encode($tab[0]));

	    $this->reset_vvusers();
	    $this->reset_vvrecusers();

		for ($i=0;$i<count($tab)-1;$i++) {
			$strres = explode("\t",$tab[$i]);
			$res[$i] = "INSERT INTO VVBASE/VVUSERS (SITENAME,NOM,PRENOM,EMAIL) VALUES ('".$strres[0]."' , '".strtoupper($strres[3])."' , '".strtoupper($strres[2])."' , '".$strres[1]."')";

		}

		$resultat=$this->query_array_insert($res);

		return $resultat;
	}

	function annuaireUpdate($tab) {
		include 'Net/SSH2.php';

		$ssh = new Net_SSH2('10.2.100.100');
		if (!$ssh->login('root', 'indianflute')) {
			$result = 'Login Failed';
		}

		//

		//$tab = explode("\n",utf8_encode($result));

		//$chps = explode("\t",utf8_encode($tab[0]));
		$lignes = array();

		for ($i=0;$i<count($tab)-1;$i++) {
			$lignes[$i] = implode("\t",$tab[$i]);
			//$res[$i] = "INSERT INTO VVBASE/VVUSERS (SITENAME,NOM,PRENOM,EMAIL) VALUES ('".$strres[0]."' , '".strtoupper($strres[3])."' , '".strtoupper($strres[2])."' , '".$strres[1]."')";

		}

		$filestr = implode("\n",$lignes);
		$ssh->exec("cp /var/www/html/annuaire/contact.dat /var/www/html/annuaire/contact_sav.dat");
		$ssh->exec("echo '".$filestr."' > /var/www/html/annuaire/contact.dat");

		$result=$ssh->exec('/var/www/html/annuaire/vvmajannuaire.sh');

		return $resultat;
	}

	/**
	 *
	 * Enter description here ...
	 * @return boolean
	 */
	function savenew_vvrecusers() {
		$updt = $this->query_AS400("select a.SITENAME,a.NOM,a.PRENOM,a.EMAIL from (select * from vvbase/vvusers)a left join  (select * from vvbase/vvrecusers)b on upper(a.nom)=upper(b.nom) and  upper(a.prenom)=upper(b.prenom) where b.prenom is null and b.nom is null ");

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {




			i5_transaction(I5_ISOLEVEL_NONE,$conn);

			for ($i=0;$i<count($updt);$i++) {

				i5_query("INSERT INTO VVBASE/VVRECUSERS (SITENAME,NOM,PRENOM,EMAIL) VALUES ('".$updt[$i]["SITENAME"]."' , '".$updt[$i]["NOM"]."' , '".$updt[$i]["PRENOM"]."' , '".$updt[$i]["EMAIL"]."')");
			}


			$result = !(i5_commit($conn));

			if (!i5_close($conn)) {
				return false;
			}

			return $result;
		}
	}

	function getLstTktFid($mag,$annee,$mois) {
		$result = $this->query_AS400("SELECT ecjj  FROM pgmcomet/ctphyecr where stgrp='1' and stdos='399' and ecanal='$mag' and ecaa='$annee' and ecmm='$mois' and jano='710' and plno like 'C%'  group by ecjj order by ecjj asc");
		return $result;

	}


	/**
	 *
	 * Enter description here ...
	 * @return boolean
	 */
	function update_vvrecusers() {
		$updt = $this->query_AS400("select * from (select clirs1, clirs2,clizk6 from pgmcomet/cophycli  where stgrp='1' and stdos='399')a inner join (select * from  vvbase/vvrecusers )b on upper(a.clirs1)=upper(b.nom) and upper(a.clirs2)=upper(b.prenom)");
		//$updt = $this->query_AS400("select * from (select clirs1, clirs2,clizk6 from pgmcomet/cophycli  where stgrp='1' and stdos='399')a inner join (select * from  vvbase/vvrecusers )b on trim(a.clizk6)=trim(b.numfid)");

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn_updt = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {





			i5_transaction(I5_ISOLEVEL_NONE,$conn_updt);

			//$res =  array();
			for ($i=0;$i<count($updt);$i++) {

				@i5_query("UPDATE  VVBASE/VVRECUSERS SET NUMFID='".$updt[$i]["CLIZK6"]."' WHERE UPPER(NOM)='".strtoupper($updt[$i]["CLIRS1"])."' AND UPPER(PRENOM)='".strtoupper($updt[$i]["CLIRS2"])."'");
				//array_push($res,"UPDATE  VVBASE/VVRECUSERS SET NUMFID='".$updt[$i]["CLIZK6"]."' WHERE UPPER(NOM)='".strtoupper($updt[$i]["CLIRS1"])."' AND UPPER(PRENOM)='".strtoupper($updt[$i]["CLIRS2"])."'");
			}


			$result = !(i5_commit($conn_updt));

			if (!i5_close($conn_updt)) {
				return false;
			}

			return $result;
		}
	}


	/**
	 *
	 * Enter description here ...
	 * @return boolean
	 */
	function reset_vvusers() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {


			i5_transaction(I5_ISOLEVEL_NONE,$conn);

			//for ($i=0;$i<count($sqlstr);$i++) {

			i5_query("DROP TABLE VVBASE/VVUSERS");
			i5_query("create table VVBASE/VVUSERS (
					ID INT generated always as identity
					(start with 1 increment by 1 cycle),
					SITENAME VARCHAR(50),
					NOM VARCHAR(50),
					PRENOM VARCHAR(50),
					EMAIL VARCHAR(50),
					Primary key (ID))");
			//}


			$result = !(i5_commit($conn));

			if (!i5_close($conn)) {
				return false;
			}

			return $result;
		}
	}









	/**
	 *
	 * Enter description here ...
	 * @return boolean
	 */
	function reset_vvimpusers() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {


			i5_transaction(I5_ISOLEVEL_NONE,$conn);

			//for ($i=0;$i<count($sqlstr);$i++) {

			i5_query("DROP TABLE VVBASE/VVIMPUSERS");
			i5_query("create table VVBASE/VVIMPUSERS (
					ID INT generated always as identity
					(start with 1 increment by 1 cycle),
					NOM VARCHAR(50),
					PRENOM VARCHAR(50),
					FONCTION VARCHAR(50),
					NUMFID VARCHAR(15),
					Primary key (ID))");
			//}


			$result = !(i5_commit($conn));

			if (!i5_close($conn)) {
				return false;
			}

			return $result;
		}
	}

	/**
	 *
	 * Enter description here ...
	 * @return boolean
	 */
	function reset_vvrecusers() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {


			i5_transaction(I5_ISOLEVEL_NONE,$conn);

			//for ($i=0;$i<count($sqlstr);$i++) {

			i5_query("DROP TABLE VVBASE/VVRECUSERS");
			i5_query("create table VVBASE/VVRECUSERS (
					ID INT generated always as identity
					(start with 1 increment by 1 cycle),
					SITENAME VARCHAR(50),
					NOM VARCHAR(50),
					PRENOM VARCHAR(50),
					EMAIL VARCHAR(50),
					NUMFID VARCHAR(15),
					FONCTION VARCHAR(50),
					Primary key (ID))");
			//}


			$result = !(i5_commit($conn));

			if (!i5_close($conn)) {
				return false;
			}

			return $result;
		}
	}

	function readXlsx() {
		/** PHPExcel */
		require_once 'Classes/PHPExcel.php';
		/**
		// Création de l'objet Reader pour un fichier Excel 2007
		$objReader = new PHPExcel_Reader_Excel2007();
		// Permet de ne récupérer que les valeurs des cellules sans les propriétés de style
		$objReader->setReadDataOnly(true);
		// Lecture du fichier.
		$objPHPExcel = $objReader->load("./herve.xlsx");
		**/

		// Si on ignore le format du fichier, utiliser PHPExcel_IOFactory
		/**  **/
		$inputFileName = "./noms_cartes.xls";
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		/** Load $inputFileName to a PHPExcel Object  **/
		$objPHPExcel = $objReader->load($inputFileName);


		$arr_data = array();
		$total_sheets=$objPHPExcel->getSheetCount(); // here 4
		$allSheetName=$objPHPExcel->getSheetNames(); // array ([0]=>'student',[1]=>'teacher',[2]=>'school',[3]=>'college')
		$objWorksheet = $objPHPExcel->setActiveSheetIndex(0); // first sheet
		$highestRow = $objWorksheet->getHighestRow(); // here 5
		$highestColumn = $objWorksheet->getHighestColumn(); // here 'E'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  // here 5
		for ($row = 1; $row <= $highestRow; $row++) {
			for ($col = 0; $col <= $highestColumnIndex; $col++) {
				$value=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
				if (is_array($arr_data) ) {
					$arr_data[$row-1][$col]=utf8_decode($value);
				}
			}
		}

		$run=$this->reset_vvimpusers();

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($connvv = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {




			i5_transaction(I5_ISOLEVEL_NONE,$connvv);
			for ($i=0;$i<count($arr_data)-1;$i++) {

				$strres = $arr_data[$i];
				i5_query("INSERT INTO VVBASE/VVIMPUSERS (NOM , PRENOM , FONCTION , NUMFID) VALUES ('".strtoupper($strres[0])."' , '".strtoupper($strres[1])."' , '".strtoupper(trim($strres[2]))."' , '".$strres[3]."')");

			}
			i5_commit($connvv);

			i5_close($connvv);

		}

		$result = $this->query_AS400("select b.nom, b.prenom , b.fonction, a.clizk6 as numfid, a.clicnf as carte from (select clirs1, clirs2, clizk6, clicnf from pgmcomet/cophycli where stgrp='1' and stdos='399')a inner join (select * from vvbase/vvimpusers )b on trim(a.clizk6)=trim(b.numfid)");

		//return $arr_data;
		return $result;


	}

	function sendCardsToprint() {
		$result = $this->putFtpBadgeFile("10.21.22.36","ftpbadges","ftpbadges","badges.xls","CartePERSONNEL_List.xls");
		return $result;
	}

	function sendCardsToprintPro() {
		$result = $this->putFtpBadgeFile("10.21.22.36","ftpbadges","ftpbadges","badges.xls","CartePRO_List.xls");
		return $result;
	}

	function cartesToXlsx($tab) {
		/** PHPExcel */
		require_once 'Classes/PHPExcel.php';


		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("Herve de CHAVIGNY")
		->setLastModifiedBy("Herve de CHAVIGNY")
		->setTitle("Office 2003 XLS Test Document")
		->setSubject("Office 2003 XLS Test Document")
		->setDescription("Test document for Office 2003 XLS, generated using PHP classes.")
		->setKeywords("office 2003 openxml php")
		->setCategory("Test result file");


		// Add some data

		$objPHPExcel->setActiveSheetIndex(0);
		$worksheet = $objPHPExcel->getActiveSheet();

		for ($row_index=0;$row_index<(count($tab));$row_index++) {
			for ($c=0;$c<count($tab[$row_index]);$c++) {
				$cell_value = ($tab[$row_index][$c]);
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
		$objPHPExcel->getActiveSheet()->setTitle('Cartes Personnel');




		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a clientâ€™s web browser (Excel2007)
		//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//header('Content-Disposition: attachment;filename="01simple.xlsx"');
		//header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('./badges.xls');
		//$objWriter->save('php://output');

		//$result= array();
		if (file_exists('badges.xls'))	 {
			//$res = new ByteArray(file_get_contents('herve.xlsx'));
			$result = true;
		} else {
			$result = false;
		}

		//array_push($result,$res);

		return $result;


	}


	function arrayToXlsx($tab) {
		/** PHPExcel */
		require_once 'Classes/PHPExcel.php';


		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("Herve de CHAVIGNY")
		->setLastModifiedBy("Herve de CHAVIGNY")
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

		$result= array();
		if (file_exists('herve.xlsx'))	 {
			$res = new ByteArray(file_get_contents('herve.xlsx'));
			//$result = true;
		} else {
			$res = false;
		}

		array_push($result,$res);

		return $result;


	}

	/**
	 *
	 * Enter description here ...
	 * @param array $sqlstr
	 * @return boolean
	 */
	function query_array_insert($sqlstr) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {


			i5_transaction(I5_ISOLEVEL_NONE,$conn);

			for ($i=0;$i<count($sqlstr);$i++) {

				i5_query(utf8_decode($sqlstr[$i]));
			}


			$result = !(i5_commit($conn));

			if (!i5_close($conn)) {
				return false;
			}

			return $result;
		}
	}


    function readCasFile($srvip,$login,$passw,$filename) {

	 $ftp_server = $srvip;
        $ftp_user = $login;
        $ftp_passwd = $passw;

        $result = false;
        require_once "ftp.api.php";
        if ( $ftp = ftp_connect($ftp_server) ) {
            if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {
   				 $result = ftp_get($ftp,$filename,$filename,FTP_BINARY);
            } else {
            	$result = "erreur d'authentification ";
            }

        } else {
        	   $result = "erreur de connection";
        }

        ftp_close($ftp);

	 if (file_exists("./".$filename)) {
		$result = file_get_contents("./".$filename);
	 }

	 return $result;

	}



    function readSonealFile($srvip,$login,$passw,$filename) {

	 $ftp_server = $srvip;
        $ftp_user = $login;
        $ftp_passwd = $passw;

        $result = false;
        require_once "ftp.api.php";
        if ( $ftp = ftp_connect($ftp_server) ) {
            if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {
            	ftp_chdir($ftp,"DEP");
            	ftp_chdir($ftp,"Soneal");
            	$result = ftp_get($ftp,$filename,$filename,FTP_BINARY);
            } else {
            	$result = "erreur d'authentification ";
            }
        } else {
        	   $result = "erreur de connection";
        }

        ftp_close($ftp);

		if (file_exists("./".$filename)) {
			$result = new ByteArray(file_get_contents("./".$filename));
	 	}

		return $result;

	}



	function openFtpDir($srvip,$login,$passw) {

	    $ftp_server = $srvip;
        $ftp_user = $login;
        $ftp_passwd = $passw;

        $result = false;

        require_once "ftp.api.php";
        if ( $ftp = ftp_connect($ftp_server) ) {
            if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {

            	$result = ftp_nlist($ftp,"");

            } else {
            	$result = "erreur d'authentification ";
            }

        } else {
        	   $result = "erreur de connection";
        }

        ftp_close($ftp);

	    return $result;

	}


	function lstSplf1000() {

		$result=`system "wrkoutq *all" | awk '{ if (($3 > 1000) && ($3 != 060210) && ($3 != "Fichiers")) {print $0}}'| awk '{ if ($5 == "")  print "<outq name=\""$1"\" bib=\""$2"\" pages=\""$3"\" editeur=\""$5"\" etat=\""$4"\" \/>";  else  print "<outq name=\""$1"\" bib=\""$2"\" pages=\""$3"\" editeur=\""$4"\" etat=\""$5"\" \/>"  }'`;
		return $result;

	}

	function ftpListDir($srvip,$login,$passw,$dir) {

	    $ftp_server = $srvip;
        $ftp_user = $login;
        $ftp_passwd = $passw;

        $result = false;

        require_once "ftp.api.php";

        if ( $ftp = ftp_connect($ftp_server) ) {
            if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {
            	$dirs = explode("|",$dir);
            	foreach ($dirs as $val) {
            		ftp_chdir($ftp,$val);
            	}

            	$result = ftp_nlist($ftp,"");
            } else {
            	$result = "erreur d'authentification ";
            }
        } else {
        	   $result = "erreur de connection";
        }

        ftp_close($ftp);
	   //$ftp->disconnect();

	    return $result;

	}


	function chgSplf($splfname,$numero,$utilisateur,$travail,$dest,$numfile) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource( $this->conn) ) {
			 if ( i5_command("CHGSPLFA FILE($splfname) JOB($numero/$utilisateur/$travail) SPLNBR($numfile)  OUTQ($dest)") )  {
			   $cmd = false;
			 } else {
			 	$cmd = true;
			 }
			@i5_close($this->conn);
		}
		return $cmd;
	}



	function sbmfidauto($socnum) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource( $this->conn) ) {
			$ret=@i5_command("SBMJOB CMD(CALL PGM(HHHPGM/VVFIDAUTO) PARM('$socnum')) JOB(VV_FID)  JOBQ(HDH/VVJOBQ) USER(HDH)");
			return  $ret;

			if (! i5_close ( $this->conn )) {
				return false;
			}
		}

	}

	function as400GetFromFTP($ip,$user,$passwd,$filename,$bib,$file400) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource ( $this->conn )) {
			//return "Connection reussie";
			i5_command("QSH CMD('echo ''$user $passwd\nget $filename $bib/$file400 (REPLACE\nquit'' > /qsys.lib/hhhpgm.lib/vvsrcprogs.file/ftpcmds.mbr ')");
			$res = i5_command("CALL HHHPGM/VVFTPCMD '$ip'");
			return $res;

			if (! i5_close ( $this->conn )) {
				// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
				return false;
			}
		}

	}

	function notExistPrt($imp) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource( $this->conn) ) {
			if ( i5_command("CHKOBJ OBJ($imp) OBJTYPE(*OUTQ)") )  {
				$cmd = false;
			} else {
				$cmd = true;
			}
			@i5_close($this->conn);
		}
		return $cmd;
	}



	function pegasTo309() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource( $this->conn) ) {
			//return "Connection reussie";
			///i5_command("QSH CMD('echo ''$user $passwd\nget $filename $bib/$file400 (REPLACE\nquit'' > /qsys.lib/hhhpgm.lib/vvsrcprogs.file/ftpcmds.mbr ')");
			$res = i5_command("CALL HHHPGM/VVCPT309");
			return $res;

			if (! i5_close ( $this->conn )) {
				// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
				return false;
			}
		}

	}

	function wrkfbib($bib,$filter) {

		$bib = strtoupper($bib);
		$filter = strtoupper($filter);
		$result = `/home/hdh/wrkfbib.sh $bib $filter`;
		return $result;

	}


	function filembr($bib,$file) {

		$bib = strtoupper($bib);
		$file = strtoupper($file);
		$result = `/home/hdh/filembr.sh $bib $file`;
		return $result;

	}


	function lstOutqXml($mask) {

		if ( strrpos($mask,'*')==false ) {

			if ($test=$this->notExistPrt($mask)) {
				return false;
			}
			$result = `system 'wrkoutq $mask'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /File/) && ($1 !~ /Fichier/) && ($2 !~ /Bib/) && ($1 != "*") ){ print "<splf name=\""$1"\" user=\""$2"\" ref=\""$3"\" etat=\""$4"\" pages=\""$5"\" expl=\""$6"\" papier=\""$7"\" pte=\""$8"\" numfile=\""$9"\" travail=\""$10"\" num=\""$11"\" spdate=\""$12"\" spheure=\""$13"\" \/>"  }}'`;
		} else {
			// "<outq name=\""$1"\" bib=\""$2"\" pages=\""$3"\" editeur=\""$4"\" etat=\""$5"\" \\>"
			$result = `system 'wrkoutq $mask'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /File/) && ($1 !~ /Fichier/)  && ($2 !~ /Bib/) && ($1 != "*") && ($0 != "") ){ print $1"\t"$2"\t"$3"\t"$4"\t"$5  }}' | awk '{ if ($5 == "")  print "<outq name=\""$1"\" bib=\""$2"\" pages=\""$3"\" editeur=\""$5"\" etat=\""$4"\" \/>";  else  print "<outq name=\""$1"\" bib=\""$2"\" pages=\""$3"\" editeur=\""$4"\" etat=\""$5"\" \/>"  }'`;
		}

		return "<root>\n$result</root>";
	}


	function lstOutqArr($mask) {

		if ( strrpos($mask,'*')==false ) {
			if ( $this->notExistPrt($mask)) {
				return false;
			}
			$result = `system 'wrkoutq $mask'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /File/) && ($1 !~ /Fichier/)  && ($2 !~ /Bib/) && ($1 != "*") ){ print $1"\t"$2"\t"$3"\t"$4"\t"$5"\t"$6"\t"$7"\t"$8"\t"$9"\t"$10"\t"$11"\t"$12"\t"$13  }}'`;
		} else {
			$result = `system 'wrkoutq $mask'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /File/) && ($1 !~ /Fichier/)  && ($2 !~ /Bib/) && ($1 != "*") && ($0 != "") ){ print $1"\t"$2"\t"$3"\t"$4"\t"$5 }}' | awk '{ if ($5 =="") print $1"\t"$2"\t"$3"\t"$5"\t"$4; else print $1"\t"$2"\t"$3"\t"$4"\t"$5 }'`;
		}

		$tab = explode("\n",$result);

		$chps = explode("\t",$tab[0]);

		/*if (count($chps)==13) {
			// des spools
			for ($i=0;$i<count($tab)-1;$i++) {
	           $res[$i]= explode("\t",$tab[$i]);
        	}
        	$res=var_dump($res);

		} else {
			// des outqs*/
			for ($i=0;$i<count($tab)-1;$i++) {
	           $res[$i]= explode("\t",$tab[$i]);
        	}
        	//$res=var_dump($res);
		//}


		return $res;

	}

	function isDevPrt($imp) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource( $this->conn) ) {
			 if ( i5_command("DSPDEVD ".$imp) )  {
			   $cmd = true;
			 } else {
			   $cmd = false;
			 }
			 @i5_close($this->conn);
		}
		return $cmd;
	}

	function getIpImp($imp) {
		$resip="";
		if ($this->isDevPrt($imp)) {
			$resip=$this->getIpDevPrt($imp);
		} else {
			$resip=$this->getIpRmtPrt($imp);
		}
		return $resip;
	}

	function getIpRmtPrt($imp) {
		if (is_resource( $this->conn) ) {
			$testrmt=`system 'wrkoutqd $imp'|grep -E '\. :'|grep 'Adresse Internet'|awk -F'   ' '{ print $2 }'|sed 's/ //g'|tr -d '\n'`;
			return $testrmt;
		}
	}

	function getIpDevPrt($imp) {
		if (is_resource($this->conn) ) {
			$testdev=`system 'dspdevd $imp'|grep -E '\. :'|grep 'ou adresse'|awk -F'               ' '{ print $2 }'|tr -d '\n'`;
			return $testdev;
		}
	}

	function getIpDevEco($magnum) {
		if (is_resource($this->conn) ) {
			$mag = 'ec02'.$magnum.'01';
			$testdev=`system 'dspdevd $mag'|grep -E '\. :'|grep 'Adresse'|awk -F'               ' '{ print $2 }'|tr -d '\n'`;
			return $testdev;
		}
	}

	function chkIp($ip) {
		$result=false;
		if (is_resource($this->conn)) {
			$ipt=sprintf("%s",$ip);
			$test="PING RMTSYS('$ipt') NBRPKT(6)";
			 $etat=`system "$test"|grep "successful"|awk -F'(' '{ print $2 }'|awk -F' ' '{print $1}'|tr -d '\n'`;
			 if (($etat != null) && ($etat!="0")) {
			 	$result = true;
			 }
		}
		return $result;
	}

	function getInfoWtr($imp) {
		$nb = `system "wrkwtr *all $imp" |wc -l|sed  's/ //g'|tr -d '\n'|awk '{ print $1}'`;
		if ( $nb == 4 ) {
			$infwtr = `system "wrkwtr *all $imp" |awk 'FNR==3 { print; }'|awk '{ if ($2 == "RMT") print $1";"$2";;"$3";"$4";"$5";"$6; else print $1";"$2";"$3";"$4";"$5";"$6";"$7 }' `;
			$result = explode(";",$infwtr);
		} else {
			$result = false;
		}

		return $result;
	}

	function startEditeur($imp) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource( $this->conn) ) {

			if ( i5_command("DSPDEVD ".$imp) )  {
				if ( i5_command("STRPRTWTR ".$imp) )  {
					$cmd = true;
				} else {
					$cmd = false;
				}
			} else {
				if ( i5_command("STRRMTWTR ".$imp) )  {
					$cmd = true;
				} else {
					$cmd = false;
				}
			}
			@i5_close($this->conn);
		}
		return $cmd;
	}

	function lstEdtMsgw() {

		$result = `system 'wrkwtr *all'|grep MSGW|awk '{ print $1"\t"$2"\t"$3"\t"$4"\t"$5"\t"$6"\t"$7 }'`;


		$tab = explode("\n",utf8_encode($result));

		$chps = explode("\t",utf8_encode($tab[0]));


		for ($i=0;$i<count($tab)-1;$i++) {
			$res[$i]= explode("\t",$tab[$i]);
		}

		return $res;
	}

	function dblqPrtMsgw($imp) {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource( $this->conn) ) {
				if ( i5_command("ENDWTR WTR(".$imp.") OPTION(*IMMED)") )  {
					if ( i5_command("DSPDEVD ".$imp) )  {
						if ( i5_command("STRPRTWTR ".$imp) )  {
							$cmd = true;
						} else {
							$cmd = false;
						}
					} else {
						if ( i5_command("STRRMTWTR ".$imp) )  {
							$cmd = true;
						} else {
							$cmd = false;
						}
					}
				} else {
					$cmd=false;
				}

		}
		@i5_close($this->conn);
		return $cmd;

	}


	function autoEdtMsgw() {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		$result = `system 'wrkwtr *all'|grep MSGW|awk '{ print $1"\t"$2"\t"$3"\t"$4"\t"$5"\t"$6"\t"$7 }'`;
		$tab = explode("\n",utf8_encode($result));
		$chps = explode("\t",utf8_encode($tab[0]));
		$cmd=false;
		for ($i=0;$i<count($tab);$i++) {
			$res[$i]= explode("\t",$tab[$i]);

			if (is_resource( $this->conn) ) {
				if ( i5_command("ENDWTR WTR(".$res[$i][0].") OPTION(*IMMED)") )  {
					if ( i5_command("DSPDEVD ".$res[$i][0]) )  {
						if ( i5_command("STRPRTWTR ".$res[$i][0]) )  {
							$cmd = true;
						} else {
							$cmd = false;
						}
					} else {
						if ( i5_command("STRRMTWTR ".$res[$i][0]) )  {
							$cmd = true;
						} else {
							$cmd = false;
						}
					}
				} else {
					$cmd=false;
				}

			}
			$cmd=true;
		}
		@i5_close($this->conn);

		return $cmd;
	}


	function listJobActifs() {
		$result = `system 'wrkjobq'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /File/) && ($1 !~ /Utilisateur/) && ($2 !~ /Utilisateur/) && ($1 !~ /Fichier/)  && ($2 !~ /Bib/) && ($1 != "*") && ($0 != "") && ($0 !~ /\./) ){ print $1"\t"$2"\t"$3"\t"$4"\t"$5 }}'  | awk '{ if ($5 =="") print $1"\t"$2"\t"$3"\t"$5"\t"$4; else print $1"\t"$2"\t"$3"\t"$4"\t"$5 }'|awk -F"\t" '{ if ($3 != 0) { print $0}}'`;


		$tab = explode("\n",utf8_encode($result));

		$chps = explode("\t",utf8_encode($tab[0]));


		for ($i=0;$i<count($tab)-1;$i++) {
			$res[$i]= explode("\t",$tab[$i]);
		}



		return $res;
	}

	function jobActif($jobname) {
		$result = `system 'wrkactjob'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /File/) && ($1 !~ /Utilisateur/) && ($2 !~ /Utilisateur/) && ($1 !~ /Fichier/)  && ($2 !~ /Bib/) && ($1 != "*") && ($0 != "") && ($0 !~ /\./) ){ print $1"\t"$2"\t"$3"\t"$4"\t"$5"\t"$6"\t"$7"\t"$8"\t"$9"\t"$10"\t"$11"\t"$12"\t"$13 }}' |grep $jobname`;


		$tab = explode("\n",utf8_encode($result));

		$chps = explode("\t",utf8_encode($tab[0]));


		for ($i=0;$i<count($tab)-1;$i++) {
			$res[$i]= explode("\t",$tab[$i]);
		}



		return $res;
	}

	function isJobActif($jobname) {
		$result = `system 'wrkactjob'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /File/) && ($1 !~ /Utilisateur/) && ($2 !~ /Utilisateur/) && ($1 !~ /Fichier/)  && ($2 !~ /Bib/) && ($1 != "*") && ($0 != "") && ($0 !~ /\./) ){ print $1"\t"$2"\t"$3"\t"$4"\t"$5"\t"$6"\t"$7"\t"$8"\t"$9"\t"$10"\t"$11"\t"$12"\t"$13 }}' |grep -c $jobname|tr -d "\n"`;

		if ( $result == "1") {
			$res = true;
		} else {
			$res = false;
		}



		return $res;
	}


	function isImpMsgw($imp) {
		$testmsgw = `system 'wrkwtr *all $imp'|grep -c MSGW`;
		$result = false;
		if ($testmsgw>=1) {
			$result = true;
		} else {
			$reult = false;
		}
		return $result;
	}


	function stopEditeur($imp) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource( $this->conn) ) {
			 if ( i5_command("ENDWTR WTR(".$imp.") OPTION(*IMMED)") )  {
			   $cmd = true;
			 } else {
			   $cmd = false;
			 }
			 @i5_close($this->conn);
		}
		return $cmd;
	}

	function holdEditeur($imp) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource( $this->conn) ) {
			 if ( i5_command("HLDWTR WTR(".$imp.") OPTION(*IMMED)") )  {
			   $cmd = true;
			 } else {
			   $cmd = false;
			 }
			 @i5_close($this->conn);
		}
		return $cmd;
	}


	function vvtecpgd() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
        if (is_resource ( $this->conn )) {
            if (i5_command("CALL",array("PGM" => "HHHPGM/VVTECPGD"),array(),$this->conn)) {
				$result = true;
			} Else {
				$result = "Error =".i5_errormsg();
			}
        }

        if (! i5_close ( $this->conn )) {
		// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
		return false;
	 }
	 return $result;

	}


	function doSendCmdsCas() {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET, QUSRSYS, HOHPGM',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		if (is_resource($conn)) {

			$cmd="CALL PGM(PGMCOMET/CASTECL01)";
			$result = @i5_command($cmd);
        }

        if (! i5_close($conn )) {
			return false;
		}

		return $result;

	}

	function doSendCmdsCasLoc() {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET, QUSRSYS, HOHPGM',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		if (is_resource($conn)) {


			$cmd="CALL PGM(HHHPGM/VVFTPLCAS)";
			$result = @i5_command($cmd);
        }

        if (! i5_close($conn )) {
			return false;
		}

		return $result;

	}


	function doSendCmdsCasFr() {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET, QUSRSYS, HOHPGM',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		if (is_resource($conn)) {

			//$exec=$this->delFtpDirFile("10.2.0.18","CASINO","CASINO","affcdexp.txt");

			$cmd="CALL PGM(HHHPGM/VVFTPCAS)";
			$result = @i5_command($cmd);


			$cmd1="CALL PGM(HHHPGM/VVFTPLCAS)";
			$result = @i5_command($cmd1);
        }

        if (! i5_close($conn )) {
			return false;
		}

		return $result;

	}

	function clearCmdsCas() {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET, QUSRSYS, HOHPGM',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		if (is_resource($conn)) {

			$cmd="CLRPFM FILE(PGMCOMET/AFFCDEXP) MBR(AFFCDEXP)";
			$result = @i5_command($cmd);
        }

        if (! i5_close($conn )) {
			return false;
		}

		return $result;

	}


	function mgedicasino($codecad) {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET, QUSRSYS, PGMNEW',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		if (is_resource($conn)) {

			$cmd="CALL PGM(PGMCOMET/MGEDICAS) PARM( '".$codecad."' 'EMG402125 ' )";
			$result = @i5_command($cmd);
			//$result = "CALL PGM(PGMCOMET/MGEDICAS) PARM( '".$codecad."' 'EMG402125 ' )";
        }

        if (! i5_close($conn )) {
			return false;
		}

		return $result;

	}


	function mgedicasino_new($codecad,$bibuser) {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET, QUSRSYS, PGMNEW',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		if (is_resource($conn)) {

			$cmd="CALL PGM(PGMCOMET/MGEDICAS) PARM('".$codecad."' 'EMG402125 ' '".$bibuser."')";
			//$cmd="CALL PGM(PGMCOMET/MGEDICAS) PARM('".$codecad."' 'EMG402125 ' 'GIEPRCE')";
			$result = @i5_command($cmd);

        	}

        	if (! i5_close($conn )) {
			return false;
		}

		return $result;

	}



	function isConnect($user,$pass) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB');
		if ($conn = @i5_connect ( '127.0.0.1', $user, $pass, $connection_parameters )) {
			$_SESSION['user'] = $user;
			//$_SESSION['groupe'] = $pass;
			return true;

		} else {
			return false;
		}
	}



	function iconnect($user, $pass) {
		$_SESSION ['user'] = $user;
		$_SESSION ['pass'] = $pass;
		// Set connection parameters
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB');

		if ($conn = @i5_connect ( '127.0.0.1', $user, $pass, $connection_parameters )) {

			$this->conn = $conn;

			if (is_resource ( $this->conn )) {
				// ENTER YOUR CODE HERE!
				return "Connection reussie";
				if (! i5_close ( $this->conn )) {
					// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
					return "Erreur de connection : " . i5_errormsg ();
				}
			} else {
				// Connection to i5 server failed, use i5_errormsg() to get the failure reason
				return "Erreur de connection : " . i5_errormsg ();
			}
		} else {
			return "Erreur de connection : " . i5_errormsg ();
		}
	}


	function iwrkactjob() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );;
		if (is_resource ( $this->conn )) {
			//return "Connection reussie";
			$res = `system "wrkactjob" `;

			return $res;

			if (! i5_close ( $this->conn )) {
				// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
				return false;
			}
		}
	}



	function isystem($cmdsys) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource ( $this->conn )) {
			// ENTER YOUR CODE HERE!


			//return "Connection r�ussie";
			$res = `system "$cmdsys" `;
			//$ar = array();
			$result = preg_split("/[G]+[e]/", $res);
			//$result = explode('\t',$res);
			return $result;

			if (! i5_close ( $this->conn )) {
				// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
				return "Erreur de connection : " . i5_errormsg ();
			}
		}
	}
    function cmdsystem($cmdsys) {
        $connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
        if (is_resource ( $this->conn )) {
            //return "Connection r�ussie";
            $res = `system "$cmdsys"`;

            return $res;

            if (! i5_close ( $this->conn )) {
                // Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
                return "Erreur de connection : " . i5_errormsg ();
            }
        }
    }

	function isysval($sysval) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource ( $this->conn )) {

			return i5_get_system_value ( $sysval );

			if (! i5_close ( $this->conn )) {
				// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
				return "Erreur de connection : " . i5_errormsg ();
			}
		}
	}

	function rtvusrprf($user) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource ( $this->conn )) {
			i5_command("DSPUSRPRF USRPRF($user) OUTPUT(*OUTFILE) OUTFILE(QTEMP/USERFILE)");
            $file = i5_open('QTEMP/USERFILE');
            $result = i5_fetch_row($file);
            @i5_close($this->conn);
		}
		return $result;
		i5_free_file($result);
	}

    function rtvallusrprf() {
        if (is_resource ( $this->conn )) {
            i5_command("DSPUSRPRF USRPRF(*ALL) OUTPUT(*OUTFILE) OUTFILE(QTEMP/USERFILE)");
            $file = i5_open('QTEMP/USERFILE');
            $result = array();
            $i=0;
            while ($res = i5_fetch_row($file) ) {
                $result[$i] = i5_fetch_row($file);
                $i++;
            }
            i5_free_file($result);
            @i5_close($this->conn);
        }
        return $result;

    }

    function rtvallsql() {
        if (is_resource ( $this->conn )) {
        	//$sql = " ";
            i5_command("QSH CMD('db2 select * from pgmcomet.cophyfou where stdos=''800'' > /qsys.lib/qtemp.lib/userfile.userfile ')");
            $file = i5_open('QTEMP/USERFILE');
            $result = array();
            $i=0;
            while ($res = i5_fetch_row($file) ) {
                $result[$i] = i5_fetch_row($file);
                $i++;
            }

        }
        return $result;
        i5_free_file($result);
    }

	function icrtrmtprt() {
		if (is_resource ( $this->conn )) {
            $ret = i5_command ("rtvusrprf",array("USRPRF"=>"HDH"),array("TEXT"=>"text","GRPAUT"=>"grpaut"),$this->conn);
	       $result = "Mon job :".$text." \nMon utilisateur :".$grpaut;

        }

		return $result;
	}



    function setOpsSpe($filename) {
    	if (is_resource ( $this->conn )) {
                $result=false;
                $file = i5_open("PCS399/$filename", I5_OPEN_READ|I5_OPEN_SHRRD, $this->conn );
                $row=i5_fetch_assoc($file,I5_READ_FIRST);
                $result = array();
                //$i=0;
                while ($row)  // while there is a row...
                {
                    $client=$row["SRCDTA"];
                    i5_query("UPDATE PGMCOMET/COPHYCLI SET CLIGU='OP001' WHERE STGRP='1' AND STDOS='399' AND CLIZK6='$client' ");
                    array_push($result,$row["SRCDTA"]);
                    // Get next row
                    $row = i5_fetch_assoc($file, I5_READ_NEXT);
                    /*if ($i==200) {
                        i5_free_query($query);
                        $i=0;
                    }
                    $i++;*/
                }
                //$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');
                //$conn1 = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
                /*for ($i=0;$i<(count($result));$i++) {
                    //i5_transaction(I5_ISOLEVEL_NONE,$conn1);
                    //i5_query("UPDATE PGMCOMET/COPHYCLI SET CLIGU='OP001' WHERE CLIZK6='".$result[$i]."'");
                    i5_command("QSH CMD('db2 UPDATE PGMCOMET/COPHYCLI SET CLIGU=''OP001'' WHERE CLIZK6=''".$result[$i]."''");
                    //i5_commit($conn1);
                    //$this->query_update("UPDATE PGMCOMET/COPHYCLI SET CLIGU='OP001' WHERE CLIZK6='$result[$i]'");
                }*/

                 i5_free_file($file);

                //$requete = "UPDATE FID400/FIDOPS SET INTEGRE='0'";
                //$query = i5_query($requete);
                /*if ($query) {
                    $result = true;
                } */


                return  $result;
                //i5_free_query($query);
                if (!i5_close($this->conn)) {
                    return "Erreur de connection";
                 }
        }


    }


    function readOperations($numcli) {
        if (is_resource ( $this->conn )) {
            $file = i5_open("PCS399/C".$numcli, I5_OPEN_READ|I5_OPEN_SHRRD, $this->conn );
            //return i5_fetch_array($file,I5_READ_FIRST);
            $row=i5_fetch_assoc($file,I5_READ_FIRST);

            $result = array();
            while ($row)  // while there is a row...
            {
                array_push($result,$row);
            	// Get next row
            	$row = i5_fetch_assoc($file, I5_READ_NEXT);

            } // loop

            i5_free_file($file);
            $this->isystem("DLTF PCS399/C".$numcli);
            return $result;

        }
    }

    function readReservations($numcli) {
    	if (is_resource ( $this->conn )) {

    		//@i5_command("ADDLIBLE PGMCOMET");
    		if ( @i5_command("CALL PGMCOMET/GIPRCRES ('$numcli')") ) {
    			$file = i5_open("PCS399/R".$numcli, I5_OPEN_READ|I5_OPEN_SHRRD, $this->conn );
    			//return i5_fetch_array($file,I5_READ_FIRST);
    			$row=i5_fetch_assoc($file,I5_READ_FIRST);

    			$result = array();
    			while ($row)  // while there is a row...
    			{
    				if (trim($row["ARTNO"]) != "REG") {
					array_push($result,$row);
    					// Get next row
				}
    					$row = i5_fetch_assoc($file, I5_READ_NEXT);


    			} // loop

    			i5_free_file($file);
    			$this->isystem("DLTF PCS399/R".$numcli);
    			return $result;

    		}
    	}
    }

	function qtes_ress_tecpro($numcde, $soc) {
		if (is_resource ( $this->conn )) {
			// code commande
			$query = i5_query ( "SELECT STGRP,STDOS,ECCSEC,ECCNUM,DCCQTR,DCCQTL FROM COPHYDCC WHERE STGRP='1' AND
			STDOS='$soc' AND ECCNUM='$numcde' AND ECCSEC='240'" );
			if (! $query) {
				return "Error code: " . i5_errno ( $query ) . "\n Error message: " . i5_errormsg ( $query ) . " ";
			} else {
				//$result=query2xml($query);
				//$result=query2array($query);
				$i = 0;
				while ( $values = i5_fetch_row ( $query, I5_READ_NEXT ) ) {
					$i ++;
					$l_xml_string .= "<item  ";
					for($j = 0; $j < i5_num_fields ( $query ); $j ++) {
						$l_xml_string .= strtolower ( stripslashes ( i5_field_name ( $query, $j ) ) ) . "=\"";
						$l_xml_string .= htmlspecialchars ( stripslashes ( $values [$j] ) ) . "\" ";
					}
					$l_xml_string .= " >";
					echo "";
					$l_xml_string .= "</item>\n";
				}
				$l_xml_string .= "</query>\n";
				i5_free_query ( $query );

				$e_xml_string = "<?xml version='1.0' encoding='UTF-8' ?> \n";
				$e_xml_string .= "<query numrows=\"" . $i . "\">\n" . $l_xml_string;
				$result = utf8_encode ( $e_xml_string );
			}

			if (! i5_close ( $this->conn )) {
				return "Erreur de connection";
			}

			return $result;
		}
	}

    function qtes_res_tecpro($numcde, $soc) {
		if (is_resource ( $this->conn )) {
			// code commande
			$query = i5_query ( "SELECT STGRP,STDOS,ECCSEC,ECCNUM,DCCQTR,DCCQTL FROM COPHYDCC WHERE STGRP='1' AND
			STDOS='$soc' AND ECCNUM='$numcde' AND ECCSEC='240'" );
			if (! $query) {
				return "Error code: " . i5_errno ( $query ) . "\n Error message: " . i5_errormsg ( $query ) . " ";
			} else {
				//$result=query2xml($query);
				//$result=query2array($query);
				$i = 0;
				$result = array ();

				while ( $values = i5_fetch_row ( $query, I5_READ_NEXT ) ) {

					$row = array ();
					for($j = 0; $j < i5_num_fields ( $query ); $j ++) {
						$key = strtolower ( stripslashes ( i5_field_name ( $query, $j ) ) );
						$val = htmlspecialchars ( stripslashes ( $values [$j] ) );
						$row ["$key"] = $val;
						//array_push($row,array("$key" => $val));


					}
					//array_push($result,$row);
					$result [$i] = $row;
					$i ++;
				}

				i5_free_query ( $query );

			}

			if (! i5_close ( $this->conn )) {
				return "Erreur de connection";
			}

			return $result;
		}
	}


	function query_multi03($requete) {
		if (is_resource ($this->conn)) {
			// code commande
			$query = i5_query ($requete);
			if (! $query) {
				return false;
			} else {
				$i = 0;
				$result = array ();

				while($values = i5_fetch_row($query,I5_READ_NEXT)) {

					$row = array ();
					for($j=0;$j<i5_num_fields($query);$j++) {
						//$key = strtolower ( stripslashes ( i5_field_name ( $query, $j ) ) );
						$key = strtolower(i5_field_name($query,$j));
						$val = (stripslashes($values[$j]));
						$row ["$key"] = $val;
						//array_push($row,array("$key" => $val));
					}
					//array_push($result,$row);
					$result[$i] = $row;
					$i ++;
				}

				i5_free_query($query);

			}

			if (!i5_close($this->conn)) {
				return "Erreur de connection";
			}

			return $result;
		}
	}

    function query_AS400($requete) {
    	$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn_qry400 = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
            // code commande
            $query = i5_query (utf8_decode($requete));
            if (! $query) {
                return false;
            } else {
                //$i = 0;
                $result = array ();

                while($values = i5_fetch_assoc($query,I5_READ_NEXT)) {

                    array_push($result,$values);
                    //$result[$i] = $row;
                    //$i ++;
                }

                i5_free_query($query);

            }

            if (!i5_close($conn_qry400)) {
                return false;
            }

            return $result;
        }
    }



    function pdfReservations($numresa) {
        if (is_resource ($this->conn)) {
            // code commande
            $query = i5_query ("select * from  fid400/fidresas  where numres='$numresa'");
            //i5_query ("select * from  pgmcomet/cophytrf  where (stdos='399') and (trffou='$numclient')  and (trfstk > '0') ");
            if (! $query) {
                $result=false;
            } else {
                //$result=query2xml($query);
                //$result=query2array($query);
                $i = 0;
                $result = array ();

                while($values = i5_fetch_row($query,I5_READ_NEXT)) {

                    $row = array ();
                    for($j=0;$j<i5_num_fields($query);$j++) {
                        //$key = strtolower ( stripslashes ( i5_field_name ( $query, $j ) ) );
                        $key = strtolower(i5_field_name($query,$j));
                        $val = utf8_encode(stripslashes($values[$j]));
                        $row ["$key"] = $val;
                        //array_push($row,array("$key" => $val));
                    }
                    //array_push($result,$row);
                    $result[$i] = $row;
                    $i ++;
                }

                i5_free_query($query);

            }

            if (!i5_close($this->conn)) {
                return false;
            }

            return $result;
        }
    }


    function query_points($requete) {
        if (is_resource ($this->conn)) {
            // code commande
            $query = i5_query ($requete);
            if (! $query) {
                return "Error code: ".i5_errno($query)."\n Error message: ".i5_errormsg($query)." ";
            } else {
                //$result=query2xml($query);
                //$result=query2array($query);
                $i = 0;
                $result = array ();

                while($values = i5_fetch_row($query,I5_READ_NEXT)) {

                    $row = array ();
                    for($j=0;$j<i5_num_fields($query);$j++) {
                        //$key = strtolower ( stripslashes ( i5_field_name ( $query, $j ) ) );
                        $key = strtolower(i5_field_name($query,$j));
                        $val = (stripslashes($values[$j]));
                        $row ["$key"] = $val;
                        //array_push($row,array("$key" => $val));
                    }
                    //array_push($result,$row);
                    $result[$i] = $row;
                    $i ++;
                }

                i5_free_query($query);

            }

            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }

            return $result;
        }
    }

	function fid_authAdd($user,$pass,$maga) {
        if (is_resource ($this->conn)) {
            // code commande
            $epass=$this->encrypt($pass);
            $requete = "INSERT INTO FID400/USERS  (USERN,PASSN,MAGA) VALUES('$user','$epass','$maga')";
            $query = i5_query($requete);
            $result = false;
            if (! $query) {
                return "Error code: ".i5_errno($query)."\n Error message: ".i5_errormsg($query)." ";
            } else {

                $result = true;

                i5_free_query($query);

            }

            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }

            return $result;
        }
    }

    function fid_newAdd($user,$pass,$maga,$nom,$prenom) {
        if (is_resource ($this->conn)) {
            // code commande
            $epass=$this->encrypt($pass);
            $requete = "INSERT INTO FID400/USERS  (USERN,PASSN,MAGA,NOM,PRENOM) VALUES('$user','$epass','$maga','$nom','$prenom')";
            $query = i5_query($requete);
            $result = false;
            if (! $query) {
                return "Error code: ".i5_errno($query)."\n Error message: ".i5_errormsg($query)." ";
            } else {

                $result = true;

                @i5_free_query($query);

            }

            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }

            return $result;
        }
    }

    function add_reserv($numres,$magres,$dateres,$datedelres,$usernres,$comres,$clires,$telres,$mobres,$emaires,$carteres,$artres,$libres,$partres) {
        if (is_resource ($this->conn)) {
            // code commande
            $requete = "INSERT INTO FID400/FIDRESAS  (NUMRES,MAGRES,DATERES,DATEDELRES,USERNRES,COMRES,CLIRES,TELRES,MOBRES,EMAIRES,CARTERES,ARTRES,LIBRES,PARTRES) VALUES('$numres','$magres','$dateres','$datedelres','$usernres','$comres','$clires','$telres','$mobres','$emaires','$carteres','$artres','$libres','$partres')";
            $query = i5_query($requete);
            $result = false;
            if (! $query) {
                return false;
            } else {

                $result = true;

                @i5_free_query($query);

            }

            if (!i5_close($this->conn)) {
                return false;
            }

            return $result;
        }
    }


    function fid_authMod($user,$pass,$maga,$nom,$prenom,$id) {
        if (is_resource ($this->conn)) {
            // code commande
            $epass=$this->encrypt($pass);

            $requete = "UPDATE  FID400/USERS  set USERN='".$user."' , PASSN='".$epass."', MAGA='".$maga."', NOM='".$nom."', PRENOM='".$prenom."'  WHERE id='".$id."'";

            i5_transaction(I5_ISOLEVEL_NONE,$this->conn);

            i5_query($requete);


            $result = !(i5_commit($this->conn));

            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }

            return $result;

        }
    }


	function maj_estampages($clients,$ldate) {
        if (is_resource ($this->conn)) {

            i5_transaction(I5_ISOLEVEL_NONE,$this->conn);

 	     $arclients = explode(',',$clients);

	     for($i = 0; $i < count($arclients); $i++){
		//$result .= "UPDATE  PGMCOMET/COPHYCLI  set CLITLF='".$ldate."' , CLITLX=''  WHERE STGRP='1' and STDOS='399' AND CLIZK6='".$arclients[$i]."'";
            	i5_query("UPDATE  PGMCOMET/COPHYCLI  set CLITLF='".$ldate."' , CLITLX=''  WHERE STGRP='1' and STDOS='399' AND CLIZK6='".$arclients[$i]."'");
	     }




            $result = !(i5_commit($this->conn));

            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }

            return $result;

        }
     }


	function fid_passMod($user,$pass) {
        if (is_resource ($this->conn)) {
            // code commande
            $epass=$this->encrypt($pass);

            $requete = "UPDATE  FID400/USERS  set USERN='".$user."' , PASSN='".$epass."' WHERE USERN='".$user."'";

            i5_transaction(I5_ISOLEVEL_NONE,$this->conn);

            i5_query($requete);


            $result = !(i5_commit($this->conn));

            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }

            return $result;

        }
    }

    function fid_nompreMod($user,$nom,$prenom) {
        if (is_resource ($this->conn)) {
            // code commande
            //$epass=$this->encrypt($pass);

            $requete = "UPDATE  FID400/USERS  set NOM='".$nom."' , PRENOM='".$prenom."' WHERE USERN='".$user."'";

            i5_transaction(I5_ISOLEVEL_NONE,$this->conn);

            i5_query($requete);


            $result = !(i5_commit($this->conn));

            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }

            return $result;

        }
    }

    function fid_auth($user,$pass) {
        if (is_resource ($this->conn)) {
            // code commande
            $requete = "select  usern,passn,maga,nom,prenom from fid400/users  where usern='".$user."' ";
            $query = i5_query ($requete);

            if (trim($pass)=="") {
            	return false;
            }
            if (! $query) {
                $result = false;
            } else {

                $values = i5_fetch_row($query,I5_READ_NEXT);

                @i5_free_query($query);

                if ( $this->decrypt($values[1]) == $pass ) {

                	   $result = $values[2]."|".$values[3]."|".$values[4];

                } else {
                	$result = false;
                }

            }

            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }

            return $result;
        }
    }

    function verif_session($usern) {  ///   pas utiliser
        if (is_resource ($this->conn)) {
            // code commande
            $idsession = session_id();
            $requete = "SELECT SESSID FROM FID400/SESSIONS WHERE  SESSID='".$idsession."' ";

            $query = i5_query ($requete);
            if (! $query) {
                return "Error code: ".i5_errno($query)."\n Error message: ".i5_errormsg($query)." ";
            } else {

                $values = i5_fetch_row($query,I5_READ_NEXT);

                $result = $values[0];

                $datecon = date("d/m/Y");
                $heucon = date("H:i",strtotime("-4 hours"));

                if (! $result) {
                	// c'est une nouvelle session

                	//i5_transaction(I5_ISOLEVEL_CHG,$this->conn);
                	$requete2 = "DELETE FROM FID400/SESSIONS WHERE USERN='".$usern."' ";
                    $query2 = i5_query ($requete2);
                    //return $requete2;
                    if ($query2) {
	                	$requete1 = "INSERT INTO FID400/SESSIONS (USERN,DATECON,HEUCON,MINCON,SESSID) VALUES ('".$usern."','".$datecon."','".$heucon."','0','".$idsession."') ";
	                    $query1 = i5_query ($requete1);
	                    if ($query1) {
	                    	i5_free_query($query1);
	                    }
                    }
                   $result = false;
                	//return $idsession;
                } else {
                	$result = true;
                }

                @i5_free_query($query);


            }

            return $result;
        }

    }

    function fid_deconuser($user) {  /// pas utiliser
    	if (is_resource ($this->conn)) {
            // code commande
            $idsession = session_id();
            $requete = "DELETE FROM FID400/SESSIONS WHERE USERN='".$usern."' AND SESSID='".$idsession."'";
            $query = i5_query ($requete);
            if (! $query) {
                return "Error code: ".i5_errno($query)."\n Error message: ".i5_errormsg($query)." ";
            } else {
                $result = true;
                @i5_free_query($query);
            }
            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }
            return $result;
    	}

    }


    function array2file400($origarray,$bib,$fic) {
    	if (is_resource ( $this->conn )) {

	    	$clrfile=`system 'CLRPFM FILE($bib/$fic)'`;
	    	$clrfile1=@i5_command("CLRPFM FILE(".$bib."/".$fic.")");

	    	$fqry = @i5_open("$bib/$fic",I5_OPEN_READWRITE|I5_OPEN_EXCL,$this->conn);

	    	$nbfields = @i5_num_fields($fqry);

	    	for ($t=0;$t<count($origarray);$t++) {
	    		i5_new_record($fqry,$origarray[$t]);
	    	}
	    	i5_free_file($fqry);

	    	// fermeture de l'acces I5 ToolsKit
	    	@i5_close($this->conn);
	    	return true;
    	}
    }



	function query2xml($requete) {

		if (is_resource ( $this->conn )) {
            // code commande
            $qr = i5_query($requete);
			$i = 0;
			while ( $values = i5_fetch_row ( $qr, I5_READ_NEXT ) ) {
				$i ++;
				$l_xml_string .= "<item  ";
				for($j = 0; $j < i5_num_fields ( $qr ); $j ++) {
					$l_xml_string .= strtolower ( stripslashes ( i5_field_name ( $qr, $j ) ) ) . "=\"";
					$l_xml_string .= htmlspecialchars ( stripslashes ( $values [$j] ) ) . "\" ";
				}
				$l_xml_string .= " >";
				echo "";
				$l_xml_string .= "</item>\n";
			}
			$l_xml_string .= "</query>\n";
			i5_free_query ( $qr );

			$e_xml_string = "<?xml version='1.0' encoding='UTF-8' ?> \n";
			$e_xml_string .= "<query numrows=\"" . $i . "\">\n" . $l_xml_string;
			$res = utf8_encode ( $e_xml_string );
			return $res;
		}
	}

	function query2array($requete) {


		if (is_resource ( $this->conn )) {
			$res = array ();
			// code commande
			$qr = @i5_query($requete);
			while ( $values = @i5_fetch_row($qr, I5_READ_NEXT) ) {

				$row = array ();
				for($j = 0; $j < i5_num_fields($qr); $j ++) {
					$key = strtolower(stripslashes(i5_field_name($qr, $j)));
					$val = htmlspecialchars(stripslashes($values [$j]));
					//$row["$key"] = $val;
					array_push($row, array($key => $val) );

				}
				array_push($res,$row);
			}

			i5_free_query($qr);
			return $res;
		}
	}

	function wrkoutq($outq) {

		if (is_resource ( $this->conn )) {
			$description = array ('outq' => $outq );
			$spool = i5_spool_list ( $description );

			if (is_resource ( $spool )) {

				$res = array ();

				while ( $spool_file = i5_spool_list_read ( $spool ) ) {

					if (is_array ( $spool_file )) {
						// $spool_file contains spool file data from the queue
						// INSERT YOUR CODE HERE !!! OR/AND DO THE NEXT:
						array_push ( $res, $spool_file );
						/* string i5_spool_get_data(string jobname, integer job_number, string username, string spool_name, integer spool_id [,string filename])
			               job_name - The name of the job that created the file
			               job_number - The number of the job that created the file
			               username - The username of the job that created the file
			               spool_name - The spool file name
			               spool_id - ID of the spool file in the queue (as returned by outq_read)
			               filename - IFS filename to store the data. If not provided, the data is returned as string
			            */
						$data = i5_spool_get_data ( $spool_file ['SPLFNAME'], $spool_file ['JOBNAME'], $spool_file ['USERNAME'], $spool_file ['JOBNBR'], "*LAST" );

					/*
			            *  if (is_string($data)) {
			            	// $data variable contains data string from the spool file
			            	// ENTER YOUR CODE HERE!
			            	return $res;
			            }


			            else {
			            	// Failed to get the data from the spool file, use i5_errormsg() to to get the failure reason. Need to close the spool list and the connection to i5 server
			            	return "Erreur de recuperation des donn�es";
			            }  */
					} else {
						// Failed to get spool file data from the queue  , need to close the spool list and the i5 connection
						return "Erreur de recuperation du spool";
					}

				}
				return $res;
			} else {
				// Failed to create an spool file lists, use i5_errormsg() to get the failure reason and close the connection to i5 server
				return "Erreur de recuperation d'une liste de spool";
			}

			if (! i5_spool_list_close ( $spool )) {
				// Failed to free spool list resourse , use i5_errormsg() to get the failure reason and close the connection to i5 server
				return "Erreur de fermeture d'une liste de spool";
			}

			if (! i5_close ( $this->conn )) {
				// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
				return "Erreur de deconnexion du serveur";
			}
		}

		else {
			// Connection to i5 server failed, use i5_errormsg() to get the failure reason
			return "Erreur de connexion du serveur";
		}

	}

	function test() {
		return "Hello world";
	}


	function contenuOutq() {
	    if (is_resource ( $this->conn )) {

		$res = i5_outq_open('M0012',$this->conn);
		return i5_outq_read($res);

	    }

	}


	function lire_spool($outq) {

		if (is_resource ( $this->conn )) {

			$description = array ("OUTQ" => $outq );

			$spool = i5_spool_list ( $description );

			if (is_resource ( $spool )) {

				$spool_file = i5_spool_list_read ( $spool );
				$data = i5_spool_get_data ( $spool_file ['SPLFNAME'], $spool_file ['JOBNAME'],$spool_file ['USERNAME'] , $spool_file ['JOBNBR'], $spool_file ['SPLFNBR'] );

				//return $spool_file;
				if (is_string ( $data )) {
					// $data variable contains data string from the spool file
					// ENTER YOUR CODE HERE!
					return $data;
				}
				if (! i5_spool_list_close ( $spool )) {
					// Failed to free spool list resourse , use i5_errormsg() to get the failure reason and close the connection to i5 server
					return "Erreur de fermeture d'une liste de spool";
				}
			}
			if (! i5_close ( $this->conn )) {
				// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
				return "Erreur de fermeture de connexion du serveur";
			}

		}

	}

    function add_userAcc($user,$objet,$droit) {
        if (is_resource ($this->conn)) {
            // code commande

            $requete = "INSERT INTO FID400/USERSACC  (USERN,OBJET,ACCES) VALUES('$user','$objet','$droit')";
            $query = i5_query($requete);
            $result = false;
            if (! $query) {
                return $result;
            } else {
                $result = true;
                i5_free_query($query);
            }

            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }

            return $result;
        }
    } //add_GroupAcc


    function add_GroupAcc($user,$grpname,$droit) {
    	if (is_resource ($this->conn)) {
    		// code commande

    		$requete = "DELETE FROM FID400/GROUPSACC  WHERE USERN='$user' and GRPN='$grpname'";
            $query = i5_query($requete);

    		$requete = "INSERT INTO FID400/GROUPSACC  (USERN,GRPN,GRPACC) VALUES('$user','$grpname','$droit')";
    		$query = i5_query($requete);
    			//i5_free_query($query);

            //i5_free_query($query);
    		if (! $query) {
    			return $result;
    		} else {
    			$result = true;
    			$requete = "DELETE FROM FID400/USERSACC WHERE USERN='$user'";
                $query = i5_query($requete);
    			//i5_free_query($query);
    			//$result = array();
    			$elts = preg_split("/[|]/",$droit);
    			$nb = count($elts);

    			for ($i=0;$i<$nb;$i++) {
    				$objelts = preg_split("/,/",$elts[$i]);
    				//array_push($result,$objelts);
    				$obj = $objelts[0];
    				$acc = $objelts[1];
    				//$this->add_userAcc($user,$obj,$acc);
    				$requete = "INSERT INTO FID400/USERSACC  (USERN,OBJET,ACCES) VALUES('$user','$obj','$acc')";
    				$query = i5_query($requete);
    			}


    		}

    		if (!i5_close($this->conn)) {
    			return "Erreur de connection";
    		}
            //i5_free_query($query);

    		return $result;

    	}
    }


    function get_usersAcc($userac) {
        if (is_resource ($this->conn)) {

            $query = i5_query ("select * from fid400/usersacc where usern='".$userac."'");
            if (! $query) {
                return "Error code: ".i5_errno($query)."\n Error message: ".i5_errormsg($query)." ";
            } else {
                $i = 0;
                $result = array ();

                while($values = i5_fetch_row($query,I5_READ_NEXT)) {

                    $row = array ();
                    for($j=0;$j<i5_num_fields($query);$j++) {
                        //$key = strtolower ( stripslashes ( i5_field_name ( $query, $j ) ) );
                        $key = strtolower(i5_field_name($query,$j));
                        $val = (stripslashes($values[$j]));
                        $row ["$key"] = $val;
                        //array_push($row,array("$key" => $val));
                    }
                    //array_push($result,$row);
                    $result[$i] = $row;
                    $i ++;
                }

                i5_free_query($query);
            }

            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }

            return $result;
        }
    }


    function query_delete($sqlstr) {
        if (is_resource ($this->conn)) {
            $result = false;
            $query = @i5_query ($sqlstr);
            if (! $query) {
                return $result;
            } else {

                $result = true;
                @i5_free_query($query);
            }

            if (!i5_close($this->conn)) {
                return $result;
            }

            return $result;
        }
    }

    function query_insert($sqlstr) {
    	if (is_resource ($this->conn)) {


            i5_transaction(I5_ISOLEVEL_NONE,$this->conn);

            i5_query($sqlstr);


            $result = (i5_commit($this->conn));

            if (!i5_close($this->conn)) {
                return false;
            }

            return $result;
        }
    }

	function query_create($sqlstr) {
        if (is_resource ($this->conn)) {
            $result = false;
            $query = @i5_query ($sqlstr);
            if (! $query) {
                return $result;
            } else {

                $result = true;
                @i5_free_query($query);
            }

            if (!i5_close($this->conn)) {
                return $result;
            }

            return $result;
        }
    }

    function query_insert_multi($sqlstr) {
    	if (is_resource ($this->conn)) {

 		//I5_ISOLEVEL_CHG - I5_ISOLEVEL_NONE

	     i5_transaction(I5_ISOLEVEL_NONE,$this->conn);
            $ar = explode("||",$sqlstr);

	     for ( $i=0; $i < count($ar); $i++ ) {

	      	i5_query($ar[$i]);

	     }

            $result = !(i5_commit($this->conn));

            //$result = (i5_commit($this->conn));

	     //i5_commit($this->conn);
	     //$result = $ar[0];

            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }

            return $result;
        }
    }




    function query_numfac() {
    	if (is_resource ($this->conn)) {

 		//I5_ISOLEVEL_CHG - I5_ISOLEVEL_NONE

	     i5_transaction(I5_ISOLEVEL_NONE,$this->conn);

	     i5_query("INSERT INTO VVMGRFA/TBMGFAC VALUES ( default , '' , '' , '' , '' , '' , '0' , 'NEW' )");

	     //i5_commit($this->conn);

            //i5_transaction(I5_ISOLEVEL_CHG,$this->conn);

	     $res = i5_query("SELECT IDFAC FROM VVMGRFA/TBMGFAC WHERE FACFOURS='NEW'");

	     $result = i5_fetch_array($res);

            i5_rollback($this->conn);


	     //i5_commit($this->conn);
            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }

            return $result;
        }
    }

    function query_update($sqlstr) {
        if (is_resource ($this->conn)) {


        	i5_transaction(I5_ISOLEVEL_NONE,$this->conn);

            i5_query($sqlstr);


            $result = !(i5_commit($this->conn));

            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }

            return $result;
        }
    }

    function listeOutqs($lstoutq) {
	if (is_resource ($this->conn)) {
		$result=`system 'wrkoutq $lstoutq'|grep -E 'HLD|RLS|DMG|CHG|CLR'`;
		$tab = explode("\n",$result);
        for ($i=0;$i<count($tab)-1;$i++) {

           $elt1 = substr($tab[$i],1,13);
           $testrmt=`system 'wrkoutqd $elt1'|grep -E '\. :'|grep 'Adresse Internet'|awk -F'   ' '{ print $2 }'|sed 's/ //g'`;
           if ( (substr_count($testrmt,'CPD0078')>0) || (substr_count($testrmt,'CPF0006')>0) || (substr_count($testrmt,'CPF3357')>0) || (trim($testrmt)=='') ) {
			   $testdev=`system 'dspdevd $elt1'|grep -E '\. :'|grep 'ou adresse'|awk -F'               ' '{ print $2 }'`;
			   if ( (substr_count($testdev,'CPF2603')>0) || (trim($testdev)=='') ) {
				   $ip = "0.0.0.0";
			   } else {
				   $ip = trim($testdev);
			   }
		   } else {
				$ip = trim($testrmt);
		   }
		   //$etat=`system "PING RMTSYS('$ip') NBRPKT(1)"|grep "successful"|awk -F'(' '{ print $2 }'|awk -F' ' '{print $1}'`;
           $elt2 = substr($tab[$i],14,13);
           $elt3 = trim(substr($tab[$i],27,10));
           $elt4 = substr($tab[$i],37,13);
           $elt5 = substr($tab[$i],51,13);

           $itab[0]=$elt1;
           $itab[1]=$elt2;
           $itab[2]=$elt3;
           $itab[3]=$elt4;
           $itab[4]=$elt5;
		   $itab[5]=$ip;
		   //$itab[6]=$etat;

           $res[$i]=$itab;
        }
        return $res;

	}
    }


    function move_spool($outq,$dest) {
	//$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
	//$conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		if (is_resource ( $this->conn )) {

			$description = array ("OUTQ" => $outq );

			$spool = i5_spool_list ( $description );

			if (is_resource ( $spool )) {

				$res = array ();
				$nbspool=0;
				while ( $spool_file = i5_spool_list_read ( $spool ) ) {

					$splfname=$spool_file['SPLFNAME'];
					$travail=$spool_file['JOBNAME'];
					$utilisateur=$spool_file['USERNAME'];
					$numfile=$spool_file['JOBNBR'];
					echo $spool_file['SPLFNBR']."\n";
					$numero=substr(strval(intval($spool_file['SPLFNBR']) + 1000000),1,6);

					$exec="CHGSPLFA FILE(".$splfname.") JOB(".$numfile."/".$utilisateur."/".$travail.") SPLNBR(".$numero.")  OUTQ(".$dest.")";
					$cmd = `system '$exec'`;
					$nbspool=$nbspool+1;
				}
				$result=true;
				if (! i5_spool_list_close ( $spool )) {
					// Failed to free spool list resourse , use i5_errormsg() to get the failure reason and close the connection to i5 server
					return false;
				}
			}
			if (! i5_close ( $this->conn  )) {
				// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
				return false;
			}
		        return $result;
		}

     }




	function impPing($ip) {
		if (is_resource ($this->conn)) {
			$etat=`system "PING RMTSYS('$ip') NBRPKT(1)"|grep "successful"|awk -F'(' '{ print $2 }'|awk -F' ' '{print $1}'`;
			if (trim($etat)=='100') {
				$result = true;
			} else {
				$result = false;
			}
			return $result;
		}
	}



	function genFacturesSpeCas($ba,$factures) {
    		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
    		if (is_resource ($this->conn)) {

			//file_put_contents("./facspecas.txt", $ba);
			$this->writeDataFile($ba,"./facspecas.txt");
    			$result=false;
    			$filecontent = file_get_contents("./facspecas.txt");
    			$filearray = preg_split("/[\n]|[\r\n]|[\r]/",$filecontent);

    			//echo "Taille : ".count($filearray)."\n";

    			$arnumfac=explode(",",$factures);
    			$listfac = array();
    			//array("00086915","00086919");
    			$nbfac=0;

    			@i5_command("CLRPFM FILE(EMG402125/MGTRAFF0)");

    			$file = @i5_open("EMG402125/MGTRAFF0", I5_OPEN_READWRITE, $this->conn);// |I5_OPEN_EXCL
    			if (!$file)          // if cannot open
    			{
    				@i5_close($this->conn);
    				return false; // display CPFnnnn
    			}

    			for ($i = 0; $i < count($filearray); $i++ ) {
    				$line = $filearray[$i];
    				if (substr($line,0,2) == "FF") {
    					$numfac=substr($line,3,8);
    					if (in_array($numfac,$arnumfac)) {
    						if (substr($line,0,3) == "FFE") {
    							//creation de facture en cours...
    							//echo "Creation de la facture fac$numfac.txt\n";
    							//$fileifs = @fopen("./fac".$numfac.".txt","w+");
    							//fwrite($fileifs,$line."\n");

							i5_new_record($file,array("MGTRAFF0"=>$line));
    							array_push($listfac,$numfac);
    							$nbfac++;
    						}
    						if (substr($line,0,3) == "FFG") {
    							//fwrite($fileifs,$line."\n");
    							i5_new_record($file,array("MGTRAFF0"=>$line));
    						}
    						if (substr($line,0,3) == "FFH") {
    							//fwrite($fileifs,$line."\n");
    							i5_new_record($file,array("MGTRAFF0"=>$line));
    						}
    						if (substr($line,0,3) == "FFL") {
    							//fwrite($fileifs,$line."\n");
    							i5_new_record($file,array("MGTRAFF0"=>$line));
    						}
    						if (substr($line,0,3) == "FFP") {
    							//fwrite($fileifs,$line."\n");
    							i5_new_record($file,array("MGTRAFF0"=>$line));
    							//fclose($fileifs);
    						}
    					}
    				}

    			}

    		@i5_free_file($file);
    		$result=true;
    		if (!i5_close($this->conn)) {
    			return false;
    		}
    	}
    	@i5_free_file($file);
    	$this->setFacCas($listfac);
    	return $result;
    }


function genFacturesCas2($filename,$factures) {
    	$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
    	if (is_resource ($this->conn)) {

    		$result=false;
    		$filecontent = $this->readFtpDirFile("ftpcamag","ftpedicas","ftpedicas","arr|".$filename);
    		$filecontent = file_get_contents("./".$filename);
    		$filearray = preg_split("/[\n]|[\r\n]|[\r]/",$filecontent);

    		//echo "Taille : ".count($filearray)."\n";

    		$arnumfac=explode(",",$factures);
    		$listfac = array();
    		//array("00086915","00086919");
    		$nbfac=0;

    		@i5_command("CLRPFM FILE(EMG402125/MGTRAFF0)");

    		$file = @i5_open("EMG402125/MGTRAFF0", I5_OPEN_READWRITE, $this->conn);// |I5_OPEN_EXCL
    		if (!$file)          // if cannot open
    		{
    			@i5_close($this->conn);
    			return false; // display CPFnnnn
    		}

    		for ($i = 0; $i < count($filearray); $i++ ) {
    			$line = $filearray[$i];
    			if (substr($line,0,2) == "FF") {
    				$numfac=substr($line,3,8);
    				if (in_array($numfac,$arnumfac)) {
    					if (substr($line,0,3) == "FFE") {
    						//creation de facture en cours...
    						//echo "Creation de la facture fac$numfac.txt\n";
    						//$fileifs = @fopen("./fac".$numfac.".txt","w+");
    						//fwrite($fileifs,$line."\n");
    						i5_new_record($file,array("MGTRAFF0"=>$line));
    						array_push($listfac,$numfac);
    						$nbfac++;
    					}
    					if (substr($line,0,3) == "FFG") {
    						//fwrite($fileifs,$line."\n");
    						i5_new_record($file,array("MGTRAFF0"=>$line));
    					}
    					if (substr($line,0,3) == "FFH") {
    						//fwrite($fileifs,$line."\n");
    						i5_new_record($file,array("MGTRAFF0"=>$line));
    					}
    					if (substr($line,0,3) == "FFL") {
    						//fwrite($fileifs,$line."\n");
    						i5_new_record($file,array("MGTRAFF0"=>$line));
    					}
    					if (substr($line,0,3) == "FFP") {
    						//fwrite($fileifs,$line."\n");
    						i5_new_record($file,array("MGTRAFF0"=>$line));
    						//fclose($fileifs);
    					}
    				}
    			}

    		}


    function genFacturesCas($filename,$factures) {
    	$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
    	if (is_resource ($this->conn)) {

    		$result=false;
    		$filecontent = $this->readFtpDirFile("195.115.159.41","X999053","dr(fd54rh5","arr|".$filename);
    		$filecontent = file_get_contents("./".$filename);
    		$filearray = preg_split("/[\n]|[\r\n]|[\r]/",$filecontent);

    		//echo "Taille : ".count($filearray)."\n";

    		$arnumfac=explode(",",$factures);
    		$listfac = array();
    		//array("00086915","00086919");
    		$nbfac=0;

    		@i5_command("CLRPFM FILE(EMG402125/MGTRAFF0)");

    		$file = @i5_open("EMG402125/MGTRAFF0", I5_OPEN_READWRITE, $this->conn);// |I5_OPEN_EXCL
    		if (!$file)          // if cannot open
    		{
    			@i5_close($this->conn);
    			return false; // display CPFnnnn
    		}

    		for ($i = 0; $i < count($filearray); $i++ ) {
    			$line = $filearray[$i];
    			if (substr($line,0,2) == "FF") {
    				$numfac=substr($line,3,8);
    				if (in_array($numfac,$arnumfac)) {
    					if (substr($line,0,3) == "FFE") {
    						//creation de facture en cours...
    						//echo "Creation de la facture fac$numfac.txt\n";
    						//$fileifs = @fopen("./fac".$numfac.".txt","w+");
    						//fwrite($fileifs,$line."\n");
    						i5_new_record($file,array("MGTRAFF0"=>$line));
    						array_push($listfac,$numfac);
    						$nbfac++;
    					}
    					if (substr($line,0,3) == "FFG") {
    						//fwrite($fileifs,$line."\n");
    						i5_new_record($file,array("MGTRAFF0"=>$line));
    					}
    					if (substr($line,0,3) == "FFH") {
    						//fwrite($fileifs,$line."\n");
    						i5_new_record($file,array("MGTRAFF0"=>$line));
    					}
    					if (substr($line,0,3) == "FFL") {
    						//fwrite($fileifs,$line."\n");
    						i5_new_record($file,array("MGTRAFF0"=>$line));
    					}
    					if (substr($line,0,3) == "FFP") {
    						//fwrite($fileifs,$line."\n");
    						i5_new_record($file,array("MGTRAFF0"=>$line));
    						//fclose($fileifs);
    					}
    				}
    			}

    		}



    		@i5_free_file($file);
    		$result=true;
    		if (!i5_close($this->conn)) {
    			return false;
    		}
    	}
    	@i5_free_file($file);
    	$this->setFacCas($listfac);
    	//@unlink($filename);
    	return $result;
    }



	function setFacCas($tabfacnums) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
        if (is_resource ($this->conn)) {



        	i5_transaction(I5_ISOLEVEL_NONE,$this->conn);

        	$today = strtotime("now");

			$annee = date("Y",$today);;
			$jour = date("d",$today);
			$mois = date("m",$today);



            for ($i = 0; $i < count($tabfacnums); $i++ ) {
    			$numfac = $tabfacnums[$i];

    			i5_query("delete from vvbase/tbfacrec where numfac='".$numfac."'");
        		i5_query("INSERT INTO  vvbase/tbfacrec  ( numfac , datefac ) values ( '".$numfac."','$jour/$mois/$annee' )");

            }

            $result = !(i5_commit($this->conn));

            if (!i5_close($this->conn)) {
                return "Erreur de connection";
            }

            return $result;
        }
    }

	function listeFacRec() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource ($this->conn)) {
			// liste des factures
			$requete="SELECT numfac,datefac FROM vvbase/tbfacrec";
			$query = i5_query ($requete);
			if (! $query) {
				return false;
			} else {
				$i = 0;
				$result = array ();

				while($values = i5_fetch_row($query,I5_READ_NEXT)) {

					$row = array ();
					for($j=0;$j<i5_num_fields($query);$j++) {
						//$key = strtolower ( stripslashes ( i5_field_name ( $query, $j ) ) );
						$key = strtolower(i5_field_name($query,$j));
						$val = (stripslashes($values[$j]));
						$row ["$key"] = $val;
						//array_push($row,array("$key" => $val));
					}
					//array_push($result,$row);
					$result[$i] = $row;
					$i ++;
				}

				i5_free_query($query);

			}

			if (!i5_close($this->conn)) {
				return false;
			}

			return $result;
		}
	}

    function fileExist($filename) {
    	if (is_resource ($this->conn)) {

	    	$file = @i5_open($filename, I5_OPEN_READ, $this->conn);
	        if ($file == false) {
				$result = false;
			} else {
				$result = true;
			}
			@i5_free_file($this->conn);
			return $result;

		}
    }

   function isBib($bib) {
   		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (i5_command("CHKOBJ OBJ(".$bib.") OBJTYPE(*LIB)")) {
			$result=true;
		} else {
			$result=false;
		}
	   	@i5_close($this->conn);
	   	return $result;
   }


	function int_vente() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource($this->conn )) {
			$result=@i5_query("DELETE FROM PGMCOMET/FTVENTE1 WHERE F1 LIKE 'AAAAAA%'");
		}
		@i5_close($this->conn);


		return $this->cpy_vente();
	}


	function cpy_vente() {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource ( $this->conn )) {
			if (@i5_command("CPYF FROMFILE(PGMCOMET/FTVENTE1) TOFILE(PGMCOMET/FTVENTE) MBROPT(*REPLACE) FMTOPT(*NOCHK)") ) {
				$resultat = true;
			}
			@i5_close($this->conn);
		}
		return $resultat;

	}


	function cpy_taxes() {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource ( $this->conn )) {
			if (@i5_command("CPYF FROMFILE(PGMCOMET/FTTAX1) TOFILE(PGMCOMET/FTTAX) MBROPT(*REPLACE) FMTOPT(*NOCHK)") ) {
				$resultat = true;
			}
			@i5_close($this->conn);
		}
		return $resultat;

	}


	function int_achat() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource($this->conn )) {
			$result=@i5_query("DELETE FROM PGMCOMET/FTACHAT1 WHERE F1 LIKE 'AAAAAA%'");
		}
		@i5_close($this->conn);


		return $this->cpy_achat();
	}

	function int_taxes() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource($this->conn )) {
			$result=@i5_query("DELETE FROM PGMCOMET/FTTAX1 WHERE F1 LIKE 'AAAAAA%'");
		}
		@i5_close($this->conn);


		return $this->cpy_taxes();
	}


	function int_docpromo() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource($this->conn )) {
			$result=@i5_query("DELETE FROM PGMCOMET/FTDOCPR1 WHERE F1 LIKE 'AAAAAA%'");
		}
		@i5_close($this->conn);


		return $this->cpy_docpromo();
	}

	function int_nomdo() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource($this->conn )) {
			$result=@i5_query("DELETE FROM PGMCOMET/FTNOMDO1 WHERE F1 LIKE 'AAAAAA%'");
		}
		@i5_close($this->conn);


		return $this->cpy_nomdo();
	}


	function cpy_nomdo() {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource ( $this->conn )) {
			if (@i5_command("CPYF FROMFILE(PGMCOMET/FTNOMDO1) TOFILE(PGMCOMET/FTNOMDO) MBROPT(*REPLACE) FMTOPT(*NOCHK)") ) {
				$resultat = true;
			}
			@i5_close($this->conn);
		}
		return $resultat;

	}



	function cpy_achat() {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource ( $this->conn )) {
			if (@i5_command("CPYF FROMFILE(PGMCOMET/FTACHAT1) TOFILE(PGMCOMET/FTACHAT) MBROPT(*REPLACE) FMTOPT(*NOCHK)") ) {
				$resultat = true;
			}
			@i5_close($this->conn);
		}
		return $resultat;

	}

	function cpy_docpromo() {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource ( $this->conn )) {
			if (@i5_command("CPYF FROMFILE(PGMCOMET/FTDOCPR1) TOFILE(PGMCOMET/FTDOCPR) MBROPT(*REPLACE) FMTOPT(*NOCHK)") ) {
				$resultat = true;
			}
			@i5_close($this->conn);
		}
		return $resultat;

	}


	function cpy_ftrsi($profil) {

		/** PHPExcel */
		//require_once 'Classes/PHPExcel.php';

		require_once 'Classes/PHPExcel/IOFactory.php';

		//file_put_contents( 'ftrsi.xls', $donnes);




		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource ( $this->conn )) {
			@i5_command("DLTF ".$profil."/FTRSI");
			@i5_command("CPYF FROMFILE(PGMCOMET/FTRSI) TOFILE(".$profil."/FTRSI) MBROPT(*REPLACE) FMTOPT(*NOCHK) CRTFILE(*YES)");
				 if (@i5_command("CLRPFM ".$profil."/FTRSI")) {



					$objPHPExcel = PHPExcel_IOFactory::load('ftrsi.xls');
					$objWorksheet = $objPHPExcel->getActiveSheet();

					//parcours chaque ligne de la feuille excel
					foreach ($objWorksheet->getRowIterator() as $row) {


						$cellIterator = $row->getCellIterator();
						$cellIterator->setIterateOnlyExistingCells(false);
						// valeur de chaque cellule de la ligne en cour de lecture
						$values =  array();
						foreach ($cellIterator as $cell) {

							array_push($values,$cell->getValue());
						}
						@i5_query("insert into  ".$profil."/ftrsi (zart,zlib,zpu,zndo,zpcb,zvola,zpdsa) values ('".implode("','",$values)."')");

					}
					$resultat = true;

			}
			@i5_close($this->conn);
		}
		return $resultat;

	}


	function dlt_vente() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource ( $this->conn )) {
			if (@i5_command("DLTF PGMCOMET/FTVENTE1") ) {
				$resultat = true;
			}
			@i5_close($this->conn);
		}
		return $resultat;
	}

	function dlt_achat() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource ( $this->conn )) {
			if (  @i5_command("DLTF PGMCOMET/FTACHAT1") ) {
				$resultat = true;
			} else {
				$resultat = false;
			}
			@i5_close($this->conn);
		}
		return $resultat;
	}


	function dlt_taxes() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource ( $this->conn )) {
			if (  @i5_command("DLTF PGMCOMET/FTTAX1") ) {
				$resultat = true;
			} else {
				$resultat = false;
			}
			@i5_close($this->conn);
		}
		return $resultat;
	}


	function dlt_nomdo() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource ( $this->conn )) {
			if (  @i5_command("DLTF PGMCOMET/FTNOMDO1") ) {
				$resultat = true;
			} else {
				$resultat = false;
			}
			@i5_close($this->conn);
		}
		return $resultat;
	}

	function dlt_docpromo() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$resultat = false;
		if (is_resource ( $this->conn )) {
			if (  @i5_command("DLTF PGMCOMET/FTDOCPR1") ) {
				$resultat = true;
			} else {
				$resultat = false;
			}
			@i5_close($this->conn);
		}
		return $resultat;
	}

	function sovldpal() {



		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET');//i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		$desc = array (
				array("NAME"=>"RETSEC",
						"IO"=>I5_INOUT,
						"TYPE"=>I5_TYPE_CHAR,
						"LENGTH"=>"3"
				),
				array("NAME"=>"RETNUM",
						"IO"=>I5_INOUT,
						"TYPE"=>I5_TYPE_CHAR,
						"LENGTH"=>"6"
				));
		//$resultat = false;
		if (is_resource ( $this->conn )) {
			$input = array("RETSEC"=>"000","RETNUM"=>"000000");
			$output = array("RETSEC"=>"sec","RETNUM"=>"num");
			//if (  @i5_command("CALL",$input,$output,$this->conn) ) {
			$hdlPgm=@i5_program_prepare("PGMCOMET/SOVLDPAL",$desc,$this->conn);
			if ( $hdlPgm ) {
				//$resultat = "true:".$retsec.":".$retnum;
				if ( @i5_program_call($hdlPgm, $input, $output) ) {
					$res = array();
					array_push($res,$sec);
					array_push($res,$num);
					$resultat = $res;
				} else {
					$resultat = i5_errormsg();
				}
			} else {
				$resultat = false;
			}
			@i5_close($this->conn);
		}
		return $resultat;
	 }

	function iflg_etat_vague_20($numvag) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$result = false;
		if (is_resource ( $this->conn )) {
			$sql = "SELECT ETAVAG FROM FGE50MG/GEVAG WHERE NUMVAG='$numvag'";
			$result=$this->query_multi03($sql);
			@i5_close($this->conn);
		}
		if ( $result[0]["etavag"] == "20" ) {
			$result = true;
		} else {
			$result = false;
		}
		return $result;
	}

	function iflg_dblq_vag($numvag) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$result = false;
		if (is_resource ( $this->conn )) {

			$sql = "UPDATE FGE50MG/GEVAG SET ETAVAG='90' WHERE NUMVAG='$numvag'";
			i5_transaction(I5_ISOLEVEL_NONE,$this->conn);
            i5_query($sql);
            $result = !(i5_commit($this->conn));

		}
		@i5_close($this->conn);

		return $result;
	}




	function iflg_sup_cdetecpro($numcde) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$this->conn = @i5_connect( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$result = false;
		if (is_resource ($this->conn)) {

			$result = true;
			if ( $file = i5_open("PGMCOMET/COPHYECC", I5_OPEN_READWRITE) ) {
				$clef = array(1,800,240,$numcde);
				$result=i5_seek($file, "=" , $clef);
				$row=@i5_fetch_row($file,I5_READ_SEEK);
				$result=@i5_update_record($file, array("STGRP" => 9999));
				while ( ($row[0]=='1') && ($row[1]=='800') && ($row[2]==$numcde) && ($row[3]=='240') ) {
					$row=@i5_fetch_row($file,I5_READ_NEXT);
					$result=@i5_update_record($file, array("STGRP" => 9999));
				}
				@i5_free_file($file);

			}

			if ( $file = i5_open("PGMCOMET/COPHYDCC", I5_OPEN_READWRITE) ) {
				$clef = array(1,800,240,$numcde);
				$result=@i5_seek($file, "=" , $clef);
				$row=@i5_fetch_row($file,I5_READ_SEEK);
				$result=@i5_update_record($file, array("STGRP" => 9999));
				while ( ($row[0]=='1') && ($row[1]=='800') && ($row[2]==$numcde) && ($row[3]=='240') ) {
					$row=@i5_fetch_row($file,I5_READ_NEXT);
					$result=@i5_update_record($file, array("STGRP" => 9999));
				}
				i5_free_file($file);

			}

			if ( $file = i5_open("PGMCOMET/COPHYFCC", I5_OPEN_READWRITE) ) {
				$clef = array(1,800,240,$numcde);
				$result=i5_seek($file, "=" , $clef);
				$row=@i5_fetch_row($file,I5_READ_SEEK);
				$result=@i5_update_record($file, array("STGRP" => 9999));
				while ( ($row[0]=='1') && ($row[1]=='800') && ($row[2]==$numcde) && ($row[3]=='240') ) {
					$row=@i5_fetch_row($file,I5_READ_NEXT);
					$result=@i5_update_record($file, array("STGRP" => 9999));
				}
				i5_free_file($file);

			}



		}
		@i5_close($this->conn);

		return $result;
	}


	function iflg_pal_picking($zpic,$allee,$depp,$niv,$codepal) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		$conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$result = false;
		if (is_resource ( $conn )) {

			$sql = "UPDATE FGE50MG/GEPAL SET ZONSTS='$zpic', ALLSTS ='$allee',  DPLSTS='$depp',  NIVSTS='$niv', ETAPAL='10' WHERE CODPAL='$codepal'";

			i5_transaction(I5_ISOLEVEL_NONE,$conn);
            i5_query($sql);
            $result = !(i5_commit($conn));

		}
		@i5_close($conn);

		return $result;
	}

	function iflg_maj_stk($stksaisie,$numart) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		$result = false;
		if (is_resource ( $this->conn )) {

			$sql = "UPDATE PGMCOMET/COPHYART SET ARTSTK='$stksaisie' WHERE STDOS='800' AND ARTNO='$numart'";
			i5_transaction(I5_ISOLEVEL_NONE,$this->conn);
            i5_query($sql);
            $result = !(i5_commit($this->conn));

		}
		@i5_close($this->conn);

		return $result;
	}

	function iflg_lst_users() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		$result = false;
		if (is_resource ( $this->conn )) {
			$file = @i5_open("FGE50MG/APSES", I5_OPEN_READ,$this->conn);
			//return i5_fetch_array($file,I5_READ_FIRST);
			$row = @i5_fetch_assoc($file,I5_READ_FIRST);

			$result = array();
			while ($row)  // while there is a row...
			{
				array_push($result,$row);
				// Get next row
				$row = i5_fetch_assoc($file, I5_READ_NEXT);

			} // loop

			@i5_free_file($file);
		}
		@i5_close($this->conn);

		return $result;
	}


	function startTecFac() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$result = false;
		if (is_resource ( $this->conn )) {
			if (! $this->isTecFac()) {
				$result = i5_command("SBMJOB CMD(CALL PGM(FCCLPEBL)) JOB(TEC_FAC) JOBD(PGMCOMET/URGENT) JOBQ(QGPL/QSPL) USER(NOEL)");
			}
		}
		@i5_close($this->conn);

		return $result;
	}

	function stopTecFac() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$result = false;
		if (is_resource ( $this->conn )) {
			if ($this->isTecFac()) {
				$result = i5_command("SNDMSG MSG('*') TOMSGQ(PGMCOMET/BL)");
			}
		}
		@i5_close($this->conn);

		return $result;

	}


	function cptPegasTecpro($nummag,$repsrc) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$result = false;
		if (is_resource ( $this->conn )) {
			$result = i5_command("CALL PGM(HHHPGM/VVCPTPEG) PARM( '$nummag' '$repsrc' )");
		}
		@i5_close($this->conn);

		return $result;
	}

	function debloqUser($user) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		$result=false;
		if (is_resource ( $this->conn )) {
			$result=i5_command("CHGUSRPRF USRPRF($user) STATUS(*ENABLED)");
		}

		return  $result;
	}

	function debloqDevice($dev) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		$result=false;
		if (is_resource ( $this->conn )) {
			//$result=i5_command("VRYCFG CFGOBJ($dev) CFGTYPE(*DEV) ??STATUS(*ON) RESET(*YES)");
			$result=i5_command("VRYCFG CFGOBJ($dev) CFGTYPE(*DEV) STATUS(*ON) RESET(*YES)");
		}
		@i5_close($this->conn);
		return  $result;
	}


	function writeDataFile($donnes,$file) {

		$fichier1 = @fopen($file, "w+");
		@fwrite($fichier1, $donnes);
		@fclose($fichier1);

	}

	function writeDataFileBadges($donnes,$file) {

		$fichier1 = @fopen($file, "w+");
		@fwrite($fichier1, $donnes->data);
		@fclose($fichier1);

	}

	function isTecFac() {
		$result = false;

		$cmd = `system 'wrkactjob'|grep -c TEC_FAC`;
		if ($cmd>=1) {
			$result = true;
		}

		return $result;
	}




	function lstLib() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		if (is_resource ( $this->conn )) {

		}
		@i5_close($this->conn);

	}

	function codCadCas() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		if (is_resource ( $this->conn )) {
			$sql = "select tabref,tablib from cophytab where tatcod='EDIFREXT' and tablib like '%CAS%'";
			$query =@i5_query($sql);
			if (! $query) {
				return false;
			} else {
				$i = 0;
				$result = array ();

				while($values = i5_fetch_row($query,I5_READ_NEXT)) {

					$row = array ();
					for($j=0;$j<i5_num_fields($query);$j++) {
						$key = strtolower(i5_field_name($query,$j));
						$val = (stripslashes($values[$j]));
						$row ["$key"] = $val;
						//array_push($row,array("$key" => $val));
					}
					$result[$i] = $row;
					$i ++;
				}
				i5_free_query($query);
			}
		}
		@i5_close($this->conn);
		return $result;
	}




}

?>
