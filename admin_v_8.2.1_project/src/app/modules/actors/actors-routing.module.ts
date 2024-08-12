import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ActorsListComponent } from './actors-list/actors-list.component';
import { ActorsComponent } from './actors.component';

const routes: Routes = [{
  path: '',
  component: ActorsComponent,
  children: [
    {
      path: 'lista',
      component: ActorsListComponent
    }
  ]
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ActorsRoutingModule { }
