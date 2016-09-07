import config = require('../config/config');
import {Category} from "../model/category";
import {Injectable} from "angular2/core";
import {Http, Response} from "angular2/http";
import "rxjs/add/operator/map";
import {Observable} from "rxjs/Observable";
import {AuthService} from "../services/auth.service";



@Injectable()
export class CategoryService {

    constructor(private _http:Http, private _authService:AuthService){

    }

     getCategory(id = null){
        let headers = this._authService.getHeaderBearer();
        if(this._authService.isLoggedIn()) {
            return this._http.get(
                config.api_url+'translationCategories/'+id,
                {headers}
            ).map(
                res => res.json()
            )
        }
    }
    
    getCategories(){
       let headers = this._authService.getHeaderBearer();
        if(this._authService.isLoggedIn()) {
            return this._http.get(
                config.api_url+'translationCategories',
                {headers}
            ).map(
                res => res.json()
            )
        }
    }

    addCategory(category:Category) {

    }

    updateCategory(category:Category) {
        let headers = this._authService.getHeaderBearer();
        let json = JSON.stringify({ id: category.id,
                                    categoryName: category.categoryName,
                                    categoryCode: category.categoryCode,
                                    description: category.description
                                  });
        if(this._authService.isLoggedIn()) {
            return this._http.put(
                config.api_url+'translationCategories/'+category.id,
                json,
                {headers}
            ).map(
                res => res.json()
            )
        }


    }

}