import {Injectable} from "angular2/core";
import {Http, Response} from "angular2/http";
import "rxjs/add/operator/map";
import {Observable} from "rxjs/Observable";
import {Language} from "../model/language";
import {AuthService} from "../services/auth.service";
import config = require('../config/config');


@Injectable()
export class LanguageService {

    constructor(private _http:Http, private _authService:AuthService) {

    }

    getLanguage(id = null) {
        let headers = this._authService.getHeaderBearer();
        if(this._authService.isLoggedIn()) {
            return this._http.get(
                config.api_url+'languages/'+id,
                {headers}
            ).map(
                res => res.json()
            )
        }
    }

    updateLanguage(language:Language) {
        let headers = this._authService.getHeaderBearer();
        let json = JSON.stringify({ id: language.id,
                                    ccode: language.ccode,
                                    defaultLocale: language.defaultLocale,
                                    active: language.active
                                  });
        if(this._authService.isLoggedIn()) {
            return this._http.put(
                config.api_url+'languages/'+language.id,
                json,
                {headers}
            ).map(
                res => res.json()
            )
        }
    }

    addLanguage(language:Language) {
        let headers = this._authService.getHeaderBearer();
        let json = JSON.stringify({ id: language.id,
            ccode: language.ccode,
            defaultLocale: language.defaultLocale,
            active: 0
        });
        if(this._authService.isLoggedIn()) {
            return this._http.post(
                config.api_url+'languages',
                json,
                {headers}
            ).map(
                res => res.json()
            )
        }
    }


    getLanguages()
    {
        let headers = this._authService.getHeaderBearer();
        if(this._authService.isLoggedIn()) {
            return this._http.get(
                config.api_url+'languages',
                {headers}
            ).map(
                res => res.json()
            )
        }
    }


}