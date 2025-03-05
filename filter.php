Flask==2.2.2
-- schema.sql
DROP TABLE IF EXISTS books;

CREATE TABLE books (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    author TEXT,
    year INTEGER,
    isbn TEXT
);
from flask import Flask, render_template, request, redirect, url_for, flash
import sqlite3
import os

app = Flask(__name__)
app.secret_key = 'your_secret_key'  # Thay đổi secret key cho phù hợp

DATABASE = 'library.db'

def get_db_connection():
    conn = sqlite3.connect(DATABASE)
    conn.row_factory = sqlite3.Row
    return conn

def init_db():
    """Khởi tạo database nếu chưa tồn tại."""
    if not os.path.exists(DATABASE):
        conn = get_db_connection()
        with app.open_resource('schema.sql') as f:
            conn.executescript(f.read().decode('utf8'))
        conn.commit()
        conn.close()

@app.route('/')
def index():
    conn = get_db_connection()
    books = conn.execute('SELECT * FROM books').fetchall()
    conn.close()
    return render_template('index.html', books=books)

@app.route('/add', methods=('GET', 'POST'))
def add_book():
    if request.method == 'POST':
        title = request.form['title']
        author = request.form['author']
        year = request.form['year']
        isbn = request.form['isbn']

        if not title:
            flash('Tiêu đề sách là bắt buộc!')
        else:
            conn = get_db_connection()
            conn.execute('INSERT INTO books (title, author, year, isbn) VALUES (?, ?, ?, ?)',
                         (title, author, year, isbn))
            conn.commit()
            conn.close()
            flash('Thêm sách thành công!')
            return redirect(url_for('index'))
    return render_template('add_book.html')

@app.route('/edit/<int:id>', methods=('GET', 'POST'))
def edit_book(id):
    conn = get_db_connection()
    book = conn.execute('SELECT * FROM books WHERE id = ?', (id,)).fetchone()

    if book is None:
        flash('Không tìm thấy sách!')
        return redirect(url_for('index'))
    
    if request.method == 'POST':
        title = request.form['title']
        author = request.form['author']
        year = request.form['year']
        isbn = request.form['isbn']
        
        if not title:
            flash('Tiêu đề sách là bắt buộc!')
        else:
            conn.execute('UPDATE books SET title = ?, author = ?, year = ?, isbn = ? WHERE id = ?',
                         (title, author, year, isbn, id))
            conn.commit()
            conn.close()
            flash('Cập nhật sách thành công!')
            return redirect(url_for('index'))
    
    conn.close()
    return render_template('edit_book.html', book=book)

@app.route('/delete/<int:id>', methods=['POST'])
def delete_book(id):
    conn = get_db_connection()
    conn.execute('DELETE FROM books WHERE id = ?', (id,))
    conn.commit()
    conn.close()
    flash('Xoá sách thành công!')
    return redirect(url_for('index'))

if __name__ == '__main__':
    init_db()  # Khởi tạo database nếu chưa tồn tại
    app.run(debug=True)
    <!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>{% block title %}Quản Lí Thư Viện{% endblock %}</title>
    <link rel="stylesheet" href="{{ url_for('static', filename='css/style.css') }}">
    <!-- Sử dụng Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Thanh điều hướng -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container">
        <a class="navbar-brand" href="{{ url_for('index') }}">Thư Viện</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="{{ url_for('index') }}">Trang Chủ</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url_for('add_book') }}">Thêm Sách</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Nội dung chính -->
    <div class="container mt-4">
        {% with messages = get_flashed_messages() %}
          {% if messages %}
            <div class="alert alert-info alert-dismissible fade show" role="alert">
              {% for message in messages %}
                <div>{{ message }}</div>
              {% endfor %}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          {% endif %}
        {% endwith %}
        {% block content %}{% endblock %}
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start mt-4">
      <div class="text-center p-3">
        © 2025 Thư Viện. All rights reserved.
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
{% extends 'base.html' %}
{% block title %}Trang Chủ - Quản Lí Thư Viện{% endblock %}

{% block content %}
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>Danh Sách Sách</h1>
  <a href="{{ url_for('add_book') }}" class="btn btn-success">Thêm Sách Mới</a>
</div>
<div class="table-responsive">
  <table class="table table-hover table-bordered align-middle">
      <thead class="table-dark">
          <tr>
              <th>ID</th>
              <th>Tiêu Đề</th>
              <th>Tác Giả</th>
              <th>Năm Xuất Bản</th>
              <th>ISBN</th>
              <th>Hành Động</th>
          </tr>
      </thead>
      <tbody>
          {% for book in books %}
          <tr>
              <td>{{ book['id'] }}</td>
              <td>{{ book['title'] }}</td>
              <td>{{ book['author'] }}</td>
              <td>{{ book['year'] }}</td>
              <td>{{ book['isbn'] }}</td>
              <td>
                  <a href="{{ url_for('edit_book', id=book['id']) }}" class="btn btn-sm btn-warning me-1">Sửa</a>
                  <form action="{{ url_for('delete_book', id=book['id']) }}" method="post" class="d-inline">
                      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xoá?')">Xoá</button>
                  </form>
              </td>
          </tr>
          {% else %}
          <tr>
              <td colspan="6" class="text-center">Chưa có sách nào.</td>
          </tr>
          {% endfor %}
      </tbody>
  </table>
</div>
{% endblock %}
{% extends 'base.html' %}
{% block title %}Thêm Sách - Quản Lí Thư Viện{% endblock %}

{% block content %}
<div class="card">
  <div class="card-header bg-success text-white">
    <h2 class="mb-0">Thêm Sách Mới</h2>
  </div>
  <div class="card-body">
    <form method="post">
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu Đề</label>
            <input type="text" name="title" class="form-control" id="title" required>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Tác Giả</label>
            <input type="text" name="author" class="form-control" id="author">
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Năm Xuất Bản</label>
            <input type="number" name="year" class="form-control" id="year">
        </div>
        <div class="mb-3">
            <label for="isbn" class="form-label">ISBN</label>
            <input type="text" name="isbn" class="form-control" id="isbn">
        </div>
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-success">Thêm Sách</button>
          <a href="{{ url_for('index') }}" class="btn btn-secondary">Huỷ</a>
        </div>
    </form>
  </div>
</div>
{% endblock %}
{% extends 'base.html' %}
{% block title %}Chỉnh Sửa Sách - Quản Lí Thư Viện{% endblock %}

{% block content %}
<div class="card">
  <div class="card-header bg-warning text-white">
    <h2 class="mb-0">Chỉnh Sửa Sách</h2>
  </div>
  <div class="card-body">
    <form method="post">
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu Đề</label>
            <input type="text" name="title" class="form-control" id="title" value="{{ book['title'] }}" required>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Tác Giả</label>
            <input type="text" name="author" class="form-control" id="author" value="{{ book['author'] }}">
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Năm Xuất Bản</label>
            <input type="number" name="year" class="form-control" id="year" value="{{ book['year'] }}">
        </div>
        <div class="mb-3">
            <label for="isbn" class="form-label">ISBN</label>
            <input type="text" name="isbn" class="form-control" id="isbn" value="{{ book['isbn'] }}">
        </div>
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-warning">Cập Nhật</button>
          <a href="{{ url_for('index') }}" class="btn btn-secondary">Huỷ</a>
        </div>
    </form>
  </div>
</div>
{% endblock %}
