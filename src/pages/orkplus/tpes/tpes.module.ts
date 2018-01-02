import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { TpesPage } from './tpes';

@NgModule({
  declarations: [
    TpesPage,
  ],
  imports: [
    IonicPageModule.forChild(TpesPage),
  ],
  exports: [
    TpesPage
  ]
})
export class TpesPageModule {}
