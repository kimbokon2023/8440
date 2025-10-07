<?php
/**
 * 환경별 설정 파일
 * 로컬 개발 환경과 서버 운영 환경을 자동으로 구분하여 각각의 환경에 맞는 설정 제공
 */

class Environment {
    const LOCAL = 'local';
    const SERVER = 'server';
    
    private static $current = null;
    
    /**
     * 현재 환경을 반환
     * @return string 'local' 또는 'server'
     */
    public static function getCurrent() {
        if (self::$current === null) {
            // 로컬 환경 감지 (HTTP_HOST 및 SERVER_ADDR 기반)
            $host = $_SERVER['HTTP_HOST'] ?? '';
            $serverAddr = $_SERVER['SERVER_ADDR'] ?? '';
            
            // 로컬 환경 조건들
            // 1. HTTP_HOST 기반 감지
            $isLocalHost = (
                strpos($host, '8440.local') !== false ||
                strpos($host, 'localhost') !== false || 
                strpos($host, '127.0.0.1') !== false ||
                strpos($host, '192.168.') !== false ||
                strpos($host, '10.0.') !== false ||
                strpos($host, '172.16.') !== false ||
                strpos($host, '172.17.') !== false ||
                strpos($host, '172.18.') !== false ||
                strpos($host, '172.19.') !== false ||
                strpos($host, '172.20.') !== false ||
                strpos($host, '172.21.') !== false ||
                strpos($host, '172.22.') !== false ||
                strpos($host, '172.23.') !== false ||
                strpos($host, '172.24.') !== false ||
                strpos($host, '172.25.') !== false ||
                strpos($host, '172.26.') !== false ||
                strpos($host, '172.27.') !== false ||
                strpos($host, '172.28.') !== false ||
                strpos($host, '172.29.') !== false ||
                strpos($host, '172.30.') !== false ||
                strpos($host, '172.31.') !== false
            );
            
            // 2. SERVER_ADDR 기반 감지 (hosts 파일 수정 시 대응)
            $isLocalServer = (
                $serverAddr === '127.0.0.1' ||
                $serverAddr === '::1' ||
                strpos($serverAddr, '192.168.') === 0 ||
                strpos($serverAddr, '10.0.') === 0
            );
            
            if ($isLocalHost || $isLocalServer) {
                self::$current = self::LOCAL;
            } else {
                self::$current = self::SERVER;
            }
        }
        return self::$current;
    }
    
    /**
     * 현재 환경이 로컬인지 확인
     * @return bool
     */
    public static function isLocal() {
        return self::getCurrent() === self::LOCAL;
    }
    
    /**
     * 현재 환경이 서버인지 확인
     * @return bool
     */
    public static function isServer() {
        return self::getCurrent() === self::SERVER;
    }
}

/**
 * 환경별 데이터베이스 설정
 * @return array DB 설정 배열
 */
function getDatabaseConfig() {
    if (Environment::isLocal()) {
        // 로컬 환경 DB 설정
        return [
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'name' => 'mirae8440'
        ];
    } else {
        // 서버 환경 DB 설정
        return [
            'host' => 'localhost',
            'user' => 'mirae8440',
            'pass' => 'dnjstksfl1!!',
            'name' => 'mirae8440'
        ];
    }
}

/**
 * 환경별 기본 URL 설정
 * @return string 기본 URL
 */
function getBaseUrl() {
    if (Environment::isLocal()) {
        // 로컬 환경에서는 현재 도메인을 사용
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $protocol . '://' . $host;
    } else {
        // 서버 환경에서는 실제 도메인 사용
        return 'https://8440.co.kr';
    }
}

/**
 * 환경별 경로 설정
 * @param string $path 경로
 * @return string 완전한 URL
 */
function getPath($path = '') {
    $baseUrl = getBaseUrl();
    return $baseUrl . ($path ? '/' . ltrim($path, '/') : '');
}

/**
 * 환경별 디버그 모드 설정
 * @return bool
 */
function isDebugMode() {
    return Environment::isLocal();
}

