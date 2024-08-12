import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AddUsersComponent } from '../add-users/add-users.component';
import { UsersService } from '../service/users.service';
import { EditUsersComponent } from '../edit-users/edit-users.component';
import { DeleteUsersComponent } from '../delete-users/delete-users.component';


@Component({
  selector: 'app-list-users',
  templateUrl: './list-users.component.html',
  styleUrls: ['./list-users.component.scss']
})
export class ListUsersComponent implements OnInit {

  search:any = null;
  state:any = null;

  USERS:any = [];

  isLoading:any;
  constructor(
    public modalService: NgbModal,
    public userService: UsersService,
  ) { }

  ngOnInit(): void {
    this.listUsers();
    this.isLoading = this.userService.isLoading$;
  }

   listUsers(){ 
    this.userService.listUsers(this.search,this.state).subscribe((resp:any) =>{
      console.log(resp);
      this.USERS = resp.users.data;
    }) 
   }

  registerUsers(){ 
    const modalRef = this.modalService.open(AddUsersComponent,{centered: true, size:"md"});

    modalRef.componentInstance.UserC.subscribe((User:any) => {
      this.USERS.unshift(User);

    });
  }

  editUsers(USER:any){
    const modalRef = this.modalService.open(EditUsersComponent,{centered: true, size:"md"});
    modalRef.componentInstance.USER = USER;

    modalRef.componentInstance.UserE.subscribe((User:any) => {
      let index = this.USERS.findIndex((item:any)=> item.id == User.id);
      if (index != -1){
        this.USERS[index] = User;
      }

    });
  }
  deleteUsers(USER:any){
    const modalRef = this.modalService.open(DeleteUsersComponent,{centered: true, size:"md"});
    modalRef.componentInstance.USER = USER;

    modalRef.componentInstance.UserD.subscribe((User:any) => {
      let index = this.USERS.findIndex((item:any)=> item.id == USER.id);
      if (index != -1){
        this.USERS.splice(index,1);
      }

    });
  }
}
