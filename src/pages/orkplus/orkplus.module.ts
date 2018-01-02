import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { OrkplusPage } from './orkplus';

@NgModule({
  declarations: [
    OrkplusPage,
  ],
  imports: [
    IonicPageModule.forChild(OrkplusPage),
  ],
  exports: [
    OrkplusPage
  ]
})
export class OrkplusPageModule {}
