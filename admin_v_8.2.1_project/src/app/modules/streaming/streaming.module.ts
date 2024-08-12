import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { StreamingRoutingModule } from './streaming-routing.module';
import { StreamingComponent } from './streaming.component';
import { StreamingAddComponent } from './streaming-add/streaming-add.component';
import { StreamingEditComponent } from './streaming-edit/streaming-edit.component';
import { StreamingDeleteComponent } from './streaming-delete/streaming-delete.component';
import { StreamingListComponent } from './streaming-list/streaming-list.component';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule, NgbModalModule } from '@ng-bootstrap/ng-bootstrap';
import { InlineSVGModule } from 'ng-inline-svg-2';
import { SeasonsComponent } from './seasons/seasons/seasons.component';
import { SeasonsEditComponent } from './seasons/seasons-edit/seasons-edit.component';
import { SeasonsDeleteComponent } from './seasons/seasons-delete/seasons-delete.component';
import { EpisodesComponent } from './episodes/episodes/episodes.component';
import { EpisodesEditComponent } from './episodes/episodes-edit/episodes-edit.component';
import { EpisodesDeleteComponent } from './episodes/episodes-delete/episodes-delete.component';


@NgModule({
  declarations: [
    StreamingComponent,
    StreamingAddComponent,
    StreamingEditComponent,
    StreamingDeleteComponent,
    StreamingListComponent,
    SeasonsComponent,
    SeasonsEditComponent,
    SeasonsDeleteComponent,
    EpisodesComponent,
    EpisodesEditComponent,
    EpisodesDeleteComponent
  ],
  imports: [
    CommonModule,
    StreamingRoutingModule,

    HttpClientModule,
    FormsModule,
    NgbModule,
    ReactiveFormsModule,
    InlineSVGModule,
    NgbModalModule,
  ]
})
export class StreamingModule { }
