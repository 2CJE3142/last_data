<?php
// �G���[���b�Z�[�W��\������
ini_set('display_errors', 1);
error_reporting(E_ALL);

// �K�v�ȃw�b�_�[��ݒ肵�āA���X�|���X�� JSON �Ƃ��ĕԂ�
header('Content-Type: application/json');

// �f�[�^�x�[�X�ڑ��i��: MySQL�j
$host = 'localhost';
$user = 'root'; // ���ۂ̃f�[�^�x�[�X���[�U�[��
$password = ''; // ���ۂ̃f�[�^�x�[�X�p�X���[�h
$database = 'health';

$conn = new mysqli($host, $user, $password, $database);

// �ڑ��`�F�b�N
if ($conn->connect_error) {
    die(json_encode(['error' => '�f�[�^�x�[�X�ڑ��G���[: ' . $conn->connect_error]));
}

// �f�[�^��S�Ď擾���� SQL �N�G���i�����ɕύX�j
$sql = "SELECT * FROM health_data ORDER BY days ASC";  // ���t���i�����j�ɕ��בւ�
$result = $conn->query($sql);

// �f�[�^�����������ꍇ�AJSON �`���ŕԂ�
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
    // ������ JSON ��Ԃ�
    echo json_encode($health_data);
} else {
    // �f�[�^��������Ȃ������ꍇ�̃��X�|���X
    echo json_encode(['error' => '�f�[�^��������܂���']);
}

// �f�[�^�x�[�X�ڑ������
$conn->close();
?>
