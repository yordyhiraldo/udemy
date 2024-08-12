import { Injectable } from '@angular/core';
import {
  CanActivate,
  ActivatedRouteSnapshot,
  RouterStateSnapshot,
  Router,
} from '@angular/router';
import { AuthService } from './auth.service';

@Injectable({ providedIn: 'root' })
export class AuthGuard implements CanActivate {
  constructor(private authService: AuthService, private router: Router) {}

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
    // const currentUser = this.authService.currentUserValue;
    // if (currentUser) {
    //   // logged in so return true
    //   return true;
    // }

    // // not logged in so redirect to login page with the return url
    // this.authService.logout();
    // return false;
    if(!this.authService.currentUserValue){
      this.router.navigateByUrl("/auth/login");
      return false;
    }
    let token = localStorage.getItem("token");
    if(!token){
      this.router.navigateByUrl("/auth/login");
      return false;
    }
    let expiration = (JSON.parse(atob(token.split(".")[1]))).exp;
    if(Math.floor((new Date).getTime()/1000) >= expiration){
      this.authService.logout();
      return false;
    }
  return true;
  }
}