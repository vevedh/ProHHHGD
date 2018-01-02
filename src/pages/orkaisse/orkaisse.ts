import { Component } from '@angular/core';
import { NavController, NavParams, IonicPage } from 'ionic-angular';
import { CaissesPage } from '../../pages/caisses/caisses';
/*
  Generated class for the Orkaisse page.

  See http://ionicframework.com/docs/v2/components/#navigation for more info on
  Ionic pages and navigation.
*/
@IonicPage()
@Component({
  selector: 'page-orkaisse',
  templateUrl: 'orkaisse.html'
})
export class OrkaissePage {

  selectMag:any;
  TabPage1:any;
  TabPage2:any;
  TabPage3:any;
  TabPage4:any;

  constructor(public navCtrl: NavController, public navParams: NavParams) {
    this.selectMag = this.navParams.get('selmag');

    this.TabPage1 = CaissesPage;
  //TabPage2 = CaissesPage;
  //TabPage3 = CaissesPage;
  //TabPage4 = CaissesPage;

  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad OrkaissePage',this.selectMag);

  }

}
