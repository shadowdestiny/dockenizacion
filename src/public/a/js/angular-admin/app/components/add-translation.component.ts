// Importar el núcleo de Angular
import {Component} from 'angular2/core';
import {ViewEncapsulation} from "angular2/core";
import {ROUTER_DIRECTIVES, RouteConfig, Router, RouteParams} from "angular2/router";
import {TranslationService} from "../services/translation.service";
import {Translation} from "../model/translation";
import {LanguageService} from "../services/language.service";
import {Language} from "../model/language";
import {CategoryService} from "../services/category.service";
import {Category} from "../model/category";
import {OnInit} from "angular2/core";

@Component({
    selector: 'add-translation',
    templateUrl: '/a/js/angular-admin/app/views/add-translation.html',
    providers: [TranslationService,CategoryService,LanguageService],
    directives: [ROUTER_DIRECTIVES]
})

export class AddTranslationComponent {

    public translation:Translation;
    public categories:Category[];
    public languages:Language[];
    public key;
    public description;

     constructor(private _translationService:TranslationService, private _categoryService:CategoryService, private _languageService:LanguageService,
                private _routeParams: RouteParams){
       
    }

    ngOnInit():any 
    {
        
        this.getLanguages();
        this.getCategories();
    }

    public addTranslation()
    {

        
    }

    getLanguages(){
        this._languageService.getLanguages()
                                        .subscribe(
                                        result => {
                                            this.languages = result.languages;
                                            
                                        },
                                        error => {

                                        }
                                    );

    }

    getCategories()
    {
        this._categoryService.getCategories()
                                    .subscribe(
                                        result => {
                                            this.categories = result.translation_categories;
                                        },
                                        error => {
 
                                        }
                                    );
    }

}