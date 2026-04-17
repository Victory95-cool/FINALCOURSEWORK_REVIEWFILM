<div class="conversation-wrapper">
    <h2 style="text-align: left; width: 100%;"><?= htmlspecialchars($messages[0]['subject'] ?? 'No Subject') ?></h2>

    <div class="glass-chat-container">
        <?php foreach ($messages as $mail): ?>
            <div class="glass-bubble <?= $mail['sender'] === 'admin' ? 'msg-admin' : 'msg-reviewer' ?>">
                <div class="msg-header">
                    <strong style="color: var(--accent);">
                        <?php
                        if ($mail['sender'] === 'admin') {
                            echo "Admin";
                        } else {
                            echo "Reviewer: " . htmlspecialchars($mail['username'] ?? '');
                        }
                        ?>
                        <span class="review-date" style="font-weight: normal; border:none; padding:0; margin-left:10px;">
                            (Date: <?= date('d/m/Y H:i:s', strtotime($mail['created_at'])) ?>)
                        </span>
                    </strong>
                </div>
                <div class="msg-body">
                    <?= nl2br(htmlspecialchars($mail['body'])) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="glass-reply-area" style="width: 100%; margin-top: 20px; padding: 20px; background: rgba(255,255,255,0.05); border-radius: 15px; border: 1px solid rgba(212,175,55,0.2);">
        <h3 style="color: var(--accent); margin-bottom: 15px;">Quick Reply</h3>
        <form method="post" action="">
            <input type="hidden" name="conversation_id" value="<?= htmlspecialchars($id) ?>">
            <input type="hidden" name="reviewer_id" value="<?= htmlspecialchars($messages[0]['reviewers_id']) ?>">
            <input type="hidden" name="subject" value="<?= htmlspecialchars($messages[0]['subject']) ?>">

            <textarea name="reply_message" placeholder="Type your reply here..." required
                style="width: 100%; min-height: 120px; background: #1a1a1a; color: white; border: 1px solid var(--accent); border-radius: 10px; padding: 15px;"></textarea>

            <div style="text-align: right; margin-top: 15px;">
                <button type="submit" name="submit_reply" class="btn-send">SEND REPLY</button>
            </div>
        </form>
    </div>
</div>

<style>
    .conversation-wrapper {
        max-width: 800px;
        margin: 0 auto;
        padding: 40px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .glass-chat-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-top: 20px;
        width: 100%;
    }

    .glass-bubble {
        background: var(--bg-surface);
        border: 1px solid rgba(212, 175, 55, 0.2);
        border-radius: 15px;
        padding: 20px;
        width: 100%;
        box-sizing: border-box;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        /* Nội dung luôn nằm bên trái bên trong bong bóng */
        text-align: left;
    }

    .msg-admin {
        border-left: 4px solid var(--accent);
    }

    .msg-reviewer {
        border-right: 4px solid #54a0ff;
    }
</style>