import { Component } from '@angular/core';
import { NavController, IonicPage,NavParams, AlertController, Loading, LoadingController, MenuController,  ModalController, ActionSheetController,  ToastController } from 'ionic-angular';
// ------- PROVIDERS ---------
import { Thservices } from '../../providers/thservices/thservices';
// -------- APPDATAS
import { AppDatas } from '../../providers/app-datas/app-datas';
/*
  Generated class for the As400 page.

  See http://ionicframework.com/docs/v2/components/#navigation for more info on
  Ionic pages and navigation.
*/
@IonicPage()
@Component({
  selector: 'page-as400',
  templateUrl: 'as400.html'
})
export class As400Page {

  tools: any;
  usrdevval: string;
  loader: Loading;
  searchname: any;
  lstPrt: Array<any>;
  lstMem: Array<any>;
  memip: any;
  memindex: any;

  constructor(public navCtrl: NavController, public navParams: NavParams,
    public menuCtrl: MenuController, public thservices: Thservices, public alertCtrl: AlertController,
    public loadingCtrl: LoadingController, public modalCtrl: ModalController, public actionSheetCtrl: ActionSheetController, public toastCtrl: ToastController,
    public appDatas: AppDatas) {

    this.searchname = null;
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad As400Page');
    this.tools = 'usrdev';
  }

  doTools($event) {
    console.log("Event", $event);
    if ($event.value == 'jobs') {
      /*this.showLoading()
      this.thservices.(this.searchname).subscribe((result) => {

      }, err => {
        console.log("Erreur liste d'imprimantes", err)
      }, () => {
        this.hideLoading();
      });
      */
    }
  }

  btnStartPrt(prtname, index) {
    this.lstMem = this.lstPrt;
    this.showLoading()
    this.thservices.startEditeur(prtname).subscribe((result) => {
      this.hideLoading();
      console.log("Start imprimantes  :", result);
      this.showToast("Start imprimantes ", 3000);
      //this.memip = this.lstPrt[index].ip;
      //this.memindex = index;
    }, err => {
      console.log("Erreur liste d'imprimantes", err)
    }, () => {
      this.hideLoading();
      this.doPrtEnter();
    });
  }

  btnStopPrt(prtname, index) {
    this.lstMem = this.lstPrt;
    this.showLoading()
    this.thservices.stopEditeur(prtname).subscribe((result) => {
      this.hideLoading();
      console.log("Stop imprimantes  :", result);
      this.showToast("Stop imprimantes ", 3000);
      //this.memip = this.lstPrt[index].ip;
      //this.memindex = index;
    }, err => {
      console.log("Erreur liste d'imprimantes", err)
    }, () => {
      this.hideLoading();
      this.doPrtEnter();
    });
  }

  btnPausePrt(prtname, index) {
    this.lstMem = this.lstPrt;
    this.showLoading()
    this.thservices.resetEditeur(prtname).subscribe((result) => {
      this.hideLoading();
      console.log("Reset imprimantes  :", result);
      this.showToast("Reset imprimantes ", 3000);
      // this.memip = this.lstPrt[index].ip;
      //this.memindex = index;
    }, err => {
      console.log("Erreur liste d'imprimantes", err)
    }, () => {
      this.hideLoading();
      this.doPrtEnter();
    });
  }

  doPrtEnter() {
    this.showLoading();
    this.thservices.getPrinters(this.searchname).subscribe((result) => {
      if (result) {
        console.log("liste d'imprimantes reussi :", result);
        this.lstPrt = [];
        for (let index = 0; index < result.length; index++) {
          //var element = array[index];
          let obj: Object = {
            nom: result[index][0],
            bib: result[index][1],
            nbspl: result[index][2],
            editeur: result[index][3],
            etat: result[index][4],
            ip: (this.lstMem) ? this.lstMem[index].ip : '',
            checked: false
          }
          this.lstPrt.push(obj);
        }
        //(this.memip)?Object(this.lstPrt[this.memindex]).ip=this.memip:Object(this.lstPrt[this.memindex]).ip='';
        //this.showToast("liste d'imprimantes reussi", 3000);
      } else {
        console.log("liste d'imprimantes impossible :", result);
        this.showToast("liste d'imprimantes impossible", 3000);
      }
    }, err => {
      console.log("Erreur liste d'imprimantes", err)
    }, () => {
      this.hideLoading();
    });
  }


  doGetIpPrt(prtname, index) {
    this.showLoading();
    this.thservices.getIpImp(prtname).subscribe((res) => {
      if (res) {
        console.log("Ip de l'imprimante :", res);
        this.lstPrt[index].ip = res;
        //this.showToast("Ip de l'imprimante reussi", 3000);
      } else {
        console.log("Ip de l'imprimante impossible :", res);
        this.showToast("Ip de l'imprimante impossible", 3000);
      }
    }, err => {
      console.log("Erreur recuperation ip :", err)
    }, () => {
      this.hideLoading();
    });
  }

  dblqDev() {
    this.showLoading();
    this.thservices.debloqDevice(this.usrdevval).subscribe((res) => {

      if (res) {
        console.log("Deblocage device reussi :", res);
        this.showToast("Deblocage device reussi", 3000);
      } else {
        console.log("Deblocage impossible :", res);
        this.showToast("Deblocage impossible", 3000);
      }
    }, err => {
      console.log("Erreur deblocage device :", err)
    }, () => {
      this.hideLoading();
    });
  }

  dblqUsr() {
    this.showLoading();
    this.thservices.debloqUser(this.usrdevval).subscribe((res) => {

      if (res) {
        console.log("Deblocage utilisateur reussi :", res);
        this.showToast("Deblocage utilisateur reussi", 3000);
      } else {
        console.log("Deblocage impossible :", res);
        this.showToast("Deblocage impossible", 3000);
      }
    }, err => {
      console.log("Erreur deblocage utilisateur :", err)
    }, () => {
      this.hideLoading();
    })
  }

showOptMenu() {

}

  showNext() {
    let msgNext = this.alertCtrl.create({
      title: 'Fonction non active',
      message: 'Cette option sera disponible dans la prochaine version',
      buttons: ['OK']
    });
    msgNext.present();
  }


  showToast(msg, duration) {
    let toast = this.toastCtrl.create({
      message: msg,
      position: 'middle',
      duration: duration,
      //showCloseButton: true,
      //closeButtonText: 'Annuler'
    });
    toast.present();
  }

  showLoading() {
    this.loader = this.loadingCtrl.create({
      content: "Chargement..."
    });

    this.loader.present();
  }

  hideLoading() {
    this.loader.dismiss().catch((err) => { console.log("HideLoading", err) });
  }
  //FIN-------------------------------------

}
