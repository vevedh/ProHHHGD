import { Component } from '@angular/core';
import { NavController, IonicPage, NavParams, Loading, LoadingController, Toast, ToastController, ViewController} from 'ionic-angular';
import { Thservices } from '../../providers/thservices/thservices';
import { MagasinsPage } from '../../pages/magasins/magasins';
/*
  Generated class for the Caisses page.

  See http://ionicframework.com/docs/v2/components/#navigation for more info on
  Ionic pages and navigation.
*/
@IonicPage()
@Component({
  selector: 'page-caisses',
  templateUrl: 'caisses.html'
})
export class CaissesPage {
  loader: Loading;
  toaster: Toast;
  selectMag: any;
  nbcaisses: any;
  svccaisse:any;
  caisses:Array<any>;

  constructor(public toastCtrl:ToastController,public loadingCtrl: LoadingController, public thservices: Thservices, public navCtrl: NavController, public navParams: NavParams, public viewCtrl:ViewController) {
    this.selectMag = this.navParams.get('selmag');
    this.caisses = [];
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad CaissesPage');
    console.log('Caisse', this.selectMag);

    
    
  }
  ionViewWillEnter() {
    this.showLoading("Recherche les caisses");
    this.thservices.getNbCaiOrk(this.selectMag.ipmag).subscribe((res) => {
      this.hideLoading();
      console.log("Nombre de caisses = ", res);
      this.nbcaisses = res;
      for (var index = 0; index < res; index++) {
        this.caisses.push({
          num:(index+1),
          test:true,
          mon:true,
          atos:true,
          tpe:true
        });
        
      }
    });
  }

  winCaisseDissmiss(){
    this.viewCtrl.dismiss().catch((err)=>{console.log("Erreur de fermeture")});
  }

  gotoBack(){
    this.navCtrl.push(MagasinsPage);
  }

  getTest(num) {
    this.showLoading("Etat caisse n°"+num.toString());
    this.thservices.getTestOrk(this.selectMag.ipmag,num).subscribe((res)=>{
      this.hideLoading();
      console.log("Etat caisse n°"+num.toString(),res);
      this.showToast("Etat caisse n°"+num.toString()+":"+res);
      /*if ( (String(res).indexOf("janus") != -1) && (String(res).indexOf("pppd") != -1) ) {
          this.caisses[num-1].test = true;
      } else {
          this.caisses[num-1].test = false;
      }*/
    },(err)=>{
      this.hideLoading();
    })
  }

  getEtat(num) {
    this.showLoading("Monetique caisse n°"+num.toString());
    this.thservices.getSrvOrk(this.selectMag.ipmag,num).subscribe((res)=>{
      this.hideLoading();
      console.log("Monetique caisse n°"+num.toString(),res);
      this.showToast("Monetique caisse n°"+num.toString()+":"+res);
      if ( (String(res).indexOf("janus") != -1) && (String(res).indexOf("pppd") != -1) ) {
          this.caisses[num-1].mon = true;
      } else {
          this.caisses[num-1].mon = false;
      }
    },(err)=>{
      this.hideLoading();
    })
  }


  getAtos(num) {
    this.showLoading("Atos caisse n°"+num.toString());
    this.thservices.getAtosOrk(this.selectMag.ipmag,num).subscribe((res)=>{
      this.hideLoading();
      console.log("Atos caisse n°"+num.toString(),res);
      this.showToast("Atos caisse n°"+num.toString()+":"+res);
      if ( (String(res).indexOf("Services actifs") != -1)  ) {
          this.caisses[num-1].atos = true;
      } else {
          this.caisses[num-1].atos = false;
      }
    },(err)=>{
      this.hideLoading();
    })
  }

  getTpe(num) {
    this.showLoading("TPE caisse n°"+num.toString());
    this.thservices.getTpeOrk(this.selectMag.ipmag,num).subscribe((res)=>{
      this.hideLoading();
      console.log("TPE caisse n°"+num.toString(),res);
      this.showToast("TPE caisse n°"+num.toString()+":"+res);
      if ( (String(res).indexOf("OK") != -1)  ) {
          this.caisses[num-1].tpe = true;
      } else {
          this.caisses[num-1].tpe = false;
      }
    },(err)=>{
      this.hideLoading();
    })
  }


  doRstCaisse(num) {
    this.showLoading("Ssh caisse n°"+num.toString());
    this.thservices.doOrkRstSsh(this.selectMag.ipmag,num).subscribe((res)=>{
      this.hideLoading();
      console.log("Ssh caisse n°"+num.toString(),res);
      this.showToast("Ssh caisse n°"+num.toString()+res);
    },(err)=>{
      this.hideLoading();
    })
  }


  doRstTpe(num) {
    this.showLoading("Tpe debloq n°"+num.toString());
    this.thservices.getTpeDblq(this.selectMag.ipmag,num).subscribe((res)=>{
      this.hideLoading();
      console.log("Tpe debloq n°"+num.toString(),res);
      this.showToast("Tpe debloq n°"+num.toString()+res);
    },(err)=>{
      this.hideLoading();
    })
  }




  showToast(msg) {
    this.toaster = this.toastCtrl.create({
      message: msg,
      position: 'top',
      duration: 4000
    });
    this.toaster.present();
  }

  hideToast() {
    this.toaster.dismiss();
  }

  showLoading(msg) {
    this.loader = this.loadingCtrl.create({
      content: msg
    });

    this.loader.present();
  }

  hideLoading() {
    this.loader.dismiss();
  }

  //---fin
}
