import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { GenresRoutingModule } from './genres-routing.module';
import { GenresComponent } from './genres.component';
import { GenresListComponent } from './genres-list/genres-list.component';
import { GenresAddComponent } from './genres-add/genres-add.component';
import { GenresEditComponent } from './genres-edit/genres-edit.component';
import { GenresDeleteComponent } from './genres-delete/genres-delete.component';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule, NgbModalModule } from '@ng-bootstrap/ng-bootstrap';
import { InlineSVGModule } from 'ng-inline-svg-2';


@NgModule({
  declarations: [
    GenresComponent,
    GenresListComponent,
    GenresAddComponent,
    GenresEditComponent,
    GenresDeleteComponent
  ],
  imports: [
    CommonModule,
    GenresRoutingModule,

    HttpClientModule,
    FormsModule,
    NgbModule,
    ReactiveFormsModule,
    InlineSVGModule,
    NgbModalModule,
  ]
})
export class GenresModule { }
