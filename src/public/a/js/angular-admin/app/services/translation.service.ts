import {Injectable} from "angular2/core";
import {Http, Response} from "angular2/http";
import "rxjs/add/operator/map";
import {Observable} from "rxjs/Observable";
import {AuthService} from "../services/auth.service";
import config = require('../config/config');
import {Translation_details} from "../model/translation_details";
import {Translation} from "../model/translation";


@Injectable()
export class TranslationService {

    constructor(private _http:Http, private _authService:AuthService) {

    }

    getTranslations(lang1,lang2,category){
        let headers = this._authService.getHeaderBearer();


        if(this._authService.isLoggedIn()) {
            if(category != ' '){
                return this._http.get(
                    config.api_url+'translations?lang1='+lang1+'&lang2='+lang2+'&having={"translationCategory_id":'+category+'}',
                    {headers}
                ).map(
                    res => res.json()
                )
            }
            else{
                
                return this._http.get(
                    config.api_url+'translations/?lang1='+lang1+'&lang2='+lang2,
                    {headers}
                ).map(
                    res => res.json()
                )

            }

        }
    }

    getTranslation(id = null) {
        let headers = this._authService.getHeaderBearer();
        if(this._authService.isLoggedIn()) {
            return this._http.get(
                config.api_url+'translations/'+id,
                {headers}
            ).map(
                res => res.json()
            )
        }
    }

    
}