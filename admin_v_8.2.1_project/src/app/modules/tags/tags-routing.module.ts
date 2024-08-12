import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { TagsComponent } from './tags.component';
import { TagsListComponent } from './tags-list/tags-list.component';

const routes: Routes = [
  {
    path: '',
    component: TagsComponent,
    children: [
      {
        path: 'lista',
        component: TagsListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TagsRoutingModule { }
