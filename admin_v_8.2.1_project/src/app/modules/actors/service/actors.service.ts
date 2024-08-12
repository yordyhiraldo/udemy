import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject, finalize } from 'rxjs';
import { URL_SERVICIOS } from 'src/app/config/config';
import { AuthService } from '../../auth';

@Injectable({
  providedIn: 'root'
})
export class ActorsService {

  isLoading$: Observable<boolean>;
  isLoadingSubject: BehaviorSubject<boolean>;
  
  constructor(
    private http: HttpClient,
    public authservice: AuthService,
  ) {
    this.isLoadingSubject = new BehaviorSubject<boolean>(false);
    this.isLoading$ = this.isLoadingSubject.asObservable();
  }

  listActors(search:any=null,state:any=null){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let LINK="?t=";
    if(search){
      LINK += "&search="+search;
    }
    if(state){
      LINK += "&state="+state;
    }
    let URL = URL_SERVICIOS + "/actors"+LINK;
    return this.http.get(URL,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

  registerActor(data:any){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/actors";
    return this.http.post(URL,data,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

  editActor(actor_id:any,data:any){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/actors/"+actor_id;
    return this.http.post(URL,data,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

  deleteActor(actor_id:any){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/actors/"+actor_id;
    return this.http.delete(URL,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }
  
}

