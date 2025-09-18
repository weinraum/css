<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1>Content</h1>

<form method="get" class="mb-3">
  <div>
    <input type="text" name="q" value="<?= esc($filters['q'] ?? '') ?>" placeholder="Titel / Identifier">
    <select name="category">
      <option value="">– Kategorie –</option>
      <?php foreach (service('contentService')->getAllCategories() as $c): ?>
        <option value="<?= (int)$c['id'] ?>" <?= (isset($filters['category']) && (int)$filters['category']===(int)$c['id'])?'selected':'' ?>>
          <?= esc($c['title']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <select name="producer">
      <option value="">– Producer –</option>
      <?php foreach (service('contentService')->getAllProducers() as $p): ?>
        <option value="<?= (int)$p['id'] ?>" <?= (isset($filters['producer']) && (int)$filters['producer']===(int)$p['id'])?'selected':'' ?>>
          <?= esc($p['name'] ?? $p['identifer_prod']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <select name="status">
      <option value="">– Status –</option>
      <option value="1" <?= (($filters['status'] ?? '')==='1')?'selected':'' ?>>aktiv</option>
      <option value="0" <?= (($filters['status'] ?? '')==='0')?'selected':'' ?>>inaktiv</option>
    </select>
    <button type="submit">Filtern</button>
    <a href="/admin/content/create">+ Neu</a>
  </div>
</form>

<table class="table">
  <thead>
    <tr>
      <th>ID</th><th>Titel</th><th>Identifier</th><th>Datum</th><th>Producer</th><th>Status</th><th></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($items as $r): ?>
    <tr>
      <td><?= (int)$r['id'] ?></td>
      <td><?= esc($r['title']) ?></td>
      <td><code><?= esc($r['identifer']) ?></code></td>
      <td><?= esc($r['date']) ?></td>
      <td><?= esc($r['producer_identifer'] ?? '') ?></td>
      <td><?= ((int)$r['status']===1)?'aktiv':'inaktiv' ?></td>
      <td><a href="/admin/content/edit/<?= (int)$r['id'] ?>">Bearbeiten</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<div class="mt-3">
  <?= $pager->links() ?>
</div>

<?= $this->endSection() ?>
