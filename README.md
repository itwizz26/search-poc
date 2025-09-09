# Document Search POC

A lightweight document search system built with:

- **Backend:** PHP (Slim 4, SQLite, PDO, PSR-4 structure)
- **Frontend:** Angular 18 (TypeScript, Angular Material-ready, responsive design)

The system allows uploading research documents, indexing their content, and providing fast search capabilities with result highlighting.

---

## Features

### Backend (PHP Slim + SQLite)

- Upload documents (stores title + content in SQLite database)
- Auto-creates schema if not present
- Preloads a sample document into the database
- REST API endpoints for:
  - Listing documents
  - Searching documents with highlighted matches
  - Deleting documents
- PSR-4 project structure with dependency injection

### Frontend (Angular 18 + TypeScript)

- **Document Management**
  - Upload documents (drag & drop support optional)
  - Paginated document list
  - Delete documents with confirmation
- **Search**
  - Real-time search suggestions (debounced input)
  - Highlight matching terms in results
  - Sort results by relevance or date
  - Show search performance metrics (time taken, result count)
- **UI/UX**
  - Responsive layout
  - Angular Material-ready design
  - Loading and error states

---

## Setup Instructions

### Backend

1. Navigate to the backend

```
cd /search-poc/backend/
```

2. Install dependencies

```
composer install
```

3. Start the API

```
php -S localhost:8080 -t public
```

4. The API is now available here

```
* htpp://localhost:8080/api/documents
* htpp://localhost:8080/api/seach?q=keyword
```

A SQLite db is automaticlly created and seeded with a sample document.

---

### Frontend

1. Navigate to frontend

```
cd ~/search-poc/frontend
```

2. Install dependencies (clean first)

```
rm -rf node_modules package-lock.json
npm install
```

3. Start Angular dev server

```
npm start
```

4. Open in browser

```
http://localhost:4200/
http://localhost:4200/docs
http://localhost:4200/search
```
---

## API Endpoints

```
GET /api/documents // List Documents
GET /api/search?q=term // Search Documents
DELETE /api/documents/{id} // Delete Document
```

---

## Notes

Proxy config (proxy.conf.json) ensures frontend /api requests map to backend at http://localhost:8080/api.

SQLite migration: The backend automatically creates the documents table and inserts a sample record if empty.

Error handling: Both backend and frontend return structured error messages with proper HTTP codes.

Testing: Tested in a PHP 8.1+, Node.js v22–24 and npm v11+ environment.

---

## Running with Docker (optional)

From the project root:

```
docker-compose up --build
```

This will spin up both backend (PHP Slim with SQLite) and frontend (Angular dev server).

---

## License

MIT License &copy; 2025
