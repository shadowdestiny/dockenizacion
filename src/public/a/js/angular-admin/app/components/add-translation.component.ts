// Importar el núcleo de Angular
import {Component} from 'angular2/core';
import {ViewEncapsulation} from "angular2/core";
import {ROUTER_DIRECTIVES, RouteConfig, Router, RouteParams} from "angular2/router";
import {TranslationService} from "../services/translation.service";
import {Translation} from "../model/translation";
import {OnInit} from "angular2/core";

@Component({
    selector: 'add-translation',
    templateUrl: '/a/js/angular-admin/app/views/add-translation.html',
    providers: [TranslationService],
    directives: [ROUTER_DIRECTIVES]
})

export class AddTranslationComponent {

    public translation:Translation;

     constructor(private _translationService:TranslationService,
                private _routeParams: RouteParams){
       
    }

    public addTranslation()
    {

        
    }

}