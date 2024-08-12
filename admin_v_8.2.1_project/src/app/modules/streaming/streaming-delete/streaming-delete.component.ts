import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { StreamingService } from '../service/streaming.service';

@Component({
  selector: 'app-streaming-delete',
  templateUrl: './streaming-delete.component.html',
  styleUrls: ['./streaming-delete.component.scss']
})
export class StreamingDeleteComponent implements OnInit {

  @Input() STREAMING:any;
  @Output() StreamingD: EventEmitter<any> = new EventEmitter();
  
  constructor(
    public modal: NgbActiveModal,
    public toastr: ToastrService,
    public streamingService: StreamingService,
  ) { }

  ngOnInit(): void {
  }

  delete(){
    this.streamingService.deleteStreaming(this.STREAMING.id).subscribe((resp:any) => {
      this.StreamingD.emit("");
      this.toastr.success( "EL STREAMING SE ELIMINO CORRECTAMENTE", 'EXITO');
      this.close();
    })
  }

  close(){
    this.modal.close();
  }

}

