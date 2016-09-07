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

    public id;
    public category:Category;
    public title:string = 'Edit Category';
    public status:boolean = false;
    
    constructor(private _categoryService:CategoryService,
                private _routeParams: RouteParams)
    {
        this.category = new Category(0,'','','');
    }

    ngOnInit():any {
        this.getCategoryById();
    }

    getCategoryById()
    {
        this.id = this._routeParams.get("id");
        this._categoryService.getCategory(this.id)
                                .subscribe(
                                    result => {
                                        this.category = result.translation_category;
                                    },
                                    error => {

                                    }
                                );
    }

    onSubmit()
    {
        this._categoryService.updateCategory(this.category)
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