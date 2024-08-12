import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { TagsRoutingModule } from './tags-routing.module';
import { TagsComponent } from './tags.component';
import { TagsAddComponent } from './tags-add/tags-add.component';
import { TagsEditComponent } from './tags-edit/tags-edit.component';
import { TagsDeleteComponent } from './tags-delete/tags-delete.component';
import { TagsListComponent } from './tags-list/tags-list.component';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule, NgbModalModule } from '@ng-bootstrap/ng-bootstrap';
import { InlineSVGModule } from 'ng-inline-svg-2';


@NgModule({
  declarations: [
    TagsComponent,
    TagsAddComponent,
    TagsEditComponent,
    TagsDeleteComponent,
    TagsListComponent
  ],
  imports: [
    CommonModule,
    TagsRoutingModule,

    HttpClientModule,
    FormsModule,
    NgbModule,
    ReactiveFormsModule,
    InlineSVGModule,
    NgbModalModule,

  ]
})
export class TagsModule { }
