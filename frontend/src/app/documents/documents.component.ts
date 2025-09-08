import { Component, OnInit } from '@angular/core';
import { ApiService } from '../services/api.service';

@Component({
  selector: 'app-documents',
  templateUrl: './documents.component.html',
  styleUrls: ['./documents.component.css']
})
export class DocumentsComponent implements OnInit {
  documents: any[] = [];
  selectedFile?: File;
  loading = false;
  error = '';

  constructor(private api: ApiService) {}

  ngOnInit(): void {
    this.loadDocs();
  }

  loadDocs() {
    this.api.listDocuments().subscribe({
      next: (res) => (this.documents = res),
      error: (err) => (this.error = 'Failed to load documents')
    });
  }

  onFileSelected(event: any) {
    this.selectedFile = event.target.files[0];
  }

  upload() {
    if (!this.selectedFile) return;
    this.loading = true;
    this.api.uploadDocument(this.selectedFile).subscribe({
      next: () => {
        this.loading = false;
        this.selectedFile = undefined;
        this.loadDocs();
      },
      error: () => {
        this.loading = false;
        this.error = 'Upload failed';
      }
    });
  }

  delete(id: number) {
    if (!confirm('Are you sure?')) return;
    this.api.deleteDocument(id).subscribe(() => this.loadDocs());
  }
}
