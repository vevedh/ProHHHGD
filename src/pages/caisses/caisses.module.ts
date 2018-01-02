import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { CaissesPage } from './caisses';

@NgModule({
  declarations: [
    CaissesPage,
  ],
  imports: [
    IonicPageModule.forChild(CaissesPage),
  ],
  exports: [
    CaissesPage
  ]
})
export class CaissesPageModule {}
