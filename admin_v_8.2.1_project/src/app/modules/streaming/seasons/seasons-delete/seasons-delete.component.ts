import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import modal from 'bootstrap/js/dist/modal';
import { ToastrService } from 'ngx-toastr';
import { StreamingSeasonsService } from '../../service/streaming-seasons.service';

@Component({
  selector: 'app-seasons-delete',
  templateUrl: './seasons-delete.component.html',
  styleUrls: ['./seasons-delete.component.scss']
})
export class SeasonsDeleteComponent implements OnInit {

  @Input() SEASON:any;
  @Output() SeasonD: EventEmitter<any> = new EventEmitter();
  
  constructor(
    public modal: NgbActiveModal,
    public toastr: ToastrService,
    public streamingSeason: StreamingSeasonsService,
  ) { }

  ngOnInit(): void {
  }

  delete(){
    this.streamingSeason.deleteSeason(this.SEASON.id).subscribe((resp:any) => {
      this.SeasonD.emit("");
      this.toastr.success("EL SEASON SE ELIMINO CORRECTAMENTE", 'EXITO');
      this.close();
    })
  }

  close(){
    this.modal.close();
  }

}