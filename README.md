# Document Search System (POC)

This is a lightweight document search system built as a proof-of-concept.  
It allows users to:
- Upload documents
- Index their content
- Perform fast searches with result highlighting

The project consists of:
- **Backend**: PHP (Slim 4, SQLite)
- **Frontend**: Angular 18 (TypeScript, Angular Material)

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 22+ and npm
- Angular CLI (`npm install -g @angular/cli`)

---

### 1. Backend Setup

```bash
cd backend
composer install
php -S localhost:8080 -t public
