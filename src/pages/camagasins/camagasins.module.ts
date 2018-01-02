import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { CamagasinsPage } from './camagasins';

@NgModule({
  declarations: [
    CamagasinsPage,
  ],
  imports: [
    IonicPageModule.forChild(CamagasinsPage),
  ],
  exports: [
    CamagasinsPage
  ]
})
export class CamagasinsPageModule {}
