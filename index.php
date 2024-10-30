<?php
// Kết nối đến MySQL thông qua file config
require 'config/config.php';

// Xử lý các request từ frontend
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

switch ($method) {
    case 'GET':
        if ($request[0] == 'get-all-timers') {
            // Lấy tất cả báo thức
            $stmt = $pdo->query("SELECT * FROM timers ORDER BY time ASC");
            $timers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($timers);
        } elseif ($request[0] == 'get-timer') {
            // Lấy báo thức phù hợp với ngày hiện tại
            $stmt = $pdo->query("SELECT * FROM timers ORDER BY time ASC");
            $timers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $todayDay = date('D'); // Lấy ngày hiện tại (Mon, Tue,...)
            $validAlarms = array_filter($timers, function($timer) use ($todayDay) {
                $repeatDays = json_decode($timer['repeat_days']);
                return in_array($todayDay, $repeatDays);
            });

            if (!empty($validAlarms)) {
                header('Content-Type: application/json');
                echo json_encode(array_values($validAlarms)[0]); // Trả về báo thức sớm nhất
            } else {
                header('Content-Type: application/json', true, 404);
                echo json_encode(["message" => "Không có báo thức nào trong ngày hôm nay."]);
            }
        }
        break;

    case 'POST':
        if ($request[0] == 'set-timer') {
            // Thêm báo thức mới
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO timers (time, song, repeat_days) VALUES (?, ?, ?)");
            $stmt->execute([$data['time'], $data['song'], json_encode($data['repeat_days'])]);
            echo json_encode(["message" => "Lưu thành công!"]);
        }
        break;

    case 'PUT':
        if ($request[0] == 'update-timer' && isset($request[1])) {
            // Cập nhật báo thức
            $id = $request[1];
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("UPDATE timers SET time = ?, song = ?, repeat_days = ? WHERE id = ?");
            $stmt->execute([$data['time'], $data['song'], json_encode($data['repeat_days']), $id]);
            echo json_encode(["message" => "Cập nhật thành công!"]);
        }
        break;

    case 'DELETE':
        if ($request[0] == 'delete-timer' && isset($request[1])) {
            // Xóa báo thức
            $id = $request[1];
            $stmt = $pdo->prepare("DELETE FROM timers WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(["message" => "Xóa thành công!"]);
        }
        break;

    default:
        header('HTTP/1.1 405 Method Not Allowed');
        break;
}
?>
