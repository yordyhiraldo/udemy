import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject, finalize } from 'rxjs';
import { URL_SERVICIOS } from 'src/app/config/config';
import { AuthService } from '../../auth';

@Injectable({
  providedIn: 'root'
})
export class StreamingEpisodesService {

  isLoading$: Observable<boolean>;
  isLoadingSubject: BehaviorSubject<boolean>;
  
  constructor(
    private http: HttpClient,
    public authservice: AuthService,
  ) {
    this.isLoadingSubject = new BehaviorSubject<boolean>(false);
    this.isLoading$ = this.isLoadingSubject.asObservable();
  }
  
  listEpisodes(streaming_season_id:any){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/streaming_episode?streaming_season_id="+streaming_season_id;
    return this.http.get(URL,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

  registerEpisode(data:any){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/streaming_episode";
    return this.http.post(URL,data,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

  editEpisode(episode_id:any,data:any){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/streaming_episode/"+episode_id;
    return this.http.post(URL,data,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

  deleteEpisode(episode_id:any){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/streaming_episode/"+episode_id;
    return this.http.delete(URL,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }

  uplooadVideoContenido(episode_id:any,data:any){
    this.isLoadingSubject.next(true)
    let Headers = new HttpHeaders({'Authorization': 'Bearer '+this.authservice.token});
    let URL = URL_SERVICIOS + "/streaming_episode/upload_video/"+episode_id;
    return this.http.post(URL,data,{headers: Headers}).pipe(
      finalize(() => this.isLoadingSubject.next(false))
    )
  }
}
