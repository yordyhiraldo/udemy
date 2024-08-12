import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject, finalize } from 'rxjs';
import { AuthService } from '../../auth';
import { URL_SERVICIOS } from 'src/app/config/config';

@Injectable({
  providedIn: 'root'
})
export class ProductPaypalService {

  isLoading$: Observable<boolean>;
  isLoadingSubject: BehaviorSubject<boolean>;
  
  constructor(
    private http: HttpClient,
    public authservice: AuthService,
  ) {
    this.isLoadingSubject = new BehaviorSubject<boolean>(false);
    this.isLoading$ = this.isLoadingSubject.asObservable();
  }

  listProducts(search:any=null,state:any=null){
    this.isLoadingSubject.next(true)
    let Headers =new HttpHeaders({"authorization": "Bearer "+this.authservice.token});
    let LINK="?t=";
    if(search){
      LINK +="&search="+search;
    }
    if(state){
      LINK +="&state="+state;
    }
    let URL = URL_SERVICIOS + "/products"+LINK;
    return this.http.get(URL,{headers: Headers}).pipe(
      finalize(()=> this.isLoadingSubject.next(false))
    )

  }

  registerProduct(data:any){
    this.isLoadingSubject.next(true)
    let Headers =new HttpHeaders({"authorization": "Bearer "+this.authservice.token});
    let URL = URL_SERVICIOS + "/products";
    return this.http.post(URL,data,{headers: Headers}).pipe(
      finalize(()=> this.isLoadingSubject.next(false))
    )
  }

 editProduct(product_id:any,data:any){
   this.isLoadingSubject.next(true)
   let Headers =new HttpHeaders({"authorization": "Bearer "+this.authservice.token});
   let URL = URL_SERVICIOS + "/products/"+product_id;
   return this.http.put(URL,data,{headers: Headers}).pipe(
    finalize(()=> this.isLoadingSubject.next(false))
  )
  }
}
