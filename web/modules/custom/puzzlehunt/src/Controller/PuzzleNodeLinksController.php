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

    // Status 1 = New, 3 = Working, 5 = Abandoned.
    if ($current_status == 1 || $current_status == 5) {
      $node->field_status = 3;
      $output .= $this->t('Changing status to Working.');
    }
    else {
      $output .= $this->t('Status was not New or Abandoned, so not changing.');
    }

    $userId = $this->currentUser()->id();

    $currentSolvers = array_column($node->field_current_solvers->getValue(), 'target_id');
    if (!in_array($userId, $currentSolvers)) {
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

    $currentSolvers = array_column($node->field_current_solvers->getValue(), 'target_id');
    if (in_array($userId, $currentSolvers)) {
      $key = array_search($userId, $currentSolvers);
      $node->field_current_solvers->removeItem($key);
      $output .= $this->t('Removing user @userId from current solvers.', ['@userId' => $this->currentUser()->id()]);

      $current_status = $node->field_status->target_id;
      // Status 3 = Working, 5 = Abandoned.
      if ($current_status == 3 && $node->field_current_solvers->isEmpty()) {
        $node->field_status = 5;
        $output .= $this->t('Changing status from Working to Abandoned.');
      }
    }
    else {
      $output .= $this->t('User @userId is not a current solver.', ['@userId' => $this->currentUser()->id()]);
    }

    $node->save();
    return $this->redirect('<front>');
  }

}
