import { Routes } from '@angular/router';

const Routing: Routes = [
  {
    path: 'dashboard',
    loadChildren: () => 
      import('./dashboard/dashboard.module').then((m) => m.DashboardModule),
  },
  {
    path: 'builder',
    loadChildren: () => 
      import('./builder/builder.module').then((m) => m.BuilderModule),
  },
  {
    path: 'crafted/pages/profile',
    loadChildren: () => 
      import('../modules/profile/profile.module').then((m) => m.ProfileModule),
    // data: { layout: 'light-sidebar' },
  },
  {
    path: 'crafted/account',
    loadChildren: () => 
      import('../modules/account/account.module').then((m) => m.AccountModule),
    // data: { layout: 'dark-header' },
  },
  {
    path: 'crafted/pages/wizards',
    loadChildren: () => 
      import('../modules/wizards/wizards.module').then((m) => m.WizardsModule),
    // data: { layout: 'light-header' },
  },
  {
    path: 'crafted/widgets',
    loadChildren: () => 
      import('../modules/widgets-examples/widgets-examples.module').then((m) => m.WidgetsExamplesModule),
    // data: { layout: 'light-header' },
  },
  {
    path: 'apps/chat',
    loadChildren: () => 
      import('../modules/apps/chat/chat.module').then((m) => m.ChatModule),
    // data: { layout: 'light-sidebar' },
  },
  //mis modulos
  {
    path: 'USUARIOS',
    loadChildren: () => 
      import('../modules/users/users.module').then((m) => m.UsersModule),
    // data: { layout: 'light-sidebar' },
  },
  {
    path: 'PLANES-Y-PRODUCTOS',
    loadChildren: () => 
      import('../modules/product-plans/product-plans.module').then((m) => m.ProductPlansModule),
    // data: { layout: 'light-sidebar' },
  },
  {
    path: 'GENEROS',
    loadChildren: () => 
      import('../modules/genres/genres.module').then((m) => m.GenresModule),
    // data: { layout: 'light-sidebar' },
  },
  {
    path: 'actors',
    loadChildren: () => 
      import('../modules/actors/actors.module').then((m) => m.ActorsModule),
    // data: { layout: 'light-sidebar' },
  },
  {
    path: 'tags',
    loadChildren: () => 
      import('../modules/tags/tags.module').then((m) => m.TagsModule),
    // data: { layout: 'light-sidebar' },
  },
  {
    path: 'streamings',
    loadChildren: () => 
      import('../modules/streaming/streaming.module').then((m) => m.StreamingModule),
    // data: { layout: 'light-sidebar' },
  },
  {
    path: 'apps/users',
    loadChildren: () => 
      import('./user/user.module').then((m) => m.UserModule),
  },
  {
    path: 'apps/roles',
    loadChildren: () => 
      import('./role/role.module').then((m) => m.RoleModule),
  },
  {
    path: 'apps/permissions',
    loadChildren: () => 
      import('./permission/permission.module').then((m) => m.PermissionModule),
  },
  {
    path: '',
    redirectTo: '/dashboard',
    pathMatch: 'full',
  },
  {
    path: '**',
    redirectTo: 'error/404',
  },
];

export { Routing };
