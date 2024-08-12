import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { TagsService } from '../tags/service/tags.service';

@Component({
  selector: 'app-tags-delete',
  templateUrl: './tags-delete.component.html',
  styleUrls: ['./tags-delete.component.scss']
})
export class TagsDeleteComponent implements OnInit {

  @Input() TAGS:any;
  @Output() TagsD: EventEmitter<any> = new EventEmitter();
  
  constructor(
    public modal: NgbActiveModal,
    public toastr: ToastrService, 
    public tagsService: TagsService,
  ) { }

  ngOnInit(): void {
  }

  delete(){
    this.tagsService.deleteTag(this.TAGS.id).subscribe((resp:any) => {
      this.TagsD.emit("");
      this.toastr.success("LA ETIQUETA SE REGISTRO CORRECTAMENTE", 'VALIDACIÓN');
      this.close();
    })
  }

  close(){
    this.modal.close();
  }

}