import { Component, ViewChild } from '@angular/core';
import { NavController, IonicPage, NavParams, AlertController, Loading, LoadingController, MenuController, Slides, Modal, ModalController, ActionSheetController } from 'ionic-angular';
//-------- PAGES -------------
//import { AdGroupesPage } from '../../pages/ad-groupes/ad-groupes';
//import { AdGrpusersPage } from '../../pages/ad-grpusers/ad-grpusers';
// ------- PROVIDERS ---------
import { Thservices } from '../../providers/thservices/thservices';
// -------- APPDATAS
import { AppDatas } from '../../providers/app-datas/app-datas';
/*
  Generated class for the AdSearch page.

  See http://ionicframework.com/docs/v2/components/#navigation for more info on
  Ionic pages and navigation.
*/
@IonicPage()
@Component({
  selector: 'page-ad-search',
  templateUrl: 'ad-search.html'
})
export class AdSearchPage {

  @ViewChild(Slides) slides: Slides;

  tools: any;
  loader: Loading;
  //---- declarations utilisateurs
  userInfos: Array<any>;
  pcsInfos: Array<any>;
  searchname: any;

  pcsearch: any;
  selUserActif: any;
  grpToDup: any;
  currentSlideIndex: number;
  userToDup: any;
  currentUserId: any;
  winGroupes: Modal;
  winGrpUsr: Modal;
  //---------------------
  grpsearch: any;
  grpInfos: Array<any>;




  constructor(public appDatas: AppDatas, public navCtrl: NavController, public navParams: NavParams, public menuCtrl: MenuController, public thservices: Thservices, public alertCtrl: AlertController, public loadingCtrl: LoadingController, public modalCtrl: ModalController, public actionSheetCtrl: ActionSheetController) {
    this.appDatas = appDatas;
    this.searchname = "";
    this.grpsearch = "";
    this.pcsearch = "";
    this.tools = "users";
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad AdSearchPage');

  }

  showOptMenu() {
    let actionSheet = this.actionSheetCtrl.create({
      title: 'Options avancées',
      buttons: [
        {
          text: 'Liste des comptes inactifs depuis n jours',
          //role: 'destructive',
          handler: () => {
            console.log('Destructive clicked');
            this.showNext();
          }
        },
        {
          text: 'Liste des comptes inactifs depuis n semaines',
          handler: () => {
            console.log('Archive clicked');
            this.showNext();
          }
        },
        {
          text: 'Liste des comptes deactivés',
          handler: () => {
            console.log('Archive clicked');
            this.showNext();
          }
        },
        {
          text: 'Annuler',
          role: 'cancel',
          handler: () => {
            console.log('Cancel clicked');
          }
        }
      ]
    });

    actionSheet.present();
  }

  getGroupes($event) {

  }


  getPcs($event) {

  }

  doEnter() {
    console.log("Enter");
    this.doSearchAd();
  }

  doGrpEnter() {
    this.doSearchGrp();
  }

  doPcEnter() {
    this.doSearchPcs();
  }

  getUsers($event) {
    //console.log("Event", $event);
    console.log("Recherche", this.searchname);
  }

forceRestart(itemname:any) {
  this.showLoading();
  this.thservices.doRebootComputer(itemname).subscribe((res) => {
    this.hideLoading();
    console.log("Redemarrage effectué : ", res);
  });
}

  doSearchPcs() {
    this.showLoading();
    this.thservices.doAdldapPcsSearch(this.pcsearch).subscribe((res) => {
      this.hideLoading();
      console.log("Recherche PCs : ", res);
      console.log("IsArray : ", typeof(res));
      if (res) {

        this.pcsInfos = [];

           if (Object(res).length == undefined) {

            let obj: Object = {
              CanonicalName: Object(res).CanonicalName,
              DNSHostName: Object(res).DNSHostName,
              DistinguishedName: Object(res).DistinguishedName,
              IPv4Address: Object(res).IPv4Address,
              Name: Object(res).Name,
              OperatingSystem: Object(res).OperatingSystem,
              OperatingSystemServicePack: Object(res).OperatingSystemServicePack,
              OperatingSystemVersion: Object(res).OperatingSystemVersion,
              SamAccountName: Object(res).SamAccountName
            }
            this.pcsInfos.push(obj);
          } else {
            for (let index = 0; index < Object(res).length; index++) {
              if (typeof (res) == 'object') {
                let obj: Object = {
                  CanonicalName: Object(res[index]).CanonicalName,
                  DNSHostName: Object(res[index]).DNSHostName,
                  DistinguishedName: Object(res[index]).DistinguishedName,
                  IPv4Address: Object(res[index]).IPv4Address,
                  Name: Object(res[index]).Name,
                  OperatingSystem: Object(res[index]).OperatingSystem,
                  OperatingSystemServicePack: Object(res[index]).OperatingSystemServicePack,
                  OperatingSystemVersion: Object(res[index]).OperatingSystemVersion,
                  SamAccountName: Object(res[index]).SamAccountName
                }
                this.pcsInfos.push(obj);
            }
          }


        }
        console.log(" PCs : ", this.pcsInfos);
      }
    });

  }



  doSearchGrp() {
    this.showLoading();
    this.thservices.doAdldapGrpSearch(this.grpsearch).subscribe((res) => {
      this.hideLoading();
      console.log("Recherche Groupes : ", typeof (res));
      console.log("Recherche Groupes : ",res);
      if (res) {
        if (Object(res).length == undefined)  {
          this.grpInfos = [];
          this.grpInfos.push(Object(res));
        } else {
          this.grpInfos = res;
        }

      }
    }, (err) => {
      this.hideLoading();
    });
  }

  tooltip(msg) {
    console.log(msg);
  }

  doSearchAd() {
    this.showLoading();
    this.thservices.doAdldapUserSearch(this.searchname).subscribe((res) => {
      this.hideLoading();
      if (res) {
        console.log("userInfos :", res);
        this.userInfos = [];
        if (Object(res).length == undefined) {
          if (Object(res) != undefined) {
            let obj: Object = {
              name: Object(res).Name,
              userid: Object(res).SamAccountName,
              email: Object(res).mail,
              displayname: Object(res).DisplayName,
              ville: Object(res).City,
              telfixe: Object(res).HomePhone,
              telmob: Object(res).mobile,
              telwork: Object(res).OfficePhone,
              codep: Object(res).PostalCode,
              street: Object(res).StreetAddress,
              pays: Object(res).co,
              groupes: Object(res).MemberOf,
              modif: (new Date(Number(String(Object(res).whenChanged).match(/\d/g).join("")))).toLocaleString(),
              creation: (new Date(Number(String(Object(res).whenCreated).match(/\d/g).join("")))).toLocaleString(),
              lastlog: (new Date(Number(String(Object(res).LastLogonDate).match(/\d/g).join("")))).toLocaleString(),
              picture: (Object(res).ThumbnailPhoto == null) ? "" : "data:image/jpeg;base64," + this.arrayBufferToBase64(Object(res).ThumbnailPhoto).toString(),
              actif: Object(res).Enabled
            }
            this.userInfos.push(obj);
            this.currentSlideIndex = this.slides.getActiveIndex();
            this.currentUserId = Object(res).SamAccountName;
          }
        } else {
          for (let index = 0; index < Object(res).length; index++) {
            //let vmodif:string = Number(String(Object(res[index]).whenChanged).match(/\d/g).join("")).toString();
            let obj: Object = {
              name: Object(res[index]).Name,
              userid: Object(res[index]).SamAccountName,
              email: Object(res[index]).mail,
              displayname: Object(res[index]).DisplayName,
              ville: Object(res[index]).City,
              telfixe: Object(res[index]).HomePhone,
              telmob: Object(res[index]).mobile,
              telwork: Object(res[index]).OfficePhone,
              codep: Object(res[index]).PostalCode,
              street: Object(res[index]).StreetAddress,
              pays: Object(res[index]).co,
              groupes: Object(res[index]).MemberOf,
              modif: (new Date(Number(String(Object(res[index]).whenChanged).match(/\d/g).join("")))).toLocaleString(),
              creation: (new Date(Number(String(Object(res[index]).whenCreated).match(/\d/g).join("")))).toLocaleString(),
              lastlog: (new Date(Number(String(Object(res[index]).LastLogonDate).match(/\d/g).join("")))).toLocaleString(),
              picture: (Object(res).ThumbnailPhoto == null) ? "" : "data:image/jpeg;base64," + this.arrayBufferToBase64(Object(res[index]).ThumbnailPhoto).toString(),
              actif: Object(res[index]).Enabled
            }

            this.userInfos.push(obj);
            console.log("Objects :", obj);
          }
          this.currentSlideIndex = this.slides.getActiveIndex();
          if (this.currentSlideIndex != null) {
            this.currentUserId = this.userInfos[this.currentSlideIndex].userid;
          }

          //this.selUserActif = Boolean(this.userInfos[this.currentSlideIndex].actif);
          console.log("Current slide index", this.slides.getActiveIndex());

        }
      }
    }, (err) => {
      this.hideLoading();
    });

  }


  showGroupes() {

    this.currentSlideIndex = this.slides.getActiveIndex();
    console.log("Current slide index :", this.currentSlideIndex);

    if (this.userInfos.length == 1) {
      this.userToDup = this.userInfos[this.currentSlideIndex].userid;
    } else {
      this.userToDup = this.currentUserId;
    }

    this.grpToDup = [];
    this.winGroupes = this.modalCtrl.create('AdGroupesPage', { grpmbr: this.userInfos[this.currentSlideIndex].groupes, user: this.userToDup });
    this.winGroupes.onDidDismiss(data => {
      if ((data != undefined) && (data.length > 0)) {
        this.grpToDup = data;
        //this.userToDup = this.userInfos[this.currentSlideIndex].userid;
        console.log("Groupes memorises", this.grpToDup);
      }

    });
    this.winGroupes.present();
  }


  majUsrInfos(user) {
    this.showLoading();
    this.thservices.doAdldapUserInf(user).subscribe((res) => {
      this.hideLoading();
      if (res) {
        console.log("Membres :", res);
        console.log("Membres Maj:", res.MemberOf);
        this.userInfos[this.currentSlideIndex].groupes = res.MemberOf;
        this.grpToDup = [];
      }

    });
  }

  dupGroupes() {
    if (this.userInfos[this.currentSlideIndex].userid != undefined) {
      let modUser = this.userInfos[this.currentSlideIndex].userid;
      console.log("Utilisateur :", this.userInfos[this.currentSlideIndex].userid);
      console.log("Groupes :", this.grpToDup);
      for (let index = 0; index < this.grpToDup.length; index++) {
        let element = this.grpToDup[index];
        console.log("Affecter a ", element.grp);
        let msgOk = this.alertCtrl.create({
          title: "Ajout d'un utilisateur au groupe",
          message: "Voulez-vous ajouter " + String(modUser).toUpperCase() + " dans le groupe :" + element.grp + " ?",
          buttons: [{
            text: 'Annuler',
            handler: () => {
              console.log('Annuler clicked');
            }
          },
          {
            text: 'OK',
            handler: () => {
              console.log('Ok clicked');
              this.showLoading();
              this.thservices.doAdUsrAddGrp(element.grp, modUser).subscribe((res) => {
                this.hideLoading();
                console.log("Ajout effectué");
                this.majUsrInfos(modUser);
              }, (err) => {
                this.hideLoading();
                console.log("Erreur d'ajout");
              }, () => {
                this.hideLoading();
              })
            }
          }]
        });
        msgOk.present();
      }
    }

  }



  slideChanged() {

    this.currentSlideIndex = this.slides.getActiveIndex();
    if (Object(this.userInfos[this.currentSlideIndex]).userid != undefined) {
      this.currentUserId = this.userInfos[this.currentSlideIndex].userid;
      console.log("Current index is", this.currentSlideIndex);
    }
    //this.selUserActif = Boolean(this.userInfos[this.currentSlideIndex].actif);
  }


  doUnlockUsr() {
    this.showLoading();
    this.thservices.doAdldapUserUnlock(this.currentUserId).subscribe((result) => {
      this.hideLoading();
      console.log(result);
      let msg = this.alertCtrl.create({
        title:"",
        subTitle:"",
        message: result.message,
        buttons: [{
          text:'OK',
        handler: () => {
          this.doEnter();
        }}]
      });
      msg.present();
    }, (err) => {
      console.log("Deblocage impossible");

    }, () => {
      this.hideLoading();
    })
  }

  doDisableUsr() {

    this.thservices.doAdldapUserDisable(this.currentUserId).subscribe((result) => {
        this.hideLoading();
        console.log(result);
      let msg = this.alertCtrl.create({
        title: "",
        subTitle: "",
        message: result.message,
        buttons: [{
          text: 'OK',
          handler: () => {
            this.doEnter();
          }
        }]
      });
      msg.present();
      }, (err) => {
        console.log("Déactivation impossible");
      }, () => {
        this.hideLoading();
      })
  }
  doEnableUsr() {
    this.thservices.doAdldapUserEnable(this.currentUserId).subscribe((result) => {
      this.hideLoading();
      console.log(result);
      let msg = this.alertCtrl.create({
        title: "",
        subTitle: "",
        message: result.message,
        buttons: [{
          text: 'OK',
          handler: () => {
            this.doEnter();
          }
        }]
      });
      msg.present();
    }, (err) => {
      console.log("Activation compte");
    }, () => {
      this.hideLoading();
    })
  }

  getUserInGroup(grpname) {
    this.showLoading();
    this.thservices.doAdUsrLstGrp(grpname).subscribe((result) => {
      console.log("Liste des utilisateurs ", result);
      this.winGrpUsr = this.modalCtrl.create('AdGrpusersPage', { lstusrs: Object(result) ,grp:grpname });
      this.winGrpUsr.present();
      this.winGrpUsr.onDidDismiss(data => {
        if ((data != undefined) && (data.length > 0)) {
          //this.grpToDup = data;
          //this.userToDup = this.userInfos[this.currentSlideIndex].userid;
          console.log("Close winGrpUsr");
        }

      })
    }, (err) => {
      console.log("Deblocage impossible");
    }, () => {
      this.hideLoading();
    });
  }

  arrayBufferToBase64(buffer) {
    var binary = '';
    var bytes = new Uint8Array(buffer);
    var len = bytes.byteLength;
    for (var i = 0; i < len; i++) {
      binary += String.fromCharCode(bytes[i]);
    }
    return window.btoa(binary);
  }


  showNext() {
    let msgNext = this.alertCtrl.create({
      title: 'Fonction non active',
      message: 'Cette option sera disponible dans la prochaine version',
      buttons: ['OK']
    });
    msgNext.present();
  }


  showConfirm() {
    let confirm = this.alertCtrl.create({
      title: 'Demande de confirmation!',
      message: 'Voulez vous vraiment effectuer cette action ?',
      buttons: [
        {
          text: 'Annuler',
          handler: () => {
            console.log('Annuler clicked');
          }
        },
        {
          text: 'OK',
          handler: () => {
            console.log('Ok clicked');
          }
        }
      ]
    });
    confirm.present();
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
