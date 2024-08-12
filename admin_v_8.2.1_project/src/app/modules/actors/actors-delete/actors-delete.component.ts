import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { ActorsService } from '../service/actors.service';

@Component({
  selector: 'app-actors-delete',
  templateUrl: './actors-delete.component.html',
  styleUrls: ['./actors-delete.component.scss']
})
export class ActorsDeleteComponent implements OnInit {

  @Input() ACTOR:any;
  @Output() ActorD: EventEmitter<any> = new EventEmitter();
  
  constructor(
    public modal: NgbActiveModal,
    public toastr: ToastrService, 
    public actorsService: ActorsService,
  ) { }

  ngOnInit(): void {
  }

  delete(){
    this.actorsService.deleteActor(this.ACTOR.id).subscribe((resp:any) => {
      this.ActorD.emit("");
      this.toastr.success("EL ACTOR SE ELIMINO CORRECTAMENTE", 'VALIDACIÓN');
      this.close();
    })
  }

  close(){
    this.modal.close();
  }

}
