import { Component, EventEmitter, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { Toast, ToastRef, ToastrModule, ToastrService } from 'ngx-toastr';
import { UserService } from 'src/app/_fake/services/user-service';
import { UsersService } from '../service/users.service';



@Component({
  selector: 'app-add-users',
  templateUrl: './add-users.component.html',
  styleUrls: ['./add-users.component.scss']
})
export class AddUsersComponent implements OnInit{

   
    @Output() UserC: EventEmitter<any> = new EventEmitter();
    
    name:string ="";
    surname:string ="";
    email:string ="";
    password:string ="";
    repit_password:string ="";
    role_id:number = 1;

    IMAGEN_PREVIZUALIZACION:any = "assets/media/avatars/300-6.jpg";
    IMAGEN_FILE:any = null;


  constructor(
    public modal: NgbActiveModal,
    private toastr: ToastrService,
    public userService: UsersService,
  ) { }

  ngOnInit(): void {
  }

  close(){ 
   this.modal.close();
  }

  save(){ 

    if (!this.IMAGEN_FILE || !this.name || !this.surname || !this.email || !this.password || !this.role_id) {
      this.toastr.error("NECESITAS INGRESAR TODOS LOS CAMPOS", "VALIDACIÓN");
      return;
    } 
    if (this.password !== this.repit_password) {
      this.toastr.error("LAS CONTRASEÑAS NO SON IGUALES", "VALIDACIÓN");
      return;
    }

    
    
    let formData = new FormData();
    formData.append("img",this.IMAGEN_FILE)
    formData.append("name",this.name)
    formData.append("surname",this.surname)
    formData.append("email",this.email)
    formData.append("password",this.password)
    formData.append("role_id",this.role_id+"")
    formData.append("state",1+"");
    this.userService.registerUser(formData).subscribe((resp:any) => {  
      console.log(resp);
      if(resp.message == 403){
        this.toastr.error(resp.message_text ,"VALIDACIÓN");
      }else{
        this.UserC.emit(resp.user);
        this.toastr.success("EL USUARIO SE REGISTRO CORRECTAMENTE" ,"EXITO");
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
