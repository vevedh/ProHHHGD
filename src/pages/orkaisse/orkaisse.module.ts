import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { OrkaissePage } from './orkaisse';

@NgModule({
  declarations: [
    OrkaissePage,
  ],
  imports: [
    IonicPageModule.forChild(OrkaissePage),
  ],
  exports: [
    OrkaissePage
  ]
})
export class OrkaissePageModule {}
