<div class="glass-chat-container">
    <?php foreach ($messages as $index => $mail): ?>

        <?php if ($index > 0): ?>
            <hr style="border: 0; height: 1px; background: rgba(255, 255, 255, 0.1); width: 95%; margin: 10px auto;">
        <?php endif; ?>

        <div class="glass-bubble <?= $mail['sender'] === 'admin' ? 'msg-admin' : 'msg-reviewer' ?>">
            <div class="msg-header">
                <strong style="color: var(--accent);">
                    <?= ($mail['sender'] === 'admin') ? "Admin" : "Reviewer: " . htmlspecialchars($mail['username'] ?? '') ?>
                    <span class="review-date">(Date: <?= date('d/m/Y H:i:s', strtotime($mail['created_at'])) ?>)</span>
                </strong>
            </div>
            <div class="msg-body">
                <?= nl2br(htmlspecialchars($mail['body'])) ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>