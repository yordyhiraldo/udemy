import { Component, EventEmitter, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { ProductPaypalService } from '../../service/product-paypal.service';


@Component({
  selector: 'app-product-add',
  templateUrl: './product-add.component.html',
  styleUrls: ['./product-add.component.scss']
  })
  export class ProductAddComponent implements OnInit{

  
  @Output() ProductC: EventEmitter<any> = new EventEmitter();
    
  name:string ="";
  type:string ="";
  category:string ="";
  description:string ="";

  constructor(
  public modal: NgbActiveModal,
  private toastr: ToastrService,
    public productPaypalService: ProductPaypalService,
 )   { }

 ngOnInit(): void {
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

     this.productPaypalService.registerProduct(data).subscribe((resp:any) => {
      console.log(resp);
      this.ProductC.emit(resp.product);
      this.toastr.success("SE HA CREADO UN PRODUCTO EXITOSAMENTE" ,"EXITO");
      this.modal.close();
    })
  }

} 
