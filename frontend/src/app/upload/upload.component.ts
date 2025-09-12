import { Component, OnInit } from '@angular/core';
import { DocumentService } from '../document.service';

@Component({
  selector: 'app-upload',
  templateUrl: './upload.component.html',
  styleUrls: ['./upload.component.css']
})
export class UploadComponent implements OnInit {
  selectedFile: File | null = null;
  documents: any[] = [];
  message: string = '';
  loading = false;

  constructor(private documentService: DocumentService) {}

  ngOnInit(): void {
    this.loadDocuments();
  }

  onFileSelected(event: any): void {
    this.selectedFile = event.target.files[0] || null;
  }

  onUpload(): void {
    if (!this.selectedFile) return;

    this.loading = true;
    this.documentService.uploadDocument(this.selectedFile).subscribe({
      next: () => {
        this.message = 'Upload successful!';
        this.selectedFile = null;
        this.loadDocuments(); // reload list
        this.loading = false;
      },
      error: () => {
        this.message = 'Upload failed!';
        this.loading = false;
      }
    });
  }

  loadDocuments(): void {
    this.documentService.getDocuments().subscribe({
      next: (docs) => this.documents = docs,
      error: (err) => console.error('Failed to load documents:', err)
    });
  }

  deleteDocument(id: number): void {
    this.documentService.deleteDocument(id).subscribe({
      next: () => {
        this.message = 'Document deleted!';
        this.loadDocuments(); // reload after delete
      },
      error: () => this.message = 'Delete failed!'
    });
  }
}