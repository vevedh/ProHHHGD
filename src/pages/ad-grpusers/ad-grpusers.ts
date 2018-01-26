import { Component } from '@angular/core';
import { NavController,IonicPage,ViewController, NavParams } from 'ionic-angular';
// -------- APPDATAS
import { AppDatas } from '../../providers/app-datas/app-datas';
import { AlertController } from 'ionic-angular/components/alert/alert-controller';
import { Thservices } from '../../providers/thservices/thservices';
import { Loading } from 'ionic-angular/components/loading/loading';
import { LoadingController } from 'ionic-angular/components/loading/loading-controller';

/*
  Generated class for the AdGrpusers page.

  See http://ionicframework.com/docs/v2/components/#navigation for more info on
  Ionic pages and navigation.
*/
@IonicPage()
@Component({
  selector: 'page-ad-grpusers',
  templateUrl: 'ad-grpusers.html'
})
export class AdGrpusersPage {


  loader:Loading;
  lstUsr:Array<any>;
  srcUsr:Array<any>;
  srcGrp:any;
  savUsr:Array<any>;
  savUsrChk:number;

  constructor(public appDatas: AppDatas, public navCtrl: NavController, public navParams: NavParams, public viewCtrl: ViewController, public alertCtrl: AlertController, public thsservices: Thservices, public loadingCtrl:LoadingController) {
    this.appDatas = appDatas;
    this.srcUsr = this.navParams.get('lstusrs');
    this.srcGrp = this.navParams.get('grp');
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad AdGrpusersPage');
    this.lstUsr = [];
    for (let i = 0; i < Object(this.srcUsr).length; i++) {
      let obj:Object = {
        usrname: this.srcUsr[i].SamAccountName,
        nom: this.srcUsr[i].Name,
        checked: false
      }
      this.lstUsr.push(obj);
    }
    this.savUsrChk=0;
    console.log(this.lstUsr);
  }

  doSelUsr($event,index) {
    if ($event.checked) {
      this.savUsrChk++;
    } else {
      this.savUsrChk--;
    }
    this.lstUsr[index].checked = $event.checked;

  }

  memSelect() {
    this.savUsr = [];
    console.log("Liste", this.lstUsr);
    for (let i = 0; i < Object(this.lstUsr).length; i++) {
      //console.log("Index",)
      if (this.lstUsr[i].checked == true) {
        let obj: Object = {
          nom: Object(this.lstUsr)[i].nom,
          usrname: Object(this.lstUsr)[i].usrname
        }
        this.savUsr.push(obj);
      }
    }
    //------------------------------------
    console.log("List users : ", this.savUsr);
  }

  dupDismiss() {
    this.memSelect();
    let data = this.savUsr;
    this.viewCtrl.dismiss(data).catch((err) => { console.log("erreur")});
  }

  supDismiss() {
    this.memSelect();
    let data = this.savUsr;
    console.log("Utilisateurs à supprimer ",data);
   // doAdUsrRmvGrp(this.srcGrp, this.savUsr.usrname)
    //this.viewCtrl.dismiss(data).catch((err) => { console.log("erreur")});

     for (const key in data) {
       if (data.hasOwnProperty(key)) {
         const element = data[key];
         console.log("supprimer "+element.usrname+":"+this.srcGrp);
        /* this.thsservices.doAdUsrRmvGrp(this.srcGrp, element.usrname).subscribe((res) => {
           this.hideLoading();
         }, err => {
           this.hideLoading();
         })*/
       }
     }



    //-----------------------
  }

  doDismiss() {
    this.viewCtrl.dismiss().catch((err) => { console.log("erreur")});
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

//------------
}
