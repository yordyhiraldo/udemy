import { Component, OnInit } from '@angular/core';
import { GenresService } from '../service/genres.service';
import { GenresAddComponent } from '../genres-add/genres-add.component';
import { GenresEditComponent } from '../genres-edit/genres-edit.component';
import { GenresDeleteComponent } from '../genres-delete/genres-delete.component';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-genres-list',
  templateUrl: './genres-list.component.html',
  styleUrls: ['./genres-list.component.scss']
})
export class GenresListComponent implements OnInit {

  search:any = null;
  state:any = null;

  GENRES:any = [];

  isLoading:any;
  constructor(
    public modalService: NgbModal,
    public genresService: GenresService,
  ) { }

  ngOnInit(): void {
    this.listGenres();
    this.isLoading = this.genresService.isLoading$;
  }

  getType(type:any){
    let value = "";
    type = parseInt(type);
    switch (type) {
      case 1:
        value = "MOVIE";
        break;
      case 2:
        value = "TV SHOW";
        break;
      case 3:
        value = "VIDEO";
        break;
      default:
        break;
    }
    return value;
  }
  listGenres(){
    this.genresService.listGenres(this.search,this.state).subscribe((resp:any) => {
      console.log(resp);
      this.GENRES = resp.genres;
    })
  }

  registerGenre(){
    const modalRef = this.modalService.open(GenresAddComponent,{centered: true, size: 'md'});

    modalRef.componentInstance.GenreC.subscribe((Genre:any) => {
      this.GENRES.unshift(Genre);
    });
  }

  editGenre(GENRE:any){
    const modalRef = this.modalService.open(GenresEditComponent,{centered: true, size: 'md'});
    modalRef.componentInstance.GENRE = GENRE;

    modalRef.componentInstance.GenreE.subscribe((Genre:any) => {
      let index = this.GENRES.findIndex((item:any) => item.id == Genre.id);
      if(index != -1){
        this.GENRES[index] = Genre;
      }
    });
  }

  deleteGenre(GENRE:any){
    const modalRef = this.modalService.open(GenresDeleteComponent,{centered: true, size: 'md'});
    modalRef.componentInstance.GENRE = GENRE;

    modalRef.componentInstance.GenreD.subscribe((Genre:any) => {
      let index = this.GENRES.findIndex((item:any) => item.id == GENRE.id);
      if(index != -1){
        this.GENRES.splice(index,1);
      }
    });
  }

}
