// Importar el núcleo de Angular
import {Component} from 'angular2/core';
import {ViewEncapsulation} from "angular2/core";
import {ROUTER_DIRECTIVES, RouteConfig, Router, RouteParams} from "angular2/router";
import {CategoryService} from "../services/category.service";
import {Category} from "../model/category";
import {OnInit} from "angular2/core";

@Component({
    selector: 'list-categories',
    templateUrl: '/a/js/angular-admin/app/views/list-categories.html',
    providers: [CategoryService],
    directives: [ROUTER_DIRECTIVES]
})

export class ListCategoryComponent implements OnInit{

    public categories:Category[];
    public title:string = 'Manage Translation Categories';

    constructor(private _categoryService: CategoryService){

    }

    ngOnInit():any {
     
    }

    

}