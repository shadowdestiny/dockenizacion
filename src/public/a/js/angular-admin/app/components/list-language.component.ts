// Importar el núcleo de Angular
import {Component} from 'angular2/core';
import {ViewEncapsulation} from "angular2/core";
import {ROUTER_DIRECTIVES, RouteConfig, Router, RouteParams} from "angular2/router";
import {LanguageService} from "../services/language.service";
import {Language} from "../model/language";
import {OnInit} from "angular2/core";

@Component({
    selector: 'list-languages',
    templateUrl: '/a/js/angular-admin/app/views/list-languages.html',
    providers: [LanguageService],
    directives: [ROUTER_DIRECTIVES]
})

export class ListLanguageComponent implements OnInit{

    public languages:Language[];
    public title:string = 'Manage Languages';

    constructor(private _languageService: LanguageService)
    {

    }

    ngOnInit():any {
       this.getLanguages();
    }

    getLanguages()
    {
        this._languageService.getLanguages()
                                    .subscribe(
                                        result => {
                                            this.languages = result.languages;
                                        },
                                        error => {

                                        }
                                    );
    }

}