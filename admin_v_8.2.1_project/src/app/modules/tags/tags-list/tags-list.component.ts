import { Component, OnInit } from '@angular/core';
import { TagsService } from '../tags/service/tags.service';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { TagsAddComponent } from '../tags-add/tags-add.component';
import { TagsEditComponent } from '../tags-edit/tags-edit.component';
import { TagsDeleteComponent } from '../tags-delete/tags-delete.component';

@Component({
  selector: 'app-tags-list',
  templateUrl: './tags-list.component.html',
  styleUrls: ['./tags-list.component.scss']
})
export class TagsListComponent implements OnInit {

  search:any = null;
  state:any = null;

  TAGS:any = [];

  isLoading:any;
  constructor(
    public modalService: NgbModal,
    public tagsService: TagsService,
  ) { }

  ngOnInit(): void {
    this.listTags();
    this.isLoading = this.tagsService.isLoading$;
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
  listTags(){
    this.tagsService.listTags(this.search,this.state).subscribe((resp:any) => {
      console.log(resp);
      this.TAGS = resp.tags;
    })
  }

  registerTags(){
    const modalRef = this.modalService.open(TagsAddComponent,{centered: true, size: 'md'});

    modalRef.componentInstance.TagsC.subscribe((Tags:any) => {
      this.TAGS.unshift(Tags);
    });
  }

  editTags(TAGS:any){
    const modalRef = this.modalService.open(TagsEditComponent,{centered: true, size: 'md'});
    modalRef.componentInstance.TAGS = TAGS;

    modalRef.componentInstance.TagsE.subscribe((Tags:any) => {
      let index = this.TAGS.findIndex((item:any) => item.id == Tags.id);
      if(index != -1){
        this.TAGS[index] = Tags;
      }
    });
  }

  deleteTags(TAGS:any){
    const modalRef = this.modalService.open(TagsDeleteComponent,{centered: true, size: 'md'});
    modalRef.componentInstance.TAGS = TAGS;

    modalRef.componentInstance.TagsD.subscribe((Tags:any) => {
      let index = this.TAGS.findIndex((item:any) => item.id == TAGS.id);
      if(index != -1){
        this.TAGS.splice(index,1);
      }
    });
  }

}
