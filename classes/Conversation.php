<?php

class Conversation
{
    // Reviewer gửi mail mới cho admin
    public function createConversation($pdo, $subject, $reviewer_id, $body)
    {
        Database::query($pdo, "INSERT INTO conversations (subject, created_at) VALUES (:subject, NOW())", [
            ':subject' => $subject
        ]);
        $cid = $pdo->lastInsertId();

        Database::query($pdo, "INSERT INTO mails_box (conversation_id, sender, reviewers_id, subject, body, created_at, is_read)
                     VALUES (:cid, 'reviewer', :rid, :subject, :body, NOW(), 0)", [
            ':cid' => $cid,
            ':rid' => $reviewer_id,
            ':subject' => $subject,
            ':body' => $body
        ]);
        return $cid;
    }

    // Admin gửi mail mới cho reviewer
    public function adminCreateConversation($pdo, $subject, $reviewer_id, $body)
    {
        Database::query($pdo, "INSERT INTO conversations (subject, created_at) VALUES (:subject, NOW())", [
            ':subject' => $subject
        ]);
        $conversation_id = $pdo->lastInsertId();

        Database::query($pdo, "INSERT INTO mails_box (conversation_id, sender, reviewers_id, subject, body, created_at, is_read) 
                               VALUES (:cid, 'admin', :rid, :subject, :body, NOW(), 0)", [
            ':cid' => $conversation_id,
            ':rid' => $reviewer_id,
            ':subject' => $subject,
            ':body' => $body
        ]);

        return $conversation_id;
    }

    // Lấy các tin nhắn trong một cuộc hội thoại
    public function getConversation($pdo, $conversation_id)
    {
        $parameters = [':cid' => $conversation_id];
        $sql = "SELECT 
                    m.*, 
                    r.username 
                FROM mails_box m
                LEFT JOIN reviewers r ON m.reviewers_id = r.id
                WHERE m.conversation_id = :cid
                ORDER BY m.created_at ASC";

        $query = Database::query($pdo, $sql, $parameters);
        return $query->fetchAll();
    }

    // Trả lời một cuộc hội thoại
    public function replyToConversation($pdo, $conversation_id, $sender_role, $reviewer_id, $subject, $body)
    {
        $sql = "INSERT INTO mails_box (conversation_id, sender, reviewers_id, subject, body, created_at, is_read)
                VALUES (:cid, :sender, :rid, :subject, :body, NOW(), 0)";

        Database::query($pdo, $sql, [
            ':cid' => $conversation_id,
            ':sender' => $sender_role,
            ':rid' => $reviewer_id,
            ':subject' => $subject,
            ':body' => $body
        ]);
    }

    // Đánh dấu đã đọc các tin nhắn của người gửi bên kia
    public function markAsRead($pdo, $conversation_id, $viewer_role)
    {
        $target_sender = ($viewer_role === 'admin') ? 'reviewer' : 'admin';
        $sql = "UPDATE mails_box SET is_read = 1 
                WHERE conversation_id = :cid AND sender = :sender";
        Database::query($pdo, $sql, [':cid' => $conversation_id, ':sender' => $target_sender]);
    }

    // Xóa một cuộc hội thoại (các tin nhắn bên trong)
    public function deleteConversation($pdo, $id)
    {
        Database::query($pdo, "DELETE FROM mails_box WHERE conversation_id = :id", [':id' => $id]);
        Database::query($pdo, "DELETE FROM conversations WHERE id = :id", [':id' => $id]);
    }

    // Lấy hộp thư của một reviewer
    public function getReviewerMailbox($pdo, $reviewer_id)
    {
        if (!$reviewer_id) {
            return [];
        }

        $sql = "SELECT c.id, c.subject, MAX(m.created_at) as created_at, 
                SUM(CASE WHEN m.sender = 'admin' AND m.is_read = 0 THEN 1 ELSE 0 END) as unread_count 
                FROM conversations c 
                JOIN mails_box m ON c.id = m.conversation_id 
                WHERE m.reviewers_id = :rid 
                GROUP BY c.id 
                ORDER BY created_at DESC";

        return Database::query($pdo, $sql, [':rid' => $reviewer_id])->fetchAll();
    }

    public function isReviewerConversationOwner($pdo, $conversationId, $reviewerId)
    {
        return (bool) Database::query(
            $pdo,
            'SELECT EXISTS (
                 SELECT 1 FROM mails_box
                 WHERE conversation_id = :owned_conversation AND reviewers_id = :owner_id
             ) AND NOT EXISTS (
                 SELECT 1 FROM mails_box
                 WHERE conversation_id = :other_conversation
                   AND (reviewers_id IS NULL OR reviewers_id <> :other_owner_id)
             )',
            [
                ':owned_conversation' => $conversationId,
                ':owner_id' => $reviewerId,
                ':other_conversation' => $conversationId,
                ':other_owner_id' => $reviewerId
            ]
        )->fetchColumn();
    }
}
