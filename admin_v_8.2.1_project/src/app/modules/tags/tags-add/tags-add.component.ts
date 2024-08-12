import { Component, EventEmitter, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { TagsService } from '../tags/service/tags.service';

@Component({
  selector: 'app-tags-add',
  templateUrl: './tags-add.component.html',
  styleUrls: ['./tags-add.component.scss']
})
export class TagsAddComponent implements OnInit {

  @Output() TagsC: EventEmitter<any> = new EventEmitter();

  title:string = ''
  type:string = '1'

  constructor(
    public modal: NgbActiveModal,
    public toastr: ToastrService, 
    public tagsService: TagsService,
  ) { }

  ngOnInit(): void {
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
      state: 1,
    }
    this.tagsService.registerTag(data).subscribe((resp:any) => {
      console.log(resp);
      if(resp.message == 403){
        this.toastr.error(resp.message_text, 'VALIDACIÓN');
      }else{
        this.TagsC.emit(resp.tag);
        this.toastr.success("LA ETIQUETA SE REGISTRO CORRECTAMENTE", 'VALIDACIÓN');
        this.modal.close();
      }
    })
  }

}
