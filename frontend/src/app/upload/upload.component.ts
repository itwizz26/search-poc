import { Component } from '@angular/core';
import { DocumentService } from '../document.service';

@Component({
  selector: 'app-upload',
  templateUrl: './upload.component.html',
  styleUrls: ['./upload.component.css']
})
export class UploadComponent {
  uploading = false;
  message = '';

  constructor(private documentService: DocumentService) {}

  onFileSelected(event: any) {
    const file = event.target.files[0];
    if (!file) return;

    this.uploading = true;
    this.message = '';

    this.documentService.uploadDocument(file).subscribe({
      next: () => {
        this.message = '✅ Upload successful!';
        this.uploading = false;
      },
      error: (err) => {
        console.error(err);
        this.message = '❌ Upload failed';
        this.uploading = false;
      }
    });
  }
}