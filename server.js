// File: server.js
const express = require('express');
const bodyParser = require('body-parser');
const sqlite3 = require('sqlite3').verbose();
const app = express();
const port = 3000;

app.use(bodyParser.json());
app.use(express.static('public'));

// Kết nối đến database
let db = new sqlite3.Database('./alarm.db', sqlite3.OPEN_READWRITE | sqlite3.OPEN_CREATE, (err) => {
    if (err) {
        console.error(err.message);
    }
    console.log('Kết nối thành công đến database.');
});

// Tạo bảng nếu chưa có
db.run(`CREATE TABLE IF NOT EXISTS timers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    time TEXT NOT NULL,
    song TEXT NOT NULL,
    repeat_days TEXT NOT NULL
)`);

// API lấy tất cả báo thức
app.get('/get-all-timers', (req, res) => {
    db.all(`SELECT * FROM timers ORDER BY time ASC`, [], (err, rows) => {
        if (err) {
            return res.status(500).json({ message: "Lỗi lấy dữ liệu." });
        }
        res.json(rows);
    });
});

// API thêm báo thức
app.post('/set-timer', (req, res) => {
    const { time, song, repeat_days } = req.body;
    db.run(`INSERT INTO timers(time, song, repeat_days) VALUES(?, ?, ?)`, [time, song, JSON.stringify(repeat_days)], function (err) {
        if (err) {
            return res.status(500).json({ message: "Lỗi lưu thời gian và nhạc." });
        }
        res.status(200).json({ message: "Lưu thành công!" });
    });
});

// API cập nhật báo thức
app.put('/update-timer/:id', (req, res) => {
    const { time, song, repeat_days } = req.body;
    const { id } = req.params;
    db.run(`UPDATE timers SET time = ?, song = ?, repeat_days = ? WHERE id = ?`, [time, song, JSON.stringify(repeat_days), id], function (err) {
        if (err) {
            return res.status(500).json({ message: "Lỗi cập nhật báo thức." });
        }
        res.status(200).json({ message: "Cập nhật thành công!" });
    });
});

// API xóa báo thức
app.delete('/delete-timer/:id', (req, res) => {
    const { id } = req.params;
    db.run(`DELETE FROM timers WHERE id = ?`, [id], function (err) {
        if (err) {
            return res.status(500).json({ message: "Lỗi xóa báo thức." });
        }
        res.status(200).json({ message: "Xóa thành công!" });
    });
});

// API lấy báo thức gần nhất
app.get('/get-timer', (req, res) => {
    db.all(`SELECT * FROM timers ORDER BY time ASC`, [], (err, rows) => {
        if (err) {
            return res.status(500).json({ message: "Lỗi lấy dữ liệu." });
        }

        const today = new Date();
        const todayDay = today.toLocaleDateString('en-US', { weekday: 'short' });

        // Lọc các báo thức phù hợp với ngày hôm nay
        const validAlarms = rows.filter(timer => {
            const repeatDays = JSON.parse(timer.repeat_days);
            return repeatDays.includes(todayDay);
        });

        if (validAlarms.length > 0) {
            res.json(validAlarms[0]);
        } else {
            res.status(404).json({ message: "Không có báo thức nào trong ngày hôm nay." });
        }
    });
});

// Chạy server
app.listen(port, () => {
    console.log(`Ứng dụng chạy trên http://localhost:${port}`);
});
