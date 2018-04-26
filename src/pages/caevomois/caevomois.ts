import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, Alert, AlertController, LoadingController, Loading} from 'ionic-angular';
//import { Chart } from 'angular-highcharts';
import { Thservices } from '../../providers/thservices/thservices';
declare var Highcharts;
/**
 * Generated class for the CaevomoisPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-caevomois',
  templateUrl: 'caevomois.html',
})
export class CaevomoisPage {

 options:any;
  enseigne: any;
  magasins: any;
  currentLoading: Loading;
  selOptions: any = { title: 'Selection d\'une enseigne' };

  constructor(public navCtrl: NavController, public navParams: NavParams, public vvs: Thservices, public loadCtrl:LoadingController, public alertCtrl: AlertController) {

  }

  ngOnInit() {
    this.init();
  }

  init() {
    Highcharts.chart('container', {
      chart: {
        type: 'line'
      },
      title: {
        text: 'Linechart'
      },
      credits: {
        enabled: false
      },
      series: [{
        name: 'Line 1',
        data: [1, 2, 3]
      }]
    });
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad CaevomoisPage');
  }


  showProgress() {
    this.currentLoading = this.loadCtrl.create({
      content: 'Patientez svp...'
    });
    console.log("Avant ", this.currentLoading._state);
    this.currentLoading.present();
    console.log("Apres ", this.currentLoading._state);
  }

  hideProgress(): void {
    //this.currentLoading.dismissAll();
    this.currentLoading.dismiss().catch((error) => console.log("HideProgress", error));


  }


  doSelect(event) {
    console.log(this.enseigne);
    this.doEnsGraph(this.enseigne);
  }

  doEnsGraph(ensnom) {

    this.options = undefined;

    this.showProgress();

    this.vvs.mob_getCaEvoMois(ensnom).subscribe((datas) => {

      this.hideProgress();

      this.options = {
        title: { text: ensnom },
        chart: {
          type: 'line'
        },
        credits: {
          enabled: false
        },
        yAxis: {
          labels: {
            enabled: false
          },
          title: {
            text: null
          }
        },
        legend: {
          enabled: true
        },
        tooltip: {
          headerFormat: '{series.name}'//,
          //pointFormat: '{point.name}: {point.y:.2f}% of total'
        },
        plotOptions: {
          series: {
            borderWidth: 0,
            dataLabels: {
              enabled: false,
              format: '{point.y:.1f}€'
            }
          }
        },
        series: []
      };


      console.log("Les données :" + JSON.stringify(datas));

      console.log("datas",datas);
      console.log("caevo",datas[1].caevo);
      console.log("eval caevo",eval(datas[1].caevo));
      let lstmois = ["janvier", "f\u00e9vrier", "mars", "avril", "mai", "juin", "juillet", "ao\u00fbt", "septembre", "octobre", "novembre", "d\u00e9cembre"];
      //let lstmois=[Date.UTC(2015,1,1), Date.UTC(2015,2,1), Date.UTC(2015,3,1), Date.UTC(2015,4,1),Date.UTC(2015,5,1), Date.UTC(2015,6,1), Date.UTC(2015,7,1), Date.UTC(2015,8,1), Date.UTC(2015,9,1), Date.UTC(2015,10,1), Date.UTC(2015,11,1), Date.UTC(2015,12,1)];
      //let series = [];

      for (let i = 0; i < Object.keys(datas).length; i++) {
        let rcaevo = [];
        let caevo = [];

        if (datas[i].caevo!="") {
          caevo = eval(datas[i].caevo);
          console.log("bcle caevo",caevo);
          if (caevo.length) {
            for (let j = 0; j < caevo.length; j++) {
              rcaevo[j] = [lstmois[j], eval(caevo[j])];
            }
          }
        }

       this.options.series.push({
          name: 'Chiffres ' + datas[i].nommag,
          data: rcaevo
        });

      }
      console.log("options 1", this.options);

      let sumevo = [];
      let caevo = [];
      if (datas[0].caevo != "") {
        caevo = eval(datas[0].caevo);
        if (caevo.length) {
          for (let j = 0; j < caevo.length; j++) {
            let tot = 0;
            for (let i = 0; i < Object.keys(datas).length; i++) {
              if (datas[i].caevo != "") {
                let caevo = eval(datas[i].caevo);
                tot = tot + parseFloat(caevo[j]);
              }
            }
            sumevo[j] = [lstmois[j], tot];
          }
      }
    }

      this.options.series.push({
        name: 'Chiffre TOTAL',
        data: sumevo
      });

      console.log("options", this.options);
      Highcharts.chart('container', this.options);



    }, (err) => {
      let msgInfo:Alert = this.alertCtrl.create({
        title: "!!! ERREUR !!!",
        message: "Erreur de communication avec le backoffice! Contater l\'administrateur!",
        buttons: ['Merci']
      });
      msgInfo.present();
    })
  }

}
