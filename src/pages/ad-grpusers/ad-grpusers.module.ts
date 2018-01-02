import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { AdGrpusersPage } from './ad-grpusers';

@NgModule({
  declarations: [
    AdGrpusersPage,
  ],
  imports: [
    IonicPageModule.forChild(AdGrpusersPage),
  ],
  exports: [
    AdGrpusersPage
  ]
})
export class AdGrpusersPageModule {}
