// Importar el núcleo de Angular
import {Component} from 'angular2/core';
import {ViewEncapsulation} from "angular2/core";
import {ROUTER_DIRECTIVES, RouteConfig, Router, RouteParams} from "angular2/router";
import {CategoryService} from "../services/category.service";
import {Category} from "../model/category";
import {OnInit} from "angular2/core";

@Component({
    selector: 'edit-category',
    templateUrl: '/a/js/angular-admin/app/views/edit-category.html',
    providers: [CategoryService],
    directives: [ROUTER_DIRECTIVES]
})

export class EditCategoryComponent {

      constructor(private _categoryService:CategoryService,
                private _routeParams: RouteParams){
        
    }


    public editCategory()
    {
       
    }

}