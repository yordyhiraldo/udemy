import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { UsersService } from '../service/users.service';

@Component({
  selector: 'app-delete-users',
  templateUrl: './delete-users.component.html',
  styleUrls: ['./delete-users.component.scss']
})
export class DeleteUsersComponent implements OnInit {

  @Input() USER:any;
  @Output() UserD: EventEmitter<any> = new EventEmitter();

  constructor(
    public modal: NgbActiveModal,
    private toastr: ToastrService,
    public userService: UsersService,
  ) { }

  ngOnInit(): void {
  }
  delete(){
    this.userService.deleteUser(this.USER.id).subscribe((resp:any) => {
      this.UserD.emit("");
      this.toastr.success("EL USUARIO SE ELMINO CORRECTAMENTE" ,"EXITO");
      this.close();
    })
  }
  close(){ 
    this.modal.close();
   }
}
