import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { GenresService } from '../service/genres.service';

@Component({
  selector: 'app-genres-edit',
  templateUrl: './genres-edit.component.html',
  styleUrls: ['./genres-edit.component.scss']
})
export class GenresEditComponent implements OnInit {

  @Input() GENRE:any;
  @Output() GenreE: EventEmitter<any> = new EventEmitter();

  title:string = ''
  type:string = '1'
  state:number = 1;

  IMAGEN_PREVIZUALIZACION:any = './assets/media/avatars/21.jpg';
  IMAGEN_FILE:any = null;
  constructor(
    public modal: NgbActiveModal,
    public toastr: ToastrService, 
    public genresService: GenresService,
  ) { }

  ngOnInit(): void {
    this.title = this.GENRE.title;
    this.type = this.GENRE.type;
    this.state = this.GENRE.state;
    this.IMAGEN_PREVIZUALIZACION = this.GENRE.imagen;
  }

  close(){
    this.modal.close();
  }

  save(){
  
    if(!this.title){
      this.toastr.error("NECESITAS INGRESAR TODOS LOS CAMPOS", 'VALIDACIÓN');
      return;
    }
    let formData = new FormData();
    if(this.IMAGEN_FILE){
      formData.append("img",this.IMAGEN_FILE)
    }
    formData.append("title",this.title);
    formData.append("type",this.type);
    formData.append("state",this.state+"");
    this.genresService.editGenre(this.GENRE.id,formData).subscribe((resp:any) => {
      console.log(resp);
      if(resp.message == 403){
        this.toastr.error(resp.message_text, 'VALIDACIÓN');
      }else{
        this.GenreE.emit(resp.genre);
        this.toastr.success("EL GENERO SE EDITO CORRECTAMENTE", 'VALIDACIÓN');
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

