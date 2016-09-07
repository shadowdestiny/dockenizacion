import {Category} from "./category";

export class Translation{
    constructor(
        public id:number,
        public translationKey:string,
        public translationCategory:number,
        public category:Category,
        public description:string,
        public translationDetail:[]
    ){}
}