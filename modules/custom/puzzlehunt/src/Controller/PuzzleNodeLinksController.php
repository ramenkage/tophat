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

    $uid = $this->currentUser()->id();

    $existing_uids = array_column($node->field_current_solvers->getValue(), 'target_id');
    if (!in_array($uid, $existing_uids)) {
      $node->field_current_solvers[] = $uid;
      $output .= $this->t('Adding user @uid to current solvers.', ['@uid' => $this->currentUser()->id()]);
    }
    else {
      $output .= $this->t('User @uid is already a current solver.', ['@uid' => $this->currentUser()->id()]);
    }

    $node->save();

    $build = [
      '#markup' => $output,
    ];
    return $build;
  }

  /**
   * Callback for the puzzlehunt.remove_solver route.
   */
  public function removeSolver(Node $node) {

    $output = "";
    $uid = $this->currentUser()->id();

    $existing_uids = array_column($node->field_current_solvers->getValue(), 'target_id');
    if (in_array($uid, $existing_uids)) {
      $key = array_search($uid, $existing_uids);
      $node->field_current_solvers->removeItem($key);
      $output .= $this->t('Removing user @uid from current solvers.', ['@uid' => $this->currentUser()->id()]);
    }
    else {
      $output .= $this->t('User @uid is not a current solver.', ['@uid' => $this->currentUser()->id()]);
    }

    $node->save();

    $build = [
      '#markup' => $output,
    ];
    return $build;
  }

}
