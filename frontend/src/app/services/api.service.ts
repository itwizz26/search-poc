import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class ApiService {
  private baseUrl = 'http://localhost:8080';

  constructor(private http: HttpClient) {}

  uploadDocument(file: File): Observable<any> {
    const formData = new FormData();
    formData.append('file', file);
    return this.http.post(`${this.baseUrl}/documents`, formData);
  }

  listDocuments(): Observable<any> {
    return this.http.get(`${this.baseUrl}/documents`);
  }

  deleteDocument(id: number): Observable<any> {
    return this.http.delete(`${this.baseUrl}/documents/${id}`);
  }

  search(query: string): Observable<any> {
    return this.http.get(`${this.baseUrl}/search?q=${encodeURIComponent(query)}`);
  }
}
