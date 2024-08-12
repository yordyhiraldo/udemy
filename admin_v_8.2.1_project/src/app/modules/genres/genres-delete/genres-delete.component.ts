import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { GenresService } from '../service/genres.service';

@Component({
  selector: 'app-genres-delete',
  templateUrl: './genres-delete.component.html',
  styleUrls: ['./genres-delete.component.scss']
})
export class GenresDeleteComponent implements OnInit {

  @Input() GENRE:any;
  @Output() GenreD: EventEmitter<any> = new EventEmitter();
  
  constructor(
    public modal: NgbActiveModal,
    public toastr: ToastrService, 
    public genresService: GenresService,
  ) { }

  ngOnInit(): void {
  }

  delete(){
    this.genresService.deleteGenre(this.GENRE.id).subscribe((resp:any) => {
      this.GenreD.emit("");
      this.toastr.success("EL GENERO SE ELIMINO CORRECTAMENTE", 'VALIDACIÓN');
      this.close();
    })
  }

  close(){
    this.modal.close();
  }
}
