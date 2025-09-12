import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface Document {
  id: number;
  title: string;
  filename: string;
  content: string;
}

@Injectable({ providedIn: 'root' })
export class DocumentService {
  private apiUrl = 'http://localhost:8080/api';

  constructor(private http: HttpClient) {}

  getDocuments(): Observable<Document[]> {
    return this.http.get<Document[]>(`${this.apiUrl}/documents`);
  }

  uploadDocument(file: File): Observable<any> {
    const formData = new FormData();
    formData.append('file', file);
    return this.http.post(`${this.apiUrl}/upload`, formData);
  }

  deleteDocument(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/documents/${id}`);
  }

  /** 🔍 Search documents by query string */
  searchDocuments(query: string): Observable<Document[]> {
    const params = new HttpParams().set('q', query);
    return this.http.get<Document[]>(`${this.apiUrl}/search`, { params });
  }
}