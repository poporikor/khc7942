<?php
header('Content-Type: application/json');

// POST 데이터 받기
$data = json_decode(file_get_contents('php://input'), true);

// 필수 필드 검증
if (empty($data['name']) || empty($data['phone']) || empty($data['email'])) {
    echo json_encode([
        'success' => false,
        'message' => '필수 정보를 모두 입력해주세요.'
    ]);
    exit;
}

// 이메일 제목
$subject = "상담 예약 신청 - " . $data['name'];

// 이메일 내용 구성
$message = "새로운 상담 예약이 접수되었습니다.\n\n";
$message .= "이름: " . $data['name'] . "\n";
$message .= "연락처: " . $data['phone'] . "\n";
$message .= "이메일: " . $data['email'] . "\n";
$message .= "희망 상담일: " . $data['preferredDate'] . "\n";
$message .= "상담 유형: " . $data['counselingType'] . "\n\n";
$message .= "상담 내용:\n" . $data['message'];

// 이메일 헤더
$headers = "From: " . $data['email'] . "\r\n";
$headers .= "Reply-To: " . $data['email'] . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// 이메일 전송
$to = "khc7942@naver.com"; // 수신자 이메일 주소
$mail_sent = mail($to, $subject, $message, $headers);

if ($mail_sent) {
    echo json_encode([
        'success' => true,
        'message' => '상담 예약이 성공적으로 접수되었습니다. 빠른 시일 내에 연락드리겠습니다.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => '상담 예약 접수 중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.'
    ]);
}
?> 