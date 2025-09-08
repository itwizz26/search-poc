CREATE TABLE IF NOT EXISTS documents (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    filename TEXT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO documents (title, filename, content)
VALUES (
  'Sample Research Document',
  'example.txt',
  'This is a preloaded research document included with the proof of concept. You can search for words like research, document, or sample to see it in the results.'
);
