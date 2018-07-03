import { Component } from '@angular/core';
import { NavController,IonicPage, NavParams, Loading, LoadingController, Alert, AlertController } from 'ionic-angular';
import { Thservices } from '../../providers/thservices/thservices';
import { Network } from '@ionic-native/network';

/*
  Generated class for the Camagasins page.

  See http://ionicframework.com/docs/v2/components/#navigation for more info on
  Ionic pages and navigation.
*/


@IonicPage()
@Component({
  selector: 'page-camagasins',
  templateUrl: 'camagasins.html'
})
export class CamagasinsPage {

  selannee: any;
  selmois: any;
  seljour: any;
  totalca: any;
  nbmag: any;

  enseigne: any;
  magasins: any;
  currentLoading: Loading;
  currentLoadingMQ: Loading;
  currentLoadingGP: Loading;
  currentLoadingCY: Loading;
  selDate: string = (new Date).toLocaleDateString('fr-FR').substr(6, 4)+"-"+(new Date).toLocaleDateString('fr-FR').substr(3, 2)+"-"+(new Date).toLocaleDateString('fr-FR').substr(0, 2);//(new Date()).toISOString();
  selOptions: any = { title: 'Selection d\'une enseigne' };

  constructor(public loadCtrl: LoadingController, public alertCtrl: AlertController, private network: Network,public navCtrl: NavController, public navParams: NavParams, public vvs: Thservices) {

  }


  displayNetworkUpdate(connectionState: string) {
    let networkType = this.network.type;
    console.log(`You are now ${ connectionState } via ${ networkType }`);
   /* this.toast.create({
      message: `You are now ${connectionState} via ${networkType}`,
      duration: 3000
    }).present();*/
  }


  ionViewDidEnter() {
    this.network.onConnect().subscribe(data => {
      console.log(data)
      this.displayNetworkUpdate(data.type);
    }, error => console.error(error));

    this.network.onDisconnect().subscribe(data => {
      console.log(data)
      this.displayNetworkUpdate(data.type);
    }, error => console.error(error));
  }
  ionViewDidLoad() {
    console.log('ionViewDidLoad CamagasinsPage'+this.selDate);
  }

  doChangeDate() {
    console.log("Date sélectionnée Mois: ", this.selDate);
    console.log("Date sélectionnée Mois: ", this.selDate.substr(5, 2));
    console.log("Date sélectionnée Jour: ", this.selDate.substr(8, 2));
    console.log("Date sélectionnée Annee: ", this.selDate.substr(0, 4));
    this.showCA();
  }

  camagSelected(ipmag) {
    console.log("Obtenir le ca pour le :"+ipmag+" date:");
  }


  showProgress() {
    this.currentLoading = this.loadCtrl.create({
      content: 'Patientez svp...'
    });
    console.log("Avant ", this.currentLoading._state);
    this.currentLoading.present();
    console.log("Apres ", this.currentLoading._state);
  }

  hideProgress(): void {
    //this.currentLoading.dismissAll();
    this.currentLoading.dismiss().catch((error) => console.log("HideProgress", error));


  }


  showProgressMQ() {
    this.currentLoadingMQ = this.loadCtrl.create({
      content: 'Patientez, svp...'
    });
    this.currentLoadingMQ.present();
  }

  hideProgressMQ() {

    this.currentLoadingMQ.dismiss().catch((error) => console.log("HideProgress", error));
  }


  showProgressGP() {
    this.currentLoadingGP = this.loadCtrl.create({
      content: 'Patientez, svp...'
    });
    this.currentLoadingGP.present();
  }

  hideProgressGP() {

    this.currentLoadingGP.dismiss().catch((error) => console.log("HideProgress", error));
  }


  showProgressCY() {
    this.currentLoadingCY = this.loadCtrl.create({
      content: 'Patientez, svp...'
    });
    this.currentLoadingCY.present();
  }

  hideProgressCY() {

    this.currentLoadingCY.dismiss().catch((error) => console.log("HideProgress", error));
  }


  doSelect(event) {
    console.log(this.enseigne);
    this.showCA();
  }

  showCA() {
    if (this.enseigne === undefined) {
      let msgEns: Alert = this.alertCtrl.create({
        title: "Attention!",
        message: "Veuillez renseigner l'\enseigne!",
        buttons: ["Ok"]
      });
      msgEns.present();
    } else {
      this.magasins = [];
      console.log("Date sélectionnée : ", this.selDate);
      console.log("Enseigne :" + this.enseigne);
     // let dateSelect: Date = (new Date(this.selDate));
      this.selannee =this.selDate.substr(0, 4);
      this.selmois = this.selDate.substr(5, 2);//((dateSelect.getMonth() + 1) < 10) ? '0' + (dateSelect.getMonth() + 1) : (dateSelect.getMonth() + 1).toString();
      this.seljour = this.selDate.substr(8, 2);//(dateSelect.getDate() < 10) ? '0' + dateSelect.getDate() : dateSelect.getDate().toString();
      console.log("Jour/Mois/Annee :" + this.seljour.toString()+"/"+this.selmois.toString()+"/"+this.selannee.toString());





      if (this.enseigne == "ECOMAX") {
        this.totalca = 0;
        this.nbmag = 3;
        let tocaEMQ = 0;
        let tocaEGP = 0;
        let tocaECY = 0;




        let tocliEMQ = 0;
        let tocliEGP = 0;
        let tocliECY = 0;

        this.showProgressMQ();
        this.vvs.getCaJour("ECOMAX MQ", this.seljour, this.selmois, this.selannee).subscribe((data) => {
          console.log("Resultat Ecomax MQ:", data);
          this.hideProgressMQ();
          if (data != undefined) {
            let magEMQ: any = data;
            for (var index = 0; index < magEMQ.length; index++) {
              tocaEMQ = tocaEMQ + Number(magEMQ[index].camag);
              tocliEMQ = tocliEMQ + Number(magEMQ[index].climag);
            }
            //console.log("Total MQ", tocaEMQ);
            this.totalca = this.totalca + tocaEMQ;
            let objMQ = {
              nommag: "ECOMAX MQ",
              camag: tocaEMQ.toFixed(2),
              climag: tocliEMQ
            }
            this.magasins.push(objMQ);
          }
        }, (err: any) => {
          this.hideProgressMQ();
          console.log("HideProgress :" + err.message + "|Chiffres impossible à récupérer!");

        }, () => this.hideProgressMQ());

        //---------------ECOMAX  GP------------------------------
        this.showProgressGP();
        this.vvs.getCaJour("ECOMAX GP", this.seljour, this.selmois, this.selannee).subscribe((resgp) => {
          console.log("Resultat Ecomax GP:", resgp);
          this.hideProgressGP();
          if (resgp != undefined) {
            let magEGP: any = resgp;
            console.log("Nb magasins :", magEGP.length.toString());
            for (var index = 0; index < magEGP.length; index++) {
              tocaEGP = tocaEGP + Number(magEGP[index].camag);
              tocliEGP = tocliEGP + Number(magEGP[index].climag);
            }
            console.log("Total EGP", tocaEGP);
            this.totalca = this.totalca + tocaEGP;
            let objGP = {
              nommag: "ECOMAX GP",
              camag: tocaEGP.toFixed(2),
              climag: tocliEGP
            }
            this.magasins.push(objGP);
          }
        }, (err) => {
          this.hideProgressGP();
          console.log("HideProgress :" + err.message + "|Chiffres impossible à récupérer!");
        }, () => this.hideProgressGP());


        //----------------ECOMAX CY-------------------------------------
        this.showProgressCY();
        this.vvs.getCaJour("ECOMAX CY", this.seljour, this.selmois, this.selannee).subscribe((resecy) => {
          console.log("Resultat Ecomax CY:", resecy);
          this.hideProgressCY();
          if (resecy != undefined) {
            let magECY: any = resecy;
            for (var index = 0; index < magECY.length; index++) {
              tocaECY = tocaECY + Number(magECY[index].camag);
              tocliECY = tocliECY + Number(magECY[index].climag);
            }
            console.log("Total CY", tocaECY);
            this.totalca = this.totalca + tocaECY;
            let objCY = {
              nommag: "ECOMAX GY",
              camag: tocaECY.toFixed(2),
              climag: tocliECY
            }
            this.magasins.push(objCY);
            //-----------------------------------------FIN TRAITEMENT
          }
        }, (err: any) => {
          this.hideProgressCY();
          console.log("HideProgress :" + err.message + "|Chiffres impossible à récupérer!");
        }, () => this.hideProgressCY());
        //-----------------ECOMAX CY FIN----------------------------------------------------


      } else {
        console.log("Enseigne selectionnées selection:" + this.enseigne);
        //----------------------GEANT ET CASINO--------------------------------------------------
        this.showProgress();
        this.vvs.getCaJour(String(this.enseigne), this.seljour, this.selmois, this.selannee).subscribe((data) => {//this.seljour, this.selmois, this.selannee).subscribe(data => {
          this.hideProgress();
          console.log("Resultat:", Object(data));
          if (data != undefined) {

            this.magasins = data;
            this.nbmag = Object(data).length;
            console.log("Nombre de magasins",this.nbmag);
            this.totalca = 0;
            for (let index = 0; index < this.magasins.length; index++) {
              this.totalca = Number(this.totalca + Number(this.magasins[index].camag));
              this.magasins[index].camag = Number(this.magasins[index].camag).toFixed(2);
            }
          }
        }, (error: any) => {
          this.hideProgress();
          console.log("HideProgress :" + error.message + "|Chiffres impossible à récupérer!");
        }, () => {
          this.hideProgress();
        });

      }



    }
  }

  //---- fin

}
