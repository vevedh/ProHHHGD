import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { AdSearchPage } from './ad-search';


@NgModule({
  declarations: [
    AdSearchPage
  ],
  imports: [
    IonicPageModule.forChild(AdSearchPage),
  ],
  exports: [
    AdSearchPage
  ]
})
export class AdSearchPageModule {}
