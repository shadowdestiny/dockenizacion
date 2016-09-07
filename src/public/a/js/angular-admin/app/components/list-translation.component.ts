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
    selector: 'list-translations',
    templateUrl: '/a/js/angular-admin/app/views/list-translations.html',
    providers: [TranslationService,LanguageService,CategoryService],
    directives: [ROUTER_DIRECTIVES]
})

export class ListTranslationComponent implements OnInit{

    public translations:Translation[];
    public languages:Language[];
    public categories:Category[];
    public title:string = 'Manage Translations';
    public lang1 = 'en';
    public lang2 = 'es';
    public language1 = '';
    public language2 = '';
    public cat = ' ';

    constructor(private _translationService: TranslationService, private _languageService: LanguageService, private _categoryService: CategoryService)
    {
        this.translations = new Array();
        this.languages = new Array();
        this.categories = new Array();
    }

    ngOnInit():any 
    {
        this.getTranslations(this.lang1,this.lang2,this.cat);
        this.getLanguages();
        this.getCategories();
    }

    onSubmit():any
    {
        this.lang1 = this.language1;
        this.lang2 = this.language2;
        this.getTranslations(this.language1,this.language2,this.cat);
        this.getLanguages();
        this.getCategories();
    }

    getTranslations(l1,l2,cat){
        this._translationService.getTranslations(l1,l2,cat)
                                        .subscribe(
                                        result => {
                                            this.translations = result.translations;
                                            console.log(result.translations);
                                        },
                                        error => {

                                        }
                                    );

        

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