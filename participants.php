<?php 

class ParticipantsField extends BaseField {

  static public $assets = array(
    'js' => array(
      'structure.js'
    ),
    'css' => array(
      'structure.css'
    )
  );

  public $fields    = array();
  public $entry     = null;
  public $structure = null;
  public $style     = 'table';
  public $modalsize = 'large';

  public function routes() {

    return array(
      array(
        'pattern' => 'add',
        'method'  => 'get|post',
        'action'  => 'add'
      ),
      array(
        'pattern' => 'sort',
        'method'  => 'post',
        'action'  => 'sort',
      ),
      array(
        'pattern' => '(:any)/update',
        'method'  => 'get|post',
        'action'  => 'update'
      ),
      array(
        'pattern' => '(:any)/delete',
        'method'  => 'get|post',
        'action'  => 'delete',
      ),
      array(
      	'pattern' => '(:any)/money',
      	'method'  => 'get|post',
      	'action'  => 'money'
      ),
      array(
      	'pattern' => '(:any)/signature',
      	'method'  => 'get|post',
      	'action'  => 'signature'
      ),
      array(
      	'pattern' => 'printversion',
      	'method'  => 'get',
      	'action'  => 'printversion'
      ),
    );
  }

  public function modalsize() {
    $sizes = array('small', 'medium', 'large');
    return in_array($this->modalsize, $sizes) ? $this->modalsize : 'medium';
  }

  public function style() {
    $styles = array('table', 'items');
    return in_array($this->style, $styles) ? $this->style : 'items';
  }

  public function structure() {
    if(!is_null($this->structure)) {
      return $this->structure;
    } else {
      return $this->structure = $this->model->structure()->forField($this->name);      
    }
  }

  public function fields() {

    $output = array();

    foreach($this->structure->fields() as $k => $v) {
      $v['name']  = $k;
      $v['value'] = '{{' . $k . '}}';
      $output[] = $v;
    }

    return $output;

  }

  public function entries() {
    $entries = $this->structure()->data()->sortBy('typ', 'status', 'nachname', 'vorname');

    return $entries;
  }

  public function color($entry) {
  	switch ($entry->typ()) {
  		case 'j':
  		case 'a':
  			return '#d9edf7';
  			break;

  		case 't':
  			switch ($entry->status()) {
  				case 'komplett':
  					return '#dff0d8';
  					break;
  				
  				case 'liste':
  					return '#fcf8e3';
  					break;
  			}
  			break;
  	}
  }

  public function money($entry) {
  	return $this->generateStatusIcon($entry, "money");
  }

  public function signature($entry) {
  	return $this->generateStatusIcon($entry, "signature");
  }

  private function checkStatus($entry, $type) {
  	$status = $entry->status();

  	$moneyStatuses = array("komplett", "bezahlt");
  	$signatureStatuses = array("einv", "komplett");

  	if ($type == "money") {
  		return in_array($status, $moneyStatuses);
  	} else {
  		return in_array($status, $signatureStatuses);
  	}
  }

  private function generateStatusIcon($entry, $type) {
  	$value = $this->checkStatus($entry, $type);

  	if ($value) {
  		return "<i class='icon icon-left fa fa-check'></i>";
  	}
	return "<i class='icon icon-left fa fa-times'></i>";
  }

  public function result() {
    return $this->structure()->toYaml();
  }

  public function entry($data) {

    if(is_null($this->entry) or !is_string($this->entry)) {
      $html = array();
      foreach($this->fields as $name => $field) {
        if(isset($data->$name)) {
          $html[] = $data->$name;          
        }
      }
      return implode('<br>', $html);
    } else {
    
      $text = $this->entry;

      foreach((array)$data as $key => $value) {
        if(is_array($value)) {
          $value = implode(', ', array_values($value));
        }
        $text = str_replace('{{' . $key . '}}', $value, $text);
      }

      return $text;
    
    }

  }

  public function label() {
    return null;
  }

  public function headline() {

    if(!$this->readonly) {

      $add = new Brick('a');
      $add->html('<i class="icon icon-left fa fa-plus-circle"></i>' . l('fields.structure.add'));
      $add->addClass('structure-add-button label-option');
      $add->data('modal', true);
      $add->attr('href', purl($this->model, 'field/' . $this->name . '/participants/add'));

    } else {
      $add = null;
    }

    $label = parent::label();
    $label->addClass('structure-label');
    $label->append($add);

    return $label;

  }

  public function content() {
    return tpl::load(__DIR__ . DS . 'template.php', array('field' => $this));
  }

  public function url($action) {
    return purl($this->model(), 'field/' . $this->name() . '/participants/' . $action);
  }  

}