<h2>Add New Reviewer</h2>
<form action="addreviewers.php" method="post">
    <label>Username</label>
    <input type="text" name="username" required>

    <label>Phone Number</label>
    <input type="text" name="phone_number" pattern="^\d{10}$" title="Please enter a valid 10-digit phone number" required>

    <label>Email</label>
    <input type="email" name="email"
        pattern="[a-zA-Z0-9._%+-]+@gmail\.com$"
        title="Please enter a valid @gmail.com address"
        required>

    <input type="submit" value="Add Reviewer" class="btn-add">
</form>