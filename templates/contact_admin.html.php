<h2>Contact Admin</h2>

<div class="form-wrapper">
    <form action="contact_admin.php" method="post">

        <p style="color: var(--text-secondary); margin-bottom: 20px;">
            You are contacting Admin as: <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>
        </p>

        <label>Your Name</label>
        <input type="text" name="reviewers_name"
            value="<?= htmlspecialchars($_SESSION['user_name']) ?>"
            readonly
            style="background-color: rgba(255,255,255,0.1); cursor: not-allowed;">

        <label>Subject</label>
        <input type="text" name="subject" placeholder="What is this about?" required>

        <label>Message</label>
        <textarea name="message" rows="6" placeholder="Type your message to admin here..." required></textarea>

        <button type="submit" class="btn-send">Send Message</button>
    </form>
</div>