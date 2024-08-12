import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ProductPlansRoutingModule } from './product-plans-routing.module';
import { ProductPlansComponent } from './product-plans.component';
import { ProductListComponent } from './product/product-list/product-list.component';
import { ProductAddComponent } from './product/product-add/product-add.component';
import { ProductEditComponent } from './product/product-edit/product-edit.component';
import { PlanesListComponent } from './planes/planes-list/planes-list.component';
import { PlanesAddComponent } from './planes/planes-add/planes-add.component';
import { PlanesEditComponent } from './planes/planes-edit/planes-edit.component';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule, NgbModalModule } from '@ng-bootstrap/ng-bootstrap';
import { InlineSVGModule } from 'ng-inline-svg-2';


@NgModule({
  declarations: [
    ProductPlansComponent,
    ProductListComponent,
    ProductAddComponent,
    ProductEditComponent,
    PlanesListComponent,
    PlanesAddComponent,
    PlanesEditComponent,
  ],
  imports: [
    CommonModule,
    ProductPlansRoutingModule,

    HttpClientModule,
    FormsModule,
    NgbModule,
    ReactiveFormsModule,
    InlineSVGModule,
    NgbModalModule,
  ]
})
export class ProductPlansModule { }
