<div class="conversation-wrapper" style="margin-bottom: 40px; border-bottom: 1px solid rgba(212,175,55,0.2); padding-bottom: 30px;">
    <h2 style="text-align: left;">Send New Message to User</h2>
    <div class="glass-reply-area">
        <form method="post" action="contact_rev.php" style="background:transparent; border:none; box-shadow:none; padding:0; display:block; width:100%;">
            <label style="display:block; text-align:left;">Select Reviewer:</label>
            <select name="reviewer_id" required style="width:100%;">
                <option value="">-- Choose Reviewer --</option>
                <?php foreach ($allReviewers as $r): ?>
                    <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['username']) ?> (ID: <?= $r['id'] ?>)</option>
                <?php endforeach; ?>
            </select>

            <label style="display:block; text-align:left; margin-top:15px;">Subject:</label>
            <input type="text" name="subject" placeholder="Enter subject..." required style="width:100%;">

            <label style="display:block; text-align:left; margin-top:15px;">Message:</label>
            <textarea name="message" placeholder="Type your message..." required style="width:100%; min-height:100px;"></textarea>

            <div style="text-align: right; margin-top: 15px;">
                <button type="submit" name="admin_send_new" class="btn-send">SEND MESSAGE</button>
            </div>
        </form>
    </div>
</div>