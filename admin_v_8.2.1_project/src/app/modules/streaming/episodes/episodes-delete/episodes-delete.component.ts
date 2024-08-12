import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { StreamingEpisodesService } from '../../service/streaming-episodes.service';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-episodes-delete',
  templateUrl: './episodes-delete.component.html',
  styleUrls: ['./episodes-delete.component.scss']
})
export class EpisodesDeleteComponent implements OnInit {

  @Input() EPISODE:any;
  @Output() EpisodeD: EventEmitter<any> = new EventEmitter();
  
  constructor(
    public modal: NgbActiveModal,
    public toastr: ToastrService, 
    public streamepisodeSeason: StreamingEpisodesService,
  ) { }

  ngOnInit(): void {
  }

  delete(){
    this.streamepisodeSeason.deleteEpisode(this.EPISODE.id).subscribe((resp:any) => {
      this.EpisodeD.emit("");
      this.toastr.success("EL EPISODIO SE ELIMINO CORRECTAMENTE", 'EXITO');
      this.close();
    })
  }

  close(){
    this.modal.close();
  }

}
