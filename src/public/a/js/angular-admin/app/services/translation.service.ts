import {Injectable} from "angular2/core";
import {Http, Response} from "angular2/http";
import "rxjs/add/operator/map";
import {Observable} from "rxjs/Observable";
import {AuthService} from "../services/auth.service";
import config = require('../config/config');


@Injectable()
export class TranslationService {

    constructor(private _http:Http, private _authService:AuthService) {

    }

}