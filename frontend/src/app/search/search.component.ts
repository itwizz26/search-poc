import { Component } from '@angular/core';
import { DocumentService, Document } from '../document.service';

@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.css']
})
export class SearchComponent {
  query = '';
  results: Document[] = [];
  loading = false;

  constructor(private documentService: DocumentService) {}

  onSearch() {
    if (!this.query.trim()) {
      this.results = [];
      return;
    }

    this.loading = true;
    this.documentService.searchDocuments(this.query).subscribe({
      next: (docs) => {
        this.results = docs;
        this.loading = false;
      },
      error: (err) => {
        console.error(err);
        this.loading = false;
      }
    });
  }
}