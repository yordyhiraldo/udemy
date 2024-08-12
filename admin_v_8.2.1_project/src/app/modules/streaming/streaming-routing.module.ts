import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { StreamingComponent } from './streaming.component';
import { StreamingListComponent } from './streaming-list/streaming-list.component';
import { StreamingAddComponent } from './streaming-add/streaming-add.component';
import { StreamingEditComponent } from './streaming-edit/streaming-edit.component';
import { SeasonsComponent } from './seasons/seasons/seasons.component';
import { EpisodesComponent } from './episodes/episodes/episodes.component';

const routes: Routes = [
  {
    path: '',
    component: StreamingComponent,
    children: [
      {
        path: 'lista',
        component: StreamingListComponent
      },
      {
        path: 'registro',
        component: StreamingAddComponent
      },
      {
        path: 'lista/editar/:id',
        component: StreamingEditComponent
      },
       //SEASONS Y EPISODES
      {
        path: 'lista/seasons/:id',
        component: SeasonsComponent
      },
      {
        path: 'lista/episodes/:id',
        component: EpisodesComponent
      }
   ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class StreamingRoutingModule { }
