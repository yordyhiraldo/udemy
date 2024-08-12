import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ProductPaypalService } from '../../service/product-paypal.service';
import { ProductAddComponent } from '../product-add/product-add.component';
import { ProductEditComponent } from '../product-edit/product-edit.component';

@Component({
  selector: 'app-product-list',
  templateUrl: './product-list.component.html',
  styleUrls: ['./product-list.component.scss']
})
export class ProductListComponent implements OnInit {
  search:any = null;
  PRODUCTS:any = [];

  isLoading:any;
  constructor(
    public modalService: NgbModal,
    public productPaypalService: ProductPaypalService,
  ) { }

  ngOnInit(): void {
    this.isLoading = this.productPaypalService.isLoading$;
    this.listProducts();
  }


 
  listProducts(){
    this.productPaypalService.listProducts(this.search).subscribe((resp:any) =>{
      console.log(resp);
      this.PRODUCTS = resp.products;
    }); 
  }

  registerProduct(){
    const modalRef = this.modalService.open(ProductAddComponent,{centered: true, size:"md"});

    modalRef.componentInstance.ProductC.subscribe((product:any) => {
      this.PRODUCTS.unshift(product);

    });
  }

  editProduct(PRODUCT: any){
    const modalRef = this.modalService.open(ProductEditComponent,{centered: true, size: 'md'});
    modalRef.componentInstance.product_selected = PRODUCT;

    modalRef.componentInstance.ProductE.subscribe((Product:any) => {
      let index = this.PRODUCTS.findIndex((item:any) => item.id == Product.id);
      if(index != -1){
      this. PRODUCTS[index] = Product;
      }
    });
  }
}
