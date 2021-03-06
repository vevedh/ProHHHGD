import { Component, ViewChild } from '@angular/core';
import { Nav, Platform, Loading, LoadingController, MenuController, Modal, ModalController, AlertController, ToastController } from 'ionic-angular';

import { StatusBar } from '@ionic-native/status-bar';
import { SplashScreen } from '@ionic-native/splash-screen';

//import { AndroidPermissions } from '@ionic-native/android-permissions';

//import { FCM } from '@ionic-native/fcm';

import { Thservices } from '../providers/thservices/thservices';
import { AppDatas } from '../providers/app-datas/app-datas';

declare var electron: any;

@Component({
  templateUrl: 'app.html'
})
export class MyApp {
  @ViewChild(Nav) navCtrl: Nav;

  rootPage: any = null;
  paramsPage: any;
  appPages: Array<{
    title: string;
    page?: string;
    icon: string;
    color?: string;
    description?: string;
    logsOut?: boolean;
    index?: number;
    tabComponent?: any;
  }>;
  userInfos: any;
  loader: Loading;
  modalWin: Modal;


  techPages: Array<{
    title: string;
    page?: string;
    icon: string;
    color?: string;
    description?: string;
    logsOut?: boolean;
    index?: number;
    tabComponent?: any;
  }> = [
    {
      title: 'Orkaisses',
      icon: 'cart',
      description: 'Gestion des caisses magasins.',
      page: 'MagasinsPage',
      color: '#E63135'
    }, {
      title: 'Windows',
      icon: 'people',
      description: 'Gestion des actions windows sur le domaine.',
      page: 'AdSearchPage',
      color: '#F46529'
    },
    {
      title: 'AS400',
      icon: 'globe',
      description: 'Gestion des actions AS400!',
      page: 'As400Page',
      color: '#0CA9EA'
    },
    {
      title: 'Wifi',
      icon: 'wifi',
      description: 'Gestion du Wifi',
      color: '#FFD439'
    },
    {
      title: 'Telephonie IP',
      icon: 'call',
      description: 'Gestion du Telephonie IP',
      color: '#FFD439'
    },
    {
      title: 'Schémas/Plans',
      icon: 'map',
      description: 'Gestion du Schémas/Plans',
      color: '#FFD439'
    },
    {
      title: 'A propos ...',
      icon: 'information-circle',
      description: 'Information application',
      page: 'AboutPage',
      color: '#FFD439'
    }
  ]

  caPages: Array<{
    title: string;
    page?: string;
    icon: string;
    color?: string;
    description?: string;
    logsOut?: boolean;
    index?: number;
    tabComponent?: any;
  }> = [
    {
      title: 'Chiffres',
      icon: 'cash',
      description: 'Chiffres d\'affaires magasins.',
      page: 'CamagasinsPage',
      color: '#1010FF'
    },
      {
        title: 'Stats Chiffres',
        icon: 'stats',
        description: 'Stats Chiffres d\'affaires magasins.',
        page: 'CaevomoisPage',
        color: '#1010FF'
      }
  ];

  constructor(public platform: Platform,
    public statusBar: StatusBar,
    public splashScreen: SplashScreen,
    public alertCtrl: AlertController,
    public toastCtrl: ToastController,
    public modCtrl: ModalController,
    public menuCtrl: MenuController,
    public appDatas: AppDatas,
    public thservices: Thservices,
    public loadingCtrl: LoadingController) {

      //,
  //private androidPermissions: AndroidPermissions
     // used for an example of ngFor and navigation
    this.appDatas.pages = this.appPages;
    this.appDatas.initpages = this.appPages;
    this.appDatas.techpages = this.techPages;
    this.appDatas.capages = this.caPages;

    this.initializeApp();



  }

  initializeApp() {


    this.platformReady();

  }

  platformReady() {
    this.platform.ready().then(() => {
      // Okay, so the platform is ready and our plugins are available.
      // Here you can do any higher level native things you might need.
      //Notifications

      this.statusBar.styleDefault();
      this.splashScreen.hide();




      if (this.platform.is('core')) {
        console.log("S'exécute sur une platform de bureau!");
        // page de login pour application de bureau
        this.rootPage = 'LoginPage';
        //this.checkActiveDir();
      } else if (this.platform.is('mobile') || this.platform.is('iphone') || this.platform.is('tablet') || this.platform.is('phablet') || this.platform.is('cordova') || this.platform.is('android')) {
        console.log("S'exécute sur une platform mobile!");
        //this.checkActiveDir();

        this.rootPage = 'LoginPage';
      }



      //-----  TEST ATOM ELECTRON -----------------
      try {
        if (electron != undefined) {
          console.log("S'execute avec electron");
          //this.appDatas.isElectronRunning = true;
        }
      } catch (error) {
        console.log("S'execute sans electron");
      }
      //-------------------------------------------





    });
  }



  goToPage(page) {

    if (page == undefined) {
      this.showNext();
    } else {
      this.navCtrl.push(page);
      this.menuCtrl.close();
    }

  }

  gotoMain() {

    //this.menuCtrl._unregister(this.menuCtrl.)
    this.navCtrl.setRoot('LoginPage');
  }

  showNext() {
    let msgNext = this.alertCtrl.create({
      title: 'Fonction non active',
      message: 'Cette option sera disponible dans la prochaine version',
      buttons: ['OK']
    });
    msgNext.present();
  }

  showLoading() {
    this.loader = this.loadingCtrl.create({
      content: "Chargement..."
    });

    this.loader.present();
  }

  hideLoading() {
    this.loader.dismiss();
  }

  //---------------------- fin
}
