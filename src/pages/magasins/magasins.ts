import { Component } from '@angular/core';
import { NavController, IonicPage, NavParams, Loading, LoadingController, Modal, ModalController } from 'ionic-angular';

import { Thservices } from '../../providers/thservices/thservices';
/*
  Generated class for the Magasins page.

  See http://ionicframework.com/docs/v2/components/#navigation for more info on
  Ionic pages and navigation.
*/
@IonicPage()
@Component({
  selector: 'page-magasins',
  templateUrl: 'magasins.html'
})
export class MagasinsPage {
  enseigne: string;
  loader: Loading;
  magasins: any;
  winOrkaisse: Modal;


  constructor(public loadingCtrl: LoadingController, public thservices: Thservices, public navCtrl: NavController, public modCtrl: ModalController, public navParams: NavParams) {

  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad MagasinsPage');

  }

  majdns(num) {
    this.showLoading();
    this.thservices.doMigDelMdns(num).subscribe((res) => {
      this.hideLoading();
      console.log("Effacer entre M0" + num + " dans le dns");
      this.showLoading();
      this.thservices.doMigDelHdns(num).subscribe((res) => {
        this.hideLoading();
        console.log("Effacer entre H0" + num + " dans le dns");
        this.showLoading();
        this.thservices.doMigDelWdns(num).subscribe((res) => {
          this.hideLoading();
          console.log("Effacer entre H0" + num + " dans le dns");
          this.showLoading();
          this.thservices.doMigAddWdns(num).subscribe((res) => {
            this.hideLoading();
            console.log("Ajouter entre W0" + num + " dans le dns");
            this.showLoading();
            this.thservices.doMigAddHdns(num).subscribe((res) => {
              this.hideLoading();
              console.log("Ajouter entre H0" + num + " dans le dns");
              this.showLoading();
              this.thservices.doMigAddMdns(num).subscribe((res) => {
                this.hideLoading();
                console.log("Ajouter entre M0" + num + " dans le dns");
              });
            });
          });
        });
      });

    });
  }

  majdsc(num) {
    this.showLoading();
    this.thservices.doMigMagDsc(num).subscribe((res) => {
      this.hideLoading();
      console.log("Modification table vvorika faite");
    });
  }

  majmag(num) {
    this.showLoading();
    this.thservices.doMigMag(num).subscribe((res) => {
      this.hideLoading();
      console.log("Modification table vvmobmagip faite");
    });
  }

  getMagasins() {
    console.log("Enseigne selectionée", this.enseigne);

    this.showLoading();
    this.thservices.getMagByEns(this.enseigne).subscribe((res) => {
      this.hideLoading();
      console.log("Liste de magasins", res);
      this.magasins = res;

      /*let truc = {
        ensnom: "ECOMAX MQ",
        id: "25",
        ipmag: "192.168.150.12",
        nommag: "BELLEVUE",
        nummag: "071"
      }*/
    });
  }

  goOrkaisse(magasin) {
    this.winOrkaisse = this.modCtrl.create('CaissesPage', { 'selmag': magasin }, );
    this.winOrkaisse.present();
    this.winOrkaisse.onDidDismiss(data => {

    });

    //this.navCtrl.push(OrkaissePage , {'selmag': magasin } );
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
}
