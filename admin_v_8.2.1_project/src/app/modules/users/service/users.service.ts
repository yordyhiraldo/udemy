import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject, finalize } from 'rxjs';
import { AuthService } from '../../auth';
import { URL_SERVICIOS } from 'src/app/config/config';

@Injectable({
  providedIn: 'root'
})
export class UsersService {

  isLoading$: Observable<boolean>;
  isLoadingSubject: BehaviorSubject<boolean>;
  
  constructor(
    private http: HttpClient,
    public authservice: AuthService,
  ) {
    this.isLoadingSubject = new BehaviorSubject<boolean>(false);
    this.isLoading$ = this.isLoadingSubject.asObservable();
  }

  listUsers(search:any=null,state:any=null){
    this.isLoadingSubject.next(true)
    let Headers =new HttpHeaders({"authorization": "bearer "+this.authservice.token});
    let LINK="?t=";
    if(search){
      LINK +="&search="+search;
    }
    if(state){
      LINK +="&state="+state;
    }
    let URL = URL_SERVICIOS + "/users"+LINK;
    return this.http.get(URL,{headers: Headers}).pipe(
      finalize(()=> this.isLoadingSubject.next(false))
    )

  }

  registerUser(data:any){
    this.isLoadingSubject.next(true)
    let Headers =new HttpHeaders({"authorization": "bearer "+this.authservice.token});
    let URL = URL_SERVICIOS + "/users";
    return this.http.post(URL,data,{headers: Headers}).pipe(
      finalize(()=> this.isLoadingSubject.next(false))
    )
  }

 editUser(user_id:any,data:any){
   this.isLoadingSubject.next(true)
   let Headers =new HttpHeaders({"authorization": "bearer "+this.authservice.token});
   let URL = URL_SERVICIOS + "/users/"+user_id;
   return this.http.post(URL,data,{headers: Headers}).pipe(
    finalize(()=> this.isLoadingSubject.next(false))
  )
  }

  deleteUser(user_id:any){
    this.isLoadingSubject.next(true)
    let Headers =new HttpHeaders({"authorization": "bearer "+this.authservice.token});
    let URL = URL_SERVICIOS + "/users/"+user_id;
    return this.http.delete(URL,{headers: Headers}).pipe(
     finalize(()=> this.isLoadingSubject.next(false))
   )
  }

}
