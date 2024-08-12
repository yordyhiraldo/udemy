import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { StreamingSeasonsService } from '../../service/streaming-seasons.service';

@Component({
  selector: 'app-seasons-edit',
  templateUrl: './seasons-edit.component.html',
  styleUrls: ['./seasons-edit.component.scss']
})
export class SeasonsEditComponent implements OnInit {

  @Input() SEASON:any;
  @Output() SeasonE: EventEmitter<any> = new EventEmitter();

  title:string = ''
  state:any = 1;
  constructor(
    public modal: NgbActiveModal,
    public toastr: ToastrService, 
    public seasonService: StreamingSeasonsService,
  ) { }

  ngOnInit(): void {
    this.title = this.SEASON.title;
    this.state = this.SEASON.state;
  }

  close(){
    this.modal.close();
  }

  save(){
  
    if(!this.title){
      this.toastr.error("NECESITAS INGRESAR TODOS LOS CAMPOS",'danger');
      return;
    }
    let data = {
      title: this.title,
      state: this.state,
    }
    this.seasonService.editSeason(this.SEASON.id,data).subscribe((resp:any) => {
      console.log(resp);
      if(resp.message == 403){
        this.toastr.error(resp.message_text ,'danger');
      }else{
        this.SeasonE.emit(resp.season);
         this.toastr.success("EL SEASON SE EDITO CORRECTAMENTE" , 'primary');
        this.modal.close();
      }
    })
  }

}
