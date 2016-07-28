import {Injectable} from "angular2/core";
import {Http, Headers} from "angular2/http";
import {Router} from "angular2/router";
import config = require('../config/config');

@Injectable()
export class AuthService {

    private loggedIn = false;

    constructor(private _http:Http, private _router:Router) {
    }

    login()
    {
        let headers = new Headers();
        let user = config.user_api;
        let pass = config.pass_api;
        headers.append('Content-type', 'application/json');
        headers.append('Authorization', 'Basic ' + btoa(user+":"+pass));
        return this._http
            .post(
                config.api_url+'users/authenticate',
                JSON.stringify({user,pass}),
                {headers}
            )
            .map(
                res => res.json()
            )
            .map(
                (res) => {
                    if(!res.error){
                        this.loggedIn=true;
                        config.token = res.data.token;
                    } else {
                        this._router.navigate(['/translate']);
                    }

                }
            );
    }

    isLoggedIn()
    {
        return this.loggedIn;
    }

    getToken()
    {
        return this.loggedIn ? config.token : null;
    }

    public getHeaderBearer()
    {
        let headers = new Headers();
        headers.append('Authorization', `Bearer ${config.token}`);
        return headers;
    }

}