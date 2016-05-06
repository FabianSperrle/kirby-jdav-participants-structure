<?php 

return function($model) {

  $form = new Kirby\Panel\Form(array(
    'entry' => array(
      'label' => 'Statusänderung Einverständniserklärung',
      'type'  => 'info',
    )
  ));

  $form->cancel($model);

  return $form;

};