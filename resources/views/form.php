<h1>Test Form</h1>
<form method="post">
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input value="<?= old('email') ?>" name="email" type="text" class="form-control">
    <div class="text-danger"><?= error('email') ?></div>
  </div>

  <div class="mb-3">
    <label class="form-label">Name</label>
    <input value="<?= old('name') ?>" name="name" type="text" class="form-control">
    <div class="text-danger"><?= error('name') ?></div>

  </div>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>
