<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1><?= $mode==='create' ? 'Content anlegen' : 'Content bearbeiten' ?></h1>

<?php if (session('success')): ?><div class="alert alert-success"><?= esc(session('success')) ?></div><?php endif; ?>
<?php if (session('error')): ?><div class="alert alert-danger"><?= esc(session('error')) ?></div><?php endif; ?>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<form method="post" action="/admin/content/save" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= esc($data['id'] ?? '') ?>">

  <div>
    <label>Titel *</label>
    <input type="text" name="title" required value="<?= esc($data['title'] ?? '') ?>">
  </div>

  <div>
    <label>Identifier (leer = auto)</label>
    <input type="text" name="identifer" value="<?= esc($data['identifer'] ?? '') ?>">
  </div>

  <div>
    <label>Datum</label>
    <input type="date" name="date" value="<?= esc($data['date'] ?? date('Y-m-d')) ?>">
  </div>

  <div>
    <label>Kategorie</label>
    <select name="_categories[]" required>
      <option value="">– wählen –</option>
      <?php foreach ($categories as $c): ?>
        <option value="<?= (int)$c['id'] ?>" <?= in_array((int)$c['id'], $data['_categories'] ?? [], true) ? 'selected':'' ?>>
          <?= esc($c['title']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label>Producer (optional, max. 1)</label>
    <select name="producer_id">
      <option value="">– keiner –</option>
      <?php foreach ($producers as $p): ?>
        <option value="<?= (int)$p['id'] ?>" <?= ((int)($data['producer_id'] ?? 0) === (int)$p['id']) ? 'selected':'' ?>>
          <?= esc($p['name'] ?? $p['identifer_prod']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <fieldset>
    <legend>Flags</legend>
    <label><input type="checkbox" name="onstart" value="1" <?= !empty($data['onstart'])?'checked':'' ?>> Onstart</label>
    <label><input type="checkbox" name="status" value="1" <?= (int)($data['status'] ?? 1)===1?'checked':'' ?>> aktiv</label>
    <label><input type="checkbox" name="is_zeitung" value="1" <?= !empty($data['is_zeitung'])?'checked':'' ?>> Zeitung</label>
    <label><input type="checkbox" name="is_provence" value="1" <?= !empty($data['is_provence'])?'checked':'' ?>> Provence</label>
    <label><input type="checkbox" name="is_traubenwerke" value="1" <?= !empty($data['is_traubenwerke'])?'checked':'' ?>> Traubenwerke</label>
  </fieldset>

  <fieldset>
    <legend>Datei-Uploads</legend>
    <div><label>image</label> <input type="file" name="image" accept="image/*"></div>
    <div><label>imageIndex</label> <input type="file" name="imageIndex" accept="image/*"></div>
    <div><label>imageBreitFlach</label> <input type="file" name="imageBreitFlach" accept="image/*"></div>
    <div><label>imageQuad</label> <input type="file" name="imageQuad" accept="image/*"></div>
    <div><label>image_2quer</label> <input type="file" name="image_2quer" accept="image/*"></div>
  </fieldset>

  <fieldset>
    <legend>Slices</legend>
    <div id="slices">
      <?php
      $slices = $data['slices'] ?? [];
      if (!$slices) {
        $slices = [['type'=>'text','text'=>'','caption'=>'','image_field'=>'','video'=>'']];
      }
      foreach ($slices as $idx => $s):
      ?>
      <div class="slice" data-idx="<?= $idx ?>">
        <label>Typ
          <select name="slices[<?= $idx ?>][type]">
            <option value="text"       <?= ($s['type']??'')==='text'?'selected':'' ?>>Text</option>
            <option value="text_image" <?= ($s['type']??'')==='text_image'?'selected':'' ?>>Text+Bild</option>
            <option value="text_quote" <?= ($s['type']??'')==='text_quote'?'selected':'' ?>>Zitat</option>
            <option value="video"      <?= ($s['type']??'')==='video'?'selected':'' ?>>Video</option>
          </select>
        </label>
        <div><label>Text</label><textarea name="slices[<?= $idx ?>][text]" rows="4"><?= esc($s['text'] ?? '') ?></textarea></div>
        <div><label>Caption</label><input type="text" name="slices[<?= $idx ?>][caption]" value="<?= esc($s['caption'] ?? '') ?>"></div>
        <div><label>Video-URL</label><input type="url" name="slices[<?= $idx ?>][video]" value="<?= esc($s['video'] ?? '') ?>"></div>
        <div>
          <label>Bild (optional)</label>
          <input type="file" name="slice_files[image_<?= $idx ?>]" accept="image/*">
          <input type="hidden" name="slices[<?= $idx ?>][image_field]" value="image_<?= $idx ?>">
        </div>
        <hr>
      </div>
      <?php endforeach; ?>
    </div>
    <button type="button" id="addSlice">+ Slice hinzufügen</button>
  </fieldset>

  <div class="mt-3">
    <button type="submit">Speichern</button>
    <?php if (!empty($data['id'])): ?>
      <button form="deleteForm" type="submit" onclick="return confirm('Wirklich löschen?')">Löschen</button>
    <?php endif; ?>
  </div>
</form>

<?php if (!empty($data['id'])): ?>
<form id="deleteForm" method="post" action="/admin/content/delete/<?= (int)$data['id'] ?>">
  <?= csrf_field() ?>
  <input type="hidden" name="confirm" value="1">
</form>
<?php endif; ?>

<script nonce="<?= csp_script_nonce() ?>">
document.getElementById('addSlice').addEventListener('click', function() {
  const wrap = document.getElementById('slices');
  const idx  = wrap.querySelectorAll('.slice').length;
  const tpl  = `
  <div class="slice" data-idx="${idx}">
    <label>Typ
      <select name="slices[${idx}][type]">
        <option value="text">Text</option>
        <option value="text_image">Text+Bild</option>
        <option value="text_quote">Zitat</option>
        <option value="video">Video</option>
      </select>
    </label>
    <div><label>Text</label><textarea name="slices[${idx}][text]" rows="4"></textarea></div>
    <div><label>Caption</label><input type="text" name="slices[${idx}][caption]" value=""></div>
    <div><label>Video-URL</label><input type="url" name="slices[${idx}][video]" value=""></div>
    <div>
      <label>Bild (optional)</label>
      <input type="file" name="slice_files[image_${idx}]" accept="image/*">
      <input type="hidden" name="slices[${idx}][image_field]" value="image_${idx}">
    </div>
    <hr>
  </div>`;
  wrap.insertAdjacentHTML('beforeend', tpl);
});
</script>

<?= $this->endSection() ?>
