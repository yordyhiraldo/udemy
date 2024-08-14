import { Component, OnInit } from '@angular/core';
import { PlanesPaypalService } from '../../service/planes-paypal.service';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ProductPaypalService } from '../../service/product-paypal.service';
import { PlanesAddComponent } from '../planes-add/planes-add.component';
import { PlanesEditComponent } from '../planes-edit/planes-edit.component';

@Component({
  selector: 'app-planes-list',
  templateUrl: './planes-list.component.html',
  styleUrls: ['./planes-list.component.scss']
})
export class PlanesListComponent implements OnInit{
  search:any = null;
  PRODUCTS:any = [];
  PLANES:any = [];

  isLoading:any;
  constructor(
    public modalService: NgbModal,
    public productPaypalService: ProductPaypalService,
    public planesPaypalService: PlanesPaypalService,
  ) { }

  ngOnInit(): void {
    this.isLoading = this.planesPaypalService.isLoading$;
    this.listProducts();
    this.listPlanes();
  }

  listPlanes(){
    this.planesPaypalService.listPlanes(this.search).subscribe((resp:any) => {
      console.log(resp);
      this.PLANES = resp.plans;
    })
  }
  
  listProducts(){
    this.productPaypalService.listProducts().subscribe((resp:any) => {
      console.log(resp);
      this.PRODUCTS = resp.products;
    })
  }

  registerPlane(){
    const modalRef = this.modalService.open(PlanesAddComponent,{centered: true, size: 'md'});
    modalRef.componentInstance.PRODUCTS = this.PRODUCTS;

    modalRef.componentInstance.PlanC.subscribe((Plane:any) => {
      this.PLANES.unshift(Plane);
    });
  }

  editPlane(PLANE:any){
    const modalRef = this.modalService.open(PlanesEditComponent,{centered: true, size: 'md'});
    modalRef.componentInstance.plane_selected = PLANE;
    modalRef.componentInstance.PRODUCTS = this.PRODUCTS;
    
    modalRef.componentInstance.PlanE.subscribe((Plane:any) => {
      let index = this.PLANES.findIndex((item:any) => item.id == Plane.id);
      if(index != -1){
        this.PLANES[index] = Plane;
      }
    });
  }

}
