import { Component } from '@angular/core';
import { NavController, IonicPage, NavParams, ViewController, AlertController, Loading, LoadingController, Modal, ModalController } from 'ionic-angular';

//import { AdGrpusersPage } from '../../pages/ad-grpusers/ad-grpusers';
// ------- PROVIDERS ---------
import { Thservices } from '../../providers/thservices/thservices';
// -------- APPDATAS
import { AppDatas } from '../../providers/app-datas/app-datas';
/*
  Generated class for the AdGroupes page.

  See http://ionicframework.com/docs/v2/components/#navigation for more info on
  Ionic pages and navigation.
*/
@IonicPage()
@Component({
  selector: 'page-ad-groupes',
  templateUrl: 'ad-groupes.html'
})
export class AdGroupesPage {

  srcGrp: any;
  srcUsr: any;
  savGrp: Array<any>;
  selGrp: Array<any>;
  savGrpChk: number;
  lstGrp: Array<any>;
  winGrpUsr: Modal;
  loader: Loading;


  constructor(public appDatas: AppDatas, public navCtrl: NavController, public navParams: NavParams, public viewCtrl: ViewController, public alertCtrl: AlertController, public loadingCtrl: LoadingController, public modalCtrl: ModalController, public thservices: Thservices) {
    this.srcGrp = this.navParams.get('grpmbr');
    this.srcUsr = this.navParams.get('user');

  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad AdGroupesPage');

    this.fillGroupes();
  }

  fillGroupes() {
    if (Object(this.srcGrp).length >= 0) {
      console.log("Liste", Object(this.srcGrp).length);
      console.log("type", typeof (this.srcGrp));
      this.lstGrp = [];
      for (let i = 0; i < Object(this.srcGrp).length; i++) {
        let element = String(Object(this.srcGrp)[i]);
        let igrpe = element.split(",")[0].replace("CN=", "");
        let ipath = element.replace(element.split(",").shift(), "").split(",OU=").join("/");
        let obj: Object = {
          grp: igrpe,
          path: ipath,
          mem: false
        }
        console.log("Path", Object(obj).path);
        this.lstGrp.push(obj);
      }
      this.savGrpChk = 0;
    }
  }

  majfillGroupes(usr) {
    this.showLoading();
    this.thservices.doAdldapUserGrpes(usr).subscribe((grpes) => {
      let newLstGrp: Array<any> = grpes.MemberOf;
      if (newLstGrp.length >= 0) {
        console.log("Liste", newLstGrp.length);
        console.log("type", typeof (newLstGrp));
        this.lstGrp = [];
        for (let i = 0; i < newLstGrp.length; i++) {
          let element = String(newLstGrp[i]);
          let igrpe = element.split(",")[0].replace("CN=", "");
          let ipath = element.replace(element.split(",").shift(), "").split(",OU=").join("/");
          let obj: Object = {
            grp: igrpe,
            path: ipath,
            mem: false
          }
          console.log("Path", Object(obj).path);
          this.lstGrp.push(obj);
        }
        this.savGrpChk = 0;
      }
    })


  }

  doRmvGrp() {

    let diagConfRmvGrp = this.alertCtrl.create({
      title: "Attention!",
      message: "Vous allez supprimer l'utilisateur " + String(this.srcUsr).toUpperCase()+" dans un ou plusieurs groupe ",
      buttons: [
        {
          text: "Annuler",
          role: "cancel",
          handler: data => {
            console.log("Operation annulée");
          }
        }, {
          text: "Confimer",
          handler: data => {
            this.selGrp = [];
            let afterGrp: Array<any> = [];
            this.lstGrp.forEach((val, index, obj) => {
              if (val.mem == true) {

                this.selGrp.push(val.grp)
                this.showLoading();
                this.thservices.doAdUsrRmvGrp(val.grp, this.srcUsr).subscribe((res) => {
                  this.hideLoading();
                }, err => {
                  this.hideLoading();
                })
              } else {
                afterGrp.push(val);
              }
            });
            console.log(afterGrp);
            this.lstGrp = afterGrp;
          }
        }

      ]
    });
    diagConfRmvGrp.present();

  }



  doNewGrp() {
    let alertNewGrp = this.alertCtrl.create({
      title: 'Ajout de groupe',
      inputs: [
        {
          name: 'grpname',
          placeholder: 'Nom du Groupe'
        },
        {
          name: 'description',
          placeholder: 'Description',
          type: 'text'
        }
      ],
      buttons: [
        {
          text: 'Annuler',
          role: 'cancel',
          handler: data => {
            console.log("Ajout d'un groupe avorter")
          }
        },
        {
          text: 'Ajouter',
          handler: data => {
            this.showLoading();
            this.thservices.doAdNewGrp(data.grpname, data.description).subscribe((res) => {
              console.log("Groupe créer :", res);
              this.hideLoading();
              this.showLoading();
              this.thservices.doAdUsrAddGrp(data.grpname, this.srcUsr).subscribe((result) => {
                console.log("Utilisateur Ajoute au groupe");
                this.majfillGroupes(this.srcUsr);
                this.hideLoading();
              })
            }, (err) => {
              console.log('Ajout impossible');
            }, () => {
              this.hideLoading();
            })
          }
        }
      ]
    });
    alertNewGrp.present().catch((err) => { console.log("Impossible d'afficher la boite de dialogue!") })
  }

  doSelGrp($event, index) {

    if ($event.checked) {
      this.savGrpChk++;
    } else {
      this.savGrpChk--;
    }
    this.lstGrp[index].mem = $event.checked;
    console.log("Selection", this.lstGrp[index].mem);
  }

  memSelect() {
    this.savGrp = [];
    console.log("Liste", this.lstGrp);
    for (let i = 0; i < Object(this.lstGrp).length; i++) {
      //console.log("Index",)
      if (Object(this.lstGrp)[i].mem == true) {
        let obj: Object = {
          grp: Object(this.lstGrp)[i].grp
        }
        this.savGrp.push(obj);
      }
    }
    //------------------------------------
    console.log("List grp", this.savGrp);
  }

  dupDismiss() {
    this.memSelect();
    let data = this.savGrp;
    this.viewCtrl.dismiss(data);
  }

  doDismiss() {
    this.viewCtrl.dismiss();
  }

  supSelectGrp() {
    //this.memSelect();
    //let data = this.savGrp;
    //this.viewCtrl.dismiss(data);
  }

  mbrSelectGrp($event, i) {

    this.showLoading();
    this.thservices.doAdUsrLstGrp(this.lstGrp[i].grp).subscribe((res) => {
      this.hideLoading();
      this.winGrpUsr = this.modalCtrl.create('AdGrpusersPage', { lstusrs: res });
      this.winGrpUsr.present();
      this.winGrpUsr.onDidDismiss(data => {
        if ((data != undefined) && (data.length > 0)) {
          //this.grpToDup = data;
          //this.userToDup = this.userInfos[this.currentSlideIndex].userid;
          console.log("Close winGrpUsr");
        }

      })
    });

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
