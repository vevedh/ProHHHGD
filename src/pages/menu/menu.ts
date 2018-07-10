import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, ModalController, MenuController, Loading, LoadingController } from 'ionic-angular';
//import { NativeStorage } from '@ionic-native/native-storage';
import {Storage } from '@ionic/storage';

// ------- PROVIDERS ---------
import { Thservices } from '../../providers/thservices/thservices';
import { AppDatas } from '../../providers/app-datas/app-datas';
/**
 * Generated class for the MenuPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-menu',
  templateUrl: 'menu.html',
})
export class MenuPage {
  loader: Loading;
  userInfos: any;
  usertype: string;
  constructor(public appDatas: AppDatas, public loadingCtrl: LoadingController,public nativeStorage:Storage, public menuCtrl: MenuController, public modCtrl: ModalController, public thservices: Thservices, public navCtrl: NavController, public navParams: NavParams) {

    this.userInfos = this.navParams.get("userInfos");
    this.usertype = this.appDatas.userType;

  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad MenuPage');
    console.log("Menu", this.menuCtrl.get());
    this.userInfos = this.navParams.get("userInfos");
    //let loginMethod = this.navParams.get("loginMethod");



  }

   doRstOrkarte() {
    this.showLoading();
    this.thservices.restartOrkarte().subscribe((res) => {
      this.hideLoading();
      console.log("Redemarrage effectué", res);
    });
   }

  doFtpMag30() {
    this.showLoading();
    this.thservices.doSshCmd('10.130.0.2','root','orika','service vsftpd restart').subscribe((res) => {
      this.hideLoading();
      console.log("Redemarrage effectué", res);
    });
  }

  doRstBizerba() {
    this.showLoading();
    this.thservices.doRebootComputer('srvbizerba.3hservices.net').subscribe((res) => {
      this.hideLoading();
      console.log("Redemarrage effectué", res);
    });
  }

  doLogout() {
    this.nativeStorage.remove("ProHHHGD").then((res) => {
      this.navCtrl.setRoot('LoginPage');
    }, (err) => {
      localStorage.removeItem("ProHHHGD");
      this.navCtrl.setRoot('LoginPage');
    });

  }


  showLoading() {
    this.loader = this.loadingCtrl.create({
      content: "Chargement..."
    });

    this.loader.present();
  }

  hideLoading() {
    this.loader.dismiss().catch((err) => { console.log("hideLoading", err) });
  }
  //----------------------------------------------

}
