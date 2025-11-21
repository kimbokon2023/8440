-- 시공사례(portfolio) 테이블 생성
CREATE TABLE IF NOT EXISTS `portfolio` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '고유 ID',
  `title` varchar(255) NOT NULL COMMENT '시공사례 제목',
  `content` text DEFAULT NULL COMMENT '상세 내용',
  `category` varchar(50) NOT NULL DEFAULT 'etc' COMMENT '카테고리 (ceiling, jamb, sill, etc)',
  `location` varchar(100) DEFAULT NULL COMMENT '시공 위치',
  `project_date` date DEFAULT NULL COMMENT '시공 날짜',
  `main_image` varchar(255) NOT NULL COMMENT '대표 이미지 경로',
  `thumbnail` varchar(255) DEFAULT NULL COMMENT '썸네일 이미지 경로',
  `images` text DEFAULT NULL COMMENT '추가 이미지 경로 (JSON 배열, TEXT로 저장)',
  `is_published` tinyint(1) NOT NULL DEFAULT 1 COMMENT '게시 여부 (1: 공개, 0: 비공개)',
  `display_order` int(11) NOT NULL DEFAULT 0 COMMENT '표시 순서',
  `view_count` int(11) NOT NULL DEFAULT 0 COMMENT '조회수',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '생성일',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '수정일',
  `created_by` varchar(50) DEFAULT NULL COMMENT '생성자 ID',
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_is_published` (`is_published`),
  KEY `idx_display_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='시공사례 테이블';

