// Importar el núcleo de Angular
import {Component} from 'angular2/core';
import {ViewEncapsulation} from "angular2/core";
import {ROUTER_DIRECTIVES, RouteConfig, Router, RouteParams} from "angular2/router";
import {LanguageService} from "../services/language.service";
import {Language} from "../model/language";
import {OnInit} from "angular2/core";

@Component({
    selector: 'add-language',
    templateUrl: '/a/js/angular-admin/app/views/add-language.html',
    providers: [LanguageService],
    directives: [ROUTER_DIRECTIVES]
})

export class AddLanguageComponent {

    public language:Language;
    public status:boolean = false;


    constructor(private _languageService:LanguageService,
                private _routeParams: RouteParams){
        this.language = new Language(0,'','',true);
    }

    public addLanguage()
    {
        this._languageService.addLanguage(this.language)
            .subscribe(
                result => {
                    this.status=true;
                },
                error => {
                    this.status=false;
                }
            );
    }


}