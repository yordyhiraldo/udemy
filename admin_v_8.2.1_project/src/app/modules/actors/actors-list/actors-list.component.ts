import { Component, OnInit } from '@angular/core';
import { ActorsService } from '../service/actors.service';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ActorsAddComponent } from '../actors-add/actors-add.component';
import { ActorsDeleteComponent } from '../actors-delete/actors-delete.component';
import { ActorsEditComponent } from '../actors-edit/actors-edit.component';

@Component({
  selector: 'app-actors-list',
  templateUrl: './actors-list.component.html',
  styleUrls: ['./actors-list.component.scss']
})
export class ActorsListComponent implements OnInit {

  search:any = null;
  state:any = null;

  ACTORS:any = [];

  isLoading:any;
  constructor(
    public modalService: NgbModal,
    public actorsService: ActorsService,
  ) { }

  ngOnInit(): void {
    this.listActors();
    this.isLoading = this.actorsService.isLoading$;
  }

  getType(type:any){
    let value = "";
    type = parseInt(type);
    switch (type) {
      case 1:
        value = "DIRECTOR";
        break;
      case 2:
        value = "ACTOR";
        break;
      default:
        break;
    }
    return value;
  }
  listActors(){
    this.actorsService.listActors(this.search,this.state).subscribe((resp:any) => {
      console.log(resp);
      this.ACTORS = resp.actors;
    })
  }

  registerActor(){
    const modalRef = this.modalService.open(ActorsAddComponent,{centered: true, size: 'md'});

    modalRef.componentInstance.ActorC.subscribe((Actor:any) => {
      this.ACTORS.unshift(Actor);
    });
  }
// 
  editActor(ACTOR:any){
    const modalRef = this.modalService.open(ActorsEditComponent,{centered: true, size: 'md'});
    modalRef.componentInstance.ACTOR = ACTOR;

    modalRef.componentInstance.ActorE.subscribe((Actor:any) => {
      let index = this.ACTORS.findIndex((item:any) => item.id == Actor.id);
      if(index != -1){
        this.ACTORS[index] = Actor;
      }
    });
  }

  deleteActor(ACTOR:any){
    const modalRef = this.modalService.open(ActorsDeleteComponent,{centered: true, size: 'md'});
    modalRef.componentInstance.ACTOR = ACTOR;

    modalRef.componentInstance.ActorD.subscribe((Actor:any) => {
      let index = this.ACTORS.findIndex((item:any) => item.id == ACTOR.id);
      if(index != -1){
        this.ACTORS.splice(index,1);
      }
    });
  }

}