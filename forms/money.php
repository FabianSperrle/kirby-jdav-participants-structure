<?php 

return function($model) {

  $form = new Kirby\Panel\Form(array(
    'entry' => array(
      'label' => 'Statusänderung Geldeingang',
      'type'  => 'info',
    )
  ));

  $form->cancel($model);

  return $form;

};