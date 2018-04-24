import { Component } from '@angular/core';
import { Chart } from 'angular-highcharts';
/**
 * Generated class for the VvchartsComponent component.
 *
 * See https://angular.io/api/core/Component for more info on Angular
 * Components.
 */
@Component({
  selector: 'vvcharts',
  templateUrl: 'vvcharts.html'
})
export class VvchartsComponent {

  options: any;

  constructor(chart:Chart) {
    console.log('Hello VvchartsComponent Component');

    this.options = {
      title: { text: 'simple chart' },
      series: [{
        data: [0.9, 0.5, 0.4, 129.2],
      }]
    };

    chart = new Chart(this.options);



  }



}
