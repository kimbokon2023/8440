-- 다온텍 회원 테이블 생성
-- member 테이블 구조를 참조하여 daon_member 테이블 생성

CREATE TABLE IF NOT EXISTS `daon_member` (
  `id` varchar(50) NOT NULL COMMENT '아이디',
  `pass` varchar(255) NOT NULL COMMENT '비밀번호',
  `name` varchar(100) NOT NULL COMMENT '이름',
  `nick` varchar(100) DEFAULT NULL COMMENT '닉네임',
  `level` int(11) DEFAULT '10' COMMENT '권한레벨(1:최고관리자, 5:관리자, 10:일반)',
  `part` varchar(50) DEFAULT NULL COMMENT '부서',
  `position` varchar(50) DEFAULT NULL COMMENT '직급',
  `hp` varchar(20) DEFAULT NULL COMMENT '핸드폰번호',
  `email` varchar(100) DEFAULT NULL COMMENT '이메일',
  `company` varchar(100) DEFAULT 'daon' COMMENT '회사코드',
  `status` enum('active','inactive','pending') DEFAULT 'active' COMMENT '상태',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
  `last_login` datetime DEFAULT NULL COMMENT '마지막로그인',
  PRIMARY KEY (`id`),
  KEY `idx_level` (`level`),
  KEY `idx_status` (`status`),
  KEY `idx_company` (`company`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='다온텍 회원';

-- 기본 관리자 계정 생성 (비밀번호: admin1234)
INSERT INTO daon_member (id, pass, name, nick, level, part, position, company, status)
VALUES
('admin', 'admin1234', '관리자', '관리자', 1, '관리부', '관리자', 'daon', 'active'),
('daon', 'daon1234', '다온텍', '다온', 5, '영업부', '담당자', 'daon', 'active');

-- 로그 테이블 생성
CREATE TABLE IF NOT EXISTS `daon_login_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL COMMENT '사용자ID',
  `login_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '로그인시간',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP주소',
  `user_agent` varchar(500) DEFAULT NULL COMMENT '브라우저정보',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_login_time` (`login_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='다온텍 로그인 로그';
