---

## **SOLUTION.md**

```markdown
# Solution Overview

This document explains the design decisions, trade-offs, and architecture of the Document Search POC.

---

## **Backend**

- Built using **Slim 4** PHP microframework.
- **PSR-4 structure** with modular config:
  - `settings.php` for app configuration (DB path, etc.)
  - `dependencies.php` for DI container and services
  - `routes.php` for all HTTP routes
- **SQLite** used as lightweight database
  - Auto-creates table and preloads a sample document
- API endpoints:
  - `GET /api/documents` – list all documents
  - `GET /api/search?q=` – search documents by content
  - `POST /api/documents` – upload document
  - `DELETE /api/documents/{id}` – delete document

**Trade-offs:**
- SQLite is used for simplicity and portability; not suitable for very large datasets.
- Slim chosen for lightweight, minimal setup.

---

## **Frontend**

- Built with **Angular 18** and TypeScript
- Modular structure with lazy-loaded routes for:
  - `DocumentsComponent` – list & delete documents
  - `SearchComponent` – real-time search with highlight
- **Proxy config** (`proxy.conf.json`) routes API calls to backend on port 8080.
- Fully Termux-compatible (Node 22–24, npm 11), with **no native modules**.
- Responsive UI, minimal realistic design, clean code style.

**Trade-offs:**
- Angular chosen for SPA capabilities and easy real-time search UI.
- Zone.js v0.14.x used to match Angular 18 requirements.
- All native modules (lmdb, sqlite3) removed to avoid Termux incompatibility.

---

## **Design Decisions**

1. **PSR-4 backend structure** ensures maintainability and clear separation of concerns.
2. **DI Container** (PHP-DI) used for database and services.
3. **SQLite** allows zero-dependency backend with auto-table creation.
4. **Angular SPA** provides clean UI, with proxy for API calls to simplify development.
5. **Preloaded document** demonstrates search capability immediately after setup.
6. **Frontend package.json** strictly contains only JS dependencies to avoid Termux build issues.

---

## **Interesting Implementations**

- Automatic table creation and sample document insertion in backend DI container.
- Angular search uses `HttpClient` with debounced input to reduce API calls.
- Search results highlight matching text for better UX.
- Proxy setup enables same-origin requests while serving Angular separately from backend.

---

## **Conclusion**

This POC demonstrates a lightweight, maintainable, and cross-platform document search system.  
The architecture allows easy expansion:
- Swap SQLite for PostgreSQL or MySQL
- Add authentication
- Enhance search ranking with scoring algorithms
- Add full document preview & PDF support
