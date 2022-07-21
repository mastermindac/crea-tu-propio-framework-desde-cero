<h1>Welcome</h1>
<?php if (isGuest()) { ?>
    <div>
        <a href="/login">Login</a>
    </div>
    <div>
        <a href="/register">Register</a>
    </div>
<?php } else { ?>
    <div>
        <a href="/contacts">Contact List</a>
    </div>
<?php } ?>
