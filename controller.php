<?php

class ParticipantsFieldController extends Kirby\Panel\Controllers\Field {

  public function add() {

    $self      = $this;
    $model     = $this->model();
    $structure = $this->structure($model);
    $modalsize = $this->field()->modalsize();
    $form      = $this->form('add', array($model, $structure), function($form) use($model, $structure, $self) {

      $form->validate();

      if(!$form->isValid()) {
        return false;
      }

      $structure->add($form->serialize());
      $self->redirect($model);

    });

    return $this->modal('add', compact('form', 'modalsize'));

  }

  public function update($entryId) {

    $self      = $this;
    $model     = $this->model();
    $structure = $this->structure($model);
    $entry     = $structure->find($entryId);

    if(!$entry) {
      return $this->modal('error', array(
        'text' => l('fields.structure.entry.error')
      ));
    }

    $modalsize = $this->field()->modalsize();
    $form      = $this->form('update', array($model, $structure, $entry), function($form) use($model, $structure, $self, $entryId) {

      // run the form validator
      $form->validate();

      if(!$form->isValid()) {
        return false;
      }

      $structure->update($entryId, $form->serialize());
      $self->redirect($model);

    });

    return $this->modal('update', compact('form', 'modalsize'));
        
  }

  public function delete($entryId) {
    
    $self      = $this;
    $model     = $this->model();
    $structure = $this->structure($model);
    $entry     = $structure->find($entryId);

    if(!$entry) {
      return $this->modal('error', array(
        'text' => l('fields.structure.entry.error')
      ));
    }

    $form = $this->form('delete', $model, function() use($self, $model, $structure, $entryId) {
      $structure->delete($entryId);
      $self->redirect($model);
    });
    
    return $this->modal('delete', compact('form'));

  }

  public function money($entryId) {
    $self      = $this;
    $model     = $this->model();
    $structure = $this->structure($model);
    $entry     = $structure->find($entryId);

    if(!$entry) {
      return $this->modal('error', array(
        'text' => l('fields.structure.entry.error')
      ));
    }

    if($entry->status == "liste") {
      return $this->modal('error', array(
        'text' => "Diese Operation ist für Nutzer auf der Warteliste nicht gestattet!"
      ));
    }

    $form = $this->form('money', $model, function() use($self, $model, $structure, $entryId, $entry) {

      $entry = $entry->toArray();

      switch ($entry['status']) {
        case 'angemeldet':
          $entry['status'] = "bezahlt";
          break;
        
        case 'bezahlt':
          $entry['status'] = "angemeldet";
          break;
        
        case 'einv':
          $entry['status'] = "komplett";
          break;
        
        case 'komplett':
          $entry['status'] = "einv";
          break;
      }

      $structure->update($entryId, $entry);
      $self->redirect($model);
    });


    return $this->modal('money', compact('form'));
  }

  public function signature($entryId) {
    $self      = $this;
    $model     = $this->model();
    $structure = $this->structure($model);
    $entry     = $structure->find($entryId);

    if(!$entry) {
      return $this->modal('error', array(
        'text' => l('fields.structure.entry.error')
      ));
    }

    if($entry->status == "liste") {
      return $this->modal('error', array(
        'text' => "Diese Operation ist für Nutzer auf der Warteliste nicht gestattet!"
      ));
    }

    $form = $this->form('signature', $model, function() use($self, $model, $structure, $entryId, $entry) {

      $entry = $entry->toArray();

      switch ($entry['status']) {
        case 'angemeldet':
          $entry['status'] = "einv";
          kirby()->trigger('jdav.participants.signature', $entry);
          break;
        
        case 'bezahlt':
          $entry['status'] = "komplett";
          kirby()->trigger('jdav.participants.signature', $entry);
          break;
        
        case 'einv':
          $entry['status'] = "angemeldet";
          break;
        
        case 'komplett':
          $entry['status'] = "bezahlt";
          break;
      }

      $structure->update($entryId, $entry);
      $self->redirect($model);
    });

    return $this->modal('signature', compact('form'));
  }

  public function printversion() {
    $self      = $this;
    $model     = $this->model();
    $structure = $this->structure($model);
    $persons   = $structure->data()->sortBy('typ', 'nachname', 'vorname');

    return $this->modal('printversion', compact('persons'));
  }

  public function sort() {
    $model     = $this->model();
    $structure = $this->structure($model);
    $structure->sort(get('ids'));
    $this->redirect($model);
  }

  protected function structure($model) {
    return $model->structure()->forField($this->fieldname());
  }

}