// Importar el núcleo de Angular
import {Component} from 'angular2/core';
import {ViewEncapsulation} from "angular2/core";
import {ROUTER_DIRECTIVES, RouteConfig, Router, RouteParams} from "angular2/router";
import {TranslationService} from "../services/translation.service";
import {Language} from "../model/language";
import {OnInit} from "angular2/core";

@Component({
    selector: 'list-languages',
    templateUrl: '/a/js/angular-admin/app/views/list-translations.html',
    providers: [TranslationService],
    directives: [ROUTER_DIRECTIVES]
})

export class ListTranslationComponent implements OnInit{


    constructor(private _translationService: TranslationService)
    {

    }

    ngOnInit():any {
    }

}