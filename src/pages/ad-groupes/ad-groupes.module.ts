import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { AdGroupesPage } from './ad-groupes';

@NgModule({
  declarations: [
    AdGroupesPage,
  ],
  imports: [
    IonicPageModule.forChild(AdGroupesPage),
  ],
  exports: [
    AdGroupesPage
  ]
})
export class AdGroupesPageModule {}
