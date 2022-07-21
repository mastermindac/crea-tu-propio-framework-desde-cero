<h1>Contacts</h1>

<?php foreach ($contacts as $contact): ?>
    <div>
        <?= $contact->name ?> <?= $contact->phone_number ?>
        <a href="/contacts/edit/<?= $contact->id ?>">Edit</a>
        <a href="/contacts/delete/<?= $contact->id ?>">Delete</a>
    </div>
<?php endforeach ?>
