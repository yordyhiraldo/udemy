import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ActorsRoutingModule } from './actors-routing.module';
import { ActorsComponent } from './actors.component';
import { ActorsAddComponent } from './actors-add/actors-add.component';
import { ActorsEditComponent } from './actors-edit/actors-edit.component';
import { ActorsDeleteComponent } from './actors-delete/actors-delete.component';
import { ActorsListComponent } from './actors-list/actors-list.component';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule, NgbModalModule } from '@ng-bootstrap/ng-bootstrap';
import { InlineSVGModule } from 'ng-inline-svg-2';


@NgModule({
  declarations: [
    ActorsComponent,
    ActorsAddComponent,
    ActorsEditComponent,
    ActorsDeleteComponent,
    ActorsListComponent
  ],
  imports: [
    CommonModule,
    ActorsRoutingModule,

    HttpClientModule,
    FormsModule,
    NgbModule,
    ReactiveFormsModule,
    InlineSVGModule,
    NgbModalModule,
  ]
})
export class ActorsModule { }
