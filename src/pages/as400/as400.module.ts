import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { As400Page } from './as400';

@NgModule({
  declarations: [
    As400Page,
  ],
  imports: [
    IonicPageModule.forChild(As400Page),
  ],
  exports: [
    As400Page
  ]
})
export class As400PageModule {}
