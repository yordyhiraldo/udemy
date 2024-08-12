import { Component, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { StreamingService } from '../service/streaming.service';

@Component({
  selector: 'app-streaming-add',
  templateUrl: './streaming-add.component.html',
  styleUrls: ['./streaming-add.component.scss']
})
export class StreamingAddComponent implements OnInit {

  title:any = '';
  subtitle:any = '';
  description:any = '';

  IMAGEN_FILE:any;
  IMAGEN_PREVISUALIZA:any;

  genre_id:any =1;
  tags_selected:any = [];
  actors_selected:any = [];
  type:any = 1;


  TAGS:any = [];
  GENRES:any = [];
  TAGS_BACKUPS:any = [];
  GENRES_BACKUPS:any = [];
  ACTORS:any = [];

  isLoading:any;

  // 
  selected_tag:any;
  selected_actor:any;
  constructor(
    public streamingService: StreamingService,
    public toastr: ToastrService, 
  ) { }

  ngOnInit(): void {
    this.isLoading = this.streamingService.isLoading$;
    this.streamingService.configAll().subscribe((resp:any) => {
      console.log(resp);
      this.TAGS = resp.tags;
      this.GENRES = resp.genres;
      this.ACTORS = resp.actors;

      this.TAGS_BACKUPS = this.TAGS.filter((item:any) => item.type == this.type);
      this.GENRES_BACKUPS = this.GENRES.filter((item:any) => item.type == this.type);
      
    })
  }

  processFile($event:any){
    if($event.target.files[0].type.indexOf("image") < 0){
      this.toastr.error("EL ARCHIVO NO ES UNA IMAGEN", "ERROR");
      return;
    }
    // 
    this.IMAGEN_FILE = $event.target.files[0];
    let reader = new FileReader();
    reader.readAsDataURL(this.IMAGEN_FILE);
    reader.onloadend = () => this.IMAGEN_PREVISUALIZA = reader.result;
    this.streamingService.isLoadingSubject.next(true);
    setTimeout(() => {
      this.streamingService.isLoadingSubject.next(false);
    }, 50);
  }

  addTags(){

    if(this.selected_tag){
      let INDEX = this.tags_selected.findIndex((item:any) => item.id == this.selected_tag);
  
      if(INDEX != -1){
         this.toastr.error('EL TAG SELECCIONADO YA EXISTE EN LA LISTA',"ERROR");
        return;
      }else{
        let TAG_S = this.TAGS.find((item:any) => item.id == this.selected_tag);
        this.tags_selected.unshift(TAG_S);
        this.selected_tag = null;
      }
    }else{
      this.toastr.error('NECESITAS SELECCIONAR UN TAG',"ERROR");
      return;
    }
  }

  addActors(){

    if(this.selected_actor){
      let INDEX = this.actors_selected.findIndex((item:any) => item.id == this.selected_actor);
  
      if(INDEX != -1){
         this.toastr.error('EL ACTOR SELECCIONADO YA EXISTE EN LA LISTA',"ERROR");
        return;
      }else{
        let actor_S = this.ACTORS.find((item:any) => item.id == this.selected_actor);
        this.actors_selected.unshift(actor_S);
        this.selected_actor = null;
      }
    }else{
       this.toastr.error('NECESITAS SELECCIONAR UN ACTOR',"ERROR");
      return;
    }
  }

  selectedType(){
    console.log(this.type);
    this.TAGS_BACKUPS = this.TAGS.filter((item:any) => item.type == this.type);
    this.GENRES_BACKUPS = this.GENRES.filter((item:any) => item.type == this.type);
  }

  save(){

    if(!this.title || !this.description || !this.genre_id || this.tags_selected.length == 0 || this.actors_selected.length == 0 ||
      !this.subtitle || !this.IMAGEN_FILE){
        this.toastr.error('EL OBLIGATORIO LLENAR TODOS LOS CAMPOS',"ERROR");
        return;
    }

    let formData = new FormData();
    formData.append("title",this.title);
    formData.append("description",this.description);
    formData.append("genre_id",this.genre_id);
    formData.append("subtitle",this.subtitle);
    formData.append("img",this.IMAGEN_FILE);
    formData.append("type",this.type);
    // *
    let TAGSt:any = [];
    this.tags_selected.forEach((tg:any) => {
      TAGSt.push(tg.title);
    });
    // ["SUSPESO","TERROR","COMEDIA"] -> "SUSPESO","TERROR","COMEDIA"
    formData.append("tags",TAGSt);
    formData.append("actors_selected",JSON.stringify(this.actors_selected));
    // *

    this.streamingService.registerStreaming(formData).subscribe((resp:any) => {
      console.log(resp);
      if(resp.message == 403){
        this.toastr.error(resp.message_text,"ERROR");
        return;
      }else{
        this.toastr.success("EL STREAMING SE REGISTRO CORRECTAMENTE","EXITO");
        this.title = null;
        this.description = null;
        this.genre_id = null;
        this.subtitle = null;
        this.IMAGEN_FILE = null;
        this.IMAGEN_PREVISUALIZA = null;
        this.type = 1;
        this.tags_selected = [];
        this.actors_selected = [];
      }
    })
  } 

  deleteTag(i:any){
    this.tags_selected.splice(i,1);
  }
  
  deleteActor(actors_selec:any){
    let INDEX_ACTOR_SELECTED = this.actors_selected.findIndex((item:any) => item.id == actors_selec.id);
    if(INDEX_ACTOR_SELECTED != -1){
      this.actors_selected.splice(INDEX_ACTOR_SELECTED,1);
    }
  }
}