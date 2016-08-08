// Importar el núcleo de Angular
import {Component} from 'angular2/core';
import {ViewEncapsulation} from "angular2/core";
import {ROUTER_DIRECTIVES, RouteConfig, Router, RouteParams} from "angular2/router";
import {TranslationService} from "../services/translation.service";
import {Translation} from "../model/translation";
import {OnInit} from "angular2/core";

@Component({
    selector: 'edit-translation',
    templateUrl: '/a/js/angular-admin/app/views/edit-translation.html',
    providers: [TranslationService],
    directives: [ROUTER_DIRECTIVES]
})

export class EditTranslationComponent implements OnInit{

    constructor(private _languageService: TranslationService,
                private _routeParams: RouteParams)
    {
        
    }

    ngOnInit():any {
        
    }

}

