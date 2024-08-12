import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { StreamingEpisodesService } from '../../service/streaming-episodes.service';
import { EpisodesEditComponent } from '../episodes-edit/episodes-edit.component';
import { EpisodesDeleteComponent } from '../episodes-delete/episodes-delete.component';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-episodes',
  templateUrl: './episodes.component.html',
  styleUrls: ['./episodes.component.scss']
})
export class EpisodesComponent  implements OnInit {

  EPISODES:any = [];
  title:any = null;
  description:any = null;

  IMAGEN_FILE:any;
  IMAGEN_PREVISUALIZA:any;

  isLoading:any;

  streaming_season_id:any;
  constructor(
    public streamingEpisodes: StreamingEpisodesService,
    public activedRouter: ActivatedRoute,
    public toastr: ToastrService, 
    public modalService: NgbModal,
    public router: Router,
  ) { }

  ngOnInit(): void {
    this.isLoading = this.streamingEpisodes.isLoading$;
    this.activedRouter.params.subscribe((resp:any) => {
      this.streaming_season_id = resp.id;
    })
    this.listEpisodes();
  }

  listEpisodes(){
    this.streamingEpisodes.listEpisodes(this.streaming_season_id).subscribe((resp:any) => {
      console.log(resp);
      this.EPISODES = resp.episodes;
    })
  }

  save(){
    if(!this.IMAGEN_FILE || !this.title || !this.description){
      this.toastr.error('NECESITAS INGRESAR TODOS LOS CAMPOS', "ERROR");
      return;
    }
    let formData = new FormData();
    formData.append("img",this.IMAGEN_FILE);
    formData.append("title",this.title);
    formData.append("description",this.description);
    formData.append("streaming_season_id",this.streaming_season_id);
    this.streamingEpisodes.registerEpisode(formData).subscribe((resp:any) => {
      console.log(resp);
      if(resp.message == 403){
        this.toastr.error( resp.message_text, "ERROR");
      }else{
        this.toastr.success( "EL EPISODIO SE REGISTRO CORRECTAMENTE" , "EXITO");
        this.EPISODES.push(resp.episode);
        this.IMAGEN_FILE = null;
        this.title = null;
        this.description = null;
        this.IMAGEN_PREVISUALIZA = null;
        
      }
    });
  }

  processFile($event:any){
    if($event.target.files[0].type.indexOf("image") < 0){
      this.toastr.error( "EL ARCHIVO NO ES UNA IMAGEN", "ERROR");
      return;
    }
    // 
    this.IMAGEN_FILE = $event.target.files[0];
    let reader = new FileReader();
    reader.readAsDataURL(this.IMAGEN_FILE);
    reader.onloadend = () => this.IMAGEN_PREVISUALIZA = reader.result;
    this.streamingEpisodes.isLoadingSubject.next(true);
    setTimeout(() => {
      this.streamingEpisodes.isLoadingSubject.next(false);
    }, 50);
  }

  editEpisode(EPISODE:any){
     const modalref = this.modalService.open(EpisodesEditComponent,{centered:true, size: 'md'});
    modalref.componentInstance.EPISODE = EPISODE;

    // 
    modalref.componentInstance.EpisodeE.subscribe((editSeason:any) => {
      let INDEX = this.EPISODES.findIndex((item:any) => item.id == editSeason.id);
      if(INDEX != -1){
        this.EPISODES[INDEX] = editSeason;
      }
    })
  }
  deleteEpisode(EPISODE:any){
    const modalref = this.modalService.open(EpisodesDeleteComponent,{centered:true, size: 'md'});
    modalref.componentInstance.EPISODE = EPISODE;

    // 
    modalref.componentInstance.EpisodeD.subscribe((editEpisode:any) => {
      let INDEX = this.EPISODES.findIndex((item:any) => item.id == EPISODE.id);
      if(INDEX != -1){
        this.EPISODES.splice(INDEX,1);
      }
    })
  }
}

