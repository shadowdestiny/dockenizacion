// Importar el núcleo de Angular
import {Component} from 'angular2/core';
import {ViewEncapsulation} from "angular2/core";
import {ROUTER_DIRECTIVES, RouteConfig, Router} from "angular2/router";
import {ListLanguageComponent} from "./components/list-language.component";
import {EditLanguageComponent} from "./components/edit-language.component";
import {AddLanguageComponent} from "./components/add-language.component";
import {ListCategoryComponent} from "./components/list-category.component";
import {AddCategoryComponent} from "./components/add-category.component";
import {EditCategoryComponent} from "./components/edit-category.component";
import {EditTranslationComponent} from "./components/edit-Translation.component";
import {AddTranslationComponent} from "./components/add-translation.component";
import {AuthService} from "./services/auth.service";
import {OnInit} from "angular2/core";
import config = require('./config/config');
import {ListTranslationComponent} from "./components/list-translation.component";



// Decorador component, indicamos en que etiqueta se va a cargar la plantilla
@Component({
    selector: 'translate-mg',
    templateUrl: '/a/js/angular-admin/app/views/translates.html',
    styleUrls: ["a/js/angular-admin/assets/css/styles-angular.css"],
    providers: [AuthService],
    directives: [ListLanguageComponent,
                 ROUTER_DIRECTIVES]
})

@RouteConfig([
    {path: "/languages",name: "Languages", component: ListLanguageComponent},
    {path: "/edit-language/:id",name: "EditLanguage", component: EditLanguageComponent},
    {path: "/add-language",name: "AddLanguage", component: AddLanguageComponent},
    {path: "/translates",name: "Translates", component: ListTranslationComponent},
    {path: "/categories",name: "Categories", component: ListCategoryComponent},
    {path: "/add-category/",name: "AddCategory", component: AddCategoryComponent},
    {path: "/edit-category/",name: "EditCategory", component: EditCategoryComponent},
    {path: "/edit-translation/",name: "EditTranslation", component: EditTranslationComponent},
    {path: "/add-translation/",name: "AddTranslation", component: AddTranslationComponent},
])


export class AppComponent implements OnInit{

    constructor(private _authService:AuthService)
    {

    }

    ngOnInit():any {
        this.getAuth();
    }

    private getAuth(){
        this._authService.login()
            .subscribe(
                result => {
                },
                error => {
                }
            );
    }
}