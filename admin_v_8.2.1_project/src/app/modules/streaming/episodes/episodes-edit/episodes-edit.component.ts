import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { StreamingEpisodesService } from '../../service/streaming-episodes.service';

@Component({
  selector: 'app-episodes-edit',
  templateUrl: './episodes-edit.component.html',
  styleUrls: ['./episodes-edit.component.scss']
})
export class EpisodesEditComponent implements OnInit {

  @Input() EPISODE:any;
  @Output() EpisodeE: EventEmitter<any> = new EventEmitter();

  title:string = ''
  state:any = 1;
  description:any = null;
  IMAGEN_FILE:any;
  IMAGEN_PREVISUALIZA:any;

  VIDEO_CONTENIDO:any;
  loading_video:boolean = false;
  link_vimeo_contenido:any = null;
  constructor(
    public modal: NgbActiveModal,
    public toastr: ToastrService,
    public episodeService: StreamingEpisodesService,
    public sanitizer: DomSanitizer,
  ) { }

  ngOnInit(): void {
    this.title = this.EPISODE.title;
    this.state = this.EPISODE.state;
    this.description = this.EPISODE.description;
    this.IMAGEN_PREVISUALIZA = this.EPISODE.imagen;
    this.link_vimeo_contenido = this.EPISODE.vimeo_id;
  }

  close(){
    this.modal.close();
  }

  save(){
  
    if(!this.title || !this.description){
      this.toastr.error("NECESITAS INGRESAR TODOS LOS CAMPOS",'danger');
      return;
    }
    let formData = new FormData();
    if(this.IMAGEN_FILE){
      formData.append("img",this.IMAGEN_FILE);
    }
    formData.append("title",this.title);
    formData.append("description",this.description);
    formData.append("state",this.state);

    this.episodeService.editEpisode(this.EPISODE.id,formData).subscribe((resp:any) => {
      console.log(resp);
      if(resp.message == 403){
        this.toastr.error( resp.message_text , 'danger');
      }else{
        this.EpisodeE.emit(resp.episode);
        this.toastr.success( "EL EPISODIO SE EDITO CORRECTAMENTE" , 'EXITO');
        this.modal.close();
      }
    })
  }

  processFile($event:any){
    if($event.target.files[0].type.indexOf("image") < 0){
      this.toastr.error(  "EL ARCHIVO NO ES UNA IMAGEN",'danger');
      return;
    }
    // 
    this.IMAGEN_FILE = $event.target.files[0];
    let reader = new FileReader();
    reader.readAsDataURL(this.IMAGEN_FILE);
    reader.onloadend = () => this.IMAGEN_PREVISUALIZA = reader.result;
    this.episodeService.isLoadingSubject.next(true);
    setTimeout(() => {
      this.episodeService.isLoadingSubject.next(false);
    }, 50);
  }

  urlGetVideoContenido(){
    return this.sanitizer.bypassSecurityTrustResourceUrl(this.link_vimeo_contenido);
  }

  processFileVideoContenido($event:any) {
    if($event.target.files[0].type.indexOf("video") < 0){
      this.toastr.error(  "EL ARCHIVO NO ES UN VIDEO", 'danger');
      return;
    }
    this.VIDEO_CONTENIDO = $event.target.files[0];
  }

  UploadVideoContenido(){
    if(!this.VIDEO_CONTENIDO){
    this.toastr.error("NO HAS SELECCIONADO NINGUN VIDEO", 'danger');
      return;
    }
    this.loading_video = true;
    let formData = new FormData();
    formData.append("video",this.VIDEO_CONTENIDO);
    this.episodeService.uplooadVideoContenido(this.EPISODE.id,formData).subscribe((resp:any) => {
      console.log(resp);
      this.loading_video = false;
      this.link_vimeo_contenido = null;
      this.episodeService.isLoadingSubject.next(true);
      setTimeout(() => {
        this.episodeService.isLoadingSubject.next(false);
        this.link_vimeo_contenido = resp.vimeo_link;
      }, 50);
    })
  }

}
