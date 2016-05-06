<table class="structure-table">
  <thead>
    <tr>
      <?php foreach($field->fields() as $f): ?>
        <?php if(isset($f['show']) && $f['show'] == "true"): ?>
          <th>
            <?php echo html($f['label'], false) ?>
          </th>
        <?php endif; ?>
      <?php endforeach ?>
      <th class="structure-table-options">  
        Geld
      </th>
      <th class="structure-table-options">  
        Zettel
      </th>
      <th class="structure-table-options">  
        &nbsp;
      </th>
    </tr>    
  </thead>
  <tbody>
    <?php foreach($field->entries() as $entry): ?>
    <tr id="structure-entry-<?php echo $entry->id() ?>">
      <?php foreach($field->fields() as $f): ?>
        <?php if(isset($f['show']) && $f['show'] == "true"): ?>
          <td style="background-color:<?= $field->color($entry) ?>;">
            <a data-modal href="<?php __($field->url($entry->id() . '/update')) ?>">
              <?php $value = ($f['name'] == "geb") ? date('d.m.Y', strtotime($entry->{$f['name']})) : html($entry->{$f['name']}, false); ?>
              <?php if ($f['name'] == "veggie") {
                $value = ($entry->{$f['name']} == "ja") ? "<i class='icon icon-left fa fa-check'></i>" : "<i class='icon icon-left fa fa-times'></i>";
              } ?>
              <?php echo $value ?>
            </a>
          </td>
        <?php endif; ?>
      <?php endforeach ?>
      <td style="background-color:<?= $field->color($entry) ?>;" class="structure-table-options">
        <a data-modal class="btn" href="<?php __($field->url($entry->id() . '/money')) ?>">
        <?php echo $field->money($entry) ?>
        </a>
      </td>
      <td style="background-color:<?= $field->color($entry) ?>;" class="structure-table-options">
        <a data-modal class="btn" href="<?php __($field->url($entry->id() . '/signature')) ?>">
        <?php echo $field->signature($entry) ?>
        </a>
      </td>
      <td style="background-color:<?= $field->color($entry) ?>;" class="structure-table-options">
        <a data-modal class="btn" href="<?php __($field->url($entry->id() . '/delete')) ?>">
          <?php i('trash-o') ?>
        </a>
      </td>
    </tr>
    <?php endforeach ?>
  </tbody>
</table>
<a class="btn" href="<?php __($field->url('printversion')); ?>">Druckversion</a>
