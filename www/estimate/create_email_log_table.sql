CREATE TABLE IF NOT EXISTS `sent_email_logs` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `order_id` INT(11) NOT NULL COMMENT '발주서 ID',
    `sender_email` VARCHAR(255) NOT NULL COMMENT '발신자 이메일',
    `recipient_email` VARCHAR(255) NOT NULL COMMENT '수신자 이메일',
    `subject` VARCHAR(255) NOT NULL COMMENT '메일 제목',
    `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '발송 시간',
    `status` VARCHAR(50) DEFAULT 'success' COMMENT '발송 상태 (success, fail)',
    `error_message` TEXT DEFAULT NULL COMMENT '에러 메시지 (실패 시)',
    PRIMARY KEY (`id`),
    KEY `idx_order_id` (`order_id`),
    KEY `idx_sent_at` (`sent_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='발주서 이메일 발송 로그';
