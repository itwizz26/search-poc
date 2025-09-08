import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { DocumentsComponent } from './documents/documents.component';
import { SearchComponent } from './search/search.component';

const routes: Routes = [
  { path: '', redirectTo: 'docs', pathMatch: 'full' },
  { path: 'docs', component: DocumentsComponent },
  { path: 'search', component: SearchComponent },
  { path: '**', redirectTo: 'docs' } // fallback for unknown routes
];

@NgModule({
  imports: [RouterModule.forRoot(routes, { useHash: false })],
  exports: [RouterModule]
})
export class AppRoutingModule {}