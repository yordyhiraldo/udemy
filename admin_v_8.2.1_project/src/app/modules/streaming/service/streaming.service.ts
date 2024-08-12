import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject, finalize } from 'rxjs';
import { AuthService } from '../../auth';
import { URL_SERVICIOS } from 'src/app/config/config';

@Injectable({
  providedIn: 'root'
})
export class StreamingService {
 
  isLoading$: Observable<boolean>;
  isLoadingSubject: BehaviorSubject<boolean>;
  
  constructor(
    private http: HttpClient,
    public authservice: AuthService,
  ) {
    this.isLoadingSubject = new BehaviorSubject<boolean>(false);
    this.isLoading$ = this.isLoadingSubject.asObservable();
  }
  
  listStreaming(search:any=null,state:any=null){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let LINK="?t=";
    if(search){
      LINK += "&search="+search;
    }
    if(state){
      LINK += "&state="+state;
    }
    let URL = URL_SERVICIOS + "/streaming"+LINK;
    return this.http.get(URL,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

  showStreaming(STREAMING_ID:string){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/streaming/"+STREAMING_ID;
    return this.http.get(URL,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

  configAll(){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/streaming/config_all";
    return this.http.get(URL,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

  registerStreaming(data:any){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/streaming";
    return this.http.post(URL,data,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

  editStreaming(streaming_id:any,data:any){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/streaming/"+streaming_id;
    return this.http.post(URL,data,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

  uplooadVideoTrailer(streaming_id:any,data:any){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/streaming/upload_video/"+streaming_id;
    return this.http.post(URL,data,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

  uplooadVideoContenido(streaming_id:any,data:any){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/streaming/upload_video_contenido/"+streaming_id;
    return this.http.post(URL,data,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

  deleteStreaming(streaming_id:any){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/streaming/"+streaming_id;
    return this.http.delete(URL,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

}

