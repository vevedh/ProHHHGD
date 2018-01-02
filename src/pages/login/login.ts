import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, AlertController, Loading, LoadingController, MenuController } from 'ionic-angular';
//import { AppVersion } from '@ionic-native/app-version';
import { Storage } from '@ionic/storage';
//import { NativeStorage } from '@ionic-native/native-storage';

//import {  AppInfo } from '@ionic-native/pro'; //, AppInfo, DeployInfo
// ------- PROVIDERS ---------
import { Thservices } from '../../providers/thservices/thservices';
// -------- APPDATAS
import { AppDatas } from '../../providers/app-datas/app-datas';

@IonicPage()
@Component({
  selector: 'page-login',
  templateUrl: 'login.html',
})
export class LoginPage {

  loader: Loading;

  version = null;
  //FB_APP_ID: number = 1879101722303652;

  account: { username: string, password: string } = {
    username: '',
    password: ''
  };

  userInfos: any;

  //public appVersion:AppVersion,
  constructor(public menuCtrl: MenuController, public thservices: Thservices, public nativeStorage:Storage,  public thservices1: Thservices,  public navCtrl: NavController, public alertCtrl: AlertController, public loadingCtrl: LoadingController, public navParams: NavParams,  public appDatas: AppDatas) {
    this.menuCtrl.enable(false);
    /*try {
      this.appVersion.getVersionNumber().then((res:AppInfo) => {
        this.version = res;
        console.log("version", this.version);
      }, (error) => {
        console.log("AppVersion non supportée");
      });
    } catch (error) {
      console.log("Version non récupérable");
    }
    */

  }

   ionViewDidLoad() {
    console.log('ionViewDidLoad LoginPage');
    this.checkActiveDir();


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

  checkActiveDir() {

   // this.nativeStorage.getItem('ProHHHGD')
    this.nativeStorage.get('ProHHHGD')
      .then((data) => {
        if (data != null) {

          this.userInfos = {
            name: data.user.name,
            email: data.user.email,
            displayname: data.user.displayname,
            ville: data.user.ville,
            telfixe: data.user.telfixe,
            telmob: data.user.telmob,
            telwork: data.user.telwork,
            codep: data.user.codep,
            street: data.user.street,
            pays: data.user.pays,
            groupes: data.user.groupes,
            picture: data.user.picture,
            password: data.user.password,
            loginMethod: "activeDir"
          };

          console.log('Infos utilisateur ', this.userInfos);
          this.checkUserName(this.userInfos.name);

          // determiner le groupe de l'utilisateur pour attribuer les autorisations
          // choix d'un niveau d'accès normal,moyen,expert
        }
      }, (err) => {
        console.log('Impossible de récupérer les données utilisateur');
        if (typeof (localStorage) !== "undefined") {
          console.log("LocalStorage est supporté");

          if (localStorage.getItem("ProHHHGD") != null) {
            console.log('Infos utilisateur ', localStorage.getItem("ProHHHGD"));
            this.userInfos = {
              name: JSON.parse(localStorage.getItem("ProHHHGD")).user.name,
              email: JSON.parse(localStorage.getItem("ProHHHGD")).user.email,
              displayname: JSON.parse(localStorage.getItem("ProHHHGD")).user.displayname,
              ville: JSON.parse(localStorage.getItem("ProHHHGD")).user.ville,
              telfixe: JSON.parse(localStorage.getItem("ProHHHGD")).user.telfixe,
              telmob: JSON.parse(localStorage.getItem("ProHHHGD")).user.telmob,
              telwork: JSON.parse(localStorage.getItem("ProHHHGD")).user.telwork,
              codep: JSON.parse(localStorage.getItem("ProHHHGD")).user.codep,
              street: JSON.parse(localStorage.getItem("ProHHHGD")).user.street,
              pays: JSON.parse(localStorage.getItem("ProHHHGD")).user.pays,
              groupes: JSON.parse(localStorage.getItem("ProHHHGD")).user.groupes,
              picture: JSON.parse(localStorage.getItem("ProHHHGD")).user.picture,
              password: JSON.parse(localStorage.getItem("ProHHHGD")).user.password,
              loginMethod: "activeDir"
            };
            //this.menuCtrl.enable(true);
            console.log('Infos utilisateur ', this.userInfos);
            this.checkUserName(this.userInfos.name);
            //this.navCtrl.setRoot(MenuPage, { userInfos: this.userInfos });

          }
        } else {
          let msg = this.alertCtrl.create({
            title:"Erreur",
            message:"LocalStorage n'est pas supporté",
            buttons: ["OK"]
          });
          msg.present();
          console.log("LocalStorage n'est pas supporté");
        }
      });
  }



  doLogin() {

    console.log('Username:', this.account.username);
    console.log('Password:', this.account.password);

    let msgLoading = this.loadingCtrl.create({
      content: "Vérifications en cours..."
    });

   msgLoading.present();
    this.thservices.doAdldapUser(this.account.username, this.account.password).subscribe((response) => {
      msgLoading.dismiss().catch((err) => { console.log('Erreur inconnue') });

      if (response) {
        //alert("Connexion etablie :" + response);
        this.showLoading("ldap userinf");
        let usrchk: string = this.account.username;
        this.thservices.doAdldapUserInf(usrchk).subscribe((res) => {
          this.hideLoading();
          console.log("auth :", res);
          //this.nativeStorage.setItem("ProHHHGD", {
           /* this.nativeStorage.set("ProHHHGD", {
            loginMethod: 'activeDir',
            user: {
              name: this.account.username,
              email: res.mail,
              displayname: res.DisplayName,
              ville: res.City,
              telfixe: res.HomePhone,
              telmob: res.mobile,
              telwork: res.OfficePhone,
              codep: res.PostalCode,
              street: res.StreetAddress,
              pays: res.co,
              groupes: res.MemberOf,
              picture: (res.thumbnailPhoto==null)?"":"data:image/jpeg;base64," + this.arrayBufferToBase64(res.thumbnailPhoto).toString(),
              password: this.account.password
            }
          }).then(() => {
            this.hideLoading();
            //this.checkActiveDir();
          }, (error) => {
            this.hideLoading();
            console.log("Memorisation NativeStorage impossible");
            if (typeof (Storage) !== "undefined") {
              console.log("LocalStorage est  supporté");
              localStorage.setItem("ProHHHGD", JSON.stringify({
                loginMethod: 'activeDir',
                user: {
                  name: this.account.username,
                  email: res.mail,
                  displayname: res.DisplayName,
                  ville: res.City,
                  telfixe: res.HomePhone,
                  telmob: res.mobile,
                  telwork: res.OfficePhone,
                  codep: res.PostalCode,
                  street: res.StreetAddress,
                  pays: res.co,
                  groupes: res.MemberOf,
                  picture: (res.thumbnailPhoto==null)?"":"data:image/jpeg;base64," + this.arrayBufferToBase64(res.thumbnailPhoto).toString(),
                  password: this.account.password
                }
              }));
            } else {
              console.log("LocalStorage n'est pas supporté");
            }
           // this.checkActiveDir();
          });*/
        }, (err) => {
          this.hideLoading();
        });
      } else {
        let msgInfo = this.alertCtrl.create({
          title: 'Authentification  impossible!',
          message: "Erreur d'authentification!",
          buttons: ['OK']
        });
        msgInfo.present();
      }
    }, (err) => {
      msgLoading.dismiss().catch((err) => { console.log('Erreur inconnue') });
      let msgInfo = this.alertCtrl.create({
        title: 'Authentification  impossible!',
        message: "Erreur d'authentification!",
        buttons: ['OK']
      });
      msgInfo.present();
    });
  }


  checkUserName(chkuser) {
    // verification que le compte est toujour actif ou existant
    this.showLoading("ldap userinfo");
    this.thservices.doAdldapUserInf(chkuser).subscribe((res) => {
      this.hideLoading();
      if (res != null) {
        if (!res.Enabled) {
          // le compte n'existe plus ou introuvable
          let msgInfo = this.alertCtrl.create({
            title: 'Authentification  impossible!',
            message: "le compte est inactif",
            buttons: ['OK']
          });
          msgInfo.present();


        } else {
          // console.log("test",Array(res.MemberOf)[0].find((element,index,obj) => String(element).split(",")[0]=="CN=GRP-INFORMATIQUE"));
          this.appDatas.userType = "invite";
          this.appDatas.pages = [];
          if (this.appDatas.initpages != undefined) {
            for (let index = 0; index < this.appDatas.initpages.length; index++) {
              let element = this.appDatas.initpages[index];
              this.appDatas.pages.push(element);

            }
          }


          if (String(Array(res.MemberOf)[0].find(element => String(element).split(",")[0] == "CN=GRP-MOB-CA")).split(",")[0] == "CN=GRP-MOB-CA") {
            console.log("Membre du groupe CA sur mobile");

            this.appDatas.userType = "chiffres";

            if (this.appDatas.capages) {
              for (let index = 0; index < this.appDatas.capages.length; index++) {
                let element = this.appDatas.capages[index];
                this.appDatas.pages.push(element);

              }
            }

            this.menuCtrl.enable(true, "menuTech");

          }

          if (String(Array(res.MemberOf)[0].find(element => String(element).split(",")[0] == "CN=GRP-INFORMATIQUE")).split(",")[0] == "CN=GRP-INFORMATIQUE") {
            console.log("Membre du groupe Informatique");

            this.appDatas.userType = "tech";

            if (this.appDatas.techpages) {
              for (let index = 0; index < this.appDatas.techpages.length; index++) {
                let element = this.appDatas.techpages[index];
                this.appDatas.pages.push(element);

              }
            }

            this.menuCtrl.enable(true, "menuTech");

          }


          if (String(Array(res.MemberOf)[0].find(element => String(element).split(",")[0] == "CN=GRP-ADM-INFORMATIQUE")).split(",")[0] == "CN=GRP-ADM-INFORMATIQUE") {
            console.log("Membre du groupe Admin Informatique");

            this.appDatas.userType = "admintech";

            if (this.appDatas.techadmpages) {
              for (let index = 0; index < this.appDatas.techadmpages.length; index++) {
                let element = this.appDatas.techadmpages[index];
                this.appDatas.pages.push(element);

              }
            }


            this.menuCtrl.enable(true, "menuTech");

          }



          this.navCtrl.setRoot('MenuPage', { userInfos: this.userInfos });



        }
      } else {
        // le compte n'existe plus ou introuvable
        let msgInfo = this.alertCtrl.create({
          title: 'Authentification  impossible!',
          message: "le compte n'existe plus ou introuvable",
          buttons: ['OK']
        });
        msgInfo.present();
      }

    });
    //---------------------------------------------------
  }




  //  ---------------- FAcebook LOGIN --------------- avec ionic-cloud-services
  /*



     this.facebookAuth.login().then((res) => {
       let showFBCnx = this.loadingCtrl.create({
         spinner: "bubbles",
         content: "Chargement facebook ..."
       });
       showFBCnx.present();

       let msgInfo = this.alertCtrl.create({
         title: 'Authentification Facebook reussie',
         message: 'Bonjour ' + this.user.social.facebook.data.full_name,
         buttons: ['OK']
       });
       msgInfo.present();
       NativeStorage.setItem('ProHHHGD',
         {
           loginMethod: 'faceBook',
           user: {
             name: this.user.social.facebook.data.full_name,
             email: this.user.social.facebook.data.email,
             picture: this.user.social.facebook.data.profile_picture

           }
         })
         .then(() => {
           //alert("NativeStorage setitem reussi");
           this.navCtrl.setRoot(MenuPage, { loginMethod: "faceBook" });
           showFBCnx.dismiss();
         }, (error) => {
           //alert("NativeStorage setitem error"+error);
           showFBCnx.dismiss();
           console.log(error);
         })

     }, (error) => {
       console.log(error);

       alert("Facebook login error " + error);
     }).catch(err => {

       alert("Facebook erreur inattendue");
     });


   }*/

  showLoading(msg:string) {
    this.loader = this.loadingCtrl.create({
      content: "Chargement..."+msg
    });

    this.loader.present();
  }

  hideLoading() {
    this.loader.dismiss().catch((err) => { console.log("HideLoading", err) });
  }



}
