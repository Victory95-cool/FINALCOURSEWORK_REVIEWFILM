<h2 style="text-align: left;">Inbox Messages</h2>

<div style="margin-top: 20px;">
    <a href="contact_rev.php" class="btn-add" style="text-decoration: none; display: inline-block;">
        Start New Topic
    </a>
</div>
<br>

<div class="glass-list">
    <?php foreach ($convs as $c): ?>
        <div class="glass-item" style="position: relative;">

            <?php if (isset($c['unread_count']) && $c['unread_count'] > 0): ?>
                <span class="msg-badge">+<?= $c['unread_count'] ?></span>
            <?php endif; ?>

            <a href="conversation.php?id=<?= $c['id'] ?>" class="content-link">
                <div style="display: flex; flex-direction: column; text-align: left;">
                    <strong>Subject: <?= htmlspecialchars($c['subject']) ?></strong>
                    <small>Received: <?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></small>
                </div>
            </a>

            <form action="delete.php" method="post" onsubmit="return confirm('Delete this conversation?')">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <input type="hidden" name="type" value="conversation">
                <button type="submit" class="btn-delete" style="width: 80px !important; margin:0; padding:5px !important;">DELETE</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>