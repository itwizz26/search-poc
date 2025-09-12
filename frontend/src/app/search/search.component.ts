import { Component } from '@angular/core';
import { DocumentService } from '../document.service';

@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.css']
})
export class SearchComponent {
  query: string = '';
  results: any[] = [];
  loading: boolean = false;

  constructor(private documentService: DocumentService) {}

  onSearch() {
    if (!this.query.trim()) {
      this.results = [];
      return;
    }

    this.loading = true;
    this.documentService.searchDocuments(this.query).subscribe({
      next: (docs: any[]) => {
        this.results = docs;
        this.loading = false;
      },
      error: (err: any) => {
        console.error(err);
        this.loading = false;
      }
    });
  }
}