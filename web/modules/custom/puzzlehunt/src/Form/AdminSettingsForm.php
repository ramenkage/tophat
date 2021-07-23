<?php

namespace Drupal\puzzlehunt\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * TODO: class docs.
 */
class AdminSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'puzzlehunt.adminsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'puzzlehunt_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('puzzlehunt.adminsettings');

    $form['slack_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Slack Bot User OAuth Token'),
      '#description' => $this->t("The authentication token for your team's custom Slack app (starting with xoxb-). Make sure your bot user has the following scopes: channels:manage, channels:read, chat:write"),
      '#default_value' => $config->get('slack_token'),
    ];

    $form['slack_channel'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Slack Channel'),
      '#description' => $this->t('The Slack channel you want the bot to post in. Do not include # (i.e., if your channel is called #botspam, enter botspam in this field).'),
      '#default_value' => $config->get('slack_channel'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('puzzlehunt.adminsettings')
      ->set('slack_token', $form_state->getValue('slack_token'))
      ->set('slack_channel', $form_state->getValue('slack_channel'))
      ->save();

    return parent::submitForm($form, $form_state);
  }

}
