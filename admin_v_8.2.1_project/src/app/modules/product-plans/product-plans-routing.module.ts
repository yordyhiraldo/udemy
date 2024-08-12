import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ProductPlansComponent } from './product-plans.component';
import { ProductListComponent } from './product/product-list/product-list.component';
import { PlanesListComponent } from './planes/planes-list/planes-list.component';

const routes: Routes = [{
  path: "",
 component: ProductPlansComponent,
 children: [
  {
    path: "lista-de-productos",
    component: ProductListComponent,
  },
  {
    path: "lista-de-planes",
    component: PlanesListComponent
  }
 ]
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ProductPlansRoutingModule { }
