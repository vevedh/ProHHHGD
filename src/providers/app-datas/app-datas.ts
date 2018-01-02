import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import { Events } from 'ionic-angular';
import { Storage } from '@ionic/storage';
import 'rxjs/add/operator/map';
//import { StorageConfigToken } from '@ionic/storage/dist/storage';

/*
  Generated class for the AppDatas provider.

  See https://angular.io/docs/ts/latest/guide/dependency-injection.html
  for more info on providers and Angular 2 DI.
*/
@Injectable()
export class AppDatas {


//let config:StorageConfig = ;

//storage = new Storage(['localstorage']:StorageConfig);

  version: string = "1.0.0";

  app_uid: string;

  pages: Array<{
    title: string;
    page?: string;
    icon: string;
    color?: string;
    description?: string;
    logsOut?: boolean;
    index?: number;
    tabComponent?: any;
  }>;

  initpages: Array<{
    title: string;
    page?: string;
    icon: string;
    color?: string;
    description?: string;
    logsOut?: boolean;
    index?: number;
    tabComponent?: any;
  }>;

  techpages: Array<{
    title: string;
    page?: string;
    icon: string;
    color?: string;
    description?: string;
    logsOut?: boolean;
    index?: number;
    tabComponent?: any;
  }>;

  techadmpages: Array<{
    title: string;
    page?: string;
    icon: string;
    color?: string;
    description?: string;
    logsOut?: boolean;
    index?: number;
    tabComponent?: any;
  }>;
  capages: Array<{
    title: string;
    page?: string;
    icon: string;
    color?: string;
    description?: string;
    logsOut?: boolean;
    index?: number;
    tabComponent?: any;
  }>;


  userType: string;

  showMenu: boolean = false;

  // running on a desktop environnment
  isDeskRunning: boolean = false;

  // running with electron
  isElectronRunning: boolean = false;

  demandeEnCours: boolean = false;

  demandeEnvoyee: boolean = false;

  constructor(private events: Events, public http:Http, public storage:Storage) {

  }

  setUid(uid) {
    this.storage.set('prohhhgd_uid', uid)
    this.events.publish('app:uid');
  }

  sendRequest(etat) {
    this.demandeEnvoyee = etat;
    this.events.publish('app:demande');
  }
}
