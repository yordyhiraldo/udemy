import { Component, EventEmitter, Input, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { ProductPaypalService } from '../../service/product-paypal.service';

@Component({
  selector: 'app-product-edit',
  templateUrl: './product-edit.component.html',
  styleUrls: ['./product-edit.component.scss']
})
export class ProductEditComponent {

  @Input() product_selected:any;
  @Output() ProductE: EventEmitter<any> = new EventEmitter();
    
  name:string ="";
  type:string ="";
  category:string ="";
  description:string ="";

  constructor(
  public modal: NgbActiveModal,
  public toastr: ToastrService,
    public productPaypalService: ProductPaypalService,
 )   { }

 ngOnInit(): void {
  this.name = this.product_selected.name;
  this.type = this.product_selected.type;
  this.category = this.product_selected.category;
  this.description = this.product_selected.description;
 }

 close(){ 
 this.modal.close();
 }

 save(){
  if (!this.name ||!this.type ||!this.category ||!this.description ){
    this.toastr.error("NECESITAS COMPLETAR TODOS LOS CAMPOS", "ERROR");
    return;
  }

  let data = {
    name: this.name,
    type: this.type,
    category: this.category,
    description: this.description, 
  }

     this.productPaypalService.editProduct(this.product_selected.id,data).subscribe((resp:any) => {
      console.log(resp);
      this.ProductE.emit(resp.product);
      this.toastr.success("EL PRODUCTO SE EDITO EXITOSAMENTE" ,"EXITO");
      this.modal.close();
    })
  }

} 

