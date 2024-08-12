import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { TagsService } from '../tags/service/tags.service';

@Component({
  selector: 'app-tags-edit',
  templateUrl: './tags-edit.component.html',
  styleUrls: ['./tags-edit.component.scss']
})
export class TagsEditComponent implements OnInit {

  @Input() TAGS:any;
  @Output() TagsE: EventEmitter<any> = new EventEmitter();

  title:string = ''
  type:string = '1'
  state:any = 1;
  constructor(
    public modal: NgbActiveModal,
    public toastr: ToastrService, 
    public tagsService: TagsService,
  ) { }

  ngOnInit(): void {
    this.title = this.TAGS.title;
    this.type = this.TAGS.type;
    this.state = this.TAGS.state;
  }

  close(){
    this.modal.close();
  }

  save(){
  
    if(!this.title){
      this.toastr.error("NECESITAS INGRESAR TODOS LOS CAMPOS", 'VALIDACIÓN');
      return;
    }
    let data = {
      title: this.title,
      type: this.type,
      state: this.state,
    }
    this.tagsService.editTag(this.TAGS.id,data).subscribe((resp:any) => {
      console.log(resp);
      if(resp.message == 403){
        this.toastr.error(resp.message_text, 'VALIDACIÓN');
      }else{
        this.TagsE.emit(resp.tag);
        this.toastr.success("LA ETIQUETA SE REGISTRO CORRECTAMENTE", 'VALIDACIÓN');
        this.modal.close();
      }
    })
  }

}