import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { UsersRoutingModule } from './users-routing.module';
import { UsersComponent } from './users.component';
import { AddUsersComponent } from './add-users/add-users.component';
import { EditUsersComponent } from './edit-users/edit-users.component';
import { DeleteUsersComponent } from './delete-users/delete-users.component';
import { ListUsersComponent } from './list-users/list-users.component';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModalModule, NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { InlineSVGModule } from 'ng-inline-svg-2';


@NgModule({
  declarations: [
    UsersComponent,
    AddUsersComponent,
    EditUsersComponent,
    DeleteUsersComponent,
    ListUsersComponent
  ],
  imports: [
    CommonModule,
    UsersRoutingModule,
    
    HttpClientModule,
    FormsModule,
    NgbModule,
    ReactiveFormsModule,
    InlineSVGModule,
    NgbModalModule, 
  ]
})
export class UsersModule { }
