<?php

require_once('Zend/Soap/AutoDiscover.php');
require_once('Zend/Soap/Server.php');
require_once('Zend/Mail.php');
require_once('Zend/Mail/Transport/Smtp.php');

class vvws_tests {

	/**
	 * Cette m�thode accepte
	 * @return string
	 */
	function __construct() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

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
	 * @param string $username
	 * @param string $password
	 * @return boolean
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
	 * @return array
	 */
	function testSendExchg() {
		require_once "init.php";

		$ec = new ExchangeClient();
		$ec->init("3HSERVICES\thsdche", "d@nZel77");
		//$ec->send_message("herve.de-chavigny@hdistribution.fr", "Subject", "A test message");

		return $ec->get_messages();
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
			$dn = $col;
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
	 * @param string $subject
	 * @param string $toEmail
	 * @param string $body
	 * @return boolean
	 */
	function mailTo($subject,$toEmail,$body) {

		$result=false;
		// set the transport to SMTP
		$tr = new Zend_Mail_Transport_Smtp('10.2.100.100');
		Zend_Mail::setDefaultTransport($tr);


		// create the mail object
		if (!($mail = new Zend_Mail())) {
       		$result=false;
		}

		// set the email text
		$mail->setBodyText($body);

		// set the subject
		$mail->setSubject($subject);

		// set sender
		$mail->setFrom('3hservices@gie-superh.com', 'AS400 ne pas repondre!');

		// add the TO email address(es)
		$toAddresses = explode(';',$toEmail);

		foreach ($toAddresses as $address) {
       		$mail->addTo(trim($address));
		}


		// send the email
		$mail->send();
		$result=true;
		return $result;
	}


	/**
	 * @param string $subject
	 * @param string $toEmail
	 * @param string $body
	 * @param string $fromName
	 * @param string $fromEmail
	 * @param string $smtpServer
	 * @return boolean
	 */
	function mailSend($subject,$toEmail,$body,$fromName,$fromEmail,$smtpServer) {

		$result=false;
		// set the transport to SMTP
		$tr = new Zend_Mail_Transport_Smtp($smtpServer);
		Zend_Mail::setDefaultTransport($tr);


		// create the mail object
		if (!($mail = new Zend_Mail())) {
       		$result=false;
		}

		// set the email text
		$mail->setBodyText($body);

		// set the subject
		$mail->setSubject($subject);

		// set sender
		$mail->setFrom($fromEmail, $fromName);

		// add the TO email address(es)
		$toAddresses = explode(';',$toEmail);

		foreach ($toAddresses as $address) {
       		$mail->addTo(trim($address));
		}


		// send the email
		$mail->send();
		$result=true;
		return $result;
	}


	/**
	 * @param array $tab
	 * @return array
	 */
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
	 * @param string $uuid
	 * @param string $name
	 * @param string $pgver
	 * @param string $platf
	 * @param string $version
	 * @param string $dimw
	 * @param string $dimh
	 * @param string $dimaw
	 * @param string $dimah
	 * @param string $dcolor
	 * @param string $ddatec
	 * @param string $dtimec
	 * @return boolean
	 */
	function mob_devconnect($uuid,$name,$pgver,$platf,$version,$dimw,$dimh,$dimaw,$dimah,$dcolor,$ddatec,$dtimec) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

			$result = false;


			$vvmobdev_exist = @i5_command("CHKOBJ OBJ(VVBASE/VVMOBDEV) OBJTYPE(*FILE)");
			if ($vvmobdev_exist) {
				// test l'existence de l' uuid du device
				$query = @i5_query("select DUUID from VVBASE/VVMOBDEV where DUUID='".$uuid."'");
				if ( i5_num_rows($query) == 1 ) {
					//update
					 $query = @i5_query("UPDATE VVBASE/VVMOBDEV
					 				SET DUUID='".$uuid."',
					 				 DNAME='".$name."',
					 				 PGVER='".$pgver."',
					 				 PLATF='".$platf."',
					 				 DVERS='".$version."',
					 				 DIMW='".$dimw."',
					 				 DIMH='".$dimh."',
					 				 DIMAW='".$dimaw."',
					 				 DIMAH='".$dimah."',
					 				 DCOLOR='".$dcolor."',
					 				 DDATEU='".$ddatec."',
					 				 DTIMEU='".$dtimec."' WHERE DUUID='".$uuid."'");

					 $result=true;
				} else {
				    //ajout
				    $ddateu = $ddatec;
				    $dtimeu = $dtimec;
				    $query = @i5_query("INSERT INTO VVBASE/VVMOBDEV (
				    						DUUID , DNAME , PGVER , PLATF , DVERS , DIMW , DIMH , DIMAW , DIMAH , DCOLOR , DDATEC , DDATEU , DTIMEC , DTIMEU ) VALUES (
				    					'".$uuid."' , '".$name."' , '".$pgver."' , '".$platf."' , '".$version."' , '".$dimw."' , '".$dimh."' , '".$dimaw."' , '".$dimah."' , '".$dcolor."' , '".$ddatec."' , '".$ddateu."' , '".$dtimec."' , '".$dtimeu."')");
					$result=true;
				}

			} else {
				$query = @i5_query("create table VVBASE/VVMOBDEV (
									ID INT generated always as identity
									(start with 1 increment by 1 cycle),
									DUUID CHAR(50),
									DNAME VARCHAR(50),
									PGVER CHAR(20),
									PLATF CHAR(50),
									DVERS VARCHAR(50),
									DIMW VARCHAR(5),
									DIMH VARCHAR(5),
									DIMAW VARCHAR(5),
									DIMAH VARCHAR(5),
									DCOLOR VARCHAR(15),
									DDATEC VARCHAR(8),
									DDATEU VARCHAR(8),
									DTIMEC VARCHAR(8),
									DTIMEU VARCHAR(8),
									Primary key (ID))
				");
				if ($query) {
				    $ddateu = $ddatec;
				    $dtimeu = $dtimec;
				    $query = @i5_query("INSERT INTO VVBASE/VVMOBDEV (
				    						DUUID , DNAME , PGVER , PLATF , DVERS , DIMW , DIMH , DIMAW , DIMAH , DCOLOR , DDATEC , DDATEU , DTIMEC , DTIMEU ) VALUES (
				    					'".$uuid."' , '".$name."' , '".$pgver."' , '".$platf."' , '".$version."' , '".$dimw."' , '".$dimh."' , '".$dimaw."' , '".$dimah."' , '".$dcolor."' , '".$ddatec."' , '".$ddateu."' , '".$dtimec."' , '".$dtimeu."')");
					$result=true;

				}
			}

			if (!i5_close($conn)) {
				$result= false;
			}
		}
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
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

			$result = false;


			$vvmobdev_exist = @i5_command("CHKOBJ OBJ(VVBASE/VVMOBGUID) OBJTYPE(*FILE)");
			if ($vvmobdev_exist) {
				// test l'existence de l' uuid du device
				$query = @i5_query("select GUID from VVBASE/VVMOBGUID where GUID='".$guid."'");
				if ( i5_num_rows($query) == 1 ) {
					//update
					$query = @i5_query("UPDATE VVBASE/VVMOBGUID
							SET GUID='".$guid."',
							UNOM='".$name."',
							UPRENOM='".$prenom."',
							UDEVICE='".$platf."',
							MDATE='".$cdate."',
							MHEURE='".$ctime."' WHERE GUID='".$guid."'");

					$result=true;
				} else {
					//ajout
					//$ddateu = $ddatec;
					//$dtimeu = $dtimec;
					$query = @i5_query("INSERT INTO VVBASE/VVMOBGUID (
							GUID , UNOM , UPRENOM , UDEVICE , CDATE , CHEURE , MDATE , MHEURE  ) VALUES (
							'".$guid."' , '".$name."' , '".$prenom."' , '".$platf."' , '".$cdate."' , '".$ctime."' , '".$cdate."' , '".$ctime."')");
					$result=true;
				}

			} else {
				$query = @i5_query("create table VVBASE/VVMOBGUID (
						ID INT generated always as identity (start with 1 increment by 1 cycle),
						GUID VARCHAR(255),
						UNOM VARCHAR(50),
						UPRENOM VARCHAR(50),
						UDEVICE VARCHAR(30),
						CDATE VARCHAR(8),
						CHEURE VARCHAR(8),
						MDATE VARCHAR(8),
						MHEURE VARCHAR(8),
						Primary key (ID))
						");
				if ($query) {
					$query = @i5_query("INSERT INTO VVBASE/VVMOBGUID (
							GUID , UNOM , UPRENOM , UDEVICE , CDATE , CHEURE , MDATE , MHEURE  ) VALUES (
							'".$guid."' , '".$name."' , '".$prenom."' , '".$platf."' , '".$cdate."' , '".$ctime."' , '".$cdate."' , '".$ctime."')");
					$result=true;

				}
			}

			if (!i5_close($conn)) {
				$result= false;
			}
		}
		return $result;
	}


	/**
	 * @param string $guid
	 * @return array
	 */
	function mob_getInfos($guid) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

			$result = false;
			$query = @i5_query("select UNOM , UPRENOM , MDATE , MHEURE from VVBASE/VVMOBGUID where GUID='".$guid."'");
			$row = @i5_fetch_row($query,I5_READ_FIRST);
			$result = $row;
			return $result;

		}
		if (!i5_close($conn)) {
		   $result= false;
		}
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
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

			$result = false;


			$vvmobdev_exist = @i5_command("CHKOBJ OBJ(VVBASE/VVMOBGUID) OBJTYPE(*FILE)");
			if ($vvmobdev_exist) {
				// test l'existence de l' uuid du device
				$query = @i5_query("select GUID from VVBASE/VVMOBGUID where GUID='".$guid."'");
				if ( i5_num_rows($query) == 1 ) {
					//update
					$query = @i5_query("UPDATE VVBASE/VVMOBGUID
							SET GUID='".$guid."',
							UDEVICE='".$platf."',
							MDATE='".$cdate."',
							MHEURE='".$ctime."' WHERE GUID='".$guid."'");

					$result=true;
				} else {
					//ajout
					//$ddateu = $ddatec;
					//$dtimeu = $dtimec;
					$query = @i5_query("INSERT INTO VVBASE/VVMOBGUID (
							GUID , UNOM , UPRENOM , UDEVICE , CDATE , CHEURE , MDATE , MHEURE  ) VALUES (
							'".$guid."' , '' , '' , '".$platf."' , '".$cdate."' , '".$ctime."' , '".$cdate."' , '".$ctime."')");
					$result=true;
				}

			} else {
				$query = @i5_query("create table VVBASE/VVMOBGUID (
						ID INT generated always as identity (start with 1 increment by 1 cycle),
						GUID VARCHAR(255),
						UNOM VARCHAR(50),
						UPRENOM VARCHAR(50),
						UDEVICE VARCHAR(30),
						CDATE VARCHAR(8),
						CHEURE VARCHAR(8),
						MDATE VARCHAR(8),
						MHEURE VARCHAR(8),
						Primary key (ID))
						");
				if ($query) {
					$query = @i5_query("INSERT INTO VVBASE/VVMOBGUID (
							GUID , UNOM , UPRENOM , UDEVICE , CDATE , CHEURE , MDATE , MHEURE  ) VALUES (
							'".$guid."' , '' , '' , '".$platf."' , '".$cdate."' , '".$ctime."' , '".$cdate."' , '".$ctime."')");
					$result=true;

				}
			}

			if (!i5_close($conn)) {
				$result= false;
			}
		}
		return $result;
	}


	/**
	 * @return string
	 */
	function mob_pageEnseignes() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

			$result = "false";
			$page_ens ="";
			// test l'existence de la table enseignes
			$vvmobens_exist = @i5_command("CHKOBJ OBJ(VVBASE/VVMOBENS) OBJTYPE(*FILE)");
			if ($vvmobens_exist) {
				$result = "table exist";
				$query = @i5_query("select LIBENS, ID from VVBASE/VVMOBENS ");

				if ( ! is_bool($query) ) {
					$result = "requete reussie ";
					$i=0;
					while ($row = @i5_fetch_row($query,I5_READ_NEXT)) {
						$page_ens = $page_ens.'<!--   section '.$row[0].' -->
					<div data-role="collapsible" data-collapsed="true" data-theme="a"
						data-content-theme="b">
						<h3>'.$row[0].'</h3>
						';
						$page_ens =  $page_ens.'<ul data-role="listview" data-theme="b" data-dividertheme="b"
							data-inset="false">
							';
						$query_mag = @i5_query("select * from VVBASE/VVMOBMAG  where IDENS='".$row[1]."'");
						while ($rowmag = @i5_fetch_row($query_mag,I5_READ_NEXT)) {
							$page_ens =  $page_ens.'<li><a id="click-mag'.$rowmag[2].'">'.$rowmag[3].'</a></li>
							';
						}
						$page_ens =  $page_ens.'</ul>
						</div>
						';
						$i++;
					}

					 $result=$page_ens;
				}
			} else {
				$query = @i5_query("create table VVBASE/VVMOBENS (
									ID INT generated always as identity
									(start with 1 increment by 1 cycle),
									LIBENS VARCHAR(50),
									Primary key (ID))
				");

				if ($query) {


				    @i5_query("INSERT INTO VVBASE/VVMOBENS (
				    						 LIBENS ) VALUES (
				    					'Enseigne GEANT')");
					@i5_query("INSERT INTO VVBASE/VVMOBENS (
				    						 LIBENS ) VALUES (
				    					'Enseigne CASINO')");
					@i5_query("INSERT INTO VVBASE/VVMOBENS (
				    						 LIBENS ) VALUES (
				    					'Enseigne ECOMAX')");
				}
				$query_mag = @i5_query("create table VVBASE/VVMOBMAG (
									ID INT generated always as identity
									(start with 1 increment by 1 cycle),
									IDENS VARCHAR(3),
									NUMMAG VARCHAR(5),
									LIBMAG VARCHAR(50),
									PAYSMAG VARCHAR(30),
									LATMAG VARCHAR(10),
									LNGMAG VARCHAR(10),
									Primary key (ID))
									");
				if ($query_mag) {
					@i5_query("INSERT INTO VVBASE/VVMOBMAG (
				    						 IDENS , NUMMAG , LIBMAG , PAYSMAG , LATMAG , LNGMAG ) VALUES (
				    					'1' , '302' , 'LA BATELIERE' , 'MARTINIQUE', '14,60' , '-61,09')");
					@i5_query("INSERT INTO VVBASE/VVMOBMAG (
				    						 IDENS , NUMMAG , LIBMAG , PAYSMAG , LATMAG , LNGMAG ) VALUES (
				    					'1' , '306' , 'OCEANIS' , 'MARTINIQUE' , '' , '')");
					@i5_query("INSERT INTO VVBASE/VVMOBMAG (
				    						 IDENS , NUMMAG , LIBMAG , PAYSMAG , LATMAG , LNGMAG ) VALUES (
				    					'1' , '310' , 'BAS DU FORT' , 'GUADELOUPE' , '' , '')");
					@i5_query("INSERT INTO VVBASE/VVMOBMAG (
				    						 IDENS , NUMMAG , LIBMAG , PAYSMAG , LATMAG , LNGMAG ) VALUES (
				    					'1' , '309' , 'CAYENNE' , 'GUYANNE' , '' , '')");

				}
				$query = @i5_query("select LIBENS, ID from VVBASE/VVMOBENS ");

				if ( $query ) {
					$i=0;
					while ($row = @i5_fetch_row($query,I5_READ_NEXT)) {
						$page_ens = $page_ens.'<!--   section '.$row[0].' -->
					<div data-role="collapsible" data-collapsed="true" data-theme="a"
						data-content-theme="b">
						<h3>'.$row[0].'</h3>
						';
						$page_ens =  $page_ens.'<ul data-role="listview" data-theme="b" data-dividertheme="b"
							data-inset="false">
							';
						$query_mag = @i5_query("select * from VVBASE/VVMOBMAG  where IDENS='".$row[1]."'");
						while ($rowmag = @i5_fetch_row($query_mag,I5_READ_NEXT)) {
							$page_ens =  $page_ens.'<li><a id="click-mag'.$rowmag[2].'">'.$rowmag[3].'</a></li>
							';
						}
						$page_ens =  $page_ens.'</ul>
						</div>
						';
						$i++;
					}

					 $result=$page_ens;
				}

			}

			if (!i5_close($conn)) {
				$result= "false";
			}
		}
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
		include 'Net/SSH2.php';

		$ssh = new Net_SSH2($ip);
		if (!$ssh->login($user, $passwd)) {
		   $result = 'Login Failed';
		}

		$result=$ssh->exec($cmd);
		return $result;
		//echo $ssh->exec('ls -la');
	}


	/**
	 *
	 * Enter description here ...
	 * @param string $annee
	 * @param string $mois
	 * @param string $jour
	 * @param string $nmag
	 * @return string
	 */
	function orkcentral_ca_tmp($annee,$mois,$jour,$nmag) {
		include 'Net/SSH2.php';

		$ssh = new Net_SSH2('10.21.0.120');
		if (!$ssh->login('root', 'orika')) {
		   $result = 'Login Failed';
		}

		//$ssh->exec('cd vvscripts');
		$cmd = "python /root/vvscripts_central/ca_live.py ".$annee."-".$mois."-".$jour." ".$nmag;
		$result=trim($ssh->exec($cmd));
		return $result;

	}

	/**
	 *
	 * Enter description here ...
	 * @param string $annee
	 * @param string $mois
	 * @param string $jour
	 * @param string $ipmag
	 * @return array
	 */
	function ork_ca_tmp($annee,$mois,$jour,$ipmag) {
		include 'Net/SSH2.php';

		$ips = explode(",","$ipmag");

		$tab = array();
		if ( $ips > 1) {
			for ($i=0;$i<(count($ips));$i++) {
				$ssh = new Net_SSH2($ips[$i]);
				if (!$ssh->login('root', 'orika')) {
					$result = 'Login Failed';
				}

				//$cmd = " if test -d /root/vvscripts;  then  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ;. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
				$cmd = " if test -d /root/vvscripts;  then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";

				$ca=trim($ssh->exec($cmd));
				array_push($tab,$ca);
			}
			$result=$tab;
		} else {

			$ssh = new Net_SSH2($ipmag);
			if (!$ssh->login('root', 'orkaisse')) {
				$result = 'Login Failed';
			}

			$cmd = " if test -d /root/vvscripts;  then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
			$ca=trim($ssh->exec($cmd));
			array_push($tab,$ca);
			$result=$tab;
		}


		return $result;

	}

/**
 *
* Enter description here ...
* @param string $ip
* @param string $annee
 * @param string $mois
* @param string $jour
* @return array
*/
function ork_ca_mag($ip,$annee,$mois,$jour) {
       include 'Net/SSH2.php';
            $fp = @fsockopen($ip, 22, $errno, $errstr,  3);
            $obj = array();


            $obj["camag"] = '0';
            $obj["climag"] = '0';
		        if ( $fp ) {
              fclose($fp);
             $ssh = new Net_SSH2('10.21.0.120');
			       //$ssh = new Net_SSH2($ips[$i]["ipmag"]);
                if ( $ssh->login('root', 'orika')  ) {

                  $cca=trim($ssh->exec("echo  -n $(sh /root/vvscripts/vvgetcajour.sh ".($ip)."   '".$annee."-".$mois."-".$jour."' ) ")," \t\n\r");
                  $ccli=trim($ssh->exec("echo   -n $(sh /root/vvscripts/vvgetclijour.sh ".($ip)."   '".$annee."-".$mois."-".$jour."' ) ")," \t\n\r");

                   if ( !empty($cca) ) {
                    $obj["camag"] = ($cca);
                 }
                 if ( !empty($ccli) ) {
                    $obj["climag"] = ($ccli);
                 }
                }
              }
          return $obj;
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
function ork_ca_tesths($ensnom,$annee,$mois,$jour) {
  include 'Net/SSH2.php';
  $sql="SELECT DISTINCT IPMAG,NOMMAG,NUMMAG FROM VVBASE/VVMOBMAGIP  WHERE  ENSNOM LIKE '$ensnom%' ";

		$ips = $this->query_as400JSON($sql);

    $tab = array();
    $nbmag = count($ips);

    if ($ensnom == "ECOMAX GP") {
     $nbmag=27;
    }

		for ($i=0;$i<($nbmag);$i++) {

            $obj = array();

            $obj["nommag"] = $ips[$i]["nommag"];
            $obj["nummag"] = $ips[$i]["nummag"];
            $obj["camag"] = '0';
            $obj["climag"] = '0';
           //if ( !($obj["nummag"] ==  "66")  ) {

    try {
            $fp = @fsockopen($ips[$i]["ipmag"], 22, $errno, $errstr,  3);
		        if ( $fp ) {
              fclose($fp);
             $ssh = new Net_SSH2('10.21.0.120');
			       //$ssh = new Net_SSH2($ips[$i]["ipmag"]);
                if ( $ssh->login('root', 'orika') ) {

                  $cca=trim($ssh->exec("echo  -n $(sh /root/vvscripts/vvgetcajour.sh ".($ips[$i]["ipmag"])."   '".$annee."-".$mois."-".$jour."' ) ")," \t\n\r");
                  $ccli=trim($ssh->exec("echo   -n $(sh /root/vvscripts/vvgetclijour.sh ".($ips[$i]["ipmag"])."   '".$annee."-".$mois."-".$jour."' ) ")," \t\n\r");
                   if ( !empty($cca) ) {
                    $obj["camag"] = ($cca);
                 }
                 if ( !empty($ccli) ) {
                    $obj["climag"] = ($ccli);
                 }
                  /*
                     $cmd = " if test  -f /root/vvscripts/get_ca_jour.sh;   then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh -n root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
                        $cmdb = " if test -f /root/vvscripts/get_cli_jour.sh;  then  echo $(. /root/vvscripts/get_cli_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh -n root@10.21.0.120 'cd /root ; tar czf - vvscripts/get_cli_jour.sh' | tar xzf - ; /root/vvscripts/get_cli_jour.sh ".$annee."-".$mois."-".$jour." ); fi";


                        $ca=trim($ssh->exec($cmd)," \t\n\r");
                         $cli=trim($ssh->exec($cmdb)," \t\n\r");

                  if ( !empty($ca) ) {
                    $obj["camag"] = ($ca);
                 }
                 if ( !empty($cli) ) {
                    $obj["climag"] = ($cli);
                 }
                 */

                }
                //$ssh->close();
              } else {
                $ssh = new Net_SSH2('10.21.0.120');
                if ( $ssh->login('root', 'orkaisse') ) {

                  $cca=trim($ssh->exec("echo  -n $(sh /root/vvscripts/vvgetcajour.sh ".($ips[$i]["ipmag"])."   '".$annee."-".$mois."-".$jour."' ) ")," \t\n\r");
                  $ccli=trim($ssh->exec("echo   -n $(sh /root/vvscripts/vvgetclijour.sh ".($ips[$i]["ipmag"])."   '".$annee."-".$mois."-".$jour."' ) ")," \t\n\r");
                   if ( !empty($cca) ) {
                    $obj["camag"] = ($cca);
                 }
                 if ( !empty($ccli) ) {
                    $obj["climag"] = ($ccli);
                 }
                  /*
                     $cmd = " if test  -f /root/vvscripts/get_ca_jour.sh;   then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh -n root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
                        $cmdb = " if test -f /root/vvscripts/get_cli_jour.sh;  then  echo $(. /root/vvscripts/get_cli_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh -n root@10.21.0.120 'cd /root ; tar czf - vvscripts/get_cli_jour.sh' | tar xzf - ; /root/vvscripts/get_cli_jour.sh ".$annee."-".$mois."-".$jour." ); fi";


                        $ca=trim($ssh->exec($cmd)," \t\n\r");
                         $cli=trim($ssh->exec($cmdb)," \t\n\r");

                  if ( !empty($ca) ) {
                    $obj["camag"] = ($ca);
                 }
                 if ( !empty($cli) ) {
                    $obj["climag"] = ($cli);
                 }
                 */

                }

            }
          } catch (Exception $e) {
             $obj["camag"] = '0';
            $obj["climag"] = '0';
          }
            array_push($tab,$obj);

  }
    $result=$tab;
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
		include 'Net/SSH2.php';

		$sql="SELECT DISTINCT t2.IPMAG, t1.NOMMAG, t1.NUMMAG FROM VVBASE/VVMOBMAGIP t1 , VVBASE/VVSECMAGIP t2 WHERE  t1.ENSNOM LIKE '$ensnom%' AND t1.IPMAG=t2.IPMAG ";
		//"SELECT t2.IPMAG FROM VVBASE/VVMOBMAGIP t1 , VVBASE/VVSECMAGIP t2 WHERE t2.GUID='$guid' AND t1.ENSNOM LIKE '$ensnom%' AND t1.IPMAG=t2.IPMAG ";
		$ips = $this->query_as400JSON($sql);

		$tab = array();

		for ($i=0;$i<(count($ips));$i++) {
		    $fp = @fsockopen($ips[$i]["ipmag"], 22, $errno, $errstr, 16);
		    if ( $fp ) {
			        $ssh = new Net_SSH2($ips[$i]["ipmag"]);
              if (!$ssh->login('root', 'orika')) {
                if (!$ssh->login('root', 'orkaisse')) {
                      $result = 'Login Failed';
                }
              }

              //$cmd = " if test -d /root/vvscripts;  then  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ;. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
              $cmd = " if test -d /root/vvscripts;  then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
              $cmdb = " if test -f /root/vvscripts/get_cli_jour.sh;  then  echo $(. /root/vvscripts/get_cli_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts/get_cli_jour.sh' | tar xzf - ; /root/vvscripts/get_cli_jour.sh ".$annee."-".$mois."-".$jour." ); fi";


              $ca=trim($ssh->exec($cmd));
              $cli=trim($ssh->exec($cmdb));


              $obj = array();
              //$obj["ipmag"] = $ips[$i]["ipmag"];
              $obj["nommag"] = $ips[$i]["nommag"];
              $obj["nummag"] = $ips[$i]["nummag"];
              $obj["camag"] = $ca;
              $obj["climag"] = $cli;

              array_push($tab,$obj);
		 }
		}
		$result=$tab;
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
	function ork_ca_tmp_hs($ensnom,$annee,$mois,$jour) {
		include 'Net/SSH2.php';

    $ensnom = "$ensnom";
    if ($ensnom == "ECOMAX GP") {
     $ensnom="ECOMAX GP";
    }
		$sql="SELECT DISTINCT IPMAG,NOMMAG,NUMMAG FROM VVBASE/VVMOBMAGIP  WHERE  ENSNOM LIKE '$ensnom%' ";
		//"SELECT DISTINCT t2.IPMAG, t1.NOMMAG, t1.NUMMAG FROM VVBASE/VVMOBMAGIP t1 , VVBASE/VVSECMAGIP t2 WHERE  t1.ENSNOM LIKE '$ensnom%' AND t1.IPMAG=t2.IPMAG ";
		//"SELECT t2.IPMAG FROM VVBASE/VVMOBMAGIP t1 , VVBASE/VVSECMAGIP t2 WHERE t2.GUID='$guid' AND t1.ENSNOM LIKE '$ensnom%' AND t1.IPMAG=t2.IPMAG ";
		$ips = $this->query_as400JSON($sql);

		$tab = array();
    $nbmag = count($ips);
    if ($ensnom == "ECOMAX GP") {
     //$nbmag=30;
     $ensnom="ecomax_gp";
      $ssh0 = new Net_SSH2('10.21.0.120');
      if ( $ssh0->login('root', 'orika') ) {
            $ssh0->exec("rm   /root/vvscripts/liste_".$ensnom.".txt && touch /root/vvscripts/liste_".$ensnom.".txt");
      }
      $z=0;
      while ($z < $nbmag) {
        $ssh = new Net_SSH2($ips[$z]["ipmag"]);
				if ( $ssh->login('root', 'orika') ) {
            	$cmd = " if test -d /root/vvscripts;  then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo -n $(ssh -n root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
						$cmdb = " if test -f /root/vvscripts/get_cli_jour.sh;  then  echo $(. /root/vvscripts/get_cli_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo -n $(ssh -n root@10.21.0.120 'cd /root ; tar czf - vvscripts/get_cli_jour.sh' | tar xzf - ; /root/vvscripts/get_cli_jour.sh ".$annee."-".$mois."-".$jour." ); fi";


						$ca=trim($ssh->exec($cmd));
            $cli=trim($ssh->exec($cmdb));

            $ssh0->exec("echo  '".$ips[$z]["ipmag"].",". $ips[$z]["nummag"].",".$ips[$z]["nommag"].",".$ca.",".$cli."'  >> /root/vvscripts/liste_".$ensnom.".txt");
        } else {
          $ssh->login('root', 'orkaisse');
          $cmd = " if test -d /root/vvscripts;  then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo -n $(ssh -n root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
						$cmdb = " if test -f /root/vvscripts/get_cli_jour.sh;  then  echo $(. /root/vvscripts/get_cli_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo -n $(ssh -n root@10.21.0.120 'cd /root ; tar czf - vvscripts/get_cli_jour.sh' | tar xzf - ; /root/vvscripts/get_cli_jour.sh ".$annee."-".$mois."-".$jour." ); fi";


						$ca=trim($ssh->exec($cmd));
            $cli=trim($ssh->exec($cmdb));

            $ssh0->exec("echo  '".$ips[$z]["ipmag"].",". $ips[$z]["nummag"].",".$ips[$z]["nommag"].",".$ca.",".$cli."'  >> /root/vvscripts/liste_".$ensnom.".txt");
        }
        $z++;
      }
      $lines = explode("\n",$ssh0->exec("cat  /root/vvscripts/liste_".$ensnom.".txt"));
     /* for ($i=0; $i < count($lines) ; $i++) {
        $line = explode(",",$lines[$i]);
        $obj = array();
				//$obj["ipmag"] = $ips[$i]["ipmag"];
        $obj["nommag"] = trim($line[2]);
				$obj["nummag"] = trim($line[1]);
				$obj["camag"] = trim($line[3]);
        $obj["climag"] = trim($line[4]);
        array_push($tab,$obj);
      }
      */
      $obj = array();
				//$obj["ipmag"] = $ips[$i]["ipmag"];
        $obj["nommag"] = '';
				$obj["nummag"] = '';
				$obj["camag"] = '0';
        $obj["climag"] = '0';
        array_push($tab,$obj);
    }  else {

   /* if ($ensnom == "ECOMAX GP") {
     $nbmag=30;
     $ensnom="ecomax_gp";
    }*/
    /*$ssh0 = new Net_SSH2('10.21.0.120');
		if ( $ssh0->login('root', 'orika') ) {
          $ssh0->exec("rm   /root/vvscripts/liste_".$ensnom.".txt && touch /root/vvscripts/liste_".$ensnom.".txt");
    }*/

    $sshA = array();
    $sshB = array();

    $z=0;
		//for ($z=0;$z<($nbmag);$z++) {
    while ($z < $nbmag) {



				$obj = array();

				$obj["nommag"] = $ips[$z]["nommag"];
				$obj["nummag"] = $ips[$z]["nummag"];
				$obj["camag"] = '0';
				$obj["climag"] = '0';

		    $fp = @fsockopen($ips[$z]["ipmag"], 22, $errno, $errstr,  0.1);
		    if ( !$fp ) {

				$obj = array();

				$obj["nommag"] = $ips[$z]["nommag"];
				$obj["nummag"] = $ips[$z]["nummag"];
				$obj["camag"] = '0';
				$obj["climag"] = '0';

				array_push($tab,$obj);
			} else {
         fclose($fp);
				$ssh = new Net_SSH2($ips[$z]["ipmag"]);
				if ( $ssh->login('root', 'orika') ) {

          try {
					//$cmd = " if test -d /root/vvscripts;  then  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ;. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
						$cmd = " if test -d /root/vvscripts;  then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo -n $(ssh -n root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
						$cmdb = " if test -f /root/vvscripts/get_cli_jour.sh;  then  echo $(. /root/vvscripts/get_cli_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo -n $(ssh -n root@10.21.0.120 'cd /root ; tar czf - vvscripts/get_cli_jour.sh' | tar xzf - ; /root/vvscripts/get_cli_jour.sh ".$annee."-".$mois."-".$jour." ); fi";


						$ca=trim($ssh->exec($cmd));
            $cli=trim($ssh->exec($cmdb));



            // $ssh0->exec("echo  '".$ips[$z]["ipmag"].",". $ips[$z]["nummag"].",".$ips[$z]["nommag"].",".$ca.",".$cli."'  >> /root/vvscripts/liste_".$ensnom.".txt");


						$obj = array();
						//$obj["ipmag"] = $ips[$i]["ipmag"];
						$obj["nommag"] = $ips[$z]["nommag"];
						$obj["nummag"] = $ips[$z]["nummag"];
						$obj["camag"] = ($ca);
						$obj["climag"] =  ($cli);

						array_push($tab,$obj);
            } catch (Exception $e) {
              $obj = array();
              //$obj["ipmag"] = $ips[$i]["ipmag"];
              $obj["nommag"] = $ips[$z]["nommag"];
              $obj["nummag"] = $ips[$z]["nummag"];
              $obj["camag"] = '0';
              $obj["climag"] = '0';

              array_push($tab,$obj);
            }

				}  else {
					$ssh1 = new Net_SSH2($ips[$z]["ipmag"]);
					if  ( $ssh1->login('root', 'orkaisse') )  {

             try {
						//$cmd = " if test -d /root/vvscripts;  then  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ;. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
							$cmd = " if test -d /root/vvscripts;  then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo -n $(ssh  -n root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
							$cmdb = " if test -f /root/vvscripts/get_cli_jour.sh;  then  echo $(. /root/vvscripts/get_cli_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo -n  $(ssh -n root@10.21.0.120 'cd /root ; tar czf - vvscripts/get_cli_jour.sh' | tar xzf - ; /root/vvscripts/get_cli_jour.sh ".$annee."-".$mois."-".$jour." ); fi";


							$ca=trim($ssh1->exec($cmd));
							$cli=trim($ssh1->exec($cmdb));


             //  $ssh0->exec("echo  '".$ips[$z]["ipmag"].",". $ips[$z]["nummag"].",".$ips[$z]["nommag"].",".$ca.",".$cli."'  >> /root/vvscripts/liste_".$ensnom.".txt");

							$obj = array();
							//$obj["ipmag"] = $ips[$i]["ipmag"];
							$obj["nommag"] = $ips[$z]["nommag"];
							$obj["nummag"] = $ips[$z]["nummag"];
							$obj["camag"] =($ca);
							$obj["climag"] = ($cli);

              array_push($tab,$obj);
               } catch (Exception $e) {
              $obj = array();
              //$obj["ipmag"] = $ips[$i]["ipmag"];
              $obj["nommag"] = $ips[$z]["nommag"];
              $obj["nummag"] = $ips[$z]["nummag"];
              $obj["camag"] = '0';
              $obj["climag"] = '0';

              array_push($tab,$obj);
            }

					} else {


							$obj = array();
							//$obj["ipmag"] = $ips[$i]["ipmag"];
							$obj["nommag"] = $ips[$z]["nommag"];
							$obj["nummag"] = $ips[$z]["nummag"];
							$obj["camag"] = '0';
							$obj["climag"] = '0';

							array_push($tab,$obj);



					}
				// fin test orkaisse
			}
		  //fin test orika
		}
    // fin test socket
    $z++;
    }
    // fin while
  }
		$result=$tab;
		return $result;
	}


	/**
	 *
	 * Enter description here ...
	 * @param string $ensnom
	 * @return array
	 */
	function ork_ca_evo_mois($ensnom) {
		include 'Net/SSH2.php';

		$sql="SELECT DISTINCT t2.IPMAG, t1.NOMMAG, t1.NUMMAG FROM VVBASE/VVMOBMAGIP t1 , VVBASE/VVSECMAGIP t2 WHERE  t1.ENSNOM LIKE '$ensnom%' AND t1.IPMAG=t2.IPMAG ";
		//"SELECT t2.IPMAG FROM VVBASE/VVMOBMAGIP t1 , VVBASE/VVSECMAGIP t2 WHERE t2.GUID='$guid' AND t1.ENSNOM LIKE '$ensnom%' AND t1.IPMAG=t2.IPMAG ";
		$ips = $this->query_as400JSON($sql);

		$tab = array();

		for ($i=0;$i<(count($ips));$i++) {
			$ssh = new Net_SSH2($ips[$i]["ipmag"]);
			if (!$ssh->login('root', 'orika')) {
				$result = 'Login Failed';
			}

			//$cmd = " if test -d /root/vvscripts;  then  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ;. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
			//$cmd = " if test -d /root/vvscripts;  then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
			$cmd = " if test -f /root/vvscripts/get_ca_evo_mois.py;  then  echo $(python /root/vvscripts/get_ca_evo_mois.py); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts/get_ca_evo_mois.py' | tar xzf - ; python /root/vvscripts/get_ca_evo_mois.py ); fi";


			$ca=trim($ssh->exec($cmd));
			//$cli=trim($ssh->exec($cmdb));


			$obj = array();
			//$obj["ipmag"] = $ips[$i]["ipmag"];
			$obj["nommag"] = $ips[$i]["nommag"];
			$obj["nummag"] = $ips[$i]["nummag"];
			$obj["caevo"] = $ca;
			//$obj["climag"] = $cli;

			array_push($tab,$obj);
		}
		$result=$tab;
		return $result;

	}


	/**
	 *
	 * Enter description here ...
	 * @param string $ensnom
	 * @return array
	 */
	function ork_evo_mois($ensnom) {
		include 'Net/SSH2.php';

		$sql="SELECT DISTINCT t2.IPMAG, t1.NOMMAG, t1.NUMMAG FROM VVBASE/VVMOBMAGIP t1 , VVBASE/VVSECMAGIP t2 WHERE  t1.ENSNOM LIKE '$ensnom%' AND t1.IPMAG=t2.IPMAG ";
		//"SELECT t2.IPMAG FROM VVBASE/VVMOBMAGIP t1 , VVBASE/VVSECMAGIP t2 WHERE t2.GUID='$guid' AND t1.ENSNOM LIKE '$ensnom%' AND t1.IPMAG=t2.IPMAG ";
		$ips = $this->query_as400JSON($sql);

		$tab = array();

		for ($i=0;$i<(count($ips));$i++) {
			$ssh = new Net_SSH2($ips[$i]["ipmag"]);
			if (!$ssh->login('root', 'orika')) {
				$result = 'Login Failed';
			}

			//$cmd = " if test -d /root/vvscripts;  then  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ;. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
			//$cmd = " if test -d /root/vvscripts;  then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
			$cmd = " if test -f /root/vvscripts/get_ca_evo_mois.py;  then  echo $(python /root/vvscripts/get_ca_evo_mois.py); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts/get_ca_evo_mois.py' | tar xzf - ; python /root/vvscripts/get_ca_evo_mois.py ); fi";


			$ca=trim($ssh->exec($cmd));
			//$cli=trim($ssh->exec($cmdb));


			$obj = array();
			//$obj["ipmag"] = $ips[$i]["ipmag"];
			$obj["nommag"] = $ips[$i]["nommag"];
			$obj["nummag"] = $ips[$i]["nummag"];
			$obj["caevo"] = $ca;
			//$obj["climag"] = $cli;

			array_push($tab,$obj);
		}
		$result=$tab;
		return $result;

	}


	/**
	 *
	 * Enter description here ...
	 * @param string $guid
	 * @param string $ensnom
	 * @param string $annee
	 * @param string $mois
	 * @param string $jour
	 * @return array
	 */
	function ork_ca_sec($guid,$ensnom,$annee,$mois,$jour) {
		include 'Net/SSH2.php';

		$sql="SELECT t2.IPMAG, t1.NOMMAG, t1.NUMMAG FROM VVBASE/VVMOBMAGIP t1 , VVBASE/VVSECMAGIP t2 WHERE t2.GUID='$guid' AND t1.ENSNOM LIKE '$ensnom%' AND t1.IPMAG=t2.IPMAG ";
		//"SELECT t2.IPMAG FROM VVBASE/VVMOBMAGIP t1 , VVBASE/VVSECMAGIP t2 WHERE t2.GUID='$guid' AND t1.ENSNOM LIKE '$ensnom%' AND t1.IPMAG=t2.IPMAG ";
		$ips = $this->query_as400JSON($sql);

		$tab = array();

		for ($i=0;$i<(count($ips));$i++) {
			$ssh = new Net_SSH2($ips[$i]["ipmag"]);
			if (!$ssh->login('root', 'orika')) {
				$result = 'Login Failed';
			}

			//$cmd = " if test -d /root/vvscripts;  then  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ;. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
			$cmd = " if test -d /root/vvscripts;  then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";

			$ca=trim($ssh->exec($cmd));

			$obj = array();
			//$obj["ipmag"] = $ips[$i]["ipmag"];
			$obj["nommag"] = $ips[$i]["nommag"];
			$obj["nummag"] = $ips[$i]["nummag"];
			$obj["camag"] = $ca;

			array_push($tab,$obj);
		}
		$result=$tab;
		return $result;

	}

	/**
	 *
	 * Enter ork_secmag here ...
	 * @param string $guid
	 * @param string $ensnom
	 * @param string $annee
	 * @param string $mois
	 * @param string $jour
	 * @return array
	 */
	function ork_secmag($guid,$ensnom,$annee,$mois,$jour) {
		include 'Net/SSH2.php';

		$sql="SELECT t2.IPMAG, t1.NOMMAG FROM VVBASE/VVMOBMAGIP t1 , VVBASE/VVSECMAGIP t2 WHERE t2.GUID='$guid' AND t1.ENSNOM LIKE '$ensnom%' AND t1.IPMAG=t2.IPMAG ";
		$ips = $this->query_as400JSON($sql);

		$tab = array();

		for ($i=0;$i<(count($ips));$i++) {
			$ssh = new Net_SSH2($ips[$i]["ipmag"]);
			if (!$ssh->login('root', 'orika')) {
				$result = 'Login Failed';
			}

			//$cmd = " if test -d /root/vvscripts;  then  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ;. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
			$cmd = " if test -d /root/vvscripts;  then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";

			$ca=trim($ssh->exec($cmd));
			array_push($tab,$ca);
		}

		$result=$tab;
		return $result;

	}


	/**
	 *
	 * Enter ork_secmag here ...
	 * @param string $guid
	 * @param string $ensnom
	 * @param string $annee
	 * @param string $mois
	 * @param string $jour
	 * @return object
	 */
	function ork_secmob($guid,$ensnom,$annee,$mois,$jour) {
		include 'Net/SSH2.php';

		$sql="SELECT t2.IPMAG, t1.NOMMAG, t1.NUMMAG FROM VVBASE/VVMOBMAGIP t1 , VVBASE/VVSECMAGIP t2 WHERE t2.GUID='$guid' AND t1.ENSNOM LIKE '$ensnom%' AND t1.IPMAG=t2.IPMAG ";
		$ips = $this->query_as400JSON($sql);

		$tab = array();

		for ($i=0;$i<(count($ips));$i++) {
			$ssh = new Net_SSH2($ips[$i]["ipmag"]);
			if (!$ssh->login('root', 'orika')) {
				$result = 'Login Failed';
			}

			//$cmd = " if test -d /root/vvscripts;  then  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ;. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
			$cmd = " if test -d /root/vvscripts;  then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";

			$obj = array();

			$ca = trim($ssh->exec($cmd));
			$obj["nommag"] = $ips[$i]["nommag"];
			$obj["nummag"] = $ips[$i]["nummag"];
			$obj["camag"] = $ca;

			array_push($tab,$obj);
		}

		$result=$tab;
		return $result;

	}

	/**
	 *
	 * Enter description here ...
	 * @param string $guid
	 * @param string $ensnom
	 * @param string $annee
	 * @param string $mois
	 * @param string $jour
	 * @return array
	 */
	function orkmob($guid,$ensnom,$annee,$mois,$jour) {
		include 'Net/SSH2.php';

		$sql="SELECT t2.IPMAG, t1.NOMMAG, t1.NUMMAG FROM VVBASE/VVMOBMAGIP t1 , VVBASE/VVSECMAGIP t2 WHERE t2.GUID='$guid' AND t1.ENSNOM LIKE '$ensnom%' AND t1.IPMAG=t2.IPMAG ";
		$ips = $this->query_as400JSON($sql);

		$tab = array();

		for ($i=0;$i<(count($ips));$i++) {
			$ssh = new Net_SSH2($ips[$i]["ipmag"]);
			if (!$ssh->login('root', 'orika')) {
				$result = 'Login Failed';
			}

			//$cmd = " if test -d /root/vvscripts;  then  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ;. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";
			$cmd = " if test -d /root/vvscripts;  then  echo $(. /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts' | tar xzf - ; /root/vvscripts/get_ca_jour.sh ".$annee."-".$mois."-".$jour." ); fi";

			$ca=trim($ssh->exec($cmd));

			$obj = array();
			$obj["ipmag"] = $ips[$i]["ipmag"];


			array_push($tab,$obj);
		}
		$result=$tab;
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
		include 'Net/SSH2.php';


		$tab = array();

		$ssh = new Net_SSH2($ip);
		if (!$ssh->login('root', 'orika')) {
			$result = 'Login Failed';
		} else {
			if (!$ssh->login('root', 'orkaisse')) {
				$result = 'Login Failed';
			} else {
				$cmd = " if test -f /root/vvscripts/vvorkaws.sh;  then  echo $(. /root/vvscripts/vvorkaws.sh ".$act." ".$cnum." ); else  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts/vvorkaws.sh' | tar xzf - ; /root/vvscripts/vvorkaws.sh ".$act." ".$cnum." ); fi";

				$result=trim(utf8_encode($ssh->exec($cmd)));
			}
		}
		return $result;

	}


	/**
	 *
	 * Enter description here ...
	 * @param string $ip
	 * @return string
	 */
	function testnbcaisses($ip) {
		include 'Net/SSH2.php';


		$tab = array();

		$ssh = new Net_SSH2($ip);
		if (!$ssh->login('root', 'orika')) {
			$result = 'false';
		} else {

			$cmd = "  echo $(ssh root@10.21.0.120 'cd /root ; tar czf - vvscripts/vvorkaws.sh' | tar xzf - ; /root/vvscripts/vvorkaws.sh 6 )";

			$result=trim(utf8_encode($ssh->exec($cmd)));
		}
		return $result;

	}



	/**
	 *
	 * Enter description here ...
	 * @param string $prenom
	 * @param string $nom
	 * @return string
	 */
	function addLinuxUser($prenom,$nom) {
		include 'Net/SSH2.php';

		$ssh = new Net_SSH2('10.2.100.100');
		if (!$ssh->login('root', 'indianflute')) {
		   $result = 'Login Failed';
		}

		$cmd1 = 'useradd '.$prenom.'.'.$nom;
		$ssh->exec($cmd1);
		$cmd2 = "echo ".substr($prenom,0,1)."!".$nom." > newpass && echo ".substr($prenom,0,1)."!".$nom." >> newpass && cat newpass | passwd -q ".$prenom.".".$nom;
		$result=$ssh->exec($cmd2);

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
	function ssh2FilePut($ip,$user,$passwd,$path,$filename,$data) {
		include 'Net/SFTP.php';
		$result="false";

		$sftp = new Net_SFTP($ip);
		if (!$sftp->login($user, $passwd)) {
		    $result = 'Login Failed';
		}

		$sftp->chdir($path);
		$sftp->put($filename, $data);

		return  $result;
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
	 * @param string $donnes
	 * @param string $file
	 * @return boolean
	 */
	function writeDataFile($donnes,$file) {

		$fichier1 = @fopen($file, "w+");
		@fwrite($fichier1, $donnes);
		@fclose($fichier1);

		return true;
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


		$result = false;
		// load parameters to variables

		// set the transport to SMTP
		$tr = new Zend_Mail_Transport_Smtp('10.2.100.100');
		Zend_Mail::setDefaultTransport($tr);

		// create the mail object
		if (!($mail = new Zend_Mail())) {
		      // die("impossible d'utiliser Zend_Mail ");
		      $result = false;
		}

		// set the email text
		$mail->setBodyText($body);

		// set the subject
		$mail->setSubject($subject);

		// set sender
		$mail->setFrom('AS400@multigros.com', "AS400 ne pas repondre!");

		// add the TO email address(es)
		$toAddresses = explode(';',$toEmail);

		foreach ($toAddresses as $address) {
		       $mail->addTo(trim($address));
		}

		// add the attachments (if any)

		if (strlen(trim($attachments)) > 0) {
				       $attachFiles = explode(';',$attachments);
		       foreach ($attachFiles as $Filename) {
		            if (file_exists($Filename)) {
		                        $fileContents = file_get_contents(trim($Filename));
		                $at = $mail->createAttachment($fileContents);
		                $at->filename = trim($Filename);
		            }
		       }
		}

		// send the email
		$mail->send();

		$result = true;
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
	 * Cette m�thode accepte
	 * @param string $user
	 * @param string $pass
	 * @return boolean
	 */
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

	/**
	 * Cette methode accepte
	 * @param string $srvip
	 * @param string $login
	 * @param string $passw
	 * @param string $filename
	 * @return string|boolean
	 */
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
	 		$result = false;
	 	}

	 } else {
	 	$result = false;
	 }

	 ftp_close($ftp);

	 if (file_exists("./".$filename)) {
	 	$result = new ByteArray(file_get_contents("./".$filename));
	 }

	 return $result;

	}



	/**
	 * Cette methode accepte
	 * @param string $srvip
	 * @param string $login
	 * @param string $passw
	 * @param string $filename
	 * @return string|boolean
	 */
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
	 		$result = false;
	 	}
	 } else {
	 	$result = false;
	 }

	 ftp_close($ftp);

		if (file_exists("./".$filename)) {
			$result = new ByteArray(file_get_contents("./".$filename));
		}

		return $result;

	}

	/**
	 * Cette methode accepte
	 * @param string $srvip
	 * @param string $login
	 * @param string $passw
	 * @return array|boolean
	 */
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
				$result = false;
			}

		} else {
			$result = false;
		}

		ftp_close($ftp);

		return $result;

	}

	/**
	 * Cette methode accepte
	 * @return string
	 */
	function lstSplf1000() {

		$result=`system "wrkoutq *all" | awk '{ if (($3 > 1000) && ($3 != 060210) && ($3 != "Fichiers")) {print $0}}'| awk '{ if ($5 == "")  print "<outq name=\""$1"\" bib=\""$2"\" pages=\""$3"\" editeur=\""$5"\" etat=\""$4"\" \/>";  else  print "<outq name=\""$1"\" bib=\""$2"\" pages=\""$3"\" editeur=\""$4"\" etat=\""$5"\" \/>"  }'`;
		return $result;

	}


	/**
	 * Cette methode accepte
	 * @param string $srvip
	 * @param string $login
	 * @param string $passw
	 * @param string $dir
	 * @return array|boolean
	 */
	function ftpListDir($srvip,$login,$passw,$dir) {

		$ftp_server = $srvip;
		$ftp_user = $login;
		$ftp_passwd = $passw;

		$result = false;

		require_once "ftp.api.php";

		if ( $ftp = ftp_connect($ftp_server) ) {
			if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {
				$dirs=explode("|",$dir);
				foreach ($dirs as &$val) {
					ftp_chdir($ftp,$val);
				}

				$result = ftp_nlist($ftp,"");
			} else {
				$result = false;
			}
		} else {
			$result = false;
		}

		ftp_close($ftp);
		//$ftp->disconnect();

		return $result;

	}


	/**
	 * Cette methode accepte
	 * @param string $splfname
	 * @param string $numero
	 * @param string $utilisateur
	 * @param string $travail
	 * @param string $dest
	 * @param string $numfile
	 * @return boolean
	 */
	function chgSplf($splfname,$numero,$utilisateur,$travail,$dest,$numfile) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			if ( i5_command("CHGSPLFA FILE(".$splfname.") JOB(".$numero."/".$utilisateur."/".$travail.") SPLNBR(".$numfile.")  OUTQ(".$dest.")") )  {
				$cmd = false;
			} else {
				$cmd = true;
			}
		}
		return $cmd;
	}

	/**
	 * Cette methode accepte
	 * @param string $numero
	 * @param string $utilisateur
	 * @param string $travail
	 * @return boolean
	 */
	function killJob($numero,$utilisateur,$travail) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			if ( i5_command("ENDJOB JOB($numero/$utilisateur/$travail) OPTION(*IMMED)  DELAY(1)") )  {
				$cmd = true;
			} else {
				$cmd = false;
			}
		}
		return $cmd;
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $ip
	 * @param string $user
	 * @param string $passwd
	 * @param string $filename
	 * @param string $bib
	 * @param string $file400
	 * @return boolean|string
	 */
	function asGetFromFTP($ip,$user,$passwd,$filename,$bib,$file400) {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			//return "Connection reussie";
			i5_command("QSH CMD('echo ''$user $passwd\nget $filename $bib/$file400 (REPLACE\nquit'' > /qsys.lib/hhhpgm.lib/vvsrcprogs.file/ftpcmds.mbr ')");
			$res = i5_command("CALL HHHPGM/VVFTPCMD '$ip'");
			return $res;

			if (! i5_close ( $conn )) {
				// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
				return "Erreur de connection : " . i5_errormsg ();
			}
		}

	}

	/**
	 *
	 * Enter description here ...
	 * @return boolean
	 */
	function pegasTo309() {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			//return "Connection reussie";
			///i5_command("QSH CMD('echo ''$user $passwd\nget $filename $bib/$file400 (REPLACE\nquit'' > /qsys.lib/hhhpgm.lib/vvsrcprogs.file/ftpcmds.mbr ')");
			$res = i5_command("CALL HHHPGM/VVCPT309");
			return $res;

			if (! i5_close ( $conn )) {
				// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
				return false;
			}
		}

	}

	/**
	 *
	 * Enter description here ...
	 * @param string $imp
	 * @return boolean
	 */
	function notExistPrt($imp) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			if ( i5_command("CHKOBJ OBJ($imp) OBJTYPE(*OUTQ)") )  {
				$cmd = false;
			} else {
				$cmd = true;
			}
		}
		if (! i5_close ( $conn )) {
			// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
			return false;
		}
		return $cmd;
	}


	/**
	 *
	 *@param string $biborig
	 *@param string $outqorig
	 *@param string $bibdest
	 *@param string $outqdest
	 *@param string $ip
	 *@return boolean|string
	 */
	function crtdupoutq($biborig,$outqorig,$bibdest,$outqdest,$ip) {

		$result = `system "CRTDUPOBJ OBJ($outqorig) FROMLIB($biborig) OBJTYPE(*OUTQ) TOLIB($bibdest) NEWOBJ($outqdest)"`;
		$step3 = `system "CHGOUTQ OUTQ($bibdest/$outqdest) INTNETADR('$ip')"`;
		//$step4 = `system "STRRMTWTR $outqdest"`;
		return $result;
	}

	/**
	 *
	 *@param string $outqdest
	 *@param string $ip
	 *@return boolean
	 */
	function crtprt2581($outqdest,$ip) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			$step0 = "CRTDUPOBJ OBJ(PROPRTCAI1) FROMLIB(QUSRSYS) OBJTYPE(*OUTQ) TOLIB(QUSRSYS) NEWOBJ(".$outqdest.")";
			$result = i5_command($step0);
			$step2 = "CHGOUTQ OUTQ(QUSRSYS/".$outqdest.") INTNETADR('".$ip."')";
			$step3 = i5_command($step2);
			//$step4 = `system "STRRMTWTR $outqdest"`;
		}
		if (! i5_close ( $conn )) {
			// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
			return false;
		}
		return $step0;
	}

	/**
	 *
	 *@param string $outqdest
	 *@param string $ip
	 *@return boolean|string
	 */
	function crtprtZebra($outqdest,$ip) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			$result = i5_command("CRTDUPOBJ OBJ(CAYPRTZEB1) FROMLIB(QUSRSYS) OBJTYPE(*OUTQ) TOLIB(QUSRSYS) NEWOBJ($outqdest)");
			$step3 = i5_command("CHGOUTQ OUTQ(QUSRSYS/$outqdest) INTNETADR('$ip')");
			//$step4 = `system "STRRMTWTR $outqdest"`;
		}
		if (! i5_close ( $conn )) {
			// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
			return false;
		}
		return $result;
	}

	/**
	 *
	 *@param string $outqdest
	 *@param string $ip
	 *@return boolean|string
	 */
	function crtprtMonarch($outqdest,$ip) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			$result = i5_command("CRTDUPOBJ OBJ(CAYPRTETQA) FROMLIB(QUSRSYS) OBJTYPE(*OUTQ) TOLIB(QUSRSYS) NEWOBJ($outqdest)");
			$step3 = i5_command("CHGOUTQ OUTQ(QUSRSYS/$outqdest) INTNETADR('$ip')");
			//$step4 = `system "STRRMTWTR $outqdest"`;
		}
		if (! i5_close ( $conn )) {
			// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
			return false;
		}
		return $result;
	}


	/**
	 *
	 * Enter description here ...
	 * @param string $bib
	 * @param string $filter
	 * @return string
	 */
	function wrkfbib($bib,$filter) {

		$bib = strtoupper($bib);
		$filter = strtoupper($filter);
		$result = `/home/hdh/wrkfbib.sh $bib $filter`;
		return $result;

	}

	/**
	 *
	 * Enter description here ...
	 * @param string $bib
	 * @param string $file
	 * @return string
	 */
	function filembr($bib,$file) {

		$bib = strtoupper($bib);
		$file = strtoupper($file);
		$result = `/home/hdh/filembr.sh $bib $file`;
		return $result;

	}

	/**
	 *
	 * Enter description here ...
	 * @param string $mask
	 * @return string
	 */
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

	/**
	 *
	 * Enter description here ...
	 * @param string $mask
	 * @return array
	 */
	function lstOutqArr($mask) {

		if ( strrpos($mask,'*')==false ) {
			if ( $this->notExistPrt($mask)) {
				return false;
			}
			$result = `system 'wrkoutq $mask'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /File/) && ($1 !~ /Fichier/)  && ($2 !~ /Bib/) && ($1 != "*") ){ print $1"\t"$2"\t"$3"\t"$4"\t"$5"\t"$6"\t"$7"\t"$8"\t"$9"\t"$10"\t"$11"\t"$12"\t"$13  }}'`;
		} else {
			$result = `system 'wrkoutq $mask'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /File/) && ($1 !~ /Fichier/)  && ($2 !~ /Bib/) && ($1 != "*") && ($0 != "") ){ print $1"\t"$2"\t"$3"\t"$4"\t"$5 }}' | awk '{ if ($5 =="") print $1"\t"$2"\t"$3"\t"$5"\t"$4; else print $1"\t"$2"\t"$3"\t"$4"\t"$5 }'`;
		}

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
	function wrkactjobArr() {



		$result = `system 'wrkactjob'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /File/) && ($1 !~ /Utilisateur/) && ($2 !~ /Utilisateur/) && ($1 !~ /Fichier/)  && ($2 !~ /Bib/) && ($1 != "*") && ($0 != "") && ($0 !~ /\./) ){ print $1"\t"$2"\t"$3"\t"$4"\t"$5"\t"$6"\t"$7"\t"$8"\t"$9"\t"$10"\t"$11"\t"$12"\t"$13 }}' | awk '{ if ($13 =="") print $1"\t"$2"\t"$3"\t"$4"\t"$5"\t"$6"\t"$7"\t"$8"\t"$9"\t"$10"\t"$13"\t"$11"\t"$12; else print $1"\t"$2"\t"$3"\t"$4"\t"$5"\t"$6"\t"$7"\t"$8"\t"$9"\t"$10"\t"$11"\t"$12"\t"$13 }'`;


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
	function wrkjobqArr() {



		$result = `system 'wrkjobq'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /File/) && ($1 !~ /Utilisateur/) && ($2 !~ /Utilisateur/) && ($1 !~ /Fichier/)  && ($2 !~ /Bib/) && ($1 != "*") && ($0 != "") && ($0 !~ /\./) ){ print $1"\t"$2"\t"$3"\t"$4"\t"$5 }}'  | awk '{ if ($5 =="") print $1"\t"$2"\t"$3"\t"$5"\t"$4; else print $1"\t"$2"\t"$3"\t"$4"\t"$5 }'`;


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
	function wrkjobqArrA() {



		$result = `system 'wrkjobq'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /File/) && ($1 !~ /Utilisateur/) && ($2 !~ /Utilisateur/) && ($1 !~ /Fichier/)  && ($2 !~ /Bib/) && ($1 != "*") && ($0 != "") && ($0 !~ /\./) ){ print $1"\t"$2"\t"$3"\t"$4"\t"$5 }}'  | awk '{ if ($5 =="") print $1"\t"$2"\t"$3"\t"$5"\t"$4; else print $1"\t"$2"\t"$3"\t"$4"\t"$5 }'|awk -F"\t" '{ if ($3 != 0) { print $0}}'`;


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
	 * @param string $bib
	 * @param string $jobq
	 * @return array
	 */
	function jobqArr($bib,$jobq) {

		$result = `system 'wrkjobq jobq($bib/$jobq)'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /Travail/) && ($1 != "*") && ($0 != "") && ($0 !~ /\./) ){ print $1"\t"$2"\t"$3"\t"$4"\t"$5 }}'`;

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
	 * @param string $job
	 * @param string $user
	 * @param string $numjob
	 * @param string $jobq
	 * @param string $bib
	 * @return boolean
	 */
	function chgJobq($job,$user,$numjob,$jobq,$bib) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			if ( i5_command("CHGJOB JOB(".$numjob."/".$user."/".$job.") JOBQ(".$bib."/".$jobq.")") )  {
				$cmd = true;
			} else {
				$cmd = false;
			}
		}
		return $cmd;
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $state
	 * @return array
	 */
	function actjobstatebArr($state) {

		$result = `system 'wrkactjob'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /File/) && ($1 !~ /Utilisateur/) && ($2 !~ /Utilisateur/) && ($1 !~ /Fichier/)  && ($2 !~ /Bib/) && ($1 != "*") && ($0 != "") && ($0 !~ /\./) ){ print $1"\t"$2"\t"$3"\t"$4"\t"$5"\t"$6"\t"$7"\t"$8"\t"$9"\t"$10"\t"$11"\t"$12"\t"$13 }}' | awk '{ if ($13 =="") print $1"\t"$2"\t"$3"\t"$4"\t"$5"\t"$6"\t"$7"\t"$8"\t"$9"\t"$10"\t"$13"\t"$11"\t"$12; else print $1"\t"$2"\t"$3"\t"$4"\t"$5"\t"$6"\t"$7"\t"$8"\t"$9"\t"$10"\t"$11"\t"$12"\t"$13 }'| grep $state|awk -F"\t" '{ if ($12 ~ /$state/) {print $1"\t"$2"\t"$3"\t"$4"\t"$5"\t"$6"\t"$7"\t"$8"\t"$9"\t"$10"\t"$11"\t"$12"\t"$13 }}'`;


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
	 * @param string $imp
	 * @return boolean
	 */
	function isDevPrt($imp) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			if ( i5_command("DSPDEVD ".$imp) )  {
				$cmd = true;
			} else {
				$cmd = false;
			}
		}
		return $cmd;
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $imp
	 * @return boolean
	 */
	function startEditeur($imp) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

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

		}
		return $cmd;
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $imp
	 * @return boolean
	 */
	function stopEditeur($imp) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			if ( i5_command("ENDWTR WTR(".$imp.") OPTION(*IMMED)") )  {
				$cmd = true;
			} else {
				$cmd = false;
			}
		}
		return $cmd;
	}


	/**
	 *
	 * Enter description here ...
	 * @return boolean|string
	 */
	function vvtecpgd() {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			if (i5_command("CALL",array("PGM" => "HHHPGM/VVTECPGD"),array(),$conn)) {
				$result = true;
			} Else {
				$result = "Error =".i5_errormsg();
			}
		}

		if (! i5_close ( $conn )) {
			// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
			return "Erreur de connection : " . i5_errormsg ();
	 }
	 return $result;

	}


	/**
	 *
	 * Enter description here ...
	 * @return boolean|string
	 */
	function mgedicasino() {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

			if ( i5_command("CALL PGMCOMET/MGEDICAS ('402125' '00097088' 'EMG402125 ')") ) {
				$result = true;
			} Else {
				$result = "Error =".i5_errormsg();
			}
		}

		if (! i5_close ( $conn )) {
			// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
			return "Erreur de connection : " . i5_errormsg ();
	 }
	 return $result;

	}

	/**
	 *
	 * Enter description here ...
	 * @param string $cmdsys
	 * @return string
	 */
	function cmdsystem($cmdsys) {
		//return i5_cmdget("wrkactjob");
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

			$res = `system "$cmdsys"`;

			return utf8_encode($res);

			if (! i5_close ( $conn )) {
				// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
				return "Erreur de connection : " . i5_errormsg ();
			}
		}
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $sysval
	 * @return string
	 */
	function isysval($sysval) {
		//return i5_cmdget("wrkactjob");
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			//return "Connection reussie";
			return i5_get_system_value ( $sysval );

			if (! i5_close ( $conn )) {
				// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
				return "Erreur de connection : " . i5_errormsg ();
			}
		}
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $user
	 * @return array
	 */
	function rtvusrprf($user) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			i5_command("DSPUSRPRF USRPRF($user) OUTPUT(*OUTFILE) OUTFILE(QTEMP/USERFILE)");
			$file = i5_open('QTEMP/USERFILE');
			$result = i5_fetch_row($file);

		}
		return $result;
		i5_free_file($result);
	}

	/**
	 *
	 * Enter description here ...
	 * @return array
	 */
	function rtvallusrprf() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			i5_command("DSPUSRPRF USRPRF(*ALL) OUTPUT(*OUTFILE) OUTFILE(QTEMP/USERFILE)");
			$file = i5_open('QTEMP/USERFILE');
			$result = array();
			$i=0;
			while ($res = i5_fetch_row($file) ) {
				$result[$i] = i5_fetch_row($file);
				$i++;
			}

		}

		i5_free_file($result);
		return $result;
	}

	/**
	 *
	 * Enter description here ...
	 * @return array
	 */
	function rtvallsql() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
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

	/**
	 *
	 * Numero client tecpro
	 * @param string $numcli
	 * @return array
	 */
	function readOpFidCli($numcli) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			$file = i5_open("PCS399/C".$numcli, I5_OPEN_READ|I5_OPEN_SHRRD, $conn );
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

	/**
	 *
	 * numero client tecpro
	 * @param string $numcli
	 * @return array
	 */
	function readReservations($numcli) {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

			//@i5_command("ADDLIBLE PGMCOMET");
			if ( @i5_command("CALL PGMCOMET/GIPRCRES ('$numcli')") ) {
				$file = i5_open("PCS399/R".$numcli, I5_OPEN_READ|I5_OPEN_SHRRD, $conn );
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

	/**
	 *
	 * Enter description here ...
	 * @param string $requete
	 * @return array|boolean|string
	 */
	function query_multi03($requete) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			// code commande
			$query = i5_query (str_replace('|','/',$requete));
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

			if (!i5_close($conn)) {
				return "Erreur de connection";
			}

			return $result;
		}
	}


	/**
	 *
	 * Enter description here ...
	 * @param string $requete
	 * @return array|boolean|string
	 */
	function query_as400JSON($requete) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			// code commande
			$query = i5_query (str_replace('|','/',$requete));
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

			if (!i5_close($conn)) {
				return "Erreur de connection";
			}

			return $result;
		}
	}


	/**
	 *
	 * Enter description here ...
	 * @param string $requete
	 * @return string
	 */
	function query2xml($requete) {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
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

	/**
	 *
	 * Enter description here ...
	 * @param string $qr
	 * @return array
	 */
	function query2array($qr) {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

			$res = array ();

			while ( $values = i5_fetch_row ( $qr, I5_READ_NEXT ) ) {

				$row = array ();
				for($j = 0; $j < i5_num_fields ( $qr ); $j ++) {
					$key = strtolower ( stripslashes ( i5_field_name ( $qr, $j ) ) );
					$val = htmlspecialchars ( stripslashes ( $values [$j] ) );
					//$row["$key"] = $val;
					array_push ( $row, array ($key => $val ) );

				}
				array_push ( $res, $row );
			}

			i5_free_query ( $qr );
			return $res;
		}
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $sqlstr
	 * @return boolean
	 */
	function query_delete($sqlstr) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			$result = false;
			$query = i5_query ($sqlstr);
			if (! $query) {
				return $result;
			} else {

				$result = true;
				i5_free_query($query);
			}

			if (!i5_close($conn)) {
				return $result;
			}

			return $result;
		}
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $sqlstr
	 * @return boolean
	 */
	function query_update($sqlstr) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {


			i5_transaction(I5_ISOLEVEL_NONE,$conn);//I5_ISOLEVEL_CHG

			i5_query($sqlstr);


			$result = !(i5_commit($conn));
			//i5_free_query($query);


			if (!i5_close($conn)) {
				return false;
			}

			return $result;
		}
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $outqn
	 * @return boolean|string
	 */
	function contenuOutq($outqn) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			$res = @i5_outq_open($outqn,$conn);
		}
		return @i5_outq_read($res);
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $outq
	 * @return boolean|string
	 */
	function lire_spool($outq) {

		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

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
			if (! i5_close ( $conn )) {
				// Failed to disconnect from i5 server, use i5_errormsg() to get the failure reason
				return "Erreur de fermeture de connexion du serveur";
			}

		}

	}

	/**
	 *
	 * Enter description here ...
	 * @param string $ip
	 * @param string $impname
	 * @return boolean|string
	 */
	function crtprthp15n($ip,$impname) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			$step1 = "CRTDEVPRT DEVD($impname) DEVCLS(*LAN) TYPE(3812) MODEL(1) LANATTACH(*IP) PORT(9100) PPRSRC1(*A4) PPRSRC2(*A4) FONT(011) FORMFEED(*AUTOCUT) PRTERRMSG(*INFO) MFRTYPMDL(*HP5) RMTLOCNAME('$ip') SYSDRVPGM(*HPPJLDRV)";
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

	/**
	 *
	 * Enter description here ...
	 * @param string $ip
	 * @param string $impname
	 * @return boolean|string
	 */
	function crtprtT650($ip,$impname) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

			$step1 = "CRTDEVPRT DEVD($impname) DEVCLS(*LAN) TYPE(3812) MODEL(1) LANATTACH(*IP) PORT(9100) PPRSRC1(*A4) PPRSRC2(*A4) FONT(011) FORMFEED(*AUTOCUT) PRTERRMSG(*INFO) MFRTYPMDL(*LEXMARKE) RMTLOCNAME('$ip') SYSDRVPGM(*HPPJLDRV)";
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

	/**
	 *
	 * Enter description here ...
	 * @param string $ip
	 * @param string $impname
	 * @return boolean|string
	 */
	function crtprtT640($ip,$impname) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

			$step1 = "CRTDEVPRT DEVD($impname) DEVCLS(*LAN) TYPE(3812) MODEL(1) LANATTACH(*IP) PORT(9100) PPRSRC1(*A4) PPRSRC2(*A4) FONT(011) FORMFEED(*AUTOCUT) PRTERRMSG(*INFO) MFRTYPMDL(*LEXMARKE) RMTLOCNAME('$ip') SYSDRVPGM(*HPPJLDRV)";
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

	/**
	 *
	 * Enter description here ...
	 * @param string $ip
	 * @param string $impname
	 * @return boolean|string
	 */
	function crtprtT630($ip,$impname) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

			$step1 = "CRTDEVPRT DEVD($impname) DEVCLS(*LAN) TYPE(3812) MODEL(1) LANATTACH(*IP) PORT(9100) PPRSRC1(*A4) PPRSRC2(*A4) FONT(011) FORMFEED(*AUTOCUT) PRTERRMSG(*INFO) MFRTYPMDL(*LEXMARKT630) RMTLOCNAME('$ip') SYSDRVPGM(*HPPJLDRV)";
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

	/**
	 *
	 * Enter description here ...
	 * @param string $ip
	 * @param string $impname
	 * @return boolean|string
	 */
	function crtprtLxkC($ip,$impname) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {

			$step1 = "CRTDEVPRT DEVD($impname) DEVCLS(*LAN) TYPE(3812) MODEL(1) LANATTACH(*IP) PORT(9100) PPRSRC1(*A4) PPRSRC2(*A4) FONT(011) FORMFEED(*AUTOCUT) PRTERRMSG(*INFO) MFRTYPMDL(*LEXMARKC) RMTLOCNAME('$ip') SYSDRVPGM(*HPPJLDRV)";
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

	/**
	 *
	 * Enter description here ...
	 * @param string $ip
	 * @param string $impname
	 * @return boolean|string
	 */
	function crtprthp1320($ip,$impname) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'
		if ($conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters )) {
			$step1 = "CRTDEVPRT DEVD($impname) DEVCLS(*LAN) TYPE(3812) MODEL(1) LANATTACH(*IP) PORT(9100) PPRSRC1(*A4) PPRSRC2(*A4) FONT(011) FORMFEED(*AUTOCUT) PRTERRMSG(*INFO) MFRTYPMDL(*HP15) RMTLOCNAME('$ip') SYSDRVPGM(*HPPJLDRV)";
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

	/**
	 *
	 * Enter description here ...
	 * @param string $numvag
	 * @return boolean
	 */
	function iflg_etat_vague_20($numvag) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		$result = false;
		if (is_resource ( $this->conn )) {
			$sql = "SELECT ETAVAG FROM FGE50TST/GEVAG WHERE NUMVAG='$numvag'";
			$result=$this->query_multi03($sql);


		}
		if ( $result[0]["etavag"] == "20" ) {
			$result = true;
		} else {
			$result = false;
		}
		return $result;
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $numvag
	 * @return boolean
	 */
	function iflg_dblq_vag($numvag) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		$result = false;
		if (is_resource ( $this->conn )) {

			$sql = "UPDATE FGE50TST/GEVAG SET ETAVAG='90' WHERE NUMVAG='$numvag'";
			i5_transaction(I5_ISOLEVEL_NONE,$this->conn);
            i5_query($sql);
            $result = !(i5_commit($this->conn));

		}
		@i5_close($this->conn);

		return $result;
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $numcde
	 * @return boolean
	 */
	function iflg_sup_cdetecpro($numcde) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		$result = false;
		if (is_resource ( $this->conn )) {

			$sql0 = "UPDATE PGMCOMET/COPHYECC SET STGRP='9999' WHERE STDOS='800' AND ECCNUM='".$numcde."'";

			$sql1 = "UPDATE PGMCOMET/COPHYDCC SET STGRP='9999' WHERE STDOS='800' AND ECCNUM='".$numcde."'";

			$sql2 = "UPDATE PGMCOMET/COPHYFCC SET STGRP='9999' WHERE STDOS='800' AND ECCNUM='".$numcde."'";

			i5_transaction(I5_ISOLEVEL_NONE,$this->conn);
            i5_query($sql0);
            i5_query($sql1);
            i5_query($sql2);

            $result = !(i5_commit($this->conn));

		}
		@i5_close($this->conn);

		return $result;
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $zpic
	 * @param string $allee
	 * @param string $depp
	 * @param string $niv
	 * @param string $codepal
	 * @return boolean
	 */
	function iflg_pal_picking($zpic,$allee,$depp,$niv,$codepal) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		$result = false;
		if (is_resource ( $this->conn )) {

			$sql = "UPDATE FGE50MG/GEPAL SET ZONSTS='$zpic', ALLSTS ='$allee',  DPLSTS='$depp',  NIVSTS='$niv', ETAPAL='10' WHERE CODPAL='$codepal'";

			i5_transaction(I5_ISOLEVEL_NONE,$this->conn);
            i5_query($sql);
            $result = !(i5_commit($this->conn));

		}
		@i5_close($this->conn);

		return $result;
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $stksaisie
	 * @param string $numart
	 * @return boolean
	 */
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

	/**
	 *
	 * Enter description here ...
	 * @param string $nummag
	 * @param string $repsrc
	 * @return boolean
	 */
	function cptPegasTecpro($nummag,$repsrc) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$result = false;
		if (is_resource($this->conn)) {
			$result = @i5_command("CALL PGM(HHHPGM/VVCPTPEG) PARM('$nummag' '$repsrc')");
		}

		@i5_close($this->conn);

		return $result;
	}

	/**
	 *
	 * Enter description here ...
	 * @return array
	 */
	function wrkjoblck() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		$result = false;
		if (is_resource($this->conn)) {

			$cmd1=@i5_command("WRKACTJOB  OUTPUT(*PRINT)");
			$cmd2=@i5_command("CPYSPLF FILE(QPDSPAJB) TOFILE(QTEMP/ACTJOB)  SPLNBR(*LAST)");

			$file = @i5_open("QTEMP/ACTJOB", I5_OPEN_READ,$this->conn);
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
			@i5_command("DLTF QTEMP/ACTJOB");
			@i5_close($this->conn);
		}
		return $result;
	}

	/**
	 *
	 * Enter description here ...
	 * @return boolean
	 */
	function isTecFac() {
		$result = false;

		$cmd = `system 'wrkactjob'|grep -c TEC_FAC`;
		if ($cmd>=1) {
			$result = true;
		}

		return $result;
	}

	/**
	 *
	 * Enter description here ...
	 * @return array
	 */
	function getSysASInf() {
		//$result = array();

		$uc = `system 'wrksyssts'|grep ':'|grep 'UC'|awk -F':' '{ print $2 $3 }'|awk '{ print $1 }'|tr -d '\n'`;
		$asp = `system 'wrksyssts'|grep ':'|grep '% ASP'|awk -F'. :' '{ print $3}'|tr -d '\n'`;
		$trav = `system 'wrksyssts'|grep ':'|grep 'Travaux'|awk -F'. :' '{ print $2 }'|awk '{ print $1 }'|tr -d '\n'`;

		//array_push($result,array("uc" => $uc));
		//array_push($result,array("asp" => $asp));
		//array_push($result,array("jobs" => $trav));
		$result = array("uc" => $uc,"asp" => $asp,"jobs" => $trav);
		return $result;
	}


	/**
	 * Cette methode accepte
	 * @return boolean
	 */
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

	/**
	 *
	 * Enter description here ...
	 * @param string $imp
	 * @return string
	 */
	function getIpImp($imp) {
		$resip="";
		if ($this->isDevPrt($imp)) {
			$resip=$this->getIpDevPrt($imp);
		} else {
			$resip=$this->getIpRmtPrt($imp);
		}
		return $resip;
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $imp
	 * @return string
	 */
	function getIpRmtPrt($imp) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource( $this->conn) ) {
			$testrmt=`system 'wrkoutqd $imp'|grep -E '\. :'|grep 'Adresse Internet'|awk -F'   ' '{ print $2 }'|sed 's/ //g'|tr -d '\n'`;
			@i5_close($this->conn);
			return $testrmt;
		}
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $imp
	 * @return string
	 */
	function getIpDevPrt($imp) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource($this->conn) ) {
			$testdev=`system 'dspdevd $imp'|grep -E '\. :'|grep 'ou adresse'|awk -F'               ' '{ print $2 }'|tr -d '\n'`;
			@i5_close($this->conn);
			return $testdev;
		}
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $ip
	 * @return boolean
	 */
	function chkIp($ip) {
		$result=false;
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );
		if (is_resource($this->conn)) {
			$ipt=sprintf("%s",$ip);
			$test="PING RMTSYS('$ipt') NBRPKT(6)";
			 $etat=`system "$test"|grep "successful"|awk -F'(' '{ print $2 }'|awk -F' ' '{print $1}'|tr -d '\n'`;
			 if (($etat != null) && ($etat!="0")) {
			 	$result = true;
			 }
		}
		@i5_close($this->conn);
		return $result;
	}


	/**
	 *
	 * Enter description here ...
	 * @param string $imp
	 * @return array|boolean
	 */
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


	/**
	 *
	 * Enter description here ...
	 * @return array
	 */
	function lstEdtMsgw() {

		$result = `system 'wrkwtr *all'|grep MSGW|awk '{ print $1"\t"$2"\t"$3"\t"$4"\t"$5"\t"$6"\t"$7 }'`;


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
	 * @param string $imp
	 * @return boolean
	 */
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

	/**
	 *
	 * Enter description here ...
	 * @return boolean
	 */
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

	/**
	 *
	 * Enter description here ...
	 * @return array
	 */
	function listJobActifs() {
		$result = `system 'wrkjobq'|awk '{ if ( ($1 !~ /5761SS1/) && ($1 !~ /File/) && ($1 !~ /Utilisateur/) && ($2 !~ /Utilisateur/) && ($1 !~ /Fichier/)  && ($2 !~ /Bib/) && ($1 != "*") && ($0 != "") && ($0 !~ /\./) ){ print $1"\t"$2"\t"$3"\t"$4"\t"$5 }}'  | awk '{ if ($5 =="") print $1"\t"$2"\t"$3"\t"$5"\t"$4; else print $1"\t"$2"\t"$3"\t"$4"\t"$5 }'|awk -F"\t" '{ if ($3 != 0) { print $0}}'`;


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
	 * @param string $imp
	 * @return boolean
	 */
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

	/**
	 *
	 * Enter description here ...
	 * @param string $user
	 * @return boolean
	 */
	function debloqUser($user) {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		$result=false;
		if (is_resource ( $this->conn )) {
			$result=i5_command("CHGUSRPRF USRPRF($user) STATUS(*ENABLED)");
		}

		return  $result;
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $dev
	 * @return boolean
	 */
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

	/**
	 *
	 * Enter description here ...
	 * @return boolean
	 */
	function debloqEcomax() {
		$connection_parameters = array(I5_OPTIONS_JOBNAME=>'I5JOB',I5_OPTIONS_INITLIBL=>'PGMCOMET',I5_OPTIONS_LOCALCP=>'UTF-8;ISO8859-1');  //i5_OPTIONS_IDLE_TIMEOUT=>120,I5_OPTIONS_LOCALCP=>'CCSID'

		$this->conn = @i5_connect ( '127.0.0.1', 'hdh', 'hdh', $connection_parameters );

		$result=false;
		if (is_resource ( $this->conn )) {
			//$result=i5_command("VRYCFG CFGOBJ($dev) CFGTYPE(*DEV) ??STATUS(*ON) RESET(*YES)");
			$res=i5_command("VRYCFG CFGOBJ('IN*') CFGTYPE(*DEV) STATUS(*ON) RESET(*YES)");
			$result=i5_command("VRYCFG CFGOBJ('EC*') CFGTYPE(*DEV) STATUS(*ON) RESET(*YES)");
		}
		@i5_close($this->conn);
		return  $result;
	}


	/**
	 *
	 * Enter description here ...
	 * @param string $imp
	 * @return boolean
	 */
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



	/**
	 * Cette methode accepte
	 * @return string
	 */
	function test() {
		return "Hello world";
	}

	//----------------------fin de la classe--------------------
}

ini_set('soap.wsdl_cache_enabled', '0');



if(isset($_GET['wsdl'])){
	// inspecter la classe Tax et retourner la description
	Zend_Loader::loadClass('Zend_Soap_AutoDiscover');
	$wsdl = new Zend_Soap_AutoDiscover();
	$wsdl->setClass('vvws_tests');
	$wsdl->handle();
}
else{
	// traitement
	Zend_Loader::loadClass('Zend_Soap_Server');
	$server = new Zend_Soap_Server('http://10.21.0.200:8000/flexservices/services/vvws_tests.php?wsdl');
	$server->setClass('vvws_tests');
	$server->handle();
}

?>
