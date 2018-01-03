import { Injectable } from '@angular/core';
import { HttpClient , HttpHeaders, HttpParams} from '@angular/common/http';
import { Observable } from 'rxjs';
//import 'rxjs/add/operator/map';

/*
  Generated class for the Thservices provider.

  See https://angular.io/docs/ts/latest/guide/dependency-injection.html
  for more info on providers and Angular 2 DI.
*/
@Injectable()
export class Thservices {

   // http: Http;
    servicesURL: string;
    emailData: any;
    appStorage: Storage;
    app_uid: any;
    first_exec: boolean;

    public options:any = {};


  constructor(public  http: HttpClient) { //, private electronService?:ElectronService
        //this.http = _http;
        this.servicesURL = "http://3hservices.hhhgd.com/Amfphp/";

    }

    getAppId(): any {

        return this.app_uid;
    }

    getFirstExec(): boolean {
        return this.first_exec;
    }



    sendMail(mailto, mailfrom, subject, text, html) {
        let callData = JSON.stringify({
            'mailto': mailto,
            'mailfrom': mailfrom,
            'subject': subject,
            'text': text,
            'html': html
        });

       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post('https://webservices.rvdechavigny.fr/api/v1/mail', callData, options)
            .map(res => res)
            .catch(this.handleError);

    }


    getUrl() {
        //return new Promise(resolve => {
        // We're using Angular Http provider to request the data,
        // then on the response it'll map the JSON data to a parsed JS object.
        // Next we process the data and resolve the promise with the new data.
        this.http.get('http://10.21.0.15/3hservices.hhhgd.com/Amfphp/test.json')
            .toPromise().then(data => {
                // we've got back the raw data, now generate the core schedule data
                // and save the data for later reference
                //resolve("http://10.21.0.15/3hservices.hhhgd.com/Amfphp/");
                return "http://10.21.0.15/3hservices.hhhgd.com/Amfphp/";
            }, (err) => {
                //resolve("http://3hservices.hhhgd.com/Amfphp/");
                return "http://3hservices.hhhgd.com/Amfphp/";
            });
        // });
    }



    getKeys(obj) {
        var keys = [];
        for (var key in obj) {
            keys.push(key);
        }
        return keys;
    }


    newDevice(obj): any {

        var callData = JSON.stringify(obj);
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/myapps", callData, options)
            .map(res => res)
            .catch(this.handleError);
    }

    checkDevice(uid): any {

       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.get("https://webservices.rvdechavigny.fr/api/v1/myapps?name=ProHHHGD&uid=" + uid, options)
            .map(res => res)
            .catch(this.handleError);
    }



    getUserList(): any {

       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.get("https://webservices.rvdechavigny.fr/api/v1/myapps?name=ProHHHGD", options)
            .map(res => res)
            .catch(this.handleError);
    }


    setUserState(uid: string,state:string): any {

        var callData = JSON.stringify({ "state": state });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.put("https://webservices.rvdechavigny.fr/api/v1/myapps/"+uid, callData, options)
            .map(res => res)
            .catch(this.handleError);


    }

    setUser(uid,user): any {
        var callData = JSON.stringify(user);
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.put("https://webservices.rvdechavigny.fr/api/v1/myapps/"+uid, callData, options)
            .map(res => res)
            .catch(this.handleError);
    }


    getPrinters(imp: any): any {

        var callData = JSON.stringify({ "imprimante": imp });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/printers", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }




    doMemDevice(uid: string, nom: string, prenom: string, dev: string, cdate: string, ctime: string): any {
        var callData = JSON.stringify({
            'serviceName': 'vvproxy_tests',
            'methodName': 'mob_connect',
            'parameters': [uid, nom, prenom, dev, cdate, ctime]
        });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post(this.servicesURL, callData, options)
            .map(res => res)
            .catch(this.handleError);
    }


    doAdldapUserInf(username: string): any {

        var callData = JSON.stringify({ "username": username });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adusr/infos", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }



    doRebootComputer(computername:string):any {


      var callData = JSON.stringify({ "computername": computername });
     // let headers = new Headers({ 'Content-Type': 'application/json' });
      let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

      return this.http.post(" https://webservices.rvdechavigny.fr/api/v1/bizsrv/restartsrv", callData, options)
        .map(res => res)
        .catch(this.handleError);

    }

    doAdldapUserGrpes(username: string): any {

        var callData = JSON.stringify({ "username": username });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adusr/grpmember", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }

    doAdldapShares(computername: string): any {

        var callData = JSON.stringify({ "computername": computername });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adshare", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }


    doMigAddWdns(nummag: string): any {

                var callData = JSON.stringify({ "nummag": nummag });
               // let headers = new Headers({ 'Content-Type': 'application/json' });
                let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

                return this.http.post("https://webservices.rvdechavigny.fr/api/v1/migcaisse/addwdns", callData, options)
                    .map(res => res)
                    .catch(this.handleError);


    }


    doMigAddHdns(nummag: string): any {

                var callData = JSON.stringify({ "nummag": nummag });
               // let headers = new Headers({ 'Content-Type': 'application/json' });
                let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

                return this.http.post("https://webservices.rvdechavigny.fr/api/v1/migcaisse/addhdns", callData, options)
                    .map(res => res)
                    .catch(this.handleError);


    }


    doMigAddMdns(nummag: string): any {

                var callData = JSON.stringify({ "nummag": nummag });
               // let headers = new Headers({ 'Content-Type': 'application/json' });
                let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

                return this.http.post("https://webservices.rvdechavigny.fr/api/v1/migcaisse/addmdns", callData, options)
                    .map(res => res)
                    .catch(this.handleError);


    }


    doMigDelMdns(nummag: string): any {

                var callData = JSON.stringify({ "nummag": nummag });
               // let headers = new Headers({ 'Content-Type': 'application/json' });
                let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

                return this.http.post("https://webservices.rvdechavigny.fr/api/v1/migcaisse/delmdns", callData, options)
                    .map(res => res)
                    .catch(this.handleError);


    }

    doMigDelHdns(nummag: string): any {

                var callData = JSON.stringify({ "nummag": nummag });
               // let headers = new Headers({ 'Content-Type': 'application/json' });
                let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

                return this.http.post("https://webservices.rvdechavigny.fr/api/v1/migcaisse/delhdns", callData, options)
                    .map(res => res)
                    .catch(this.handleError);


    }

    doMigDelWdns(nummag: string): any {

                var callData = JSON.stringify({ "nummag": nummag });
               // let headers = new Headers({ 'Content-Type': 'application/json' });
                let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

                return this.http.post("https://webservices.rvdechavigny.fr/api/v1/migcaisse/delwdns", callData, options)
                    .map(res => res)
                    .catch(this.handleError);


    }




    doAdldapUserOffweek(nbweek: string): any {

        var callData = JSON.stringify({ "nbweek": nbweek });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adusroff", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }

    doAdldapComputerOffweek(nbweek: string): any {

        var callData = JSON.stringify({ "nbweek": nbweek });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adcpoff", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }


    doAdldapUserEnable(username: string): any {

        var callData = JSON.stringify({ "username": username });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adusrenable", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }


    doAdldapUserUnlock(username: string): any {

        var callData = JSON.stringify({ "username": username });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adusrunlock", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }


    doAdldapUserDisable(username: string): any {

        var callData = JSON.stringify({ "username": username });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adusrdisable", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }


    doAdldapUserTumbNail(username: string): any {

        var callData = JSON.stringify({ "username": username });
        //let headers = new Headers({ 'Content-Type': 'application/json'});
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adusrthumbnail", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }


    doAdldapUserSearch(searchname: string): any {

        var callData = JSON.stringify({ "searchname": searchname });
        //let headers = new Headers({ 'Content-Type': 'application/json'});
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adusr/search", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }

    //Liste les groupes selon la recherche
    doAdldapGrpSearch(searchname: string): any {

        var callData = JSON.stringify({ "searchname": searchname });
        //let headers = new Headers({ 'Content-Type': 'application/json'});
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adgrp/search", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }

    //Liste les pcs selon la recherche
    doAdldapPcsSearch(searchname: string): any {

        var callData = JSON.stringify({ "searchname": searchname });
        //let headers = new Headers({ 'Content-Type': 'application/json'});
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adpcs/search", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }



    // liste les comptes inactifs depuis xx jours
    doAdldapUserOffNbjrs(nbweek: string): any {

        var callData = JSON.stringify({ "nbweek": nbweek });
        //let headers = new Headers({ 'Content-Type': 'application/json'});
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adusroffnbjrs", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }

    // deactive les comptes inactifs depuis xx jours
    doAdDisabledUsrNbj(nbjrs:string): any {
        var callData = JSON.stringify({ "nbweek": nbjrs });
        //let headers = new Headers({ 'Content-Type': 'application/json'});
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adusr/offnbjrs", callData, options)
            .map(res => res)
            .catch(this.handleError);
    }

    // Liste des utilisateurs membre d'un groupe
    doAdUsrLstGrp(grpe:string): any {
        var callData = JSON.stringify({ "groupe": grpe });
        //let headers = new Headers({ 'Content-Type': 'application/json'});
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adusr/lstusrgrp", callData, options)
            .map(res => res)
            .catch(this.handleError);
    }

    //retirer un utilisateur a un groupe :
     doAdUsrRmvGrp(grpe:string,user:string): any {
        var callData = JSON.stringify({ "groupe": grpe ,"username":user});
        //let headers = new Headers({ 'Content-Type': 'application/json'});
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adusr/rmvusrgrp", callData, options)
            .map(res => res)
            .catch(this.handleError);
    }

    //Ajouter un utilisateur a un groupe :
     doAdUsrAddGrp(grpe:string,user:string): any {
        var callData = JSON.stringify({ "groupe": grpe ,"username":user});
        //let headers = new Headers({ 'Content-Type': 'application/json'});
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adusr/addusrgrp", callData, options)
            .map(res => res)
            .catch(this.handleError);
    }

    //Ajouter un groupe :
     doAdNewGrp(grpe:string,desc:string): any {
        var callData = JSON.stringify({ "grpname": grpe ,"desc":desc});
        //let headers = new Headers({ 'Content-Type': 'application/json'});
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/adusr/newgrp", callData, options)
            .map(res => res)
            .catch(this.handleError);
    }


    doAdldapUser(username: string, password: string) {

        var callData = JSON.stringify({
            'serviceName': 'vvproxy_tests',
            'methodName': 'adldapTest',
            'parameters': [username, password]
        });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post(this.servicesURL, callData, options)
            .map(res => res)
            .catch(this.handleError);
    }


    doGlpiSupport() {
        var callData = JSON.stringify({});
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/glpimail", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }




    mob_getInfos(guid: string): any {

        var callData = JSON.stringify({
            'serviceName': 'vvproxy_tests',
            'methodName': 'mob_getInfos',
            'parameters': [guid]
        });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post(this.servicesURL, callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    mob_getCaJourSec(guid: string, ensnom: string, annee: string, mois: string, jour: string): any {
        var callData = JSON.stringify({
            'serviceName': 'vvproxy_tests',
            'methodName': 'ork_ca_sec',
            'parameters': [guid, ensnom, annee, mois, jour]
        });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post(this.servicesURL, callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    mob_getCaJour(ensnom: string, annee: string, mois: string, jour: string): any {
         var callData = JSON.stringify({
             'enseigne':ensnom,
             'annee':annee,
             'mois':mois,
             'jour':jour
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/cajour", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    mob_getCaEvoMois(ensnom: string): any {
        var callData = JSON.stringify({
             'enseigne':ensnom
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/caevomois", callData, options)
            .map(res => res)
            .catch(this.handleError);



    }

    doAutoEdtMsgw(): any {

        var callData = null;
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/rstedtmsgw", callData, options)
            .map(res => res)
            .catch(this.handleError);



    }


    getIpImp(imp: string): any {
        var callData = JSON.stringify({
             'imprimante':imp
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/ipimp", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }



    debloqUser(user: string): any {

        var callData = JSON.stringify({
             'user':user
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/dblquser", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    dblqPrtEcomax(prtn: string): any {
        var callData = JSON.stringify({
             'imprimante':prtn
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/dblqprtecomax", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    debloqEcomax(): any {
        var callData = null;
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/dblqecomax", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }


    restartOrkarte(): any {

        var callData = null;
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/rstorkarte", callData, options)
            .map(res => res)
            .catch(this.handleError);
    }

    debloqDevice(dev: string): any {
        var callData = JSON.stringify({
             'device':dev
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/dblqdev", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    chkIp(ipval: string): any {
        var callData = JSON.stringify({
             'ipval':ipval
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/chkIp", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    testIp(ipvall: string): any {
        var callData = JSON.stringify({
             'ipval':ipvall
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/chkIp", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    startEditeur(imp: string): any {
        var callData = JSON.stringify({
             'imprimante':imp
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/startedt", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    stopEditeur(imp: string): any {
        var callData = JSON.stringify({
             'imprimante':imp
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/stopedt", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }


    resetEditeur(imp: string): any {
        var callData = JSON.stringify({
             'imprimante':imp
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/rstimp", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }



    getInfosMag(num: string): any {
        var callData = JSON.stringify({
             'num':num
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/infmag", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }


    // maj table vvmobmagip  migmagdsc
    doMigMagDsc(num: string): any {
        var callData = JSON.stringify({
             'num':num
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/migmagdsc", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }


    // maj table vvmobmagip  /migmagmob
    doMigMag(num: string): any {
        var callData = JSON.stringify({
             'num':num
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/migmagmob", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }

    /*getActifMobUser(): any {

        var callData = JSON.stringify({
            'serviceName': 'vvproxy_tests',
            'methodName': 'query_AS400JSON',
            'parameters': ["select * from vvbase/vvmobca where ETAT='ACTIF' "]
        });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};
        return this.http.post(this.servicesURL, callData, options)
            .map(res => res)
            .catch(this.handleError);

    }


    getSecoffMobUser(): any {

        var callData = JSON.stringify({
            'serviceName': 'vvproxy_tests',
            'methodName': 'query_AS400JSON',
            'parameters': ["select * from vvbase/vvmobca where ETAT='SECOFF' "]
        });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};
        return this.http.post(this.servicesURL, callData, options)
            .map(res => res)
            .catch(this.handleError);

    }
*/


    /*getInfMobUser(guid: string): any {

        var callData = JSON.stringify({
            'serviceName': 'vvproxy_tests',
            'methodName': 'query_AS400JSON',
            'parameters': ["select * from vvbase/vvmobca where GUID='" + guid + "' "]
        });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};
        return this.http.post(this.servicesURL, callData, options)
            .map(res => res)
            .catch(this.handleError);

    }*/


    /*setInfMobUser(guid: string, nom: string, prenom: string, email: string): any {
        var callData = JSON.stringify({
            'serviceName': 'vvproxy_tests',
            'methodName': 'query_update',
            'parameters': ["update  vvbase/vvmobca set NOM='" + nom + "', PRENOM='" + prenom + "' , EMAIL='" + email + "' where  GUID='" + guid + "' "]
        });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};
        return this.http.post(this.servicesURL, callData, options)
            .map(res => res)
            .catch(this.handleError);

    }*/


    setInitMag(nummag: string, ipcaisse: string): any {
        var callData = JSON.stringify({
             'num':nummag,
             'ipcaisse':ipcaisse
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/initmag", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    setInfMag(nummag: string, chp: string, valdata: string): any {
        var callData = JSON.stringify({
             'num':nummag,
             'champ':chp,
             'valdata':valdata
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/infmag", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }


    getnIpImp(nmimp: string): any {
         var callData = JSON.stringify({
             'imprimante':nmimp
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/ipimp", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }


    getNbCaiOrk(ipm: string): any {
        var callData = JSON.stringify({
             'ipmagasin':ipm
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/orknbcai", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    getTestOrk(ipm: string, cnum: string): any {
        var callData = JSON.stringify({
             'ipmagasin':ipm,
             'numcaisse':cnum
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/orkcaisse", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    getSrvOrk(ipm: string, cnum: string): any {
        var callData = JSON.stringify({
             'ipmagasin':ipm,
             'numcaisse':cnum
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/orksrv", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    getAtosOrk(ipm: string, cnum: string): any {
        var callData = JSON.stringify({
             'ipmagasin':ipm,
             'numcaisse':cnum
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/orkatos", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    getTpeOrk(ipm: string, cnum: string): any {
        var callData = JSON.stringify({
             'ipmagasin':ipm,
             'numcaisse':cnum
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/orktpe", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }


    getTpeDblq(ipm: string, cnum: string): any {
        var callData = JSON.stringify({
             'ipmagasin':ipm,
             'numcaisse':cnum
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/orktpedblq", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    doOrkRstSsh(ipm: string, cnum: string): any {
        var callData = JSON.stringify({
             'ipmagasin':ipm,
             'numcaisse':cnum
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/orkrstssh", callData, options)
            .map(res => res)
            .catch(this.handleError);

    }

    getMagByEns(ens: string): any {
        var callData = JSON.stringify({
             'enseigne':ens
            });
       // let headers = new Headers({ 'Content-Type': 'application/json' });
        let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

        return this.http.post("https://webservices.rvdechavigny.fr/api/v1/magbyens", callData, options)
            .map(res => res)
            .catch(this.handleError);


    }





  getCaJour(enseigne, jour, mois, annee) {
    let callData = JSON.stringify({
      "enseigne": (enseigne.toString()),
      "annee": annee,
      "mois": mois,
      "jour": jour
    });
   // let headers = new Headers({ 'Content-Type': 'application/json' });
    let options:any =  { headers: new HttpHeaders().set('Content-Type', 'application/json'), params: new HttpParams().set('headers', ' headers')};

    return this.http.post('https://webservices.rvdechavigny.fr/api/v1/cajour', callData, options)
      .map(res => res)
      .catch(this.handleError);

  }


    handleError(error: any): any {
        console.log(error);
        return Observable.throw(error.json().error || 'Server error'); //.json().error
        //return JSON.parse(error);
    }

}

