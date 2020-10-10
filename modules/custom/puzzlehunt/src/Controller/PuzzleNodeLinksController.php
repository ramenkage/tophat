<?php

namespace Drupal\puzzlehunt\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * TODO: class docs.
 */
class PuzzleNodeLinksController extends ControllerBase {

  /**
   * Callback for the puzzlehunt.add_solver route.
   */
  public function addSolver(Node $node) {

    $output = "";
    $current_status = $node->field_status->target_id;

    // Status 1 = New, status 3 = Working.
    if ($current_status == 1) {
      $node->field_status = 3;
      $output .= $this->t('Changing status from New to Working.');
    }
    else {
      $output .= $this->t('Status was not New, so not changing.');
    }

    $userId = $this->currentUser()->id();

    $existingUsers = array_column($node->field_current_solvers->getValue(), 'target_id');
    if (!in_array($userId, $existingUsers)) {
      $node->field_current_solvers[] = $userId;
      $output .= $this->t('Adding user @userId to current solvers.', ['@userId' => $this->currentUser()->id()]);
    }
    else {
      $output .= $this->t('User @userId is already a current solver.', ['@userId' => $this->currentUser()->id()]);
    }

    $node->save();
    return $this->redirect('<front>');
  }

  /**
   * Callback for the puzzlehunt.remove_solver route.
   */
  public function removeSolver(Node $node) {

    $output = "";
    $userId = $this->currentUser()->id();

    $existingUsers = array_column($node->field_current_solvers->getValue(), 'target_id');
    if (in_array($userId, $existingUsers)) {
      $key = array_search($userId, $existingUsers);
      $node->field_current_solvers->removeItem($key);
      $output .= $this->t('Removing user @userId from current solvers.', ['@userId' => $this->currentUser()->id()]);
    }
    else {
      $output .= $this->t('User @userId is not a current solver.', ['@userId' => $this->currentUser()->id()]);
    }

    $node->save();
    return $this->redirect('<front>');
  }

}
