<h1>Edit Contact</h1>
<form method="POST" action="/contacts/edit/<?= $contact->id ?>">
  <div class="mb-3">
    <label class="form-label">Name</label>
    <input value="<?= old('name') ?? $contact->name ?>" name="name" type="text" class="form-control">
    <div class="text-danger"><?= error('name') ?></div>
  </div>

  <div class="mb-3">
    <label class="form-label">Phone Number</label>
    <input value="<?= old('phone_number') ?? $contact->phone_number ?>" name="phone_number" type="text" class="form-control">
    <div class="text-danger"><?= error('phone_number') ?></div>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
