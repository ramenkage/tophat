<?php

namespace Drupal\puzzlehunt\Controller;

use Drupal\Component\Utility\Html;
use Drupal\node\Entity\Node;

use Drupal\jeditable\Controller\JeditableAjax as BaseController;

/**
 * TODO: class docs.
 */
class JeditableAjax extends BaseController {

  /**
   * {@inheritdoc}
   */
  public function jeditableAjaxSave() {
    $response = parent::jeditableAjaxSave();

    $array = explode('-', $_POST['id']);
    // Fieldtype and $delta can used when expanding the scope of the module.
    list($type, $id, $field_name, $field_type, $delta) = $array;
    $value = Html::escape($_POST['value']);

    // Was a solution added through the editable field?
    if ($field_name === 'field_solution' && !empty($value)) {
      $node = Node::load($id);
      // Set status to Solved (2).
      $node->field_status = 2;
      // Remove current solvers.
      unset($node->field_current_solvers);
      $node->save();
    }

    return $response;
  }

}
