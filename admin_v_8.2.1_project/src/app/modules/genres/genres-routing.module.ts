import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { GenresComponent } from './genres.component';
import { GenresListComponent } from './genres-list/genres-list.component';

const routes: Routes = [
  {
    path: "",
    component: GenresComponent,
    children: [
      {
        path:"lista",
        component: GenresListComponent
      }
    ],
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class GenresRoutingModule { }
