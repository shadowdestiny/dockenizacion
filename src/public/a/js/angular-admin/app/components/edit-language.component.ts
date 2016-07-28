// Importar el núcleo de Angular
import {Component} from 'angular2/core';
import {ViewEncapsulation} from "angular2/core";
import {ROUTER_DIRECTIVES, RouteConfig, Router, RouteParams} from "angular2/router";
import {LanguageService} from "../services/language.service";
import {Language} from "../model/language";
import {OnInit} from "angular2/core";

@Component({
    selector: 'edit-language',
    templateUrl: '/a/js/angular-admin/app/views/edit-language.html',
    providers: [LanguageService],
    directives: [ROUTER_DIRECTIVES]
})

export class EditLanguageComponent implements OnInit{

    public id;
    public language:Language;
    public title:string = 'Edit Language';
    public status:boolean = false;

    constructor(private _languageService: LanguageService,
                private _routeParams: RouteParams)
    {
        this.language = new Language(0,'','',true);
    }

    ngOnInit():any {
        this.getLanguageById();
    }

    getLanguageById()
    {
        this.id = this._routeParams.get("id");
        this._languageService.getLanguage(this.id)
                                .subscribe(
                                    result => {
                                        this.language = result.language;
                                    },
                                    error => {

                                    }
                                );
    }

    onSubmit()
    {
        this._languageService.updateLanguage(this.language)
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