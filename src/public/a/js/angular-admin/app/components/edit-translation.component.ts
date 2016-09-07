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
    selector: 'edit-translation',
    templateUrl: '/a/js/angular-admin/app/views/edit-translation.html',
    providers: [TranslationService,CategoryService,LanguageService],
    directives: [ROUTER_DIRECTIVES]
})

export class EditTranslationComponent {

    public id;
    public translation:Translation;
    public categories:Category[];
    public cat:Category;
    public languages:Language[];
    public key;
    public description;

     constructor(private _translationService:TranslationService, private _categoryService:CategoryService, private _languageService:LanguageService,
                private _routeParams: RouteParams){
                    this.cat = new Category(0,'','','');
                    this.translation = new Translation(0,'',0,this.cat,'');
       
    }

    ngOnInit():any 
    {
        this.getLanguages();
        this.getCategories();
        this.getTranslationById();
    }

    public editTranslation()
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

    getTranslationById()
    {
        this.id = this._routeParams.get("id");
        this._translationService.getTranslation(this.id)
                                .subscribe(
                                    result => {
                                        this.translation = result.translation;
                                        console.log(result.translation);
                                    },
                                    error => {

                                    }
                                );
    }

}