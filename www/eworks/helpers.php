<?php

if (!function_exists('normalizeApprovalIds')) {
    /**
     * Normalize a bang-separated approval string into an array of IDs.
     */
    function normalizeApprovalIds(?string $value): array
    {
        if ($value === null) {
            return [];
        }

        $parts = explode('!', trim($value));
        $clean = [];

        foreach ($parts as $part) {
            $part = trim($part);
            if ($part !== '') {
                $clean[] = $part;
            }
        }

        return $clean;
    }
}

if (!function_exists('isUserNextApprover')) {
    /**
     * Determine whether the given user is the next approver in the approval line.
     */
    function isUserNextApprover(?string $line, ?string $confirmed, string $userId): bool
    {
        $lineIds = normalizeApprovalIds($line);
        if (empty($lineIds)) {
            return false;
        }

        $userIndex = array_search($userId, $lineIds, true);
        if ($userIndex === false) {
            return false;
        }

        $confirmedIds = normalizeApprovalIds($confirmed);

        // 이미 결재했다면 미결 대상이 아님
        if (in_array($userId, $confirmedIds, true)) {
            return false;
        }

        if (empty($confirmedIds)) {
            // 첫 번째 결재자만 미결 대상
            return $userIndex === 0;
        }

        $lastConfirmed = end($confirmedIds);
        $lastIndex = array_search($lastConfirmed, $lineIds, true);

        if ($lastIndex === false) {
            // 예외 상황(결재선에 없는 ID가 저장된 경우)에는 첫 결재자가 아니라면 대기 처리하지 않음
            return $userIndex === 0;
        }

        return ($lastIndex + 1) === $userIndex;
    }
}

if (!function_exists('fetchPendingApprovalIds')) {
    /**
     * Retrieve the list of eworks `num` where the user is the next approver.
     */
    function fetchPendingApprovalIds(PDO $pdo, string $dbName, string $userId): array
    {
        $pattern = '%!' . $userId . '!%';
        $sql = "SELECT num, e_line_id, e_confirm_id FROM {$dbName}.eworks " .
               "WHERE is_deleted IS NULL " .
               " AND CONCAT('!', COALESCE(e_viewexcept_id, ''), '!') NOT LIKE :viewPattern " .
               " AND CONCAT('!', COALESCE(e_line_id, ''), '!') LIKE :linePattern " .
               " AND status IN ('send', '상신', 'noend', 'ing')";

        $pending = [];

        try {
            $stmh = $pdo->prepare($sql);
            $stmh->execute([
                ':viewPattern' => $pattern,
                ':linePattern' => $pattern,
            ]);

            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                if (isUserNextApprover($row['e_line_id'] ?? '', $row['e_confirm_id'] ?? '', $userId)) {
                    $pending[] = (int)($row['num'] ?? 0);
                }
            }
        } catch (PDOException $ex) {
            error_log('fetchPendingApprovalIds error: ' . $ex->getMessage());
        }

        error_log('fetchPendingApprovalIds user ' . $userId . ' count: ' . count($pending));

        return $pending;
    }
}


