<?php
// エラーメッセージを表示する
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 必要なヘッダーを設定して、レスポンスを JSON として返す
header('Content-Type: application/json');

// データベース接続（例: MySQL）
$host = 'localhost';
$user = 'root'; // 実際のデータベースユーザー名
$password = ''; // 実際のデータベースパスワード
$database = 'health';

$conn = new mysqli($host, $user, $password, $database);

// 接続チェック
if ($conn->connect_error) {
    die(json_encode(['error' => 'データベース接続エラー: ' . $conn->connect_error]));
}

// データを全て取得する SQL クエリ（昇順に変更）
$sql = "SELECT * FROM health_data ORDER BY days ASC";  // 日付順（昇順）に並べ替え
$result = $conn->query($sql);

// データが見つかった場合、JSON 形式で返す
if ($result->num_rows > 0) {
    $health_data = [];
    while ($row = $result->fetch_assoc()) {
        $health_data[] = [
            'days' => $row['days'],
            'steps' => $row['steps'],
            'weight' => $row['weight'],
            'fat' => $row['fat'],
            'height' => $row['height']
        ];
    }
    // 正しい JSON を返す
    echo json_encode($health_data);
} else {
    // データが見つからなかった場合のレスポンス
    echo json_encode(['error' => 'データが見つかりません']);
}

// データベース接続を閉じる
$conn->close();
?>
