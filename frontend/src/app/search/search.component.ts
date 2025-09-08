import { Component } from '@angular/core';
import { ApiService } from '../services/api.service';

@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.css']
})
export class SearchComponent {
  query = '';
  results: any[] = [];
  metrics: { time?: number; count?: number } = {};

  constructor(private api: ApiService) {}

  doSearch() {
    if (!this.query.trim()) return;
    const start = performance.now();
    this.api.search(this.query).subscribe((res: any) => {
      this.results = res.results;
      const end = performance.now();
      this.metrics = { time: end - start, count: res.results.length };
    });
  }

  highlight(content: string): string {
    const regex = new RegExp(`(${this.query})`, 'gi');
    return content.replace(regex, '<mark>$1</mark>');
  }
}
