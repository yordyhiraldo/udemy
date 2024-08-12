import { Component, EventEmitter, Input, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { UsersService } from '../service/users.service';

@Component({
  selector: 'app-edit-users',
  templateUrl: './edit-users.component.html',
  styleUrls: ['./edit-users.component.scss']
})
export class EditUsersComponent {

  @Input() USER:any;

  @Output() UserE: EventEmitter<any> = new EventEmitter();
    
  name:string ="";
  surname:string ="";
  email:string ="";
  password:string ="";
  repit_password:string ="";
  role_id:number = 1;
  state:number = 1;


  IMAGEN_PREVIZUALIZACION:any = "assets/media/avatars/300-6.jpg";
  IMAGEN_FILE:any = null;


constructor(
  public modal: NgbActiveModal,
  private toastr: ToastrService,
  public userService: UsersService,
) { }

ngOnInit(): void {
   this.name = this.USER.name;
   this.surname = this.USER.surname;
   this. email = this.USER.email;
   this.IMAGEN_PREVIZUALIZACION = this.USER.avatar ;
   this.state = this.USER.state;
}

close(){ 
 this.modal.close();
}

save(){ 

  if (!this.name || !this.surname || !this.email || !this.role_id) {
    this.toastr.error("NECESITAS INGRESAR TODOS LOS CAMPOS", "VALIDACIÓN");
    return;
  } 
  if (this.password && (this.password !== this.repit_password)) {
    this.toastr.error("LAS CONTRASEÑAS NO SON IGUALES", "VALIDACIÓN");
    return;
  }
  
  let formData = new FormData();
  if (this.IMAGEN_FILE){ 
    formData.append("img",this.IMAGEN_FILE)
  }
  formData.append("name",this.name)
  formData.append("surname",this.surname)
  formData.append("email",this.email)
  if (this.password){ 
    formData.append("password",this.password)
  }
  formData.append("role_id",this.role_id+"")
  formData.append("state",this.state+"");
  this.userService.editUser(this.USER.id,formData).subscribe((resp:any) => {  
    console.log(resp);
    if(resp.message == 403){
      this.toastr.error(resp.message_text ,"VALIDACIÓN");
    }else{
      this.UserE.emit(resp.user);
      this.toastr.success("EL USUARIO SE EDITO CORRECTAMENTE" ,"EXITO");
      this.modal.close();
    }
    
  })
}

processAvatar($event:any){ 
  if($event.target.files[0].type.indexOf("image") < 0){
    this.toastr.error("EL ARCHIVO NO ES UNA IMAGEN", "MENSAJE DE VALIDACIÓN");
    return;
  }
  //
  this.IMAGEN_FILE = $event.target.files[0];
  let reader = new FileReader();
  reader.readAsDataURL(this.IMAGEN_FILE);
  reader.onloadend = () => this.IMAGEN_PREVIZUALIZACION = reader.result;
}

}
