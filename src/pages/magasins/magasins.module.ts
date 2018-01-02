import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { MagasinsPage } from './magasins';

@NgModule({
  declarations: [
    MagasinsPage,
  ],
  imports: [
    IonicPageModule.forChild(MagasinsPage),
  ],
  exports: [
    MagasinsPage
  ]
})
export class MagasinsPageModule {}
